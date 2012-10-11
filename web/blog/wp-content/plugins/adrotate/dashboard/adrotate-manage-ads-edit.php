<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
			<?php if(!$ad_edit_id) { ?>
			<h3><?php _e('New Ad', 'adrotate'); ?></h3>
			<?php
				// Initial date for new entries
				list($syear, $smonth, $sday, $shour, $sminute, $ssecond) = split('([^0-9])', current_time('mysql'));
				list($eyear, $emonth, $eday, $ehour, $eminute, $esecond) = split('([^0-9])', date('Y-m-d H:i:s', strtotime("+12 weeks", $now)));
			
				$query = "SELECT `id` FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'empty' ORDER BY `id` DESC LIMIT 1;";
				$edit_id = $wpdb->get_var($query);
				if($edit_id == 0) {
				    $wpdb->insert($wpdb->prefix."adrotate", array('title' => '', 'bannercode' => '', 'thetime' => $now, 'updated' => $now, 'author' => $userdata->user_login, 'imagetype' => '', 'image' => '', 'link' => '', 'tracker' => 'N', 'timeframe' => '', 'timeframelength' => 0, 'timeframeclicks' => 0, 'timeframeimpressions' => 0, 'type' => 'empty', 'weight' => 6, 'sortorder' => 0));
				    $edit_id = $wpdb->insert_id;
				}
				$ad_edit_id = $edit_id;
			} else { ?>
			<h3><?php _e('Edit Ad', 'adrotate'); ?></h3>
			<?php
			}
			
			$edit_banner 	= $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$ad_edit_id';");
			$groups			= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' ORDER BY `id` ASC;"); 
			$schedules		= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '$edit_banner->id' ORDER BY `id` ASC;"); 
			$user_list		= $wpdb->get_results("SELECT `ID`, `display_name` FROM `$wpdb->users` ORDER BY `user_nicename` ASC;");
			$saved_user 	= $wpdb->get_var("SELECT `user` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$edit_banner->id' AND `group` = 0 AND `block` = 0;");
			$stoptime 		= $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '$edit_banner->id' ORDER BY `stoptime` DESC LIMIT 1;");
			$linkmeta		= $wpdb->get_results("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$edit_banner->id' AND `block` = 0 AND `user` = 0;");
			foreach($linkmeta as $meta) {
				$meta_array[] = $meta->group;
			}
			
			if(!is_array($meta_array)) $meta_array = array();
			
			if($ad_edit_id) {
				if($edit_banner->type != 'empty') {
					// Errors
					if(strlen($edit_banner->bannercode) < 1) 
						echo '<div class="error"><p>'. __('The AdCode cannot be empty!', 'adrotate').'</p></div>';
	
					if($edit_banner->tracker == 'N' AND strlen($edit_banner->link) < 1 AND $saved_user > 0) 
						echo '<div class="error"><p>'. __('You\'ve set an advertiser but didn\'t enable clicktracking!', 'adrotate').'</p></div>';
	
					if($edit_banner->tracker == 'Y' AND strlen($edit_banner->link) < 1) 
						echo '<div class="error"><p>'. __('You\'ve enabled clicktracking but didn\'t provide an url in the url field!', 'adrotate').'</p></div>';
	
					if($edit_banner->tracker == 'N' AND strlen($edit_banner->link) > 0) 
						echo '<div class="error"><p>'. __('You didn\'t enable clicktracking but you did use the url field!', 'adrotate').'</p></div>';
	
					if(!preg_match("/%link%/i", $edit_banner->bannercode) AND $edit_banner->tracker == 'Y') 
						echo '<div class="error"><p>'. __('You didn\'t use %link% in your AdCode but did enable clicktracking!', 'adrotate').'</p></div>';
	
					if(preg_match("/%link%/i", $edit_banner->bannercode) AND $edit_banner->tracker == 'N') 
						echo '<div class="error"><p>'. __('You\'ve %link% in your AdCode but didn\'t enable clicktracking!', 'adrotate').'</p></div>';
	
					if(!preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image != '') 
						echo '<div class="error"><p>'. __('You didn\'t use %image% in your AdCode but did select an image!', 'adrotate').'</p></div>';
	
					if(preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image == '') 
						echo '<div class="error"><p>'. __('You did use %image% in your AdCode but did not select an image!', 'adrotate').'</p></div>';
					
					if((($edit_banner->imagetype != '' AND $edit_banner->image == '') OR ($edit_banner->imagetype == '' AND $edit_banner->image != ''))) 
						echo '<div class="error"><p>'. __('There is a problem saving the image specification. Please reset your image and re-save the ad!', 'adrotate').'</p></div>';
					
					if(!$schedules) 
						echo '<div class="error"><p>'. __('This ad has no schedules!', 'adrotate').'</p></div>';
					
					
					// Ad Notices
					$adstate = adrotate_evaluate_ad($edit_banner->id);
	 				if($edit_banner->type == 'error' AND $adstate == 'normal')
	 					echo '<div class="error"><p>'. __('AdRotate cannot find an error but the ad is marked erroneous, try re-saving the ad!', 'adrotate').'</p></div>';

	 				if($adstate == 'expired')
						echo '<div class="error"><p>'. __('This ad is expired and currently not shown on your website!', 'adrotate').'</p></div>';

	 				if($adstate == 'expires2days')
						echo '<div class="updated"><p>'. __('The ad will expire in less than 2 days!', 'adrotate').'</p></div>';

	 				if($adstate == 'expires7days')
						echo '<div class="updated"><p>'. __('This ad will expire in less than 7 days!', 'adrotate').'</p></div>';

					if($edit_banner->type == 'disabled') 
						echo '<div class="updated"><p>'. __('This ad has been disabled and does not rotate on your site!', 'adrotate').'</p></div>';
				}

				// Determine image field
				if($edit_banner->imagetype == "field") {
					$image_field = $edit_banner->image;
					$image_dropdown = '';
				}
				
				if($edit_banner->imagetype == "dropdown") {
					$image_field = '';
					$image_dropdown = $edit_banner->image;
				}
			}
			?>
			
			<script language="JavaScript">
			jQuery(document).ready(function() {
				jQuery('#adrotate_image_button').click(function() {
					formfield = jQuery('#adrotate_image').attr('name');
					tb_show('', 'media-upload.php?type=image&TB_iframe=true');
					return false;
				});
				
				window.send_to_editor = function(html) {
					imgurl = jQuery('img',html).attr('src');
					jQuery('#adrotate_image').val(imgurl);
					tb_remove();
				}
			
			});
			</script>

		  	<form method="post" action="admin.php?page=adrotate">
		    	<input type="hidden" name="adrotate_username" value="<?php echo $userdata->user_login;?>" />
		    	<input type="hidden" name="adrotate_id" value="<?php echo $edit_banner->id;?>" />
		    	<input type="hidden" name="adrotate_type" value="<?php echo $edit_banner->type;?>" />
	
		    	<table class="widefat" style="margin-top: .5em">
	
					<thead>
					<tr>
						<th colspan="4"><?php _e('The basics (Required)', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <th><?php _e('Title:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<label for="adrotate_title"><input tabindex="1" name="adrotate_title" type="text" size="80" class="search-input" value="<?php echo $edit_banner->title;?>" autocomplete="off" /></label>
				        </td>
			      	</tr>
			      	<tr>
				        <th valign="top"><?php _e('AdCode:', 'adrotate'); ?></th>
				        <td colspan="2">
				        	<label for="adrotate_bannercode"><textarea tabindex="2" name="adrotate_bannercode" cols="65" rows="15"><?php echo stripslashes($edit_banner->bannercode); ?></textarea></label>
				        </td>
				        <td>
					        <p><strong><?php _e('Options:', 'adrotate'); ?></strong></p>
					        <p><em>%id%, %image%, %link%</em><br />
					        <?php _e('HTML/JavaScript allowed, use with care!', 'adrotate'); ?></p>
					        
					        <p><strong><?php _e('Basic Examples:', 'adrotate'); ?></strong></p>
					        <p><?php _e('Clicktracking:', 'adrotate'); ?> <em>&lt;a href="%link%"&gt;This ad is great!&lt;/a&gt;</em></p>
					        <p><?php _e('Image:', 'adrotate'); ?> <em>&lt;a href="http://example.com"&gt;&lt;img src="%image%" /&gt;&lt;/a&gt;</em></p>
					        <p><?php _e('Combination:', 'adrotate'); ?> <em>&lt;a href="%link%"&gt;&lt;img src="%image%" /&gt;&lt;/a&gt;</em></p>
					        
					        <p><strong><?php _e('Advanced Example:', 'adrotate'); ?></strong></p>
					        <p><?php _e('Clicktracking:', 'adrotate'); ?> <em>&lt;span class="ad-%id%"&gt;&lt;a href="%link%"&gt;Text Link Ad!&lt;/a&gt;&lt;/span&gt;</em></p>
				        </td>
			      	</tr>
			      	<tr>
				        <th><?php _e('Activate:', 'adrotate'); ?></th>
				        <td colspan="3">
					        <label for="adrotate_active">
					        <select tabindex="3" name="adrotate_active">
								<option value="active" <?php if($edit_banner->type == "active") { echo 'selected'; } ?>><?php _e('Yes, this ad will be used', 'adrotate'); ?></option>
								<option value="disabled" <?php if($edit_banner->type == "disabled") { echo 'selected'; } ?>><?php _e('No, no do not show this ad anywhere', 'adrotate'); ?></option>
							</select>
							</label>
						</td>
			      	</tr>
			      	<tr>
				        <th><?php _e('Sortorder:', 'adrotate'); ?></th>
				        <td colspan="3">
					        <label for="adrotate_sortorder"><input tabindex="4" name="adrotate_sortorder" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->sortorder;?>" /> <em><?php _e('For administrative purposes set a sortorder.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this. Will default to ad id.', 'adrotate'); ?></em></label>
						</td>
			      	</tr>
					</tbody>
	
				<?php if($edit_banner->type != 'empty') { ?>
					<thead>
					<tr>
						<th colspan="4"><?php _e('Preview', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <td colspan="4">
				        	<div><?php echo adrotate_preview($edit_banner->id); ?></div>
					        <br /><em><?php _e('Note: While this preview is an accurate one, it might look different then it does on the website.', 'adrotate'); ?>
							<br /><?php _e('This is because of CSS differences. Your themes CSS file is not active here!', 'adrotate'); ?></em>
						</td>
			      	</tr>
			      	</tbody>
				<?php } ?>
	
					<thead>
					<tr>
						<th colspan="4"><?php _e('Usage', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <th><?php _e('In a post or page:', 'adrotate'); ?></th>
				        <td><p>[adrotate banner="<?php echo $edit_banner->id; ?>"]</p></td>
				        <th><?php _e('Directly in a theme:', 'adrotate'); ?></th>
				        <td><p>&lt;?php echo adrotate_ad(<?php echo $edit_banner->id; ?>); ?&gt;</p></td>
			      	</tr>
			      	</tbody>
	
					<thead>
					<tr>
						<th colspan="4" bgcolor="#DDD"><?php _e('Advanced (Everything below is optional)', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <th valign="top"><?php _e('Advertiser:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<label for="adrotate_tracker">
				        	<select tabindex="5" name="adrotate_advertiser" style="min-width: 200px;">
								<option value="0" <?php if($saved_user == '0') { echo 'selected'; } ?>><?php _e('Not specified', 'adrotate'); ?></option>
							<?php 
							foreach($user_list as $user) {
/*
							foreach($user_list as $id) {
								$user = get_userdata($id->ID); 
								if(strlen($user->first_name) < 1) $firstname = $user->user_login;
									else $firstname = $user->first_name;
								if(strlen($user->last_name) < 1) $lastname = ''; 
									else $lastname = $user->last_name;
								if($user->ID == $userdata->ID) $you = ' (You)';
									else $you = '';
*/
								if($user->ID == $userdata->ID) $you = ' (You)';
									else $you = '';
							?>
								<option value="<?php echo $user->ID; ?>"<?php if($saved_user == $user->ID) { echo ' selected'; } ?>><?php echo $user->display_name; ?><?php echo $you; ?></option>
							<?php } ?>
							</select>
					        <em><?php _e('Must be a registered user on your site with appropriate access roles.', 'adrotate'); ?></em>
					        </label>
						</td>
			      	</tr>
			      	<tr>
				        <th valign="top"><?php _e('Clicktracking:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<label for="adrotate_tracker"><?php _e('Enable?', 'adrotate'); ?> <input tabindex="6" type="checkbox" name="adrotate_tracker" <?php if($edit_banner->tracker == 'Y') { ?>checked="checked" <?php } ?> /> <label for="adrotate_link">url: <input tabindex="7" name="adrotate_link" type="text" size="80" class="search-input" value="<?php echo $edit_banner->link;?>" /><br />
					        <em><?php _e('Use %link% in the adcode instead of the actual url.', 'adrotate'); ?><br />
					        <?php _e('For a random seed you can use %random%. A generated timestamp you can use.', 'adrotate'); ?></em>
					        </label>
				        </td>
			      	</tr>
					<tr>
				        <th valign="top"><?php _e('Banner image:', 'adrotate'); ?></th>
						<td colspan="3">
							<label for="adrotate_image">
								<?php _e('Media:', 'adrotate'); ?> <input tabindex="8" size="100" id="adrotate_image" type="text" name="adrotate_image" value="<?php echo $image_field; ?>" /> <input tabindex="15" id="adrotate_image_button" type="button" value="<?php _e('Select Image', 'adrotate'); ?>" /><br />
							</label>
							<label for="adrotate_image_dropdown">
								<?php _e('- OR -', 'adrotate'); ?><br />
								<?php _e('Banner folder:', 'adrotate'); ?> <select tabindex="9" name="adrotate_image_dropdown" style="min-width: 200px;">
			   						<option value=""><?php _e('No image selected', 'adrotate'); ?></option>
									<?php echo adrotate_folder_contents($image_dropdown); ?>
								</select><br />
								<em><?php _e('Use %image% in the code. Accepted files are:', 'adrotate'); ?> jpg, jpeg, gif, png, swf and flv. <?php _e('Use either the text field or the dropdown. If the textfield has content that field has priority.', 'adrotate'); ?></em>
							</label>
						</td>
					</tr>
			      	<tr>
					    <th valign="top"><?php _e('Weight:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<label for="adrotate_weight">
				        	<input type="radio" tabindex="10" name="adrotate_weight" value="2" <?php if($edit_banner->weight == "2") { echo 'checked'; } ?> /> 2, <?php _e('Barely visible', 'adrotate'); ?><br />
				        	<input type="radio" tabindex="11" name="adrotate_weight" value="4" <?php if($edit_banner->weight == "4") { echo 'checked'; } ?> /> 4, <?php _e('Less than average', 'adrotate'); ?><br />
				        	<input type="radio" tabindex="12" name="adrotate_weight" value="6" <?php if($edit_banner->weight == "6") { echo 'checked'; } ?> /> 6, <?php _e('Normal coverage', 'adrotate'); ?><br />
				        	<input type="radio" tabindex="13" name="adrotate_weight" value="8" <?php if($edit_banner->weight == "8") { echo 'checked'; } ?> /> 8, <?php _e('More than average', 'adrotate'); ?><br />
				        	<input type="radio" tabindex="14" name="adrotate_weight" value="10" <?php if($edit_banner->weight == "10") { echo 'checked'; } ?> /> 10, <?php _e('Best visibility', 'adrotate'); ?>
				        	</label>
						</td>
					</tr>
					</tbody>
				</table>
	
		    	<p class="submit">
					<input tabindex="17" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save ad', 'adrotate'); ?>" />
					<a href="admin.php?page=adrotate&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
		    	</p>

				<h3><?php _e('Schedules', 'adrotate'); ?></h3>

		    	<table class="widefat" style="margin-top: .5em">
					<thead>
					<tr>
						<th colspan="4"><?php _e('Add a new schedule (Required)', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
			      		<th valign="top" valign="top">Important:</th>
				        <td colspan="3"><em><?php _e('Time uses a 24 hour clock. When you\'re used to the AM/PM system keep this in mind: If the the start or end time is after lunch, add 12 hours. 2PM is 14:00 hours. 6AM is 6:00 hours.', 'adrotate'); ?><br /><?php _e('The maximum clicks and impressions are measured over the set schedule only. Every schedule can have it\'s own limit!', 'adrotate'); ?></em></td>
					</tr>
			      	<tr>
				        <th><?php _e('Start time (day/month/year hh:mm):', 'adrotate'); ?></th>
				        <td>
				        	<label for="adrotate_sday">
				        	<input tabindex="22" name="adrotate_sday" class="search-input" type="text" size="4" maxlength="2" value="<?php echo $sday;?>" /> /
							<select tabindex="23" name="adrotate_smonth">
								<option value="01" <?php if($smonth == "01") { echo 'selected'; } ?>><?php _e('January', 'adrotate'); ?></option>
								<option value="02" <?php if($smonth == "02") { echo 'selected'; } ?>><?php _e('February', 'adrotate'); ?></option>
								<option value="03" <?php if($smonth == "03") { echo 'selected'; } ?>><?php _e('March', 'adrotate'); ?></option>
								<option value="04" <?php if($smonth == "04") { echo 'selected'; } ?>><?php _e('April', 'adrotate'); ?></option>
								<option value="05" <?php if($smonth == "05") { echo 'selected'; } ?>><?php _e('May', 'adrotate'); ?></option>
								<option value="06" <?php if($smonth == "06") { echo 'selected'; } ?>><?php _e('June', 'adrotate'); ?></option>
								<option value="07" <?php if($smonth == "07") { echo 'selected'; } ?>><?php _e('July', 'adrotate'); ?></option>
								<option value="08" <?php if($smonth == "08") { echo 'selected'; } ?>><?php _e('August', 'adrotate'); ?></option>
								<option value="09" <?php if($smonth == "09") { echo 'selected'; } ?>><?php _e('September', 'adrotate'); ?></option>
								<option value="10" <?php if($smonth == "10") { echo 'selected'; } ?>><?php _e('October', 'adrotate'); ?></option>
								<option value="11" <?php if($smonth == "11") { echo 'selected'; } ?>><?php _e('November', 'adrotate'); ?></option>
								<option value="12" <?php if($smonth == "12") { echo 'selected'; } ?>><?php _e('December', 'adrotate'); ?></option>
							</select> /
							<input tabindex="24" name="adrotate_syear" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $syear;?>" />&nbsp;&nbsp;&nbsp; 
							<input tabindex="25" name="adrotate_shour" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $shour;?>" /> :
							<input tabindex="26" name="adrotate_sminute" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $sminute;?>" />
							</label>
				        </td>
				        <th><?php _e('End time (day/month/year hh:mm):', 'adrotate'); ?></th>
				        <td>
				        	<label for="adrotate_eday">
				        	<input tabindex="27" name="adrotate_eday" class="search-input" type="text" size="4" maxlength="2" value="<?php echo $eday;?>"  /> /
							<select tabindex="28" name="adrotate_emonth">
								<option value="01" <?php if($emonth == "01") { echo 'selected'; } ?>><?php _e('January', 'adrotate'); ?></option>
								<option value="02" <?php if($emonth == "02") { echo 'selected'; } ?>><?php _e('February', 'adrotate'); ?></option>
								<option value="03" <?php if($emonth == "03") { echo 'selected'; } ?>><?php _e('March', 'adrotate'); ?></option>
								<option value="04" <?php if($emonth == "04") { echo 'selected'; } ?>><?php _e('April', 'adrotate'); ?></option>
								<option value="05" <?php if($emonth == "05") { echo 'selected'; } ?>><?php _e('May', 'adrotate'); ?></option>
								<option value="06" <?php if($emonth == "06") { echo 'selected'; } ?>><?php _e('June', 'adrotate'); ?></option>
								<option value="07" <?php if($emonth == "07") { echo 'selected'; } ?>><?php _e('July', 'adrotate'); ?></option>
								<option value="08" <?php if($emonth == "08") { echo 'selected'; } ?>><?php _e('August', 'adrotate'); ?></option>
								<option value="09" <?php if($emonth == "09") { echo 'selected'; } ?>><?php _e('September', 'adrotate'); ?></option>
								<option value="10" <?php if($emonth == "10") { echo 'selected'; } ?>><?php _e('October', 'adrotate'); ?></option>
								<option value="11" <?php if($emonth == "11") { echo 'selected'; } ?>><?php _e('November', 'adrotate'); ?></option>
								<option value="12" <?php if($emonth == "12") { echo 'selected'; } ?>><?php _e('December', 'adrotate'); ?></option>
							</select> /
							<input tabindex="29" name="adrotate_eyear" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $eyear;?>" />&nbsp;&nbsp;&nbsp; 
							<input tabindex="30" name="adrotate_ehour" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $ehour;?>" /> :
							<input tabindex="31" name="adrotate_eminute" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $eminute;?>" />
							</label>
						</td>
			      	</tr>	
			      	<tr>
			      		<th><?php _e('Maximum Clicks:', 'adrotate'); ?></th>
				        <td><input tabindex="32" name="adrotate_maxclicks" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->maxclicks;?>" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></td>
					    <th><?php _e('Maximum Impressions:', 'adrotate'); ?></th>
				        <td><input tabindex="33" name="adrotate_maxshown" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->maxshown;?>" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></td>
					</tr>
					</tbody>					
				</table>

				<?php if($schedules) { ?>
				<h3><?php _e('Current Schedules', 'adrotate'); ?></h3>

		    	<table class="widefat" style="margin-top: .5em">
					<thead>
					<tr>
			      		<th scope="col" class="manage-column column-cb check-column">&nbsp;</th>
				        <th width="12%"><?php _e('From', 'adrotate'); ?></th>
				        <th><?php _e('Until', 'adrotate'); ?></th>
				        <th width="10%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
				        <th width="10%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
					</tr>
					</thead>
	
					<tbody>
					<?php foreach($schedules as $schedule) { ?>
			      	<tr id='schedule-<?php echo $schedule->id; ?>' class=' <?php echo $class; ?>'>
			      		<th><input type="checkbox" name="scheduleselect[]" value="<?php echo $schedule->id; ?>" /></th>
				        <td><?php echo date_i18n("F d, Y - H:i", $schedule->starttime);?></td>
				        <td><?php echo date_i18n("F d, Y - H:i", $schedule->stoptime);?></td>
				        <td><center><?php echo $schedule->maxclicks;?></center></td>
				        <td><center><?php echo $schedule->maximpressions;?></center></td>
			      	</tr>
			      	<?php } ?>
					<thead>
			      	<tr>
			      		<td colspan="5"><em><?php _e('To delete schedules tick the checkboxes for each schedule you want to remove and save the advert!', 'adrotate'); ?></em></td>
			      	</tr>
					</thead>
					</tbody>
				</table>
		      	<?php } ?>

				<h3><?php _e('Timeframe', 'adrotate'); ?></h3>

		    	<table class="widefat" style="margin-top: .5em">
					<thead>
					<tr>
						<th colspan="4"><?php _e('Timeframe (Optional)', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
			      		<th>Important:</th>
				        <td colspan="3"><em><?php _e('Set a click or impression limit per hour, day, week or month. This option overrules any other click or impression limit as long as the ad is within a valid schedule.', 'adrotate'); ?></em></td>
					</tr>
			      	<tr>
				        <th><?php _e('Timeframe:', 'adrotate'); ?></th>
				        <td colspan="3">
					        <input tabindex="18" name="adrotate_timeframelength" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->timeframelength;?>" /> <select tabindex="19" name="adrotate_timeframe">
								<option value="" <?php if($edit_banner->timeframe == "") { echo 'selected'; } ?>>No limits</option>
								<option value="hour" <?php if($edit_banner->timeframe == "hour") { echo 'selected'; } ?>>Hour(s)</option>
								<option value="day" <?php if($edit_banner->timeframe == "day") { echo 'selected'; } ?>>Day(s)</option>
								<option value="week" <?php if($edit_banner->timeframe == "week") { echo 'selected'; } ?>>Week(s)</option>
								<option value="month" <?php if($edit_banner->timeframe == "month") { echo 'selected'; } ?>>Month(s)</option>
							</select>
				        </td>
			      	</tr>
			      	<tr>
				        <th><?php _e('Maximum clicks:', 'adrotate'); ?></th>
				        <td>
					        <label for="adrotate_timeframeclicks"></label><input tabindex="20" name="adrotate_timeframeclicks" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->timeframeclicks;?>" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></label>
				        </td>
				        <th valign="top"><?php _e('Maximum impressions:', 'adrotate'); ?></th>
				        <td>
					        <label for="adrotate_timeframeimpressions"><input tabindex="21" name="adrotate_timeframeimpressions" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->timeframeimpressions;?>" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></label>
				        </td>
			      	</tr>
					</tbody>					
				</table>

		    	<p class="submit">
					<input tabindex="34" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save ad', 'adrotate'); ?>" />
					<a href="admin.php?page=adrotate&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
		    	</p>


				<?php if($groups) { ?>
				<h3><?php _e('Select Groups', 'adrotate'); ?></h3>

		    	<table class="widefat" style="margin-top: .5em">
		  			<thead>
	  				<tr>
						<th colspan="3"><?php _e('Select the group(s) this ad belongs to (Optional)', 'adrotate'); ?></th>
					</tr>
		  			</thead>

					<tbody>
					<?php foreach($groups as $group) {
						$ads_in_group = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = ".$group->id." AND `block` = 0;");
						$class = ('alternate' != $class) ? 'alternate' : ''; ?>
					    <tr id='group-<?php echo $group->id; ?>' class=' <?php echo $class; ?>'>
							<th width="2%"><input type="checkbox" name="groupselect[]" value="<?php echo $group->id; ?>" <?php if(in_array($group->id, $meta_array)) echo "checked"; ?> /></th>
							<td><?php echo $group->id; ?> - <strong><?php echo $group->name; ?></strong></td>
							<td width="15%"><?php echo $ads_in_group; ?> <?php _e('Ads', 'adrotate'); ?></td>
						</tr>
		 			<?php } ?>
					</tbody>					
				</table>

		    	<p class="submit">
					<input tabindex="35" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save ad', 'adrotate'); ?>" />
					<a href="admin.php?page=adrotate&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
		    	</p>
				<?php } ?>
			</form>