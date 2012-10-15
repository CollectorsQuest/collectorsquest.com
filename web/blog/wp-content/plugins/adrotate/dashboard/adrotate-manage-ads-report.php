<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
<h3><?php _e('This ads performance', 'adrotate'); ?></h3>

<?php
	$today 			= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
	$banner 		= $wpdb->get_row($wpdb->prepare("SELECT `title`, `tracker` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '%s';", $ad_edit_id));
	$stats 			= $wpdb->get_row($wpdb->prepare("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '%s';", $ad_edit_id));
	$stats_today 	= $wpdb->get_row($wpdb->prepare("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '%s' AND `thetime` = '$today';"), $ad_edit_id);

	// Prevent gaps in display
	if($stats->impressions == 0) { 
		$impressions = 0;
	} else {
		$impressions = $stats->impressions;
	}
	
	if($stats_today->impressions == 0) { 
		$todayimpressions = 0;
	} else {
		$todayimpressions = $stats_today->impressions;
	}
	
	if($banner->tracker == 'Y') {
		$ctr = adrotate_ctr($stats->clicks, $stats->impressions);						
		$ctr = $ctr.' %';

		if($stats->clicks == 0) { 
			$clicks = 0;
		} else { 
			$clicks = $stats->clicks;
		}
		if($stats_today->clicks == 0) { 
			$todayclicks = 0;
		} else { 
			$todayclicks = $stats_today->clicks;
		}
	} else {
		$clicks = $todayclicks = $ctr = '--';
	}

	if($adrotate_debug['stats'] == true) {
		echo "<p><strong>[DEBUG] Ad Stats (all time)</strong><pre>";
		$memory = (memory_get_usage() / 1024 / 1024);
		echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
		$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
		echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
		print_r($stats); 
		echo "</pre></p>"; 
		echo "<p><strong>[DEBUG] Ad Stats (today)</strong><pre>";
		print_r($stats_today); 
		echo "</pre></p>"; 
	}	

?>

<table class="widefat" style="margin-top: .5em">
	<thead>
	<tr>
		<th colspan="5" bgcolor="#DDD"><?php _e('Statistics for', 'adrotate'); ?> '<?php echo $banner->title; ?>'</th>
	</tr>
	</thead>

	<tbody>
  	<tr>
        <td width="20%"><div class="stats_large"><?php _e('Impressions', 'adrotate'); ?><br /><div class="number_large"><?php echo $impressions; ?></div></div></td>
        <td width="20%"><div class="stats_large"><?php _e('Clicks', 'adrotate'); ?><br /><div class="number_large"><?php echo $clicks; ?></div></div></td>
        <td width="20%"><div class="stats_large"><?php _e('Impressions today', 'adrotate'); ?><br /><div class="number_large"><?php echo $todayimpressions; ?></div></div></td>
        <td width="20%"><div class="stats_large"><?php _e('Clicks today', 'adrotate'); ?><br /><div class="number_large"><?php echo $todayclicks; ?></div></div></td>
        <td width="20%"><div class="stats_large"><?php _e('CTR', 'adrotate'); ?><br /><div class="number_large"><?php echo $ctr; ?></div></div></td>
  	</tr>
  	<tr>
        <th colspan="5">
        	<?php
        	$adstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$ad_edit_id' GROUP BY `thetime` DESC LIMIT 21;");
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
					
					$clicks_array[date_i18n("d M", $result->thetime)] = $result->clicks;
					$impressions_array[date_i18n("d M", $result->thetime)] = $result->impressions;
				}
				
				if($adrotate_debug['stats'] == true) { 
					echo "<p><strong>[DEBUG] Found clicks as presented to PHPGraphLib</strong><pre>"; 
					print_r($clicks_array); 
					echo "</pre></p>"; 
					echo "<p><strong>[DEBUG] Found impressions as presented to PHPGraphLib</strong><pre>"; 
					print_r($impressions_array); 
					echo "</pre></p>"; 
				}

				$impressions_title = urlencode(serialize(__('Impressions over the past 21 days', 'adrotate')));
				$impressions_array = urlencode(serialize($impressions_array));
				echo "<img src=\"".plugins_url("adrotate/library/graph_single_ad.php?title=$impressions_title&data=$impressions_array", "AdRotate")."\" />";

				if($banner->tracker == "Y") {
					$clicks_title = urlencode(serialize(__('Clicks over the past 21 days', 'adrotate')));
					$clicks_array = urlencode(serialize($clicks_array));
					echo "<img src=\"".plugins_url("adrotate/library/graph_single_ad.php?title=$clicks_title&data=$clicks_array", "AdRotate")."\" />";
				}
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
		<th colspan="5" bgcolor="#DDD"><?php _e('Export options for', 'adrotate'); ?> '<?php echo $banner->title; ?>'</th>
	</tr>
	</thead>
    <tbody>
    <tr>
		<th width="10%"><?php _e('Select period', 'adrotate'); ?></th>
		<td width="40%" colspan="4">
			<?php wp_nonce_field('adrotate_report_ads','adrotate_nonce'); ?>
	    	<input type="hidden" name="adrotate_export_id" value="<?php echo $ad_edit_id; ?>" />
			<input type="hidden" name="adrotate_export_type" value="single" />
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
  	<tr>
		<td colspan="5">
			<b><?php _e('Note:', 'adrotate'); ?></b> <em><?php _e('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate'); ?></em>
		</td>
  	</tr>
	</tbody>
	</form>
</table>