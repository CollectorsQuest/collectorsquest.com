<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
		<table class="widefat" style="margin-top: .5em">

			<thead>
			<tr>
				<th colspan="4"><?php _e('Overall statistics', 'adrotate'); ?></th>
			</tr>
			</thead>
			
			<tbody>

			<?php if($adrotate_debug['stats'] == true) { ?>
			<tr>
				<td colspan="4">
					<?php 
					echo "<p><strong>Globalized Statistics from cache</strong><pre>"; 
					print_r($adrotate_stats); 
					echo "</pre></p>"; 
					?>
				</td>
			</tr>
			<?php } ?>

		    <tr>
				<th width="10%"><?php _e('General', 'adrotate'); ?></th>
				<td width="40%"><?php echo $adrotate_stats['banners']; ?> <?php _e('ads, sharing a total of', 'adrotate'); ?> <?php echo $adrotate_stats['impressions']; ?> <?php _e('impressions.', 'adrotate'); ?> <?php echo $adrotate_stats['tracker']; ?> <?php _e('ads have tracking enabled.', 'adrotate'); ?></td>
			</tr>
		    <tr>
				<th><?php _e('Average clicks on all ads', 'adrotate'); ?></th>
				<td><?php echo $clicks; ?></td>
			</tr>
		    <tr>
				<th><?php _e('Click-Through-Rate', 'adrotate'); ?></th>
				<td><?php echo $ctr; ?>%, <?php _e('based on', 'adrotate'); ?> <?php echo $adrotate_stats['impressions']; ?> <?php _e('impressions and', 'adrotate'); ?> <?php echo $adrotate_stats['clicks']; ?> <?php _e('clicks.', 'adrotate'); ?></td>
			</tr>
	      	<tr>
		        <th colspan="4">
		        	<?php
		        	$adstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` GROUP BY `thetime` DESC LIMIT 21;");

					if($adstats) {
					
						$adstats = array_reverse($adstats);

						if($adrotate_debug['stats'] == true) { 
							echo "<p><strong>[DEBUG] 21 days (Or as much as is available) Ad stats</strong><pre>"; 
							print_r($adstats); 
							echo "</pre></p>"; 
						}

						foreach($adstats as $result) {
							if($result->clicks == null) $result->clicks = '0';
							if($result->impressions == null) $result->impressions = '0';
						
							$clicks_array[date_i18n("M d", $result->thetime)] = $result->clicks;
							$impressions_array[date_i18n("M d", $result->thetime)] = $result->impressions;
						}

						if($adrotate_debug['stats'] == true) { 
							echo "<p><strong>[DEBUG] Found clicks as presented to PHPGraphLib</strong><pre>"; 
							print_r($clicks_array);
							echo "</pre></p>"; 
							echo "<p><strong>[DEBUG] Found impressions as presented to PHPGraphLib</strong><pre>"; 
							print_r($impressions_array);
							echo "</pre></p>"; 
						}

						$impressions_title = urlencode(serialize(__('Total impressions of all ads over the past 21 days')));
						$impressions_array = urlencode(serialize($impressions_array));
						echo "<img src=\"".plugins_url("adrotate/library/graph_all_ads.php?title=$impressions_title&data=$impressions_array", "AdRotate")."\" />";

						$clicks_title = urlencode(serialize(__('Total clicks of all ads over the past 21 days')));
						$clicks_array = urlencode(serialize($clicks_array));
						echo "<img src=\"".plugins_url("adrotate/library/graph_all_ads.php?title=$clicks_title&data=$clicks_array", "AdRotate")."\" />";
					} else {
						_e('No data to show!', 'adrotate');
					} 
					?>
		        </th>
	      	</tr>
			</tbody>
			
	 			<form method="post" action="admin.php?page=adrotate">
			<thead>
			<tr>
				<th colspan="5" bgcolor="#DDD"><?php _e('Export options for', 'adrotate'); ?> 'Global report'</th>
			</tr>
			</thead>
		    <tbody>
		    <tr>
				<th width="10%"><?php _e('Select period', 'adrotate'); ?></th>
				<td width="40%" colspan="4">
			    	<input type="hidden" name="adrotate_export_id" value="0" />
	    			<input type="hidden" name="adrotate_export_type" value="global" />
			        <select name="adrotate_export_month" id="cat" class="postform">
				        <option value="0"><?php _e('Whole year', 'adrotate'); ?></option>
				        <option value="1" <?php if($export_month == "1") { echo 'selected'; } ?>><?php _e('January', 'adrotate'); ?></option>
				        <option value="2" <?php if($export_month == "2") { echo 'selected'; } ?>><?php _e('February', 'adrotate'); ?></option>
				        <option value="3" <?php if($export_month == "3") { echo 'selected'; } ?>><?php _e('March', 'adrotate'); ?></option>
				        <option value="4" <?php if($export_month == "4") { echo 'selected'; } ?>><?php _e('April', 'adrotate'); ?></option>
				        <option value="5" <?php if($export_month == "5") { echo 'selected'; } ?>><?php _e('May', 'adrotate'); ?></option>
				        <option value="6" <?php if($export_month == "6") { echo 'selected'; } ?>><?php _e('June', 'adrotate'); ?></option>
				        <option value="7" <?php if($export_month == "7") { echo 'selected'; } ?>><?php _e('July', 'adrotate'); ?></option>
				        <option value="8" <?php if($export_month == "8") { echo 'selected'; } ?>><?php _e('August', 'adrotate'); ?></option>
				        <option value="9" <?php if($export_month == "9") { echo 'selected'; } ?>><?php _e('September', 'adrotate'); ?></option>
				        <option value="10" <?php if($export_month == "10") { echo 'selected'; } ?>><?php _e('October', 'adrotate'); ?></option>
				        <option value="11" <?php if($export_month == "11") { echo 'selected'; } ?>><?php _e('November', 'adrotate'); ?></option>
				        <option value="12" <?php if($export_month == "12") { echo 'selected'; } ?>><?php _e('December', 'adrotate'); ?></option>
					</select> 
					<input type="text" name="adrotate_export_year" size="10" class="search-input" value="<?php echo gmdate('Y'); ?>" autocomplete="off" />
				</td>
			</tr>
		    <tr>
				<th width="10%"><?php _e('Email options', 'adrotate'); ?></th>
				<td width="40%" colspan="4">
		  			<input type="text" name="adrotate_export_addresses" size="45" class="search-input" value="" autocomplete="off" /> <em><?php _e('Maximum of 3 email addresses, comma seperated. Leave empty to download the CSV file instead.', 'adrotate'); ?></em>
				</td>
			</tr>
		    <tr>
				<th width="10%">&nbsp;</th>
				<td width="40%" colspan="4">
		  			<input type="submit" name="adrotate_export_submit" class="button-primary" value="<?php _e('Export', 'adrotate'); ?>" /> <em><?php _e('Download or email your selected timeframe as a CSV file.', 'adrotate'); ?></em>
				</td>
			</tr>
			</tbody>
			</form>

			<thead>
			<tr>
				<th colspan="4"><?php _e('The last 50 clicks in the past 24 hours', 'adrotate'); ?></th>
			</tr>
			</thead>
			
			<tbody>
			<tr>
				<td colspan="4">
				<?php 
				if($adrotate_stats['lastclicks']) {
					foreach($adrotate_stats['lastclicks'] as $last) {
						$bannertitle = $wpdb->get_var("SELECT `title` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$last[bannerid]'");
						echo '<strong>'.date_i18n('d-m-Y', $last['timer']) .'</strong> - '. $bannertitle .' - '.$last['useragent'].'<br />';
					}
				} else {
					echo '<em>'.__('No recent clicks', 'adrotate').'</em>';
				} ?>
				</td>
			</tr>
	      	<tr>
				<td colspan="4">
					<b><?php _e('Note:', 'adrotate'); ?></b> <em><?php _e('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate'); ?></em>
				</td>
	      	</tr>
			</tbody>
		</table>
