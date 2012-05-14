<?php $number = substr(md5(rand(1, 999)), 0, 6); ?>

<?php if (!empty($group) && !empty($questions)) : ?>
	<div class="<?php echo $this -> pre; ?>">
		<div id="<?php echo $this -> pre; ?>search<?php echo $number; ?>" class="<?php echo $this -> pre; ?>search">
			<?php if (current_user_can('edit_plugins') && $this -> get_option('adminlinks') == "Y") : ?>
				<p>
					<small>
						<?php echo $wpfaqHtml -> link(__('Edit Group', $this -> plugin_name), $wpfaqHtml -> admin_gu_save($group -> id), array('title' => __('Change the details of this group', $this -> plugin_name))); ?>
						| <?php echo $wpfaqHtml -> link(__('Delete Group', $this -> plugin_name), $wpfaqHtml -> admin_delete('groups', $group -> id), array('onclick' => "if (!confirm('" . __('Are you sure you wish to remove this group and all its questions?', $this -> plugin_name) . "')) { return false; }")); ?>
					</small>
				</p>
			<?php endif; ?>
			
			<?php if (!empty($group -> searchbox) && $group -> searchbox == "Y") : ?>
				<?php $this -> render('searchbox', array('group' => $group, 'number' => $number), 'default', true); ?>
			<?php endif; ?>
		
			<div id="<?php echo $this -> pre; ?>questions<?php echo $number; ?>" class="<?php echo $this -> pre; ?>questions">
				<?php $this -> render('questions' . DS . 'loop', array('group' => $group, 'questions' => $questions, 'number' => $number), 'default', true); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if (!empty($group -> askbox) && $group -> askbox == "Y") : ?>
	<div id="<?php echo $this -> pre; ?>ask<?php echo $number; ?>" class="<?php echo $this -> pre; ?>ask">
		<?php $this -> render('askbox', array('group' => $group, 'number' => $number), 'default', true); ?>
	</div>
<?php endif; ?>