<?php if (!empty($groups)) : ?>
	<form action="?page=<?php echo $this -> sections -> groups; ?>&amp;method=mass" onsubmit="if (!confirm('<?php _e('Are you sure you wish to execute this action on the selected groups?', $this -> plugin_name); ?>')) { return false; }" method="post">
		<div class="tablenav">
			<div class="alignleft actions">
				<a href="?page=<?php echo $this -> sections -> groups; ?>&amp;method=order" class="button"><?php _e('Order Groups', $this -> plugin_name); ?></a>
				<select name="action" class="action">
					<option value="">- <?php _e('Bulk Actions', $this -> plugin_name); ?> -</option>
					<option value="delete"><?php _e('Delete', $this -> plugin_name); ?></option>
					<option value="activate"><?php _e('Activate', $this -> plugin_name); ?></option>
					<option value="deactivate"><?php _e('Deactivate', $this -> plugin_name); ?></option>
				</select>
				<input type="submit" class="button" name="apply" value="<?php _e('Apply', $this -> plugin_name); ?>" />
			</div>
			<?php $this -> render('paginate', array('paginate' => $paginate), 'admin', true); ?>
		</div>
		<table class="widefat">
			<thead>
				<tr>
					<th class="check-column"><input type="checkbox" name="checkboxall" value="checkboxall" id="checkboxall" /></th>
					<th><?php _e('ID', $this -> plugin_name); ?></th>
					<th><?php _e('Name', $this -> plugin_name); ?></th>
					<th><?php _e('Questions', $this -> plugin_name); ?></th>
					<th><?php _e('Status', $this -> plugin_name); ?></th>
					<th><?php _e('Modified', $this -> plugin_name); ?></th>
					<th><?php _e('Post/Page', $this -> plugin_name); ?></th>
					<th><?php _e('Shortcode', $this -> plugin_name); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="check-column"><input type="checkbox" name="checkboxall" value="checkboxall" id="checkboxall" /></th>
					<th><?php _e('ID', $this -> plugin_name); ?></th>
					<th><?php _e('Name', $this -> plugin_name); ?></th>
					<th><?php _e('Questions', $this -> plugin_name); ?></th>
					<th><?php _e('Status', $this -> plugin_name); ?></th>
					<th><?php _e('Modified', $this -> plugin_name); ?></th>
					<th><?php _e('Post/Page', $this -> plugin_name); ?></th>
					<th><?php _e('Shortcode', $this -> plugin_name); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<?php $class = ''; ?>
				<?php foreach ($groups as $group) : ?>
				<?php $class = ("alternate" == $class) ? '' : 'alternate'; ?>
				<tr id="grouprow<?php echo $group -> id; ?>" class="<?php echo $class; ?>">
					<th class="check-column"><input type="checkbox" name="groupslist[]" value="<?php echo $group -> id; ?>" id="checklist<?php echo $group -> id; ?>" /></th>
					<td><label for="checklist<?php echo $group -> id; ?>"><?php echo $group -> id; ?></label></td>
					<td>
						<?php echo $wpfaqHtml -> link($group -> name, '?page=' . $this -> sections -> groups . '&amp;method=view&amp;id=' . $group -> id, array('class' => "row-title")); ?>
						<div class="row-actions">
							<span class="edit"><?php echo $wpfaqHtml -> link(__('Edit', $this -> plugin_name), '?page=' . $this -> sections -> groups_save . '&amp;id=' . $group -> id); ?> |</span>
							<span class="delete"><?php echo $wpfaqHtml -> link(__('Delete', $this -> plugin_name), '?page=' . $this -> sections -> groups . '&amp;method=delete&amp;id=' . $group -> id, array('class' => "submitdelete", 'onclick' => "if (!confirm('" . __('Are you sure you want to delete this group?', $this -> plugin_name) . "')) { return false; }")); ?> |</span>
							<span class="view"><?php echo $wpfaqHtml -> link(__('View', $this -> plugin_name), '?page=' . $this -> sections -> groups . '&amp;method=view&amp;id=' . $group -> id); ?> |</span>
							<span class="edit"><?php echo $wpfaqHtml -> link(__('Order Questions', $this -> plugin_name), '?page=' . $this -> sections -> questions . '&amp;method=order&amp;group_id=' . $group -> id); ?></span>
						</div>
					</td>
					<td><?php $wpfaqDb -> model = $wpfaqQuestion -> model; ?><?php $count = $wpfaqDb -> count(array('group_id' => $group -> id)); ?><?php echo (!empty($count)) ? $wpfaqHtml -> link($count, $this -> url . "&amp;method=view&amp;id=" . $group -> id) : '<label for="checklist' . $group -> id . '">' . __('none', $this -> plugin_name); ?></label></td>
					<td><?php echo (empty($group -> active) || $group -> active == "Y") ? '<span class="' . $this -> pre . 'grn">' . __('Active', $this -> plugin_name) : '<span class="' . $this -> pre . 'red">' . __('Inactive', $this -> plugin_name); ?></span></td>
					<td><label for="checklist<?php echo $group -> id; ?>"><abbr title="<?php echo $group -> modified; ?>"><?php echo date("Y-m-d", strtotime($group -> modified)); ?></abbr></label></td>
					<td>
						<?php if (empty($group -> pp) || $group -> pp == "none") : ?>
							<?php _e('none', $this -> plugin_name); ?>
						<?php else : ?>
							<?php echo ucfirst($group -> pp); ?> - <?php echo $wpfaqHtml -> link($group -> pp_title, get_permalink($group -> pp_id), array('target' => "_blank", 'title' => $group -> pp_title)); ?>
						<?php endif; ?>
					</td>
					<td><code>[<?php echo $this -> pre; ?>group id=<?php echo $group -> id; ?>]</code></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="tablenav">
			<div class="alignleft">
				<?php if (empty($_GET['showall'])) : ?>
					<select name="perpage" class="alignleft action" onchange="change_perpage(this.value);">
						<option value="">- <?php _e('Per Page', $this -> plugin_name); ?> -</option>
						<?php $p = 5; ?>
						<?php while ($p < 100) : ?>
							<option <?php echo (isset($_COOKIE[$this -> pre . 'groupsperpage']) && $_COOKIE[$this -> pre . 'groupsperpage'] == $p) ? 'selected="selected"' : ''; ?> value="<?php echo $p; ?>"><?php echo $p; ?> <?php _e('groups', $this -> plugin_name); ?></option>
							<?php $p += 5; ?>
						<?php endwhile; ?>
					</select>
				<?php endif; ?>
				
				<script type="text/javascript">
				function change_perpage(perpage) {
					if (perpage != "") {
						document.cookie = "<?php echo $this -> pre; ?>groupsperpage=" + perpage + "; expires=<?php echo $wpfaqHtml -> gen_date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
						window.location = "<?php echo $_SERVER['REQUEST_URI']; ?>";
					}
				}
				
				function change_sorting(field, dir) {
					document.cookie = "<?php echo $this -> pre; ?>groupssorting=" + field + "; expires=<?php echo date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
					document.cookie = "<?php echo $this -> pre; ?>groups" + field + "dir=" + dir + "; expires=<?php echo date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
					window.location = "<?php echo preg_replace("/\&?" . $this -> pre . "page\=(.*)?/si", "", $_SERVER['REQUEST_URI']); ?>";
				}
				</script>
			</div>
			<?php $this -> render('paginate', array('paginate' => $paginate), 'admin', true); ?>
		</div>
	</form>
<?php else : ?>
	<p class="<?php echo $this -> pre; ?>error"><?php _e('No FAQ groups were found', $this -> plugin_name); ?></p>
<?php endif; ?>