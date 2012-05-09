<?php if (!empty($questions)) : ?>
	<?php if ($this -> get_option('acc') == "Y") : ?>
		<div class="<?php echo $this -> pre; ?>acc" id="<?php echo $this -> pre; ?>acc<?php echo $group -> id; ?>">
			<?php foreach ($questions as $question) : ?>
				<h4 class="<?php echo $this -> pre; ?>acctoggle"><a style="<?php if ($this -> get_option('accbullet') != "none") : ?>background:url('<?php echo $this -> url(); ?>/images/bullets/<?php echo $this -> get_option('accbullet'); ?>.png') center left no-repeat;<?php endif; ?>"><?php echo $question -> question; ?></a></h4>
				<div class="<?php echo $this -> pre; ?>acccontent">
					<div class="<?php echo $this -> pre; ?>acccontenti">
						<?php if ($this -> get_option('showquestionexcerpts') == "N" || ($this -> get_option('showquestionexcerpts') == "Y" && $question -> pp == "none")) : ?>
                        	<?php if ($this -> get_option('filter_the_content') == "N") : ?>
                            	<?php echo do_shortcode(wpautop($question -> answer)); ?>
                            <?php else : ?>
                            	<?php echo apply_filters('the_content', $question -> answer); ?>
                            <?php endif; ?>
                        <?php else : ?>
                        	<?php
							
							global $post;
							$oldpost = $post;
							
							$post = get_post($question -> pp_id);
							$post -> post_content = $question -> answer;
							setup_postdata($post);
							the_excerpt();
							
							$post = $oldpost;
							
							?>
                        <?php endif; ?>
						
						<?php if (current_user_can('edit_plugins') && $this -> get_option('adminlinks') == "Y") : ?>
							<p>
								<small>
									<?php echo $wpfaqHtml -> link(__('Edit Question', $this -> plugin_name), $wpfaqHtml -> admin_qu_save($question -> id)); ?>
									| <?php echo $wpfaqHtml -> link(__('Delete Question', $this -> plugin_name), $wpfaqHtml -> admin_qu_delete($question -> id), array('onclick' => "if (!confirm('" . __('Are you sure you wish to remove this question permanently?', $this -> plugin_name) . "')) { return false; }")); ?>
								</small>
							</p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
        
        <?php $accactive = $this -> get_option('accactive'); ?>
        <?php if (empty($accactive)) { $dontopen = true; }; ?>
		
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#wpfaqacc<?php echo $group -> id; ?>").accordion({
				<?php echo (empty($dontopen) || $dontopen == false) ? 'active:' . ($accactive - 1) . ',' : 'active:"0",'; ?>
				header: "h4.wpfaqacctoggle",
				event: "<?php echo ($this -> get_option('accevent') == "over") ? 'mouseover' : 'click'; ?>",
				autoHeight: false,
				collapsible: <?php if ($this -> get_option('acccollapsible') == "Y") : ?>true<?php else : ?>false<?php endif; ?>
			})
		});
		</script>
	<?php elseif ($this -> get_option('clickoc') == "Y") : ?>		
		<div class="<?php echo $this -> pre; ?>" id="<?php echo $this -> pre; ?><?php echo $group -> id; ?>">
			<?php foreach ($questions as $question) : ?>
				<h4 class="<?php echo $this -> pre; ?>toggle" style="<?php if ($this -> get_option('accbullet') != "none") : ?>background:url('<?php echo $this -> url(); ?>/images/bullets/<?php echo $this -> get_option('accbullet'); ?>.png') center left no-repeat;<?php endif; ?>"><?php echo $question -> question; ?></h4>
				<div class="<?php echo $this -> pre; ?>content" style="display:none;">
					<div class="<?php echo $this -> pre; ?>contenti">
						<?php if ($this -> get_option('showquestionexcerpts') == "N" || ($this -> get_option('showquestionexcerpts') == "Y" && $question -> pp == "none")) : ?>
                        	<?php if ($this -> get_option('filter_the_content') == "N") : ?>
                            	<?php echo do_shortcode(wpautop($question -> answer)); ?>
                            <?php else : ?>
                            	<?php echo apply_filters('the_content', $question -> answer); ?>
                            <?php endif; ?>
                        <?php else : ?>
                        	<?php
							
							global $post;
							$oldpost = $post;
							
							$post = get_post($question -> pp_id);
							$post -> post_content = $question -> answer;
							setup_postdata($post);
							the_excerpt();
							
							$post = $oldpost;
							
							?>
                        <?php endif; ?>
						
						<?php if (current_user_can('edit_plugins') && $this -> get_option('adminlinks') == "Y") : ?>
							<p>
								<small>
									<?php echo $wpfaqHtml -> link(__('Edit Question', $this -> plugin_name), $wpfaqHtml -> admin_save('questions', $question -> id)); ?>
									| <?php echo $wpfaqHtml -> link(__('Delete Question', $this -> plugin_name), $wpfaqHtml -> admin_delete('questions', $question -> id), array('onclick' => "if (!confirm('" . __('Are you sure you wish to permanently remove this question?', $this -> plugin_name) . "')) { return false; }")); ?>
								</small>
							</p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('h4.<?php echo $this -> pre; ?>toggle').click(function() {
				jQuery(this).next("h4 + div.<?php echo $this -> pre; ?>content").slideToggle();
			});
		});
		</script>
	<?php else : ?>	
		<div class="<?php echo $this -> pre; ?>" id="<?php echo $this -> pre; ?><?php echo $group -> id; ?>">
			<?php foreach ($questions as $question) : ?>
				<h4 class="<?php echo $this -> pre; ?>toggle" style="<?php if ($this -> get_option('accbullet') != "none") : ?>background:url('<?php echo $this -> url(); ?>/images/bullets/<?php echo $this -> get_option('accbullet'); ?>.png') center left no-repeat;<?php endif; ?>"><?php echo $question -> question; ?></h4>
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
									<?php echo $wpfaqHtml -> link(__('Edit Question', $this -> plugin_name), $wpfaqHtml -> admin_save('questions', $question -> id)); ?>
									| <?php echo $wpfaqHtml -> link(__('Delete Question', $this -> plugin_name), $wpfaqHtml -> admin_delete('questions', $question -> id), array('onclick' => "if (!confirm('" . __('Are you sure you wish to permanently remove this question?', $this -> plugin_name) . "')) { return false; }")); ?>
								</small>
							</p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
<?php else : ?>
	<p><small class="<?php echo $this -> pre; ?>error"><?php _e('No questions were found', $this -> plugin_name); ?></small></p>		
<?php endif; ?>