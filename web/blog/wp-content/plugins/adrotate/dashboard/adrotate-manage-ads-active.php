<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
<h3><?php _e('Active Ads', 'adrotate'); ?></h3>

<form name="banners" id="post" method="post" action="admin.php?page=adrotate">

	<div class="tablenav">
		<div class="alignleft actions">
			<select name="adrotate_action" id="cat" class="postform">
		        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
		        <option value="deactivate"><?php _e('Deactivate', 'adrotate'); ?></option>
		        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
		        <option value="reset"><?php _e('Reset stats', 'adrotate'); ?></option>
		        <option value="" disabled><?php _e('-- Renew --', 'adrotate'); ?></option>
		        <option value="renew-31536000"><?php _e('For 1 year', 'adrotate'); ?></option>
		        <option value="renew-5184000"><?php _e('For 180 days', 'adrotate'); ?></option>
		        <option value="renew-2592000"><?php _e('For 30 days', 'adrotate'); ?></option>
		        <option value="renew-604800"><?php _e('For 7 days', 'adrotate'); ?></option>
		        <option value="" disabled><?php _e('-- Weight --', 'adrotate'); ?></option>
		        <option value="weight-2">2 - <?php _e('Barely visible', 'adrotate'); ?></option>
		        <option value="weight-4">4 - <?php _e('Less than average', 'adrotate'); ?></option>
		        <option value="weight-6">6 - <?php _e('Normal coverage', 'adrotate'); ?></option>
		        <option value="weight-8">8 - <?php _e('More than average', 'adrotate'); ?></option>
		        <option value="weight-10">10 - <?php _e('Best visibility', 'adrotate'); ?></option>
			</select>
			<input type="submit" id="post-action-submit" name="adrotate_action_submit" value="Go" class="button-secondary" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php _e('Sort by', 'adrotate'); ?> <select name="adrotate_order" id="cat" class="postform">
		        <option value="sortorder ASC" <?php if($order == "sortorder ASC") { echo 'selected'; } ?>><?php _e('Sort Order (ascending, default)', 'adrotate'); ?></option>
		        <option value="sortorder DESC" <?php if($order == "sortorder DESC") { echo 'selected'; } ?>><?php _e('Sort Order (descending)', 'adrotate'); ?></option>
		        <option value="thetime ASC" <?php if($order == "thetime ASC") { echo 'selected'; } ?>><?php _e('Creation date (ascending)', 'adrotate'); ?></option>
		        <option value="thetime DESC" <?php if($order == "thetime DESC") { echo 'selected'; } ?>><?php _e('Creation date (descending)', 'adrotate'); ?></option>
		        <option value="updated ASC" <?php if($order == "updated ASC") { echo 'selected'; } ?>><?php _e('Updated (ascending)', 'adrotate'); ?></option>
		        <option value="updated DESC" <?php if($order == "updated DESC") { echo 'selected'; } ?>><?php _e('Updated (descending)', 'adrotate'); ?></option>
		        <option value="ID ASC" <?php if($order == "ID ASC") { echo 'selected'; } ?>><?php _e('ID', 'adrotate'); ?></option>
		        <option value="ID DESC" <?php if($order == "ID DESC") { echo 'selected'; } ?>><?php _e('ID reversed', 'adrotate'); ?></option>
		        <option value="title ASC" <?php if($order == "title ASC") { echo 'selected'; } ?>><?php _e('Title (A-Z)', 'adrotate'); ?></option>
		        <option value="title DESC" <?php if($order == "title DESC") { echo 'selected'; } ?>><?php _e('Title (Z-A)', 'adrotate'); ?></option>
			</select>
			<input type="submit" id="post-query-submit" name="adrotate_order_submit" value="Sort" class="button-secondary" />
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
			<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('CTR', 'adrotate'); ?></center></th>
		</tr>
		</thead>
		<tbody>
	<?php
	if ($activebanners) {
		foreach($activebanners as $banner) {
			$today = gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
			$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '".$banner['id']."';");
			$stats_today = $wpdb->get_row("SELECT `clicks`, `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '".$banner['id']."' AND `thetime` = '$today';");

			// Get Click Through Rate
			$ctr = adrotate_ctr($stats->clicks, $stats->impressions);						

			// Prevent gaps in display
			if($stats->impressions == 0) 		$stats->impressions 		= 0;
			if($stats->clicks == 0)				$stats->clicks 				= 0;
			if($stats_today->impressions == 0) 	$stats_today->impressions 	= 0;
			if($stats_today->clicks == 0) 		$stats_today->clicks 		= 0;
			
			if($adrotate_debug['dashboard'] == true) {
				echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='9'><pre>";
				$memory = (memory_get_usage() / 1024 / 1024);
				echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
				$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
				echo "Peak memory usage: " . round($peakmemory, 2) ." MB</pre><br />"; 
				echo "Ad Specs: <pre>";
				print_r($banner); 
				echo "</pre>"; 
				echo "Stats: <pre>";
				print_r($stats); 
				echo "</pre>"; 
				echo "Stats today: <pre>";
				print_r($stats_today); 
				echo "</pre></td></tr>"; 
			}
						
			$groups	= $wpdb->get_results("
				SELECT 
					`".$wpdb->prefix."adrotate_groups`.`name` 
				FROM 
					`".$wpdb->prefix."adrotate_groups`, 
					`".$wpdb->prefix."adrotate_linkmeta` 
				WHERE 
					`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$banner['id']."'
					AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
					AND `".$wpdb->prefix."adrotate_linkmeta`.`block` = 0
					AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
				;");
			$grouplist = '';
			foreach($groups as $group) {
				$grouplist .= $group->name.", ";
			}
			$grouplist = rtrim($grouplist, ", ");
			
			if($class != 'alternate') {
				$class = 'alternate';
			} else {
				$class = '';
			}

			if($banner['lastactive'] <= $in7days) {
				$errorclass = ' row_error';
			} else {
				$errorclass = '';
			}

			?>
		    <tr id='adrotateindex' class='<?php echo $class.$errorclass; ?>'>
				<th class="check-column"><input type="checkbox" name="bannercheck[]" value="<?php echo $banner['id']; ?>" /></th>
				<td><center><?php echo $banner['id'];?></center></td>
				<td><?php echo date_i18n("F d, Y", $banner['firstactive']);?></td>
				<td><span style="color: <?php echo adrotate_prepare_color($banner['lastactive']);?>;"><?php echo date_i18n("F d, Y", $banner['lastactive']);?></span></td>
				<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=edit&ad='.$banner['id']);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($banner['title']));?></a></strong> - <a href="<?php echo admin_url('/admin.php?page=adrotate&view=report&ad='.$banner['id']);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a><?php if($groups) echo '<br /><em style="color:#999">'.$grouplist.'</em>'; ?></td>
				<td><center><?php echo $banner['weight']; ?></center></td>
				<td><center><?php echo $stats->impressions; ?></center></td>
				<td><center><?php echo $stats_today->impressions; ?></center></td>
				<?php if($banner['tracker'] == "Y") { ?>
				<td><center><?php echo $stats->clicks; ?></center></td>
				<td><center><?php echo $stats_today->clicks; ?></center></td>
				<td><center><?php echo $ctr; ?> %</center></td>
				<?php } else { ?>
				<td><center>--</center></td>
				<td><center>--</center></td>
				<td><center>--</center></td>
				<?php } ?>
			</tr>
			<?php } ?>
		<?php } else { ?>
		<tr id='no-groups'>
			<th class="check-column">&nbsp;</th>
			<td colspan="10"><em><?php _e('No ads created yet!', 'adrotate'); ?></em></td>
		</tr>
	<?php } ?>
	</tbody>
</table>

</form>
