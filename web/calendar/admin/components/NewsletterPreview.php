<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	
	hookDB();
	
	if(isset($_GET['pID']) && is_numeric($_GET['pID'])){
		$result = doQuery("SELECT TemplateSource FROM " . HC_TblPrefix . "newsletters WHERE PkID = " . cIn($_GET['pID']));
		if(hasRows($result)){
			echo mysql_result($result,0,0);
		} else {
		?>
		<span class="eventMain">Invalid Template. Please select a different template.</span>
		<?
		}//end if
	} else {
	?>
		<span class="eventMain">Invalid Template. Please select a different template.</span>
	<?
	}//end if
?>