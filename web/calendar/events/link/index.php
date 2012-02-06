<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	hookDB();
	
	if(isset($_GET['tID']) && is_numeric($_GET['tID'])){
		if(isset($_GET['oID']) && is_numeric($_GET['oID'])){
			
			$tID = $_GET['tID'];
			$oID = $_GET['oID'];
			
			switch($tID){
			//	Event URL Clicked
				case 1:
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($oID));
						if(hasRows($result)){
							doQuery("UPDATE " . HC_TblPrefix . "events SET URLClicks = URLClicks + 1 WHERE PkID = '" . cIn($oID) . "'");
							header('Location: ' . mysql_result($result,0,24));
						} else {
							die("Invalid Link");
						}//end if
					break;
			//	Driving Directions Clicked
				case 2:
						$result = doQuery("SELECT LocationAddress, LocationCity, LocationState, LocationZip FROM " . HC_TblPrefix . "events WHERE PkID = " . $oID);
						if(hasRows($result)){
							doQuery("UPDATE " . HC_TblPrefix . "events SET Directions = Directions + 1 WHERE PkID = '" . cIn($oID) . "'");
							
							$resultSet = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 8");
							switch(mysql_result($resultSet,0,0)){
								case 0:
									header("Location: http://maps.google.com/maps?q=" . urlencode(mysql_result($result,0,"LocationAddress") . ", " . mysql_result($result,0,"LocationCity") . ", " . mysql_result($result,0,"LocationState") . " " . mysql_result($result,0,"LocationZip")) . "&hl=en");
									break;
									
								case 1:
									header('Location: http://www.mapquest.com/maps/map.adp?country=US&countryid=250&addtohistory=&searchtab=address&searchtype=address&address=' . urlencode(mysql_result($result,0,"LocationAddress")) . '&city=' . urlencode(mysql_result($result,0,"LocationCity")) . '&state=' . urlencode(mysql_result($result,0,"LocationState")) . '&zipcode=' . urlencode(mysql_result($result,0,"LocationZip")) . '&search=++Search++');
									break;
									
								case 2:
									header('Location: http://maps.yahoo.com/maps_result?addr=' . urlencode(mysql_result($result,0,"LocationAddress")) . '&csz=' . urlencode(mysql_result($result,0,"LocationCity")) . '+' . urlencode(mysql_result($result,0,"LocationState")) . '+' . urlencode(mysql_result($result,0,"LocationZip")) . '&country=us&new=1&name=&qty=');
									break;
							}//end switch
						} else {
							die("Invalid Link");
						}//end if
					break;
			//	Weather Link Clicked
				case 3:
						$result = doQuery("SELECT LocationZip FROM " . HC_TblPrefix . "events WHERE PkID = " . $oID);
						if(hasRows($result)){
							$resultSet = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 9");
							switch(mysql_result($resultSet,0,0)){
								case 0:
									header('Location: http://www.weather.com/weather/local/' . urlencode(mysql_result($result,0,"LocationZip")));
									break;
									
								case 1:
									header('Location: http://wwwa.accuweather.com/index-forecast.asp?partner=accuweather&amp;myadc=0&zipcode=' . urlencode(mysql_result($result,0,"LocationZip")) . '&u=1');
									break;
									
								case 2:
									header('Location: http://www.weatherunderground.com/cgi-bin/findweather/getForecast?query=' . urlencode(mysql_result($result,0,"LocationZip")));
									break;
									
								case 3:
									header('Location: http://weather.yahoo.com/search/weather2?p=' . urlencode(mysql_result($result,0,"LocationZip")));
									break;
								
								case 4:
									header('Location: http://wwwa.accuweather.com/canada-weather-forecast.asp?partner=accuweather&amp;myadc=0&postalcode=' . urlencode(mysql_result($result,0,"LocationZip")));
									break;
							}//end switch
						} else {
							die("Invalid Link");
						}//end if
					break;
					
				default:
						die("Invalid Link");
					break;
					
			}//end switch
			
		} else {
			die("Invalid Link");
		}//end if
		
	} else {
		die("Invalid Link");
	}//end if
?>