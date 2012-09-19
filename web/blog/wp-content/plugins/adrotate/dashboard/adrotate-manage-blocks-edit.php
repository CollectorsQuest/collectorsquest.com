<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
				<?php if(!$block_edit_id) { ?>
				<h3><?php _e('New Block', 'adrotate'); ?></h3>
					<?php
					$action = "block_new";
					$query = "SELECT `id` FROM `".$wpdb->prefix."adrotate_blocks` WHERE `name` = '' ORDER BY `id` DESC LIMIT 1;";
					$edit_id = $wpdb->get_var($query);
					if($edit_id == 0) {
					    $wpdb->insert($wpdb->prefix."adrotate_blocks", array('name' => '', 'rows' => 2, 'columns' => 2, 'gridfloat' => 'none', 'gridpadding' => 1, 'gridborder' => 0, 'adwidth' => 125, 'adheight' => 125, 'admargin' => 1, 'adpadding' => 0, 'adborder' => 0, 'wrapper_before' => '', 'wrapper_after' => '', 'sortorder' => 0));
					    $edit_id = $wpdb->insert_id;
					}
					$block_edit_id = $edit_id;
					?>
				<?php } else { ?>
				<h3><?php _e('Edit Block', 'adrotate'); ?></h3>
				<?php 
					$action = "block_edit";
				} 
				
				$edit_block = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate_blocks` WHERE `id` = '$block_edit_id';");
				$groups		= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' ORDER BY `id` ASC;"); 
				$linkmeta	= $wpdb->get_results("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = 0 AND `block` = '$block_edit_id' AND `user` = 0;");
				foreach($linkmeta as $meta) {
					$meta_array[] = $meta->group;
				}
				if(!is_array($meta_array)) $meta_array = array();
				
				// Split and make the grid border settings useful
				list($gridborderpx, $gridbordercolor, $gridborderstyle) = explode(" ", $edit_block->gridborder, 3);
				$gridborderpx = rtrim($gridborderpx, "px");
				if($gridbordercolor == '') $gridbordercolor = '#fff';
				// And for adverts
				list($adborderpx, $adbordercolor, $adborderstyle) = explode(" ", $edit_block->adborder, 3);
				$adborderpx = rtrim($adborderpx, "px");
				if($adbordercolor == '') $adbordercolor = '#fff';
				?>

				<form name="editblock" id="post" method="post" action="admin.php?page=adrotate-blocks">
			    	<input type="hidden" name="adrotate_id" value="<?php echo $edit_block->id;?>" />
			    	<input type="hidden" name="adrotate_action" value="<?php echo $action;?>" />

				   	<table class="widefat" style="margin-top: .5em">
				   	
			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('The basics', 'adrotate'); ?></th>
						</tr>
			  			</thead>
			  			
						<tbody>
					    <tr>
							<th width="15%"><?php _e('ID:', 'adrotate'); ?></th>
							<td colspan="3"><?php echo $edit_block->id; ?></td>
						</tr>
					    <tr>
							<th width="15%"><?php _e('Name / Reference:', 'adrotate'); ?></th>
							<td colspan="3"><input tabindex="1" name="adrotate_blockname" type="text" class="search-input" size="80" value="<?php echo $edit_block->name; ?>" autocomplete="off" /></td>
						</tr>
				      	<tr>
					        <th><?php _e('Sortorder:', 'adrotate'); ?></th>
					        <td colspan="3">
						        <input tabindex="2" name="adrotate_sortorder" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_block->sortorder;?>" /> <em><?php _e('For administrative purposes set a sortorder.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this. Will default to block id.', 'adrotate'); ?></em>
							</td>
				      	</tr>
						</tbody>
	
			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('Block shape  and border (Required) - Define the shape and size of the block', 'adrotate'); ?></th>
						</tr>
			  			</thead>
			  			
						<tbody>
					    <tr>
							<th valign="top"><?php _e('Rows and Columns', 'adrotate'); ?></strong></th>
							<td>
								<label for="adrotate_gridrows"><input tabindex="3" name="adrotate_gridrows" type="text" class="search-input" size="3" value="<?php echo $edit_block->rows; ?>" autocomplete="off" /> <?php _e('rows', 'adrotate'); ?>,</label> <label for="adrotate_gridcolumns"><input tabindex="4" name="adrotate_gridcolumns" type="text" class="search-input" size="3" value="<?php echo $edit_block->columns; ?>" autocomplete="off" /> <?php _e('columns', 'adrotate'); ?>.</label>
							</td>
							<td colspan="2">
						        <p><em><?php _e('Make a grid for your ads. Filling in 2 and 2 makes a 2x2 grid. (Default: 2/2)', 'adrotate'); ?></em></p>
							</td>
						</tr>
					    <tr>
							<th valign="top"><?php _e('Float', 'adrotate'); ?></strong></th>
							<td>
								<label for="adrotate_gridfloat"><select tabindex="6" name="adrotate_gridfloat">
						        	<option value="none" <?php if($edit_block->gridfloat == 'none') { echo 'selected'; } ?>><?php _e('None', 'adrotate'); ?></option>
						        	<option value="left" <?php if($edit_block->gridfloat == "left") { echo 'selected'; } ?>><?php _e('Left', 'adrotate'); ?></option>
						        	<option value="right" <?php if($edit_block->gridfloat == "right") { echo 'selected'; } ?>><?php _e('Right', 'adrotate'); ?></option>
						        	<option value="inherit" <?php if($edit_block->gridfloat == "inherit") { echo 'selected'; } ?>><?php _e('Inherit', 'adrotate'); ?></option>
						        </select></label> 
							</td>
							<td colspan="2">
								<p><em><?php _e('This will help in aligning your block. Set to none if unsure.', 'adrotate'); ?></em></p>
							</td>
						</tr>
					    <tr>
							<th valign="top"><?php _e('Block Padding', 'adrotate'); ?></strong></th>
							<td>
								<label for="adrotate_gridpadding"><input tabindex="5" name="adrotate_gridpadding" type="text" class="search-input" size="3" value="<?php echo $edit_block->gridpadding; ?>" autocomplete="off" /> <?php _e('pixel(s)', 'adrotate'); ?>.</label>
								</td>
							<td colspan="2">
						        <p><em><?php _e('An invisible border inside the block in pixels. (Default: 1)', 'adrotate'); ?></em></p>
							</td>
						</tr>
					    <tr>
							<th valign="top"><?php _e('Block Border', 'adrotate'); ?></strong></th>
							<td>
								<label for="adrotate_gridborderstyle"><?php _e('Style', 'adrotate'); ?> 
						        <select tabindex="6" name="adrotate_gridborderstyle">
						        	<option value="" <?php if($gridborderstyle == '') { echo 'selected'; } ?>><?php _e('None', 'adrotate'); ?></option>
						        	<option value="solid" <?php if($gridborderstyle == "solid") { echo 'selected'; } ?>><?php _e('Solid', 'adrotate'); ?></option>
						        	<option value="dotted" <?php if($gridborderstyle == "dotted") { echo 'selected'; } ?>><?php _e('Dotted', 'adrotate'); ?></option>
						        	<option value="dashed" <?php if($gridborderstyle == "dashed") { echo 'selected'; } ?>><?php _e('Dashed', 'adrotate'); ?></option>
						        	<option value="double" <?php if($gridborderstyle == "double") { echo 'selected'; } ?>><?php _e('Double', 'adrotate'); ?></option>
						        	<option value="groove" <?php if($gridborderstyle == "groove") { echo 'selected'; } ?>><?php _e('Groove', 'adrotate'); ?></option>
						        	<option value="ridge" <?php if($gridborderstyle == "ridge") { echo 'selected'; } ?>><?php _e('Ridge', 'adrotate'); ?></option>
						        	<option value="inset" <?php if($gridborderstyle == "inset") { echo 'selected'; } ?>><?php _e('Inset', 'adrotate'); ?></option>
						        	<option value="outset" <?php if($gridborderstyle == "outset") { echo 'selected'; } ?>><?php _e('Outset', 'adrotate'); ?></option>
						        </select></label> 
								<label for="adrotate_gridborderpx"><?php _e('Width', 'adrotate'); ?> <input tabindex="7" name="adrotate_gridborderpx" type="text" class="search-input" size="3" value="<?php echo $gridborderpx; ?>" autocomplete="off" /> <?php _e('pixel(s). Color', 'adrotate'); ?> </label>
								<label for="adrotate_gridbordercolor"><input tabindex="8" type="text" id="gridcolor" name="adrotate_gridbordercolor" size="8" value="<?php echo $gridbordercolor; ?>" /></label> 
						        <div id="adrotate_grid_colorpicker"></div>
						        <script type="text/javascript">
								jQuery(document).ready(function() {
									jQuery('#adrotate_grid_colorpicker').hide();
									jQuery('#adrotate_grid_colorpicker').farbtastic("#gridcolor");
									jQuery("#gridcolor").click(function(){jQuery('#adrotate_grid_colorpicker').slideDown()});
									jQuery("#gridcolor").blur(function(){jQuery('#adrotate_grid_colorpicker').slideUp()});
								});
  								</script>

							</td>
							<td colspan="2">
								<p><em><?php _e('Set the border width to 0 to disable. Color must be a valid hex value. (Default: 0/#fff/none)', 'adrotate'); ?></em></p>
							</td>
						</tr>
						</tbody>

			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('Advert shape and border (Required) - Define the shape and size of the adverts', 'adrotate'); ?></th>
						</tr>
			  			</thead>
			  			
						<tbody>
					    <tr>
							<th valign="top"><?php _e('Width and Height', 'adrotate'); ?></strong></th>
							<td>
								<label for="adrotate_adwidth"><input tabindex="9" name="adrotate_adwidth" type="text" class="search-input" size="3" value="<?php echo $edit_block->adwidth; ?>" autocomplete="off" /> <?php _e('pixel(s) wide', 'adrotate'); ?>,</label> <label for="adrotate_adheight"><input tabindex="10" name="adrotate_adheight" type="text" class="search-input" size="3" value="<?php echo $edit_block->adheight; ?>" autocomplete="off" /> <?php _e('pixel(s) high.', 'adrotate'); ?></label>
							</td>
							<td colspan="2">
						        <p><em><?php _e('Define the maximum size of the ads in pixels. Height can be \'auto\' (Default: 125/125)', 'adrotate'); ?></em></p>
							</td>
						</tr>
					    <tr>
							<th valign="top"><?php _e('Advert Margin and Padding', 'adrotate'); ?></strong></th>
							<td>
								<label for="adrotate_admargin"><input tabindex="11" name="adrotate_admargin" type="text" class="search-input" size="3" value="<?php echo $edit_block->admargin; ?>" autocomplete="off" /> <?php _e('pixel(s) margin', 'adrotate'); ?>,</label> <label for="adrotate_adpadding"><input tabindex="12" name="adrotate_adpadding" type="text" class="search-input" size="3" value="<?php echo $edit_block->adpadding; ?>" autocomplete="off" /> <?php _e('pixel(s) padding.', 'adrotate'); ?></label>
								</td>
							<td colspan="2">
						        <p><em><?php _e('An invisible border outside and inside the advert in pixels. (Default: 1/0)', 'adrotate'); ?></em></p>
							</td>
						</tr>
					    <tr>
							<th valign="top"><?php _e('Advert Border', 'adrotate'); ?></strong></th>
							<td>
						        <label for="adrotate_adborderstyle"><?php _e('Style', 'adrotate'); ?> 
						       	<select tabindex="13" name="adrotate_adborderstyle">
						        	<option value="" <?php if($adborderstyle == '') { echo 'selected'; } ?>><?php _e('None', 'adrotate'); ?></option>
						        	<option value="solid" <?php if($adborderstyle == "solid") { echo 'selected'; } ?>><?php _e('Solid', 'adrotate'); ?></option>
						        	<option value="dotted" <?php if($adborderstyle == "dotted") { echo 'selected'; } ?>><?php _e('Dotted', 'adrotate'); ?></option>
						        	<option value="dashed" <?php if($adborderstyle == "dashed") { echo 'selected'; } ?>><?php _e('Dashed', 'adrotate'); ?></option>
						        	<option value="double" <?php if($adborderstyle == "double") { echo 'selected'; } ?>><?php _e('Double', 'adrotate'); ?></option>
						        	<option value="groove" <?php if($adborderstyle == "groove") { echo 'selected'; } ?>><?php _e('Groove', 'adrotate'); ?></option>
						        	<option value="ridge" <?php if($adborderstyle == "ridge") { echo 'selected'; } ?>><?php _e('Ridge', 'adrotate'); ?></option>
						        	<option value="inset" <?php if($adborderstyle == "inset") { echo 'selected'; } ?>><?php _e('Inset', 'adrotate'); ?></option>
						        	<option value="outset" <?php if($adborderstyle == "outset") { echo 'selected'; } ?>><?php _e('Outset', 'adrotate'); ?></option>
						        </select></label> 
								<label for="adrotate_adborderpx"><?php _e('Width', 'adrotate'); ?> <input tabindex="14" name="adrotate_adborderpx" type="text" class="search-input" size="3" value="<?php echo $adborderpx; ?>" autocomplete="off" /> <?php _e('pixel(s). Color', 'adrotate'); ?> </label>
								<label for="adrotate_adbordercolor"><input type="text" id="adcolor" name="adrotate_adbordercolor" size="5" value="<?php echo $adbordercolor; ?>" /></label>  
						        <div id="adrotate_ad_colorpicker"></div>
						        <script type="text/javascript">
								jQuery(document).ready(function() {
									jQuery('#adrotate_ad_colorpicker').hide();
									jQuery('#adrotate_ad_colorpicker').farbtastic("#adcolor");
									jQuery("#adcolor").click(function(){jQuery('#adrotate_ad_colorpicker').slideDown()});
									jQuery("#adcolor").blur(function(){jQuery('#adrotate_ad_colorpicker').slideUp()});
								});
  								</script>
							</td>
							<td colspan="2">
								<p><em><?php _e('Set the border width to 0 to disable. Color must be a valid hex value. (Default: 0/#fff/none)', 'adrotate'); ?></em></p>
							</td>
						</tr>
						</tbody>

			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('Wrapper code (Optional) - Wraps around each ad', 'adrotate'); ?></th>
						</tr>
			  			</thead>
			  			
						<tbody>
					    <tr>
							<th valign="top"><?php _e('Before ad', 'adrotate'); ?></strong></th>
							<td colspan="2"><textarea tabindex="15" name="adrotate_wrapper_before" cols="65" rows="3"><?php echo $edit_block->wrapper_before; ?></textarea></td>
							<td>
						        <p><strong><?php _e('Example:', 'adrotate'); ?></strong></p>
						        <p><em>&lt;span style="margin: 2px;"&gt;</em></p>
							</td>
						</tr>
					    <tr>
							<th valign="top"><?php _e('After ad', 'adrotate'); ?></strong></th>
							<td colspan="2"><textarea tabindex="16" name="adrotate_wrapper_after" cols="65" rows="3"><?php echo $edit_block->wrapper_after; ?></textarea></td>
							<td>
								<p><strong><?php _e('Example:', 'adrotate'); ?></strong></p>
								<p><em>&lt;/span&gt;</em></p>
							</td>
						</tr>
						</tbody>
	
						<thead>
						<tr valign="top">
							<th colspan="4"><?php _e('Usage', 'adrotate'); ?></th>
						</tr>
						</thead>
		
						<tbody>
				      	<tr>
					        <th width="15%"><?php _e('In a post or page:', 'adrotate'); ?></th>
					        <td><p>[adrotate block="<?php echo $edit_block->id; ?>"]</p></td>
					        <th width="15%"><?php _e('Directly in a theme:', 'adrotate'); ?></th>
					        <td width="35%"><p>&lt;?php echo adrotate_block(<?php echo $edit_block->id; ?>); ?&gt;</p></td>
				      	</tr>
				      	</tbody>
					</table>
					
			    	<p class="submit">
						<input tabindex="17" type="submit" name="adrotate_block_submit" class="button-primary" value="<?php _e('Save', 'adrotate'); ?>" />
						<a href="admin.php?page=adrotate-blocks&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			    	</p>

					<h3><?php _e('Select Groups', 'adrotate'); ?></h3>

				   	<table class="widefat" style="margin-top: .5em">
			  			<thead>
		  				<tr>
							<th colspan="3"><?php _e('Choose the groups to use in this block', 'adrotate'); ?></th>
						</tr>
			  			</thead>
	
						<tbody>
						<?php if($groups) {
							foreach($groups as $group) {
								$ads_in_group = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = ".$group->id." AND `block` = 0;");
								$class = ('alternate' != $class) ? 'alternate' : ''; ?>
							    <tr class='<?php echo $class; ?>'>
									<th width="2%"><input type="checkbox" name="groupselect[]" value="<?php echo $group->id; ?>" <?php if(in_array($group->id, $meta_array)) echo "checked"; ?> /></th>
									<td><?php echo $group->id; ?> - <strong><?php echo $group->name; ?></strong></td>
									<td width="10%"><?php echo $ads_in_group; ?> <?php _e('Ads', 'adrotate'); ?></td>
								</tr>
				 			<?php } ?>
						<?php } else { ?>
						<tr>
							<th class="check-column">&nbsp;</th>
							<td colspan="2"><em><?php _e('No groups created!', 'adrotate'); ?></em></td>
						</tr>
						<?php } ?>
						</tbody>					
					</table>
				
			    	<p class="submit">
						<input tabindex="18" type="submit" name="adrotate_block_submit" class="button-primary" value="<?php _e('Save', 'adrotate'); ?>" />
						<a href="admin.php?page=adrotate-blocks&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			    	</p>
	
				</form>
