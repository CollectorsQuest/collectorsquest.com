<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
			<h3><?php _e('Manage Blocks', 'adrotate'); ?></h3>

			<form name="blocks" id="post" method="post" action="admin.php?page=adrotate-blocks">
	
				<div class="tablenav">
					<div class="alignleft">
						<select name="adrotate_action" id="cat" class="postform">
					        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
					        <option value="block_delete"><?php _e('Delete Block(s)', 'adrotate'); ?></option>
						</select>
						<input onclick="return confirm('<?php _e('You are about to delete a block', 'adrotate'); ?>\n<?php _e('This action can not be undone!', 'adrotate'); ?>\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" type="submit" id="post-action-submit" name="adrotate_action_submit" value="<?php _e('Go', 'adrotate'); ?>" class="button-secondary" />
					</div>
				</div>
	
			   	<table class="widefat" style="margin-top: .5em">

		  			<thead>
	  				<tr>
						<th class="check-column">&nbsp;</th>
						<th width="5%"><center><?php _e('ID', 'adrotate'); ?></center></th>
						<th><?php _e('Name', 'adrotate'); ?></th>
						<th width="5%"><center><?php _e('Groups', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="15%"><center><?php _e('Code', 'adrotate'); ?></center></th>
					</tr>
		  			</thead>

					<tbody>
		  			
					<?php $blocks = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix . "adrotate_blocks` WHERE `name` != '' ORDER BY `sortorder` ASC, `id` ASC;");
					if ($blocks) {
						foreach($blocks as $block) {
							$today 			= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
							$stats 			= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `block` = '$block->id';");
							$stats_today	= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `block` = '$block->id' AND `thetime` = '$today';");
	
							// Prevent gaps in display
							if($stats->impressions == 0) 		$stats->impressions 		= 0;
							if($stats->clicks == 0) 			$stats->clicks 				= 0;
							if($stats_today->impressions == 0) 	$stats_today->impressions 	= 0;
							if($stats_today->clicks == 0) 		$stats_today->clicks 		= 0;

							$groups_in_block = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `block` = ".$block->id.";");
							$class = ('alternate' != $class) ? 'alternate' : ''; ?>
						    <tr class='<?php echo $class; ?>'>
								<th class="check-column"><input type="checkbox" name="blockcheck[]" value="<?php echo $block->id; ?>" /></th>
								<td><center><?php echo $block->id;?></center></td>
								<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=edit&block='.$block->id);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo $block->name;?></a></strong><br /><a href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=report&block='.$block->id);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a></td>
								<td><center><?php echo $groups_in_block;?></center></td>
								<td><center><?php echo $stats->impressions;?></center></td>
								<td><center><?php echo $stats_today->impressions;?></center></td>
								<td><center><?php echo $stats->clicks;?></center></td>
								<td><center><?php echo $stats_today->clicks;?></center></td>
								<td><center>[adrotate block="<?php echo $block->id; ?>"]</center></td>
							</tr>
						<?php unset($stats);?>
			 			<?php } ?>
					<?php } else { ?>
					<tr>
						<th class="check-column">&nbsp;</th>
						<td colspan="8"><em><?php _e('No blocks created yet!', 'adrotate'); ?></em></td>
					</tr>
					<?php } ?>
		 			</tbody>

				</table>
			</form>
