
	<form action="?page=<?php echo $this -> sections -> questions; ?>&amp;method=mass" method="post" onsubmit="if (!confirm('<?php _e('Are you sure you wish to execute this action on the selected questions?', $this -> plugin_name); ?>')) { return false; }">
		<div class="tablenav">
			<div class="alignleft actions">
				<input type="submit" name="export" value="<?php _e('Export', $this -> plugin_name); ?>" class="button" />
            	<a href="?page=<?php echo $this -> sections -> questions; ?>&amp;method=order" class="button" title="<?php _e('Change the order of all questions.', $this -> plugin_name); ?>"><?php _e('Order Questions', $this -> plugin_name); ?></a>
            
				<select name="action" class="action" onchange="change_action(this.value);">
					<option value="">- <?php _e('Bulk Actions', $this -> plugin_name); ?> -</option>
					<option value="delete"><?php _e('Delete', $this -> plugin_name); ?></option>
					<option value="movetogroup"><?php _e('Move to group...', $this -> plugin_name); ?></option>
					<option value="approved"><?php _e('Set as Approved', $this -> plugin_name); ?></option>
					<option value="unapproved"><?php _e('Set as Unapproved', $this -> plugin_name); ?></option>
				</select>
				
				<span id="groupsdiv" style="display:none;">
					<?php $wpfaqDb -> model = $this -> pre . 'Group'; ?>
					<?php if ($groups = $wpfaqDb -> find_all()) : ?>
						<select name="group_id" class="action">
							<option value="">- <?php _e('Select Group', $this -> plugin_name); ?> -</option>		
							<?php foreach ($groups as $group) : ?>
								<?php $wpfaqDb -> model = $wpfaqQuestion -> model; ?>
								<option value="<?php echo $group -> id; ?>"><?php echo $group -> name; ?> (<?php echo $wpfaqDb -> count(array('group_id' => $group -> id)); ?> <?php _e('questions', $this -> plugin_name); ?>)</option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
				</span>
			
            	<!-- Apply -->
				<input type="submit" name="apply" value="<?php _e('Apply', $this -> plugin_name); ?>" class="button" />
                
                <?php $approved = (isset($_COOKIE[$this -> pre . 'approved']) && $_COOKIE[$this -> pre . 'approved'] != "") ? $_COOKIE[$this -> pre . 'approved'] : "all"; ?>
                <?php if (!empty($_GET['approved'])) { $approved = $_GET['approved']; } ?>
                <select onchange="wpfaq_change_approved(this.value);" name="" class="action">
                	<option <?php echo (!empty($approved) && $approved == "all") ? 'selected="selected"' : ''; ?> value="all"><?php _e('Show All', $this -> plugin_name); ?></option>
                    <option <?php echo (!empty($approved) && $approved == "Y") ? 'selected="selected"' : ''; ?> value="Y"><?php _e('Show Approved Only', $this -> plugin_name); ?></option>
                    <option <?php echo (!empty($approved) && $approved == "N") ? 'selected="selected"' : ''; ?> value="N"><?php _e('Show Unapproved Only', $this -> plugin_name); ?></option>
                </select>
                
                <?php $wpfaqDb -> model = $wpfaqGroup -> model; ?>
                <?php if ($groups = $wpfaqDb -> find_all()) : ?>
                    <select onchange="window.location = '?page=<?php echo $this -> sections -> groups; ?>&method=view&id=' + this.value + '#<?php echo $this -> pre; ?>groupquestions<?php echo $group -> id; ?>';" name="group" class="action">
                    	<option value=""><?php _e('- All Groups -'); ?></option>
                        <?php foreach ($groups as $group) : ?>
                        	<option value="<?php echo $group -> id; ?>"><?php echo $group -> name; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                
                <script type="text/javascript">
				function change_action(action) {
					jQuery('#groupsdiv').hide();
				
					if (action != "") {
						if (action == "movetogroup") {
							jQuery('#groupsdiv').show();
						}
					}
				}
				</script>
			</div>
			<?php $this -> render('paginate', array('paginate' => $paginate), 'admin', true); ?>
		</div>
		
        <?php if (!empty($questions)) : ?>
		<table class="widefat">
			<thead>
				<tr>
					<th class="check-column"><input type="checkbox" name="checkboxall" value="checkboxall" id="checkboxall" /></th>
					<th><?php _e('ID', $this -> plugin_name); ?></th>
					<th><?php _e('Question', $this -> plugin_name); ?></th>
					<th><?php _e('Approved', $this -> plugin_name); ?></th>
					<th><?php _e('Group', $this -> plugin_name); ?></th>
                    <th><?php _e('Post/Page', $this -> plugin_name); ?></th>
					<th><?php _e('Shortcode', $this -> plugin_name); ?></th>
					<th><?php _e('Date', $this -> plugin_name); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="check-column"><input type="checkbox" name="checkboxall" value="checkboxall" id="checkboxall" /></th>
					<th><?php _e('ID', $this -> plugin_name); ?></th>
					<th><?php _e('Question', $this -> plugin_name); ?></th>
					<th><?php _e('Approved', $this -> plugin_name); ?></th>
					<th><?php _e('Group', $this -> plugin_name); ?></th>
                    <th><?php _e('Post/Page', $this -> plugin_name); ?></th>
					<th><?php _e('Shortcode', $this -> plugin_name); ?></th>
					<th><?php _e('Date', $this -> plugin_name); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<?php $class = ''; ?>
				<?php foreach ($questions as $question) : ?>
				<?php $class = ("alternate" == $class) ? '' : 'alternate'; ?>
				<tr class="<?php echo $class; ?>">
					<th class="check-column"><input type="checkbox" name="questions[]" value="<?php echo $question -> id; ?>" id="checklist<?php echo $question -> id; ?>" /></th>
					<td><label for="checklist<?php echo $question -> id; ?>"><?php echo $question -> id; ?></label></td>
					<td>
						<?php echo $wpfaqHtml -> link($wpfaqHtml -> truncate($question -> question, 65, '...'), '?page=' . $this -> sections -> questions_save . '&amp;id=' . $question -> id, array('class' => "row-title")); ?>
						<div class="row-actions">
							<span class="edit"><?php echo $wpfaqHtml -> link(__('Edit', $this -> plugin_name), '?page=' . $this -> sections -> questions_save . '&amp;id=' . $question -> id, array('class' => "edit")); ?> |</span>
							<span class="delete"><?php echo $wpfaqHtml -> link(__('Delete', $this -> plugin_name), '?page=' . $this -> sections -> questions . '&amp;method=delete&amp;id=' . $question -> id, array('class' => "submitdelete", 'onclick' => "if (!confirm('" . __('Are you sure you want to delete this question?', $this -> plugin_name) . "')) { return false; }")); ?> |</span>
                            <span class="edit"><?php echo $wpfaqHtml -> link(__('Related Questions', $this -> plugin_name), '?page=' . $this -> sections -> questions . '&amp;method=related&amp;id=' . $question -> id, array('class' => "edit")); ?></span>
						</div>
					</td>
					<td><label for="checklist<?php echo $question -> id; ?>"><?php echo (!empty($question -> approved) && $question -> approved == "Y") ? '<span style="color:green;">' . __('Yes', $this -> plugin_name) : '<span style="color:red;">' . __('No', $this -> plugin_name); ?></span></label></td>
					<td><a href="?page=<?php echo $this -> sections -> groups; ?>&amp;method=view&amp;id=<?php echo $question -> group_id; ?>" title="View the details of this group"><?php echo $wpfaqGroup -> get_title($question -> group_id); ?></a></td>
                    <td>
						<?php if (empty($question -> pp) || $question -> pp == "none") : ?>
							<?php _e('none', $this -> plugin_name); ?>
						<?php else : ?>
							<?php echo ucfirst($question -> pp); ?> - <?php echo $wpfaqHtml -> link($question -> pp_title, get_permalink($question -> pp_id), array('target' => "_blank", 'title' => $question -> pp_title)); ?>
						<?php endif; ?>
					</td>
					<td><code>[<?php echo $this -> pre; ?>question id=<?php echo $question -> id; ?>]</code></td>
					<td><label for="checklist<?php echo $question -> id; ?>"><abbr title="<?php echo $question -> modified; ?>"><?php echo date("Y-m-d", strtotime($question -> modified)); ?></abbr></label></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="tablenav">
			<div class="alignleft">
				<?php if (empty($_GET['showall'])) : ?>
					<select name="perpage" class="widefat alignleft action" style="width:auto;" onchange="change_perpage(this.value);">
						<option value="">- <?php _e('Per Page', $this -> plugin_name); ?> -</option>
						<?php $p = 5; ?>
						<?php while ($p < 100) : ?>
							<option <?php echo (isset($_COOKIE[$this -> pre . 'questionsperpage']) && $_COOKIE[$this -> pre . 'questionsperpage'] == $p) ? 'selected="selected"' : ''; ?> value="<?php echo $p; ?>"><?php echo $p; ?> <?php _e('questions', $this -> plugin_name); ?></option>
							<?php $p += 5; ?>
						<?php endwhile; ?>
					</select>
				<?php endif; ?>
				
				<script type="text/javascript">
				function change_perpage(perpage) {
					if (perpage != "") {
						document.cookie = "<?php echo $this -> pre; ?>questionsperpage=" + perpage + "; expires=<?php echo $wpfaqHtml -> gen_date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
						window.location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					}
				}
				
				function change_sorting(field, dir) {
					document.cookie = "<?php echo $this -> pre; ?>questionssorting=" + field + "; expires=<?php echo date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
					document.cookie = "<?php echo $this -> pre; ?>questions" + field + "dir=" + dir + "; expires=<?php echo date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
					window.location = "<?php echo preg_replace("/\&?" . $this -> pre . "page\=(.*)?/si", "", $_SERVER['REQUEST_URI']); ?>";
				}
				</script>
			</div>
			<?php $this -> render('paginate', array('paginate' => $paginate), 'admin', true); ?>
		</div>
        <?php else : ?>
        	<p class="<?php echo $this -> pre; ?>error"><?php _e('No questions were found, please add one.', $this -> plugin_name); ?></p>
        <?php endif; ?>
	</form>