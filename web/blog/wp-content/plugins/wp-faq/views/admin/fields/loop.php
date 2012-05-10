<?php if (!empty($fields)) : ?>
	<form action="?page=<?php echo $this -> sections -> fields; ?>&amp;method=mass" method="post" id="Field.form" onsubmit="if (!confirm('<?php _e('Are you sure you wish to execute this action?', $this -> plugin_name); ?>')) { return false; };">
		<div class="tablenav">
			<div class="alignleft actions">
				<a href="?page=<?php echo $this -> sections -> fields; ?>&amp;method=order" title="<?php _e('Sort/order all your custom fields', $this -> plugin_name); ?>" class="button action"><?php _e('Order Fields', $this -> plugin_name); ?></a>
				<select name="action" class="widefat" style="width:auto;">
					<option value="">- <?php _e('Bulk Actions', $this -> plugin_name); ?> -</option>
					<option value="delete"><?php _e('Delete', $this -> plugin_name); ?></option>
					<option value="required"><?php _e('Set as Required', $this -> plugin_name); ?></option>
					<option value="notrequired"><?php _e('Set as NOT Required', $this -> plugin_name); ?></option>
				</select>
				<input class="button-secondary action" type="submit" name="execute" value="<?php _e('Apply', $this -> plugin_name); ?>" class="button" />
			</div>
			<?php $this -> render('paginate', array('paginate' => $paginate), 'admin', true); ?>
		</div>
		<table class="widefat">
			<thead>
				<tr>
					<th class="check-column"><input type="checkbox" name="" value="" id="checkboxall" /></th>
					<th><?php _e('ID', $this -> plugin_name); ?></th>
					<th><?php _e('Title', $this -> plugin_name); ?></th>
					<th><?php _e('Slug', $this -> plugin_name); ?></th>
					<th><?php _e('Type', $this -> plugin_name); ?></th>
					<th><?php _e('Required', $this -> plugin_name); ?></th>
					<th><?php _e('Modified', $this -> plugin_name); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="check-column"><input type="checkbox" name="" value="" id="checkboxall" /></th>
					<th><?php _e('ID', $this -> plugin_name); ?></th>
					<th><?php _e('Title', $this -> plugin_name); ?></th>
					<th><?php _e('Slug', $this -> plugin_name); ?></th>
					<th><?php _e('Type', $this -> plugin_name); ?></th>
					<th><?php _e('Required', $this -> plugin_name); ?></th>
					<th><?php _e('Modified', $this -> plugin_name); ?></th>
				</tr>
			</tfoot>
			<tbody>
				<?php $class = ''; ?>
				<?php $types = $this -> get_option('fieldtypes'); ?>
				<?php foreach ($fields as $field) : ?>
					<tr class="<?php echo $class = ($class == "") ? 'alternate' : ''; ?>" id="Field.row<?php echo $field -> id; ?>">
						<th class="check-column"><input type="checkbox" name="fieldslist[]" id="checklist<?php echo $field -> id; ?>" value="<?php echo $field -> id; ?>" /></td>
						<td><label for="checklist<?php echo $field -> id; ?>"><?php echo $field -> id; ?></label></td>
						<td>
							<strong><a href="?page=<?php echo $this -> sections -> fields; ?>&amp;method=save&amp;id=<?php echo $field -> id; ?>" title="<?php _e('Edit this custom field', $this -> plugin_name); ?>" class="row-title"><?php echo $field -> title; ?></a></strong>
							<div class="row-actions">
								<span class="edit"><?php echo $wpfaqHtml -> link(__('Edit', $this -> plugin_name), '?page=' . $this -> sections -> fields . '&amp;method=save&amp;id=' . $field -> id); ?><?php if ($field -> slug != "email") : ?> |<?php endif; ?></span>
                                <?php if ($field -> slug != "email") : ?>
									<span class="delete"><?php echo $wpfaqHtml -> link(__('Delete', $this -> plugin_name), '?page=' . $this -> sections -> fields . '&amp;method=delete&amp;id=' . $field -> id, array('class' => "submitdelete", 'onclick' => "if (!confirm('" . __('Are you sure you want to delete this custom field?', $this -> plugin_name) . "')) { return false; }")); ?></span>
                                <?php endif; ?>
							</div>
						</td>
						<td><label for="checklist<?php echo $field -> id; ?>"><?php echo $field -> slug; ?></label></td>
						<td><label for="checklist<?php echo $field -> id; ?>"><?php echo $wpfaqHtml -> field_type($field -> fieldtype); ?></label></td>
						<td><label for="checklist<?php echo $field -> id; ?>"><?php echo (empty($field -> required) || $field -> required == "N") ? '<span style="color:red;">' . __('No', $this -> plugin_name) : '<span style="color:green;">' . __('Yes', $this -> plugin_name); ?></span></label></td>
						<td><label for="checklist<?php echo $field -> id; ?>"><abbr title="<?php echo $field -> modified; ?>"><?php echo date("Y-m-d", strtotime($field -> modified)); ?></abbr></label></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="tablenav">
			<div class="alignleft">
				<?php if (empty($_GET['showall'])) : ?>
					<select class="widefat alignleft" style="width:auto;" name="perpage" onchange="change_perpage(this.value);">
						<option value="">- <?php _e('Per Page', $this -> plugin_name); ?> -</option>
						<?php $p = 5; ?>
						<?php while ($p < 100) : ?>
							<option <?php echo (!empty($_COOKIE[$this -> pre . 'fieldsperpage']) && $_COOKIE[$this -> pre . 'fieldsperpage'] == $p) ? 'selected="selected"' : ''; ?> value="<?php echo $p; ?>"><?php echo $p; ?> <?php _e('per page', $this -> plugin_name); ?></option>
							<?php $p += 5; ?>
						<?php endwhile; ?>
					</select>
				<?php endif; ?>
				
				<script type="text/javascript">
				function change_perpage(perpage) {
					if (perpage != "") {
						document.cookie = "<?php echo $this -> pre; ?>fieldsperpage=" + perpage + "; expires=<?php echo $wpfaqHtml -> gen_date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
						window.location = "<?php echo preg_replace("/\&?" . $this -> pre . "page\=(.*)?/si", "", $_SERVER['REQUEST_URI']); ?>";
					}
				}
				
				function change_sorting(field, dir) {
					document.cookie = "<?php echo $this -> pre; ?>fieldssorting=" + field + "; expires=<?php echo date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
					document.cookie = "<?php echo $this -> pre; ?>fields" + field + "dir=" + dir + "; expires=<?php echo date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC; path=/";
					window.location = "<?php echo preg_replace("/\&?" . $this -> pre . "page\=(.*)?/si", "", $_SERVER['REQUEST_URI']); ?>";
				}
				</script>
			</div>
			<?php $this -> render('paginate', array('paginate' => $paginate), 'admin', true); ?>
		</div>
	</form>
<?php else : ?>
	<p class="<?php echo $this -> pre; ?>error"><?php _e('No custom fields were found', $this -> plugin_name); ?></p>
<?php endif; ?>