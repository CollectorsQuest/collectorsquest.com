<?php

include("phpCal/phpCal.php");

if (isset($_GET["month"]))
{
  $cal = new phpCal(cIn($_GET["month"]), cIn($_GET["year"]), $browsePast);
}
else
{
  $cal = new phpCal(date("m"), date("Y"), $browsePast);
}

$query = "	SELECT DISTINCT StartDate 
             FROM " . HC_TblPrefix . "events
             LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
            WHERE IsActive = 1 AND IsApproved = 1";

if (isset($_SESSION['hc_favorites']))
{
  $query = $query . " AND " . HC_TblPrefix . "eventcategories.CategoryID in (" . $_SESSION['hc_favorites'] . ")";
}//end if

$query = $query . " ORDER BY StartDate;";

$result = doQuery($query);
if (hasRows($result))
{
  while($row = mysql_fetch_row($result))
  {
    $datepart = split("-",$row[0]);
    $datestamp = date("m/d/Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
    $events[] = $datestamp;
  }
}
else
{
  $events[] = "";
}

$cal->setEventDays($events);
$cal->setLinks("", "");

echo $cal->createCal();
