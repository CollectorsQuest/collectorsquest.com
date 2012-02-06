<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['sID']) && is_numeric($_GET['sID'])){
		$sID = $_GET['sID'];
	} else {
		$sID = 0;
	}//end if
?>
<script language="JavaScript">
function chkDate(obj){
	var re = /^(\d{1,2})[\/|-](\d{1,2})[\/|-](\d{2}|\d{4})$/
	if(obj.value != ''){
		if(!re.test(obj.value)) {
			return 1;
		} else {
			return 0;
		}//end if
	}//end if
}//end chkDate()

function chkFrm()
{
dirty = 0;
warn = "Your search could not be completed because of the following reasons:\n";
startDate = document.eventSearch.startDate.value;
endDate = document.eventSearch.endDate.value;
	
	if(chkDate(document.eventSearch.startDate) == 1){
		dirty = 1;
		warn = warn + '\n*Start Date Format is Invalid.';
	} else if(document.eventSearch.startDate.value == ''){
		dirty = 1;
		warn = warn + "\n*Start Date is Required";
	} else if(document.eventSearch.startDate.value.length < 10) {
		dirty = 1;
		warn = warn + '\n*Start Date Must Include Leading 0\'s (MM/DD/YYYY)';
	}//end if 
	
	if(chkDate(document.eventSearch.endDate) == 1){
		dirty = 1;
		warn = warn + '\n*End Date Format is Invalid.';
	} else if(document.eventSearch.endDate.value == ''){
		dirty = 1;
		warn = warn + "\n*End Date is Required";
	} else if(document.eventSearch.endDate.value.length < 10) {
		dirty = 1;
		warn = warn + '\n*End Date Must Include Leading 0\'s (MM/DD/YYYY)';
	}//end if
	
	if(compareDates(startDate, 'MM/d/yyyy', endDate, 'MM/d/yyyy') == 1){
		dirty = 1;
		warn = warn + "\n*Start Date Cannot Occur After End Date";
	}//end if
	
/*
	if(compareDates(document.eventSearch.startDate.value, 'MM/d/yyyy', '<?echo date("m/d/Y", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")) );?>', 'MM/d/yyyy') == 0){
		dirty = 1;
		warn = warn + "\n*Cannot Search Past Events";
	}//end if
*/
	if(validateCheckArray('eventSearch','catID[]',1,'Category') > 0){
		dirty = 1;
		warn = warn + '\n*Category Selection is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease make the required changes and submit your search again.');
		return false;
	} else {
		return true;
	}//end if
	
}//end chkFrm


var calx = new CalendarPopup();
var caly = new CalendarPopup();
</script>

<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Event(s) Deleted Successfully!");
				break;
				
			case "2" :
				feedback(1, "Events Updated Successfully!");
				break;
				
			case "3" :
				feedback(1, "Events Series Created Successfully!");
		}//end switch
	}//end if
	
	switch($sID){
		case 1:
			appInstructions(0, "Editing_Events", "Edit Event Search", "Please use the form below to search for the event(s) you wish to edit.");
			break;
		case 2:
			appInstructions(0, "Delete_Event", "Delete Event Search", "Please use the form below to search for the event(s) you wish to delete.");
			break;
		case 3:
			appInstructions(0, "Create_Series", "Create Series Search", "Please use the form below to search for the events you wish to include in your series.");
			break;
		default:
			appInstructions(0, "Event_Activity_Report", "Event Report Search", "Please use the form below to search the events for which you wish to generate a report.");
			break;
	}//end switch
?>
<form name="eventSearch" id="eventSearch" method="post" action="<?echo CalAdminRoot . "/index.php?com=searchresults";?>" onSubmit="return chkFrm();">
<input type="hidden" name="sID" id="sID" value="<?echo $sID;?>">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td colspan="6" class="eventMain"><b>Events Ocurring From</b></td>
				</tr>
				<tr><td><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td>
						
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="25">&nbsp;</td>
								<td><input size="10" maxlength="10" type="text" name="startDate" id="startDate" value="<?echo date("m/d/Y");?>" class="input"></td>
								<td>&nbsp;<a href="javascript:;" onclick="caly.select(document.forms[0].startDate,'anchor1','MM/dd/yyyy'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalAdminRoot;?>/images/datepicker/cal.gif" width="16" height="16" border="0" alt=""></a>&nbsp;</td>
								<td class="eventMain">&nbsp;to&nbsp;&nbsp;</td>
								<td><input size="10" maxlength="10" type="text" name="endDate" id="endDate" value="<?echo date("m/d/Y", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" class="input"></td>
								<td>&nbsp;<a href="javascript:;" onclick="caly.select(document.forms[0].endDate,'anchor2','MM/dd/yyyy'); return false;" name="anchor2" id="anchor2"><img src="<?echo CalAdminRoot;?>/images/datepicker/cal.gif" width="16" height="16" border="0" alt=""></a>&nbsp;</td>
							</tr>
						</table>
						
					</td>
				</tr>
				<tr><td><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"></td></tr>
			</table>
		</td>		
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td colspan="2" class="eventMain"><b>Having the Keyword / Phrase</b> (<i>optional</i>)</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td>
						<input size="25" maxlenght="50" type="text" name="keyword" id="keyword" value="" class="input">
					</td>
				</tr>
				<tr><td colspan="6"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="6" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="6"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td colspan="2" class="eventMain"><b>Happening in the City of:</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td>
						<select name="city" id="city" class="eventInput">
							<option value="">All Cities</option>
						<?	$result = doQuery("	SELECT DISTINCT LocationCity
												FROM " . HC_TblPrefix . "events
												WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= NOW() AND LocationCity != ''
												ORDER BY LocationCity");
							while($row = mysql_fetch_row($result)){?>
								<option value="<?echo $row[0];?>"><?echo $row[0];?></option>
						<?	}//end while
						?>
						</select>
					</td>
				</tr>
				<tr><td colspan="6"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="6" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="6"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"><br>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td class="eventMain" colspan="2">
						<b>In the Following Categories</b>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td width="25">&nbsp;</td>
					<td class="eventMain">
						<table cellpadding="0" cellspacing="0" border="0">
							<?php
								$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
								$cnt = 0;
								
								while($row = mysql_fetch_row($result)){
									if(($cnt % 3 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
								?>
									<td class="eventMain"><input type="checkbox" name="catID[]" id="catID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
									<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo $row[1];?></label>&nbsp;&nbsp;</td>
								<?
									$cnt = $cnt + 1;
								}//end while
							?>
						</table>
						<?	if($cnt > 1){	?>
							<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"><br>
							[ <a class="main" href="javascript:;" onClick="checkAllArray('eventSearch', 'catID[]');">Select All Categories</a> 
							&nbsp;|&nbsp; <a class="main" href="javascript:;" onClick="uncheckAllArray('eventSearch', 'catID[]');">Deselect All Categories</a> ]
							<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
						<?	}//end if	?>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="eventMain">
			
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="25"></td>
					<td class="eventMain" colspan="2">
						<input type="submit" name="submit" id="submit" value=" Begin Search " class="button">
					</td>
				</tr>
			</table>
			<br><br>
		</td>
	</tr>
</table>
</form>