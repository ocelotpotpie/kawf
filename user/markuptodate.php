<?php

if (!$user->valid() || !isset($forum)) {
  header("Location: " . $page);
  exit;
}

if ($tid == "all") {
  if (isset($tthreads)) {
    reset($tthreads);
    while (list(, $tthread) = each($tthreads)) {
      $index = find_thread_index($tthread['tid']);
      if (!isset($index))
        continue;

      $thread = sql_querya("select *, (UNIX_TIMESTAMP(tstamp) - $user->tzoff) as unixtime from f_threads" . $indexes[$index]['iid'] . " where tid = '" . addslashes($tthread['tid']) . "'");
      if (!$thread)
        continue;

      if ($thread['unixtime'] > $tthread['unixtime'])
        sql_query("update f_tracking set tstamp = NOW() where fid = " . $forum['fid'] . " and tid = " . $thread['tid'] . " and aid = " . $user->aid);
    }
  }
} else
  sql_query("update f_tracking set tstamp = NOW() where fid = " . $forum['fid'] . " and tid = " . addslashes($tid) . " and aid = " . $user->aid);

Header("Location: " . $page);

?>
