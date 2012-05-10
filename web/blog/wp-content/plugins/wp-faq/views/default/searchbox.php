<h3><?php _e('Search the FAQs', $this -> plugin_name); ?></h3>
<p><?php _e('Use the form below to search the FAQs', $this -> plugin_name); ?></p>

<form action="" method="post" id="<?php echo $this -> pre; ?>searchform<?php echo $number; ?>" class="<?php echo $this -> pre; ?> <?php echo $this -> pre; ?>searchform" onsubmit="<?php echo $this -> pre; ?>_search('<?php echo $number; ?>'); return false;">
	<input type="hidden" name="<?php echo $number; ?>[group_id]" value="<?php echo $group -> id; ?>" />
	
	<?php if (empty($group -> groupsmenu) || $group -> groupsmenu == "N") : ?>
		<input type="hidden" name="<?php echo $number; ?>[group]" value="<?php echo $group -> id; ?>" />
	<?php endif; ?>

	<div class="<?php echo $this -> pre; ?>searchformi">	
		<table>
			<tbody>
				<tr>
					<td><?php _e('Search for', $this -> plugin_name); ?> </td>
					<td><input type="text" name="<?php echo $number; ?>[s]" value="" size="15" /></td>
					<?php if ((!empty($showgroupsmenu) && $showgroupsmenu == true) || (!empty($group -> groupsmenu) && $group -> groupsmenu == "Y")) : ?>
						<td> <?php _e('in', $this -> plugin_name); ?> </td>
						<td>
							<select name="<?php echo $number; ?>[group]">
								<option value=""><?php _e('the entire FAQ', $this -> plugin_name); ?></option>
								
								<?php $wpfaqDb -> model = $wpfaqGroup -> model; ?>
								<?php if ($groups = $wpfaqDb -> find_all()) : ?>
									<?php foreach ($groups as $gr) : ?>
										<option <?php echo (!empty($group -> id) && $group -> id == $gr -> id) ? 'selected="selected"' : ''; ?> value="<?php echo $gr -> id; ?>"><?php echo $gr -> name; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</td>
					<?php endif; ?>
					<td><input type="submit" name="wpfaqsearch" value="<?php _e('Search', $this -> plugin_name); ?>" /></td>
					<td><span id="<?php echo $this -> pre; ?>searchloading<?php echo $number; ?>" style="display:none; float:right;"><img border="0" style="border:none;" src="<?php echo $this -> url(); ?>/images/loading.gif" alt="<?php _e('loading', $this -> plugin_name); ?>" /></span></td>
				</tr>
			</tbody>
		</table>
		
		<?php $this -> render('errors', array('errors' => $errors), 'default', true); ?>
	</div>
</form>