<?php
if (!isset($forum)) {
  echo "Invalid forum\n";
  exit;
}

$page = $_REQUEST['page'];
$tid = $_REQUEST['tid'];

if (!$user->valid()) {
  header("Location: $page");
  exit;
}

if (!$user->capable($forum['fid'], 'Lock')) {
  echo "You are not allowed to stick/unstick this thread\n";
  exit;
}

if (!isset($_REQUEST['stick']) || !is_numeric($_REQUEST['stick'])) {
  header("Location: $page");
  exit;
}

$stick = $_REQUEST['stick'];

if (!$user->is_valid_token($_REQUEST['token'])) {
  err_not_found('Invalid token');
}

$index = find_thread_index($tid);
if (!isset($index)) {
  echo "Invalid thread!\n";
  exit;
}

$sql = "select * from f_threads" . $indexes[$index]['iid'] . " where tid = '" . addslashes($tid) . "'";
$result = mysql_query($sql) or sql_error($sql);

$thread = mysql_fetch_array($result);

$options = explode(",", $thread['flags']);
foreach ($options as $name => $value) {
  if ($options[$name] == 'Sticky')
    unset($options[$name]);
}

if ($stick) {
    $options[] = 'Sticky';
    $what = 'Stuck';
} else {
    $what = 'Unstuck';
}

$flags = implode(",", $options);

$sql = "update f_threads" . $indexes[$index]['iid'] . " set flags = '" . addslashes($flags) . "' where tid = '" . addslashes($tid) . "'";
mysql_query($sql) or sql_error($sql);

sql_query("update f_messages" . $indexes[$index]['iid'] . " set " .
        "changes = CONCAT(changes, '$what by " . addslashes($user->name) . "/" . $user->aid . " at ', NOW(), '\n') " .
        "where mid = '" . addslashes($thread['mid']) . "'");

header("Location: $page");
?>