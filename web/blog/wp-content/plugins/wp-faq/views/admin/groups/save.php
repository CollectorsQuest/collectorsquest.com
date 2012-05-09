<div class="wrap <?php echo $this -> pre; ?>">
	<h2><?php _e('Save a Group', $this -> plugin_name); ?></h2>
	
	<form action="?page=<?php echo $this -> sections -> groups_save; ?>" method="post">
		<?php echo $wpfaqForm -> hidden('wpfaqGroup.id'); ?>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="wpfaqGroup.name"><?php _e('Name', $this -> plugin_name); ?></label></th>
					<td>
						<?php echo $wpfaqForm -> text('wpfaqGroup.name'); ?>
						<span class="howto"><?php _e('the name of your group for identification purposes', $this -> plugin_name); ?></span>
						
						<script type="text/javascript">
						jQuery(document).ready(function() {						
							jQuery("[id$=name]").keyup(function(event) {
								jQuery("[id$=pp_title]").val(jQuery("[id$=name]").attr("value"));
							});
						});
						</script>
					</td>
				</tr>
				<tr>
					<th><label for="wpfaqGroup.adminnotifyY"><?php _e('Admin Notifications', $this -> plugin_name); ?></label></th>
					<td>
						<?php $adminnotify = array("Y" => __('On', $this -> plugin_name), "N" => __('Off', $this -> plugin_name)); ?>
						<?php echo $wpfaqForm -> radio('wpfaqGroup.adminnotify', $adminnotify, array('separator' => false, 'default' => "N", 'onclick' => "if (this.value == 'Y') { jQuery('#adminemaildiv').show(); } else { jQuery('#adminemaildiv').hide(); }")); ?>
						<span class="howto"><?php _e('receive an email notification when a new question has been asked for this group specifically', $this -> plugin_name); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<div id="adminemaildiv" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqGroup.adminnotify') == "Y") ? 'block' : 'none'; ?>;">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="wpfaqGroup.email"><?php _e('Admin Email', $this -> plugin_name); ?></label></th>
						<td>
							<?php echo $wpfaqForm -> text('wpfaqGroup.email'); ?>
							<span class="howto"><?php _e('fill in a single, valid email address', $this -> plugin_name); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="wpfaqGroup.searchboxN"><?php _e('Search Box', $this -> plugin_name); ?></label></th>
					<td>
						<?php $search = array("Y" => __('On', $this -> plugin_name), "N" => __('Off', $this -> plugin_name)); ?>
						<?php echo $wpfaqForm -> radio('wpfaqGroup.searchbox', $search, array('separator' => false, 'default' => "N", 'onclick' => "if (this.value == 'Y') { jQuery('#searchboxdiv').show(); } else { jQuery('#searchboxdiv').hide(); }")); ?>
						<span class="howto"><?php _e('places a search box above the group questions on the front-end', $this -> plugin_name); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div id="searchboxdiv" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqGroup.searchbox') == "Y") ? 'block' : 'none'; ?>;">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for=""><?php _e('Show Groups Menu', $this -> plugin_name); ?></label></th>
						<td>
							<?php $groupsmenu = array("Y" => __('Yes', $this -> plugin_name), "N" => __('No', $this -> plugin_name)); ?>
							<?php echo $wpfaqForm -> radio('wpfaqGroup.groupsmenu', $groupsmenu, array('separator' => false, 'default' => "N")); ?>
							<span class="howto"><?php _e('show a dropdown with other groups to search within', $this -> plugin_name); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="wpfaqGroup.askboxN"><?php _e('Ask Box', $this -> plugin_name); ?></label></th>
					<td>
						<?php $submission = array("Y" => __('On', $this -> plugin_name), "N" => __('Off', $this -> plugin_name)); ?>
						<?php echo $wpfaqForm -> radio('wpfaqGroup.askbox', $submission, array('separator' => false, 'default' => "N")); ?>
						<span class="howto"><?php _e('places a submission box below the group questions for users/members to ask questions', $this -> plugin_name); ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="wpfaqGroup.activeY"><?php _e('Active Status', $this -> plugin_name); ?></label></th>
					<td>
						<?php $active = array("Y" => __('Active', $this -> plugin_name), "N" => __('Inactive', $this -> plugin_name)); ?>
						<?php echo $wpfaqForm -> radio('wpfaqGroup.active', $active, array('separator' => false, 'default' => "Y", 'onclick' => "if (this.value == 'Y') { jQuery('div#ppdiv').show(); } else { jQuery('div#ppdiv').hide(); };")); ?>
						<span class="howto"><?php _e('deactivating a group will prevent its questions from being shown on the front-end', $this -> plugin_name); ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="wpfaqGroup.keywords"><?php _e('Keywords/Tags', $this -> plugin_name); ?></label></th>
					<td>
						<?php echo $wpfaqForm -> text('wpfaqGroup.keywords'); ?>
						<span class="howto"><?php _e('separate keywords/tags with commas eg. group, keyword, faqs', $this -> plugin_name); ?></span>
					</td>
				</tr>
			</tbody>
		</table>

		<div id="ppdiv" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqGroup.active') == "" || $wpfaqHtml -> field_value('wpfaqGroup.active') == "Y") ? 'block' : 'none'; ?>;">		
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="wpfaqGroup.ppnone"><?php _e('Save WordPress Post/Page', $this -> plugin_name); ?></label></th>
						<td>
							<?php $createpage = array("none" => __('No', $this -> plugin_name), "post" => __('Post', $this -> plugin_name), "page" => __('Page', $this -> plugin_name)); ?>
							<?php echo $wpfaqForm -> radio('wpfaqGroup.pp', $createpage, array('separator' => false, 'default' => "none", 'onclick' => "change_createpp(this.value);")); ?>
							
							<script type="text/javascript">
							function change_createpp(createpp) {
								jQuery('#createppdiv').hide();
								jQuery('#createpagediv').hide();
								jQuery('#createpostdiv').hide();
							
								if (createpp != "" && (createpp == "post" || createpp == "page")) {
									jQuery('#createppdiv').show();
									
									if (createpp == "page") {
										jQuery('#createpagediv').show();
									} else if (createpp == "post") {
										jQuery('#createpostdiv').show();	
									}
								}
							}
							</script>
							
							<span class="howto"><?php _e('would you like to have a WordPress post/page saved & maintained for this group? (recommended)', $this -> plugin_name); ?></span>
						</td>
					</tr>
				</tbody>
			</table>

			<div id="createppdiv" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqGroup.pp') == "post" || $wpfaqHtml -> field_value('wpfaqGroup.pp') == "page") ? 'block' : 'none'; ?>;">		
				<?php echo $wpfaqForm -> hidden('wpfaqGroup.pp_id'); ?>
			
				<table class="form-table">
					<tbody>
						<tr>
							<th><label for="wpfaqGroup.pp_title"><?php _e('Post/Page Title', $this -> plugin_name); ?></label></th>
							<td>
								<?php echo $wpfaqForm -> text('wpfaqGroup.pp_title'); ?>
								<span class="howto"><?php _e('WordPress post/page title as it will appear on the front-end', $this -> plugin_name); ?></span>
							</td>
						</tr>
					</tbody>
				</table>
                
                <div id="createpostdiv" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqGroup.pp') == "post") ? 'block' : 'none'; ?>;">
                	<table class="form-table">
                    	<tbody>
                        	<tr>
                            	<th><label for=""><?php _e('Post Categories', $this -> plugin_name); ?></label></th>
                                <td>
                                	<?php if ($categories = get_categories(array('hide_empty' => 0, 'pad_counts' => 1))) : ?>
										<?php $pp_categories = maybe_unserialize($wpfaqHtml -> field_value('wpfaqGroup.pp_categories')); ?>
                                        <div>
                                            <input type="checkbox" name="categoriesselectall" value="1" id="checkboxall" onclick="jqCheckAll(this, '<?php echo $this -> sections -> settings; ?>', 'latestposts_categories');" />
                                            <label for="categoriesselectall"><strong><?php _e('Select All', $this -> plugin_name); ?></strong></label>
                                        </div>
                                        <div style="max-height:200px; overflow:auto;">
                                            <?php foreach ($categories as $category) : ?>
                                                <label><input <?php echo (!empty($pp_categories) && in_array($category -> cat_ID, $pp_categories)) ? 'checked="checked"' : ''; ?> type="checkbox" name="wpfaqGroup[pp_categories][]" value="<?php echo $category -> cat_ID; ?>" id="checklist<?php echo $category -> cat_ID; ?>" /> <?php echo $category -> cat_name; ?></label><br/>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="<?php echo $this -> pre; ?>error"><?php _e('No categories are available.', $this -> plugin_name); ?></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
				
				<div id="createpagediv" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqGroup.pp') == "page") ? 'block' : 'none'; ?>;">
					<table class="form-table">
						<tbody>
							<tr>
								<th><label for="wpfaqGroup.pp_parent"><?php _e('Page Parent', $this -> plugin_name); ?></label></th>
								<td>						
									<select id="wpfaqGroup.pp_parent" name="wpfaqGroup[pp_parent]">
										<option value="0">- <?php _e('Main (no parent)', $this -> plugin_name); ?> -</option>
										<?php parent_dropdown($wpfaqHtml -> field_value('wpfaqGroup.pp_parent')); ?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<p class="submit">
			<input type="submit" name="save" value="<?php _e('Save Group', $this -> plugin_name); ?>" class="button-primary" />
		</p>
	</form>
</div>