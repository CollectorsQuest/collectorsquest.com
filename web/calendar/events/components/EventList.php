<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Newsletter Subscription Successfully Deleted.");
				break;
				
		}//end switch
	}//end if
	
	$day = date("d");
	$month = date("m");
	$year = date("Y");
	
	if(isset($_GET['month']) && is_numeric($_GET['month'])){
		if($_GET['month'] > $month || $_GET['year'] > $year){
			$day = 1;
		}//end if
		$month = $_GET['month'];
	}//end if
	
	if(isset($_GET['day']) && is_numeric($_GET['day'])){
		$day = $_GET['day'];
		$dayView = 1;
	}//end if
	
	if(isset($_GET['year']) && is_numeric($_GET['year'])){
		$year = $_GET['year'];
	}//end if
	
	if(isset($_GET['theDate'])){
		$theDate = $_GET['theDate'];
		$datePart = explode("-", $theDate);
		$day = $datePart[2];
		
	} else {
		$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
	}//end if
	
	//	check for valid date
	if($browsePast == 0){
		if( ($theDate < date("Y-m-d")) || (!checkdate($month, $day, $year)) ){
			feedback(2, "Unable to Display Invalid or Past Date");
			
			$day = date("d");
			$month = date("m");
			$year = date("Y");
			$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
			if(isset($dayView)){
				$dayView = false;
			}//end if
		}//end if
		
	} else {
		if( (!isset($_GET['day']) && $theDate < date("Y-m-d")) || (!checkdate($month, $day, $year)) ){
			if(!checkdate($month, $day, $year)){
				feedback(2, "Unable to Display Invalid Date");
			} else {
				feedback(2, "To View Past Events Select a Single Day.");
			}//end if
			
			$day = date("d");
			$month = date("m");
			$year = date("Y");
			$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
			if(isset($dayView)){
				$dayView = false;
			}//end if
		}//end if
		
	}//end if
	
	$remove = (date("w", mktime(0,0,0,$month,$day,$year)) + 6) % 7;
	
	if(isset($dayView) && $dayView == true){
	//	show only this day
		$query = "	SELECT DISTINCT " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "eventcategories.EventID = " . HC_TblPrefix . "events.PkID)
					WHERE " . HC_TblPrefix . "events.StartDate = '" . $theDate . "'";
						if($browsePast == 0){
							$query .= " AND " . HC_TblPrefix . "events.StartDate >= NOW() ";
						}//end if
			$query .= "	AND " . HC_TblPrefix . "events.IsActive = 1 
						AND " . HC_TblPrefix . "events.IsApproved = 1 ";
		
	} else {
	//	show this week through sunday
//		$startDate = date("Y-m-d", mktime(0, 0, 0, $month, $day - $remove, $year));
//		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 6, $year));
		$startDate = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day) + 6, $year));
		
		$query = "	SELECT DISTINCT " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "eventcategories.EventID = " . HC_TblPrefix . "events.PkID)
					WHERE StartDate BETWEEN '" . cIn($startDate) . "' AND '" . cIn($stopDate) . "'
						AND " . HC_TblPrefix . "events.StartDate >= NOW() 
						AND " . HC_TblPrefix . "events.IsActive = 1 
						AND " . HC_TblPrefix . "events.IsApproved = 1 ";
	}//end if
	
	if( isset($_SESSION['hc_favorites']) ){
		$query = $query . " AND " . HC_TblPrefix . "eventcategories.CategoryID in (" . $_SESSION['hc_favorites'] . ") ";
	}//end if
	
	$query = $query . " ORDER BY " . HC_TblPrefix . "events.StartDate, " . HC_TblPrefix . "events.TBD,  " . HC_TblPrefix . "events.StartTime, " . HC_TblPrefix . "events.Title";

	$result = doQuery($query);	?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	    <tr>
          <td style="padding-bottom: 10px;" colspan=3 valign="middle" align="center" class="main">
                  <?php
                          $cat_query = "SELECT PkID,CategoryName,Color FROM " . HC_TblPrefix . "categories WHERE IsActive='1' ORDER BY " . HC_TblPrefix . "categories.CategoryName";
                          $cat_result = doQuery($cat_query);
                          if(hasRows($cat_result)) {
                                  while($cat_row = mysql_fetch_array($cat_result)) {
                                          $catid = $cat_row['PkID'];
                                          $category = $cat_row['CategoryName'];
                                          $color = $cat_row['Color'];
                                          $urlcolor = urlencode($color);
                                          ?>
                                          <span class="larger"><a href="<?echo CalRoot;?>/index.php?com=searchbycategory&catID=<?echo $catid;?>" title="<?echo "Show Only: $category"?>"><img align="absmiddle" src="<?echo CalRoot;?>/images/color_image.php?width=20&height=12&color=<?echo $urlcolor;?>" class="categorybox" border="0"><span style="color: <?print $color; ?>;"><?echo $category?></span></a></span>&nbsp;
                                          <?php
                                  } // end while
                          } // end if
                  ?>
          </td>
        </tr>
		  <tr>
      <td colspan="3" align="right">
              <?php
                      $prevDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) - 7, $year));
                      $prevPart = explode("-", $prevDate);
                      $prevMonth = $prevPart[1];
                      $prevYear = $prevPart[0];

                      $nextDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 7, $year));
                      $nextPart = explode("-", $nextDate);
                      $nextMonth = $nextPart[1];
                      $nextYear = $nextPart[0];
              ?>
              <div id="week-arrows">
                      <span class="prev-week"><a href="<?echo CalRoot;?>/?theDate=<?echo $prevDate?>&month=<?echo $prevMonth;?>&year=<?echo $prevYear;?>">previous week</a></span>
                      <span class="next-week"><a href="<?echo CalRoot;?>/?theDate=<?echo $nextDate?>&month=<?echo $nextMonth;?>&year=<?echo $nextYear;?>">next week</a></span>
              </div>
      </td>
  </tr>
<?
	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			if(($curDate != $row[9]) or ($cnt == 0)){
				$curDate = $row[9];
				?>
				<tr>
					<td class="eventFrontMain" colspan="3">
						<b><?php
							$datepart = split("-",cOut($row[9]));
							$datestamp = date("l, F jS Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
							echo $datestamp;
						?></b>
					</td>
				</tr>
			<?
				$cnt = 0;
			}//end if
		?>
			<tr>
				<td style="padding:2px;" class="eventListHL" width="120">
					<?php
						//check for valid start time
						if($row[10] != ''){
							$timepart = split(":", $row[10]);
							$startTime = date("h:ia", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
						} else {
							$startTime = "";
						}//end if
						
						//check for valid end time
						if($row[12] != ''){
							$timepart = split(":", $row[12]);
							$endTime = '&nbsp;-&nbsp;' . date("h:ia", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
						} else {
							$endTime = "";
						}//end if
							
						//check for valid start time
						if($row[11] == 0){
							echo $startTime . $endTime;
						} elseif($row[11] == 1) {
							echo "All Day Event";
							
						} elseif($row[11] == 2) {
							echo "TBA";
							
						}//end if
					?>
				</td>
				<td valign="middle" align="right" style="padding:3px;" class="eventListHL" width="20">

					<?php
						$query = "SELECT PkID,CategoryName,Color from " . HC_TblPrefix . "categories LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID) WHERE EventID='" . $row[0] . "'";
						$cat_result = doQuery($query);
						if(hasRows($cat_result)) {
							$cat_row = mysql_fetch_row($cat_result);
							$color = $cat_row[2];
							$urlcolor = urlencode($color);
							$category = $cat_row[1];
							$catid = $cat_row[0];
							?><a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0] . $calSaver;?>" title="<?echo $category;?> "><img src="<?echo CalRoot; ?>/images/color_image.php?width=20&height=12&color=<?echo $urlcolor;?>" border=0 title="<?echo $row[1];?>" alt="<?echo $row[1];?>" class="categorybox"></a>
						<?
						}  // end if
					?>
				</td>
				<td align="left" style="padding:2px;" class="eventListHL">
					<?php
						if(isset($_GET['theDate'])){
							$calSaver = "&month=" . $month . "&year=" . $year;
						} else {
						 $calSaver = "";
						}//end if
					?>
						<a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0] . $calSaver;?>" title="<?echo $row[1];?>" alt="<?echo $row[1];?>" class="eventActual"><?echo cOut($row[1]);?></a>
					
				</td>
			</tr>
		<?
		$cnt++;
		}//end while
	} else {
	?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="eventMain">
				<br>
				There are no events scheduled for that date.
				<br><br>
				Please continue to navigate to select a new date.<br><br>
				<a href="<?echo CalRoot;?>" class="eventMain">Click here to view this weeks events.</a> 
				
				<?if(isset($_SESSION['hc_favorites'])){	?>
				Or<br><br>
				<a href="<?echo CalRoot;?>/index.php?com=filter" class="eventFrontMain">Click here to add more categories to your filter.</a>
				<?}//end if	?>
				<br><br>
			</td>
		</tr>
	<?php
	}//end if	?>
	 <tr>
                <td colspan="3" align="right">
			<div id="week-arrows">
		        <span class="prev-week"><a href="<?echo CalRoot;?>/?theDate=<?echo $prevDate?>&month=<?echo $prevMonth;?>&year=<?echo $prevYear;?>">previous week</a></span>
		        <span class="next-week"><a href="<?echo CalRoot;?>/?theDate=<?echo $nextDate?>&month=<?echo $nextMonth;?>&year=<?echo $nextYear;?>">next week</a></span>
        		</div>
                </td>
        </tr>

</table>
