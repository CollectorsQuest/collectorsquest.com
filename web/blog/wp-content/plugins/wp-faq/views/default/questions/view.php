<?php if (!empty($question)) : ?>
	<div class="<?php echo $this -> pre; ?>" id="<?php echo $this -> pre; ?><?php echo $question -> group_id; ?>">
		<h4 class="<?php echo $this -> pre; ?>toggle" style="background:url('<?php echo $this -> url(); ?>/images/bullets/<?php echo $this -> get_option('accbullet'); ?>.png') center left no-repeat;"><?php echo $question -> question; ?></h4>
		<div class="<?php echo $this -> pre; ?>content">
			<div class="<?php echo $this -> pre; ?>contenti">
            	<?php if ($this -> get_option('filter_the_content') == "N") : ?>
					<?php echo do_shortcode(wpautop($question -> answer)); ?>
                <?php else : ?>
                	<?php echo apply_filters('the_content', $question -> answer); ?>
                <?php endif; ?>
				
				<?php if (current_user_can('edit_plugins') && $this -> get_option('adminlinks') == "Y") : ?>
					<p>
						<small>
							<?php echo $wpfaqHtml -> link(__('Edit Question', $this -> plugin_name), $wpfaqHtml -> admin_qu_save($question -> id)); ?>
							| <?php echo $wpfaqHtml -> link(__('Delete Question', $this -> plugin_name), $wpfaqHtml -> admin_qu_delete($question -> id), array('onclick' => "if (!confirm('" . __('Are you sure you wish to permanently delete this question?', $this -> plugin_name) . "')) { return false; }")); ?>
						</small>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
    
   <?php if (!empty($related) && $this -> get_option('showrelatedquestions') == "Y") : ?>
   		<?php
		
		$rquestions = array();
		
		foreach ($related as $r) {
			$wpfaqDb -> model = $wpfaqQuestion -> model;
			$newrquestion = $wpfaqDb -> find(array('id' => $r -> rel_id));
			$rquestions[] = $newrquestion;
		}
		
		?>
        
        <h3><?php _e('Related FAQs', $this -> plugin_name); ?></h3>
        <?php $this -> render('questions' . DS . 'loop', array('questions' => $rquestions, 'dontopen' => true), 'default', true); ?>
   <?php endif; ?>
<?php endif; ?>