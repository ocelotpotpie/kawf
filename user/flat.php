<?php

while (list($key) = each($indexes)) {
  $sql = "select count(*) from f_messages" . $indexes[$key]['iid'];
  $result = mysql_query($sql) or sql_error($sql);

  list($nummids) = mysql_fetch_row($result);
  $curmid = 0;

  $nummessages = 0;
  while ($curmid < $nummids) {
    $sql = "select mid,state from f_messages" . $indexes[$key]['iid'] . " order by mid limit $nummessages,1000;";
    $result = mysql_query($sql) or sql_error($sql);

    $curmid += mysql_num_rows($result);

    while (list($mid,$state) = mysql_fetch_row($result)) {
      $nummessages++;
      if ($state == 'Deleted' || $state == 'Moderated')
        continue;
?>
<a href="<?php echo "/" . $forum['shortname'] . "/msgs/" . $mid; ?>.phtml">&nbsp;</a><br>
<?php
    }

    mysql_free_result($result);
  }
}
?>
