<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$eID = 0;
	
	if(isset($_POST['eventID'])){
		$catID = $_POST['eventID'];
		foreach ($catID as $val){
			$eID = $eID . ", " . $val;
		}//end while
	} elseif(isset($_GET['eID'])){
		include('../../events/includes/include.php');
		hookDB();
		$eID = urldecode($_GET['eID']);
		$print = true;
	} else {
		header("Location: LogOut.php");
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID IN(" . cIn($eID) . ") ORDER BY StartDate");
	
	if(hasRows($result)){
		if(!isset($print)){
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="20"><img src="<?echo CalAdminRoot;?>/images/icons/iconPrint.gif" width="15" height="15" alt="" border="0"></td>
				<td class="eventMain"><a href="<?echo CalAdminRoot;?>/components/EventViewDetail.php?eID=<?echo urlencode($eID);?>" class="main" target="_blank">Printer Friendly</a></td>
				<td class="eventMain" align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&sID=1" class="main">&laquo;&laquo;Generate New Report</a></td>
			</tr>
		</table>
		<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"><br>
		<?
		} else {
		?>
			<html>
			<head>
				<link rel="stylesheet" type="text/css" href="<?echo CalAdminRoot;?>/admin.css">
			</head>
			<body>
			<span class="eventMain"><b><?echo CalName;?> Event Report -- Powered by Helios <?echo HC_Version;?></b></span>
			<br><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0"><br>
		<?
		}//end if
			$resultX = doQuery("SELECT MAX(Views) as MaxViews, MAX(MViews) as MaxMobileViews, MAX(Directions), MAX(Downloads), MAX(EmailToFriend), MAX(URLClicks)
								FROM " . HC_TblPrefix . "events
								WHERE views > 0");
			
			$maxViews = cOut(mysql_result($resultX,0,0));
			$maxMobileViews = cOut(mysql_result($resultX,0,1));
			$maxDirections = cOut(mysql_result($resultX,0,2));
			$maxDownloads = cOut(mysql_result($resultX,0,3));
			$maxEmail = cOut(mysql_result($resultX,0,4));
			$maxURL = cOut(mysql_result($resultX,0,5));
			
			$cnt = 0;
			
		while($row = mysql_fetch_row($result)){
			if($row[9] > date("Y-m-d")){
				$daysPublished = daysDiff(date("Y-m-d"), $row[27]);
			} else {
				$daysPublished = daysDiff($row[9], $row[27]);
			}//end if
			
			$eventDate = stampToDate($row[9], "l \\t\h\e jS \o\f F Y");
			$publishDate = stampToDate($row[27], "l \\t\h\e jS \o\f F Y");
			
			if(isset($print) && $cnt % 4 == 0 && $cnt > 0){
		?>
				<p style="page-break-before: always;">&nbsp;</p>
				<span class="eventMain"><b><?echo CalName;?> Event Report -- Powered by Helios <?echo HC_Version;?></b></span>
				<br><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"><br>
		<?
			}//end if
		?>
			<table cellpadding="0" cellspacing="0" border="0" <?if(!isset($print)){?>width="100%"<?}else{?>width="550"<?}//end if?>>
				<tr>
					<td class="eventActivityReport">&nbsp;<?echo $row[1];?></td>
				</tr>
				<tr>
					<td class="eventMain" style="padding: 5px;">
						<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"><br>
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td colspan="2" class="eventMain">
									<?	
										$meterSize = 340;
										if($maxViews > 0){
											$meterViews = round($meterSize * ($row[28] / $maxViews), '0');
										} else {
											$meterViews = 1;
										}//end if
										$meterViewsFill = $meterSize - $meterViews;
										
										if($maxMobileViews > 0){
											$meterMobileViews = round($meterSize * ($row[34] / $maxMobileViews), '0');
										} else {
											$meterMobileViews = 1;
										}//end if
										$meterMobileViewsFill = $meterSize - $meterMobileViews;
										
										if($maxDirections > 0){
											$meterDirections = round($meterSize * ($row[30] / $maxDirections), '0');
										} else {
											$meterDirections = 1;
										}//end if
											$meterDirectionsFill = $meterSize - $meterDirections;
										
										if($maxDownloads > 0){
											$meterDownloads = round($meterSize * ($row[31] / $maxDownloads), '0');
										} else {
											$meterDownloads = 1;
										}//end if
										$meterDownloadsFill = $meterSize - $meterDownloads;
										
										if($row[32] > 0){
											$meterEmail = round($meterSize * ($row[32] / $maxEmail), '0');
										} else {
											$meterEmail = 1;
										}//end if
										$meterEmailFill = $meterSize - $meterEmail;
										
										if($maxURL > 0){
											$meterURL = round($meterSize * ($row[33] / $maxURL), '0');
										} else {
											$meterURL = 1;
										}//end if
										$meterURLFill = $meterSize - $meterURL;
										
										$aveViews = round($row[28] / $daysPublished, 2);
										$aveMobileViews = round($row[34] / $daysPublished, 2);
										$aveDirections = round($row[30] / $daysPublished, 2);
										$aveDownloads = round($row[31] / $daysPublished, 2);
										$aveEmail = round($row[32] / $daysPublished, 2);
										$aveURL = round($row[33] / $daysPublished, 2);
									?>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td class="eventMain" colspan="2"><b>Event Stats</b></td>
											<td class="eventMain" width="30" align="right"><b>Max</b></td>
											<td class="eventMain" width="50" align="right"><b>Daily</b></td>
										</tr>
										<tr><td colspan="4" bgcolor="#BDBDBD"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="2" alt="" border="0"></td></tr>
										<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
										<tr>
											<td class="eventMain" width="125">&nbsp;Views (<?echo cOut($row[28]);?>):</td>
											<td><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterGreen.gif" width="<?echo $meterViews;?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?echo $meterViewsFill?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"></td>
											<td class="eventMain" align="right"><?echo $maxViews;?></td>
											<td class="eventMain" align="right"><?echo number_format($aveViews, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
										<tr>
											<td class="eventMain" width="125">&nbsp;Mobile Views (<?echo cOut($row[34]);?>):</td>
											<td><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterBlue.gif" width="<?echo $meterMobileViews;?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?echo $meterMobileViewsFill?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"></td>
											<td class="eventMain" align="right"><?echo $maxMobileViews;?></td>
											<td class="eventMain" align="right"><?echo number_format($aveMobileViews, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
										<tr>
											<td class="eventMain">&nbsp;Directions (<?echo cOut($row[30]);?>):</td>
											<td><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterYellow.gif" width="<?echo $meterDirections;?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?echo $meterDirectionsFill?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"></td>
											<td class="eventMain" align="right"><?echo $maxDirections;?></td>
											<td class="eventMain" align="right"><?echo number_format($aveDirections, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
										<tr>
											<td class="eventMain">&nbsp;Downloads (<?echo cOut($row[31]);?>):</td>
											<td><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterPurple.gif" width="<?echo $meterDownloads;?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?echo $meterDownloadsFill?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"></td>
											<td class="eventMain" align="right"><?echo $maxDownloads;?></td>
											<td class="eventMain" align="right"><?echo number_format($aveDownloads, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
										<tr>
											<td class="eventMain">&nbsp;Email to Friend (<?echo cOut($row[32]);?>):</td>
											<td><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterPeach.gif" width="<?echo $meterEmail;?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?echo $meterEmailFill?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"></td>
											<td class="eventMain" align="right"><?echo $maxEmail;?></td>
											<td class="eventMain" align="right"><?echo number_format($aveEmail, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
										<tr>
											<td class="eventMain">&nbsp;URL Clicks (<?echo cOut($row[33]);?>):</td>
											<td><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterDGray.gif" width="<?echo $meterURL;?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?echo $meterURLFill?>" height="10" alt="" border="0"><img src="<?echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0"></td>
											<td class="eventMain" align="right"><?echo $maxURL;?></td>
											<td class="eventMain" align="right"><?echo number_format($aveURL, 2, '.', '');?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="25">&nbsp;</td>
								<td class="eventMain">
									<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0"><br>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="eventMain" width="110"><b>Event Date:</b></td>
											<td class="eventMain"><?echo $eventDate?></td>
										</tr>
										<tr>
											<td class="eventMain"><b>Published Date:</b></td>
											<td class="eventMain"><?echo $publishDate;?></td>
										</tr>
										<tr>
											<td class="eventMain"><b>Days Published:</b></td>
											<td class="eventMain"><?echo $daysPublished;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
	<?	if(!isset($print)){	?>
			<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0">
	<?	} elseif(isset($print) && $cnt % 4 != 3 ) {	?>
			<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
	<?	}//end if
			$cnt++;
		}//end while
		
		if(!isset($print)){
		?>
			</body>
			</html>
		<?
		}//end if
		
	} else {
	?>
		Invalid event(s). Please <a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&sID=1" class="main">click here</a> to find an event.
	<?
	}//end if
?>
