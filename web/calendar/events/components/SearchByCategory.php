<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>

<?php
		// get the category ID
 		if(isset($_GET['catID'])) {
                        $catIDWhere = $_GET['catID'];
                        $cnt = 1;
                }
		$query = "SELECT CategoryName,Color FROM " . HC_TblPrefix . "categories WHERE PkID='" . $catIDWhere . "'";
		$result = doQuery($query);
		if(hasRows($result)) {
			$row = mysql_fetch_array($result);
			$color = $row['Color'];
			$urlcolor = urlencode($color);
			$category = $row['CategoryName'];
		}
?>

<script language="JavaScript">
function doDelete(eID, eTitle){
	if(confirm('Event Delete Is Permanent!\nAre you sure you want to delete the event:\n\n' + eTitle + '\n\n          Ok = YES Delete Event\n          Cancel = NO Don\'t Delete Event')){
		alert('delete event ' + eID);
	}//end if
}//end doDelete
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	 <tr>
                        <td colspan=3 valign="middle" align="center" class="main">
                                <?php
                                        $cat_query = "SELECT PkID,CategoryName,Color FROM " . HC_TblPrefix . "categories WHERE IsActive='1' ORDER BY " . HC_TblPrefix . "categories.CategoryName";
                                        $cat_result = doQuery($cat_query);
                                        if(hasRows($cat_result)) {
                                                while($cat_row = mysql_fetch_array($cat_result)) {
                                                        $catid = $cat_row['PkID'];
                                                        $catcategory = $cat_row['CategoryName'];
                                                        $catcolor = $cat_row['Color'];
                                                        $caturlcolor = urlencode($catcolor);
                                                        ?>
                                                        <span class="larger"><a href="<?echo CalRoot;?>/index.php?com=searchbycategory&catID=<?echo $catid;?>" title="<?echo "Show Only: $catcategory"?>"><img align="absmiddle" src="<?echo CalRoot;?>/images/color_image.php?width=20&height=12&color=<?echo $caturlcolor;?>" class="categorybox" border="0"><span style="color: <?print $catcolor; ?>;"><?echo $catcategory?></span></a></span>&nbsp;
                                                        <?php
                                                } // end while
                                        } // end if
                                ?>
                        </td>
                </tr>

	<tr>
		<td colspan="3">
			<br>Click <b>&quot;Search for Events&quot;</b> on the right to refine or change your search.<br><br>
		</td>
	</tr>
	<tr>
		<td colspan="3" bgcolor="<?echo $color;?>" class="categorytitle">
			<b>This Week's <? echo $category; ?>s</b><br>
		</td>
	</tr>
	<?php
		// start date today plus 6 days
		$startDate = date("Y-m-d");
		$endDate =  date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+6,  date("Y")));
		
		$query = "	SELECT " . HC_TblPrefix . "events.*, " . HC_TblPrefix . "eventcategories.CategoryID as Category
					FROM " . HC_TblPrefix . "events 
						INNER JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
					WHERE StartDate BETWEEN '" . cIn($startDate) . "' AND '" . cIn($endDate) . "' 
									AND Title LIKE('%" . cIn((isset($keyword)) ? $keyword : null) . "%')
									AND IsActive = 1 
									AND IsApproved = 1 ";
					
				if(isset($catIDWhere)){
					$query = $query . " AND (" . HC_TblPrefix . "eventcategories.CategoryID In(" . cIn($catIDWhere) . "))";
				}//end if
				
				if(isset($_POST['city']) && $_POST['city'] != ''){
					$query = $query . " AND " . HC_TblPrefix . "events.LocationCity = '" . $_POST['city'] . "'";
				}//end if
		$query = $query . " ORDER BY StartDate, TBD, StartTime, Title";

		$result = doQuery($query);
		
		if(hasRows($result)){
			$cnt = 0;
			$curDate = "";
			$curID = "";
			while( $row = mysql_fetch_row($result)){
				if(($curDate != $row[9]) or ($cnt == 0)){
					$curDate = $row[9];
					
					if($cnt > 0){
				?>
					<tr><td colspan="3"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br></td></tr>
				<?
					}//end if
				?> 
					<tr>
						<td class="eventFrontMain" colspan="7">
							<b><?php
								$datepart = split("-",$row[9]);
								$datestamp = date("l, F jS Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
								echo $datestamp;
							?></b>
						</td>
					</tr>
				<?
				}//end if
				
				if($curID != $row[0]){
					$curID = $row[0];
			?>
					<tr>
						<td width="120" class="eventListHL">
							
							<?php
								//check for valid start time
								if($row[10] != ''){
									$timepart = split(":", $row[10]);
									$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
								} else {
									$startTime = "";
								}//end if
								
								//check for valid end time
								if($row[12] != ''){
									$timepart = split(":", $row[12]);
									$endTime = '-' . date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
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
						<td class="eventListHL" width="10">&nbsp;</td>
						<td class="eventListHL"><a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0] . $calSaver;?>" title="<?echo $category;?>" class="eventActual"><img align="absmiddle" src="<?echo CalRoot; ?>/images/color_image.php?width=20&height=12&color=<?echo $urlcolor;?>" border=0 title="<?echo $row[1];?>" alt="<?echo $row[1];?>" class="categorybox"><?echo cOut($row[1]);?></a>
						</td>
					</tr>
			<?
				}//end if
				
			$cnt = $cnt + 1;
			}//end while
	} else {
	?>
	<tr>
		<td class="eventMain">
			There are no events that meet that search criteria.<br>
			<a href="<?echo CalRoot;?>/index.php?com=search" class="eventMain">Please click here to search again.</a>
			<br><br>
		</td>
	</tr>
	<?php
	}//end if
	?>
</table>
