<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
			<h3><?php _e('Ads that need immediate attention', 'adrotate'); ?></h3>

			<form name="errorbanners" id="post" method="post" action="admin.php?page=adrotate">
			
			<div class="tablenav">
				<div class="alignleft actions">
					<select name="adrotate_error_action" id="cat" class="postform">
				        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
				        <option value="deactivate"><?php _e('Deactivate', 'adrotate'); ?></option>
				        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
				        <option value="reset"><?php _e('Reset stats', 'adrotate'); ?></option>
				        <option value="" disabled><?php _e('-- Renew --', 'adrotate'); ?></option>
				        <option value="renew-31536000"><?php _e('For 1 year', 'adrotate'); ?></option>
				        <option value="renew-5184000"><?php _e('For 180 days', 'adrotate'); ?></option>
				        <option value="renew-2592000"><?php _e('For 30 days', 'adrotate'); ?></option>
				        <option value="renew-604800"><?php _e('For 7 days', 'adrotate'); ?></option>
					</select>
					<input type="submit" id="post-action-submit" name="adrotate_error_action_submit" value="Go" class="button-secondary" />
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
					</tr>
	  			</thead>
	  			<tbody>
				<?php foreach($errorbanners as $errbanner) {
					$today = gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
					
					if($adrotate_debug['dashboard'] == true) {
						echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='9'><pre>";
						$memory = (memory_get_usage() / 1024 / 1024);
						echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
						$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
						echo "Peak memory usage: " . round($peakmemory, 2) ." MB</pre><br />"; 
						echo "Ad Specs: <pre>";
						print_r($errbanner); 
						echo "</pre></td></tr>"; 
					}
								
					$groups	= $wpdb->get_results("
						SELECT 
							`".$wpdb->prefix."adrotate_groups`.`name` 
						FROM 
							`".$wpdb->prefix."adrotate_groups`, 
							`".$wpdb->prefix."adrotate_linkmeta` 
						WHERE 
							`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$errbanner['id']."'
							AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
							AND `".$wpdb->prefix."adrotate_linkmeta`.`block` = 0
							AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
						;");
					$grouplist = '';
					foreach($groups as $group) {
						$grouplist .= $group->name.", ";
					}
					$grouplist = rtrim($grouplist, ", ");
					
					if($errbanner['type'] == 'error') {
						$errorclass = ' row_error';
					} else {
						$errorclass = '';
					}

					if($errbanner['lastactive'] <= $now OR $errbanner['lastactive'] <= $in2days) {
						$expiredclass = ' row_urgent';
					} else {
						$expiredclass = '';
					}

					if($class != 'alternate') {
						$class = 'alternate';
					} else {
						$class = '';
					}
					?>
				    <tr id='adrotateindex' class='<?php echo $class.$expiredclass.$errorclass; ?>'>
						<th class="check-column"><input type="checkbox" name="errorbannercheck[]" value="<?php echo $errbanner['id']; ?>" /></th>
						<td><center><?php echo $errbanner['id'];?></center></td>
						<td><?php echo date_i18n("F d, Y", $errbanner['firstactive']);?></td>
						<td><span style="color: <?php echo adrotate_prepare_color($errbanner['lastactive']);?>;"><?php echo date_i18n("F d, Y", $errbanner['lastactive']);?></span></td>
						<td><strong><a class="row-title" href="<?php echo admin_url("/admin.php?page=adrotate&view=edit&ad=".$errbanner['id']);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($errbanner['title']));?></a></strong> - <a href="<?php echo admin_url("/admin.php?page=adrotate&view=report&ad=".$errbanner['id']);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a><?php if($groups) echo '<br /><em style="color:#999">'.$grouplist.'</em>'; ?></td>
					</tr>
	 			<?php } ?>
				</tbody>
			</table>
			</form>
