<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
				<form name="disabled_banners" id="post" method="post" action="admin.php?page=adrotate">
	
				<h3><?php _e('Disabled Ads', 'adrotate'); ?></h3>
	
				<div class="tablenav">
					<div class="alignleft actions">
						<select name="adrotate_disabled_action" id="cat" class="postform">
					        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
					        <option value="activate"><?php _e('Activate', 'adrotate'); ?></option>
					        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
					        <option value="reset"><?php _e('Reset stats', 'adrotate'); ?></option>
						</select>
						<input type="submit" id="post-action-submit" name="adrotate_disabled_action_submit" value="Go" class="button-secondary" />
					</div>
	
					<br class="clear" />
				</div>
	
			   	<table class="widefat" style="margin-top: .5em">
		 			<thead>
		  				<tr>
							<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
							<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
							<th width="12%"><?php _e('Show from', 'adrotate'); ?></th>
							<th width="12%"><?php _e('Show until', 'adrotate'); ?></th>
							<th><?php _e('Title', 'adrotate'); ?></th>
							<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
							<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
							<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
							<th width="5%"><center><?php _e('CTR', 'adrotate'); ?></center></th>
						</tr>
		  			</thead>
		  			<tbody>
					<?php
					foreach($disabledbanners as $disbanner) {
						$today = gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
						$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '".$disbanner['id']."';");
	
						// Sort out CTR
						if($stats->impressions == 0) $ctrimpressions = 0.001;
							else $ctrimpressions = $stats->impressions;
						if($stats->clicks == 0) $ctrclicks = 0.001;
							else $ctrclicks = $stats->clicks;
						$ctr = round((100/$ctrimpressions)*$ctrclicks,2);						
	
						// Prevent gaps in display
						if($stats->impressions == 0) 		$stats->impressions 		= 0;
						if($stats->clicks == 0)				$stats->clicks 				= 0;
						
						if($adrotate_debug['dashboard'] == true) {
							echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='9'><pre>";
							$memory = (memory_get_usage() / 1024 / 1024);
							echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
							$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
							echo "Peak memory usage: " . round($peakmemory, 2) ." MB</pre><br />"; 
							echo "Ad Specs: <pre>";
							print_r($disbanner); 
							echo "</pre>"; 
							echo "Stats: <pre>";
							print_r($stats); 
							echo "</pre></td></tr>"; 
						}
									
						$groups	= $wpdb->get_results("
							SELECT 
								`".$wpdb->prefix."adrotate_groups`.`name` 
							FROM 
								`".$wpdb->prefix."adrotate_groups`, 
								`".$wpdb->prefix."adrotate_linkmeta` 
							WHERE 
								`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$disbanner['id']."'
								AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
								AND `".$wpdb->prefix."adrotate_linkmeta`.`block` = 0
								AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
							;");
						$grouplist = '';
						foreach($groups as $group) {
							$grouplist .= $group->name.", ";
						}
						$grouplist = rtrim($grouplist, ", ");
						
						if($disbanner['type'] == 'disabled') {
							$inactiveclass = ' row_inactive';
						} else {
							$inactiveclass = '';
						}
	
						if($disbanner['type'] == 'error') {
							$errorclass = ' row_error';
						} else {
							$errorclass = '';
						}
	
						if($class != 'alternate') {
							$class = 'alternate';
						} else {
							$class = '';
						}
						?>
					    <tr id='adrotateindex' class='<?php echo $class.$inactiveclass.$errorclass; ?>'>
							<th class="check-column"><input type="checkbox" name="disabledbannercheck[]" value="<?php echo $disbanner['id']; ?>" /></th>
							<td><center><?php echo $disbanner['id'];?></center></td>
							<td><?php echo date_i18n("F d, Y", $disbanner['firstactive']);?></td>
							<td><span style="color: <?php echo adrotate_prepare_color($disbanner['lastactive']);?>;"><?php echo date_i18n("F d, Y", $disbanner['lastactive']);?></span></td>
							<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=edit&ad='.$disbanner['id']);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($disbanner['title']));?></a></strong> - <a href="<?php echo admin_url('/admin.php?page=adrotate&view=report&ad='.$disbanner['id']);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a><?php if($groups) echo '<br /><em style="color:#999">'.$grouplist.'</em>'; ?></td>
							<td><center><?php echo $disbanner['weight']; ?></center></td>
							<td><center><?php echo $stats->impressions; ?></center></td>
							<?php if($disbanner['tracker'] == "Y") { ?>
							<td><center><?php echo $stats->clicks; ?></center></td>
							<td><center><?php echo $ctr; ?> %</center></td>
							<?php } else { ?>
							<td><center>--</center></td>
							<td><center>--</center></td>
							<?php } ?>
						</tr>
		 			<?php } ?>
					</tbody>
				</table>
				
				</form>
