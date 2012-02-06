<?php

$result = doQuery("Select SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 10");
$maxShow = mysql_result($result,0,0);
$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= now() ORDER BY Views DESC LIMIT " . $maxShow);
$row_cnt = mysql_num_rows($result);
$curDate = "";
$cnt = 0;

while ($row = mysql_fetch_row($result))
{
  if ($curDate != $row[9])
  {
    $curDate = $row[9];
    $dateParts = split("-", $row[9]);
    if ($cnt > 0)
    {
      echo "<br>";
    }

    echo '<b>', stampToDate($row[9], $dateFormat), '</b>';
  }

  if ($showTime == 1)
  {
    if($row[10] != '')
    {
      $timepart = split(":", $row[10]);
      $startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 1, 1, 1971));
    }
    else
    {
      $startTime = "All Day";
    }

    echo "<div><a href=\"" . CalRoot ."/index.php?com=detail&eID=" . $row[0] . "&month=" . $dateParts[1] . "&year=" . $dateParts[0] . "\" class=\"eventMain\">" . cOut($row[1]) . "</a> - " . $startTime . "</div>";
  }
  else
  {
    echo "<div><a href=\"" . CalRoot ."/index.php?com=detail&eID=" . $row[0] . "&month=" . $dateParts[1] . "&year=" . $dateParts[0] . "\" class=\"eventMain\">" . cOut($row[1]) . "</a></div>";
  }

  $cnt = $cnt + 1;
}

?>

<br>
<div align="center">
	 <img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0">
	 <a href="<?echo CalRoot;?>/index.php?com=rss"><img src="<?echo CalRoot;?>/images/rss2.gif" width="80" height="15" alt="" border="0"></a>

	 <img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0">
	 <a href="<?echo CalRoot;?>/index.php?com=hotlist"><img src="<?echo CalRoot;?>/images/hot.gif" width="80" height="15" alt="" border="0"></a>

	 <br>&nbsp;
</div>
