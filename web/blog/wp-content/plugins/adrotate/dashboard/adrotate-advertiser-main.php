<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
		<?php
			$user_has_ads = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = 0 AND `block` = 0 AND `user` = ".$current_user->ID.";");

			if($user_has_ads > 0) {
				$result = adrotate_prepare_advertiser_report($current_user->ID); 
				
				// Get Click Through Rate
				$ctr = adrotate_ctr($result['total_clicks'], $result['total_impressions']);						
		?>
	
				<h3><?php _e('Your ads', 'adrotate'); ?></h3>
				
				<table class="widefat" style="margin-top: .5em">
					<thead>
						<tr>
						<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
						<th width="13%"><?php _e('Show from', 'adrotate'); ?></th>
						<th width="13%"><?php _e('Show until', 'adrotate'); ?></th>
						<th><?php _e('Title', 'adrotate'); ?></th>
						<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('CTR', 'adrotate'); ?></center></th>
						<th width="15%"><?php _e('Contact publisher', 'adrotate'); ?></th>
					</tr>
					</thead>
					
					<tbody>
				<?php
				if($result['ads']) {
					foreach($result['ads'] as $ad) {
						
						$class 			= ('alternate' != $class) ? 'alternate' : '';
						$expiredclass 	= ($ad['endshow'] <= $now OR $ad['endshow'] <= $in2days) ? ' error' : '';
				?>
					    <tr id='banner-<?php echo $ad['id']; ?>' class='<?php echo $class.$expiredclass; ?>'>
							<td><center><?php echo $ad['id'];?></center></td>
							<td><?php echo date_i18n("F d, Y", $ad['startshow']);?></td>
							<td><span style="color: <?php echo adrotate_prepare_color($ad['endshow']);?>;"><?php echo date_i18n("F d, Y", $ad['endshow']);?></span></td>
							<th><strong><?php echo stripslashes(html_entity_decode($ad['title']));?></strong></th>
							<td><center><?php echo $ad['impressions'];?></center></td>
							<td><center><?php echo $ad['impressions_today'];?></center></td>
							<td><center><?php echo $ad['clicks'];?></center></td>
							<td><center><?php echo $ad['clicks_today'];?></center></td>
							<?php if($ad['impressions'] == 0) $ad['impressions'] = 1; ?>
							<td><center><?php echo round((100/$ad['impressions']) * $ad['clicks'],2); ?> %</center></td>
							<td><a href="admin.php?page=adrotate-advertiser&view=message&request=renew&id=<?php echo $ad['id']; ?>"><?php _e('Renew', 'adrotate'); ?></a> - <a href="admin.php?page=adrotate-advertiser&view=message&request=remove&id=<?php echo $ad['id']; ?>"><?php _e('Remove', 'adrotate'); ?></a> - <a href="admin.php?page=adrotate-advertiser&view=message&request=other&id=<?php echo $ad['id']; ?>"><?php _e('Other', 'adrotate'); ?></a></td>
						</tr>
						<?php } ?>
				    <tr>
						<th width="10%" colspan="2"><?php _e('Export options', 'adrotate'); ?></th>
						<td width="40%" colspan="8">
				  			<form method="post" action="admin.php?page=adrotate">
				    			<input type="hidden" name="adrotate_export_id" value="<?php echo $current_user->ID; ?>" />
				    			<input type="hidden" name="adrotate_export_type" value="advertiser" />
				    			<input type="hidden" name="adrotate_export_month" value="0" />
				    			<input type="hidden" name="adrotate_export_year" value="0" />
								<input type="submit" name="adrotate_export_submit" class="button-primary" value="<?php _e('Export', 'adrotate'); ?>" /> <em><?php _e('Download this overview as a CSV file.', 'adrotate'); ?></em>
							</form>
						</td>
					</tr>
				<?php } else { ?>
					<tr id='no-ads'>
						<th>&nbsp;</th>
						<td colspan="10"><em><?php _e('No ads to show!', 'adrotate'); ?> <a href="admin.php?page=adrotate-advertiser&view=message&request=issue"><?php _e('Contact your publisher', 'adrotate'); ?></a>.</em></td>
					</tr>
				<?php } ?>
					</tbody>
				</table>

				<h3><?php _e('Summary', 'adrotate'); ?></h3>
				
				<table class="widefat" style="margin-top: .5em">					

					<thead>
					<tr>
						<th colspan="2"><?php _e('Overall statistics', 'adrotate'); ?></th>
						<th><?php _e('The last 8 clicks in the past 24 hours', 'adrotate'); ?></th>
					</tr>
					</thead>
					
					<tbody>

					<?php if($adrotate_debug['userstats'] == true) { ?>
					<tr>
						<td colspan="3">
							<?php 
							echo "<p><strong>User Report</strong><pre>"; 
							print_r($result); 
							echo "</pre></p>"; 
							?>
						</td>
					</tr>
					<?php } ?>
		
				    <tr>
						<th width="10%"><?php _e('General', 'adrotate'); ?></th>
						<td width="40%"><?php echo $result['ad_amount']; ?> <?php _e('ads, sharing a total of', 'adrotate'); ?> <?php echo $result['total_impressions']; ?> <?php _e('impressions.', 'adrotate'); ?></td>
						<td rowspan="5" style="border-left:1px #EEE solid;">
						<?php 
						if($result['last_clicks']) {
							foreach($result['last_clicks'] as $last) {
								$bannertitle = $wpdb->get_var("SELECT `title` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$last[bannerid]'");
								echo '<strong>'.date_i18n('d-m-Y', $last['timer']) .'</strong> - '. $bannertitle .'<br />';
							}
						} else {
							echo '<em>'.__('No recent clicks', 'adrotate').'</em>';
						} ?>
						</td>
					</tr>
				    <tr>
						<th><?php _e('The best', 'adrotate'); ?></th>
						<td><?php if($result['thebest']) {?>'<?php echo $result['thebest']['title']; ?>' <?php _e('with', 'adrotate'); ?> <?php echo $result['thebest']['clicks']; ?> <?php _e('clicks.', 'adrotate'); ?><?php } else { ?><?php _e('No ad stands out at this time.', 'adrotate'); ?><?php } ?></td>
					</tr>
				    <tr>
						<th><?php _e('The worst', 'adrotate'); ?></th>
						<td><?php if($result['theworst']) {?>'<?php echo $result['theworst']['title']; ?>' <?php _e('with', 'adrotate'); ?> <?php echo $result['theworst']['clicks']; ?> <?php _e('clicks.', 'adrotate'); ?><?php } else { ?><?php _e('All ads seem equally bad.', 'adrotate'); ?><?php } ?></td>
					</tr>
				    <tr>
						<th><?php _e('Average on all ads', 'adrotate'); ?></th>
						<td><?php echo $result['total_clicks']; ?> <?php _e('clicks.', 'adrotate'); ?></td>
					</tr>
				    <tr>
						<th><?php _e('Click-Through-Rate', 'adrotate'); ?></th>
						<td><?php echo $ctr; ?>%, <?php _e('based on', 'adrotate'); ?> <?php echo $result['total_impressions']; ?> <?php _e('impressions and', 'adrotate'); ?> <?php echo $result['total_clicks']; ?> <?php _e('clicks.', 'adrotate'); ?></td>
					</tr>
			      	<tr>
				        <th colspan="3">
				        	<?php
				        	$adstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker`, `".$wpdb->prefix."adrotate_linkmeta` WHERE `".$wpdb->prefix."adrotate_stats_tracker`.`ad` = `".$wpdb->prefix."adrotate_linkmeta`.`ad` AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = $current_user->ID GROUP BY `thetime` DESC LIMIT 21;");
		        			
							if($adstats) {
								
								$adstats = array_reverse($adstats);
		
								if($adrotate_debug['userstats'] == true) { 
									echo "<p><strong>[DEBUG] 21 days (Or as much as is available) Ad stats</strong><pre>"; 
									print_r($adstats); 
									echo "</pre></p>"; 
								}
		
								foreach($adstats as $stat) {
									if($stat->clicks == null) $stat->clicks = '0';
									if($stat->impressions == null) $stat->impressions = '0';
								
									$clicks_array[date_i18n("M d", $stat->thetime)] = $stat->clicks;
									$impressions_array[date_i18n("M d", $stat->thetime)] = $stat->impressions;
								}
			
								if($adrotate_debug['userstats'] == true) { 
									echo "<p><strong>[DEBUG] Found clicks as presented to PHPGraphLib</strong><pre>"; 
									print_r($clicks_array); 
									echo "</pre></p>"; 
									echo "<p><strong>[DEBUG] Found impressions as presented to PHPGraphLib</strong><pre>"; 
									print_r($impressions_array); 
									echo "</pre></p>"; 
								}
			
								$impressions_title = urlencode(serialize(__('Impressions of all your ads over the past 21 days', 'adrotate')));
								$impressions_array = urlencode(serialize($impressions_array));
								echo "<img src=\"".plugins_url("adrotate/library/graph_all_ads.php?title=$impressions_title&data=$impressions_array", "AdRotate")."\" />";

								$clicks_title = urlencode(serialize(__('Clicks of all your ads over the past 21 days', 'adrotate')));
								$clicks_array = urlencode(serialize($clicks_array));
								echo "<img src=\"".plugins_url("adrotate/library/graph_all_ads.php?title=$clicks_title&data=$clicks_array", "AdRotate")."\" />";
							} else {
								_e('No data to show!', 'adrotate');
							} 
							?>
				        </th>
			      	</tr>
					</tbody>
				</table>
				
			<?php } else { ?>
				<table class="widefat" style="margin-top: .5em">
					<thead>
						<tr>
							<th><?php _e('Notice', 'adrotate'); ?></th>
						</tr>
					</thead>
					<tbody>
					    <tr>
							<td><?php _e('No ads for user. If you feel this to be in error please', 'adrotate'); ?> <a href="admin.php?page=adrotate-advertiser&view=message&request=issue"><?php _e('contact the site administrator', 'adrotate'); ?></a>.</td>
						</tr>
					</tbody>
				</table>
			<?php } ?>