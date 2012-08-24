<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
				<?php if(!$group_edit_id) { ?>
				<h3><?php _e('New group', 'adrotate'); ?></h3>
					<?php
					$action = "group_new";
					$query = "SELECT `id` FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` = '' ORDER BY `id` DESC LIMIT 1;";
					$edit_id = $wpdb->get_var($query);
					if($edit_id == 0) {
						$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_groups` (`name`, `fallback`, `sortorder`, `cat`, `cat_loc`,`page`,`page_loc`) VALUES ('', 0, '', '', 0, '', 0);");
						$edit_id = $wpdb->get_var($query);
					}
					$group_edit_id = $edit_id;
					?>
				<?php } else { ?>
				<h3><?php _e('Edit Group', 'adrotate'); ?></h3>
				<?php 
					$action = "group_edit";
				}

				$edit_group = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = '$group_edit_id';");
				$groups		= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' ORDER BY `id` ASC;"); 
				$ads 		= $wpdb->get_results("SELECT `id`, `title`, `tracker`, `weight` FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'active' ORDER BY `id` ASC;");
				$linkmeta	= $wpdb->get_results("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = '$group_edit_id' AND `block` = 0 AND `user` = 0;");
				foreach($linkmeta as $meta) {
					$meta_array[] = $meta->ad;
				}
				if(!is_array($meta_array)) $meta_array = array();
				?>
	
				<form name="editgroup" id="post" method="post" action="admin.php?page=adrotate-groups">
			    	<input type="hidden" name="adrotate_id" value="<?php echo $edit_group->id;?>" />
			    	<input type="hidden" name="adrotate_action" value="<?php echo $action;?>" />
	
				   	<table class="widefat" style="margin-top: .5em">
	
			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('The basics (required)', 'adrotate'); ?></th>
						</tr>
			  			</thead>
	
						<tbody>
					    <tr>
							<th width="15%"><?php _e('ID:', 'adrotate'); ?></th>
							<td colspan="3"><?php echo $edit_group->id; ?></td>
						</tr>
					    <tr>
							<th width="15%"><?php _e('Name:', 'adrotate'); ?></th>
							<td colspan="3">
								<label for="adrotate_groupname"><input tabindex="1" name="adrotate_groupname" type="text" class="search-input" size="80" value="<?php echo $edit_group->name; ?>" autocomplete="off" /></label>
							</td>
						</tr>
						<?php if($edit_group->name != '') { ?>
				      	<tr>
					        <th><?php _e('This group is in the block(s):', 'adrotate'); ?></th>
					        <td colspan="3"><p><?php echo adrotate_group_is_in_blocks($edit_group->id); ?></p></td>
				      	</tr>
						<?php } ?>
						</tbody>

			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('Advanced (optional)', 'adrotate'); ?></th>
						</tr>
			  			</thead>
	
						<tbody>
					    <tr>
							<th><?php _e('Fallback group?', 'adrotate'); ?></th>
							<td colspan="3">
								<label for="adrotate_fallback">
								<select tabindex="2" name="adrotate_fallback">
						        <option value="0"><?php _e('No', 'adrotate'); ?></option>
							<?php if ($groups) { ?>
								<?php foreach($groups as $group) { ?>
							        <option value="<?php echo $group->id;?>" <?php if($edit_group->fallback == $group->id) { echo 'selected'; } ?>><?php echo $group->id;?> - <?php echo $group->name;?></option>
					 			<?php } ?>
							<?php } ?>
								</select> <em><?php _e('You need atleast two groups to use this feature!', 'adrotate'); ?></em>
								</label>
							</td>
						</tr>
				      	<tr>
					        <th valign="top"><?php _e('Include ads in categories?', 'adrotate'); ?></th>
					        <td colspan="3">
					        <label for="adrotate_cat_location">
						        <select tabindex="2" name="adrotate_cat_location">
						        	<option value="0" <?php if($edit_group->cat_loc == 0) { echo 'selected'; } ?>><?php _e('Do not use this feature', 'adrotate'); ?></option>
						        	<option value="1" <?php if($edit_group->cat_loc == 1) { echo 'selected'; } ?>><?php _e('Before the post content', 'adrotate'); ?></option>
						        	<option value="2" <?php if($edit_group->cat_loc == 2) { echo 'selected'; } ?>><?php _e('After the post content', 'adrotate'); ?></option>
						        	<option value="3" <?php if($edit_group->cat_loc == 3) { echo 'selected'; } ?>><?php _e('Before and after the content', 'adrotate'); ?></option>
						        </select> 
					        </td>
				      	</tr>
				      	<tr>
					        <th valign="top"><?php _e('Which categories?', 'adrotate'); ?></th>
					        <td colspan="3">
					        <label for="adrotate_categories">
						        <select multiple="true" tabindex="2" name="adrotate_categories[]">
						        <?php echo adrotate_dropdown_categories($edit_group->cat, 0, 0, 0); ?>
						        </select> <em><?php _e('Click the categories you want the adverts to show in. Hold down CTRL (CMD for Apple OS X) to select multiple.', 'adrotate'); ?></em>
					        </label>
					        </td>
				      	</tr>
				      	<tr>
					        <th valign="top"><?php _e('Include ads in pages?', 'adrotate'); ?></th>
					        <td colspan="3">
					        <label for="adrotate_page_location">
						        <select tabindex="2" name="adrotate_page_location">
						        	<option value="0" <?php if($edit_group->page_loc == 0) { echo 'selected'; } ?>><?php _e('Do not use this feature', 'adrotate'); ?></option>
						        	<option value="1" <?php if($edit_group->page_loc == 1) { echo 'selected'; } ?>><?php _e('Before the page content', 'adrotate'); ?></option>
						        	<option value="2" <?php if($edit_group->page_loc == 2) { echo 'selected'; } ?>><?php _e('After the page content', 'adrotate'); ?></option>
						        	<option value="3" <?php if($edit_group->page_loc == 3) { echo 'selected'; } ?>><?php _e('Before and after the content', 'adrotate'); ?></option>
						        </select>
							</label>
					        </td>
				      	</tr>
				      	<tr>
					        <th valign="top"><?php _e('Which pages?', 'adrotate'); ?></th>
					        <td colspan="3">
					        <label for="adrotate_pages">
						        <select multiple="true" tabindex="2" name="adrotate_pages[]">
						        <?php echo adrotate_dropdown_pages($edit_group->page, 0, 0, 0); ?>
						        </select> <em><?php _e('Click the pages you want the adverts to show in. Hold down CTRL (CMD for Apple OS X) to select multiple.', 'adrotate'); ?></em>
					        </label>
					        </td>
				      	</tr>
				      	<tr>
					        <th><?php _e('Sortorder:', 'adrotate'); ?></th>
					        <td colspan="3">
						        <label for="adrotate_sortorder"><input tabindex="23" name="adrotate_sortorder" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_group->sortorder;?>" /> <em><?php _e('For administrative purposes set a sortorder.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this. Will default to group id.', 'adrotate'); ?></em></label>
							</td>
				      	</tr>
						</tbody>
	
						<thead>
						<tr>
							<th colspan="4"><?php _e('Usage', 'adrotate'); ?></th>
						</tr>
						</thead>
		
						<tbody>
				      	<tr>
					        <th width="15%"><?php _e('In a post or page:', 'adrotate'); ?></th>
					        <td width="35%"><p>[adrotate group="<?php echo $edit_group->id; ?>"]</p></td>
					        <th width="15%"><?php _e('Directly in a theme:', 'adrotate'); ?></th>
					        <td width="35%"><p>&lt;?php echo adrotate_group(<?php echo $edit_group->id; ?>); ?&gt;</p></td>
				      	</tr>
				      	</tbody>
					</table>
				
			    	<p class="submit">
						<input tabindex="3" type="submit" name="adrotate_group_submit" class="button-primary" value="<?php _e('Save', 'adrotate'); ?>" />
						<a href="admin.php?page=adrotate-groups&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			    	</p>

					<h3><?php _e('Select Ads', 'adrotate'); ?></h3>

				   	<table class="widefat" style="margin-top: .5em">
			  			<thead>
		  				<tr>
							<th colspan="2"><?php _e('Choose the ads to use in this group', 'adrotate'); ?></th>
							<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
							<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
							<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
							<th width="15%"><?php _e('Visible until', 'adrotate'); ?></th>
						</tr>
			  			</thead>
	
						<tbody>
						<?php if($ads) {
							foreach($ads as $ad) {
								$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '".$ad->id.";");
								$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '".$ad->id."' ORDER BY `stoptime` DESC LIMIT 1;");						

								// Prevent gaps in display
								if($stats->impressions == 0) 		$stats->impressions 		= 0;
								if($stats->clicks == 0) 			$stats->clicks 				= 0;

								$class = ('alternate' != $class) ? 'alternate' : ''; ?>
							    <tr class='<?php echo $class; ?>'>
									<th width="2%"><input type="checkbox" name="adselect[]" value="<?php echo $ad->id; ?>" <?php if(in_array($ad->id, $meta_array)) echo "checked"; ?> /></th>
									<td><?php echo $ad->id; ?> - <strong><?php echo $ad->title; ?></strong></td>
									<td><center><?php echo $stats->impressions; ?></center></td>
									<td><center><?php if($ad->tracker == 'Y') { echo $stats->clicks; } else { ?>--<?php } ?></center></td>
									<td><center><?php echo $ad->weight; ?></center></td>
									<td><span style="color: <?php echo adrotate_prepare_color($stoptime);?>;"><?php echo date_i18n("F d, Y", $stoptime); ?></span></td>
								</tr>
							<?php unset($stats);?>
				 			<?php } ?>
						<?php } else { ?>
						<tr>
							<th class="check-column">&nbsp;</th>
							<td colspan="5"><em><?php _e('No ads created!', 'adrotate'); ?></em></td>
						</tr>
						<?php } ?>
						</tbody>					
			 		</table>

			    	<p class="submit">
						<input tabindex="3" type="submit" name="adrotate_group_submit" class="button-primary" value="<?php _e('Save', 'adrotate'); ?>" />
						<a href="admin.php?page=adrotate-groups&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			    	</p>
	
				</form>
