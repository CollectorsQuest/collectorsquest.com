<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$result = doQuery("SELECT PkID, Title, Description, StartDate, PublishDate, Views FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= NOW() ORDER BY Views DESC LIMIT 25");
	
	if(hasRows($result)){	?>
		The following are our current most popular events. For more information about an event, click its title.
		<br><br>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">	<?
		$cnt = 1;
		$sty = 0;
		while($row = mysql_fetch_row($result)){	
			if($cnt < 11){	?>
				<tr><td class="eventListHL"><b><?echo $cnt;?>)
				 <?php 		// for color box category
                                                $query = "SELECT PkID,CategoryName,Color from " . HC_TblPrefix . "categories LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID) WHERE EventID='" . $row[0] . "'";
                                                $cat_result = doQuery($query);
                                                if(hasRows($cat_result)) {
                                                        $cat_row = mysql_fetch_row($cat_result);
                                                        $color = $cat_row[2];
                                                        $urlcolor = urlencode($color);
                                                        ?><a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0] . $calSaver;?>" title="<?echo $category;?> "><img align="absmiddle" src="<?echo CalRoot; ?>/images/color_image.php?width=20&height=12&color=<?echo $urlcolor;?>" border=0 title="<?echo $row[1];?>" alt="<?echo $row[1];?>" class="categorybox"></a>
                                                <?
                                                }  // end if
                                ?>
					<a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0];?>" class="eventActual"><?echo $row[1];?></a></b></td>
					<td width="150" class="eventListHL" valign="middle"><b><?echo stampToDate($row[3], $dateFormat);?></b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="2" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="2" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain"><?echo makeTeaser(strip_tags($row[2]), 100);?>&nbsp;[ <a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0];?>" class="main">Event Details</a> ]</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
		<?	} else {?>
				<tr>
					<td class="eventListHL"><?echo $cnt;?>) <a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0];?>" class="eventActual"><?echo $row[1];?></a></td>
					<td class="eventListHL"><?echo stampToDate($row[3], $dateFormat);?></td>
				</tr>
	<?		$sty++;
			}//end if
			$cnt++;
		}//end while	?>
		</table>
<?	}//end if
?>
