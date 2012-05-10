<?php

$this -> predefined_pages();

global $user_ID, $post, $post_ID, $wp_meta_boxes;
$post_ID = $this -> get_option('edimagespost');

wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
wp_nonce_field('internal-linking', '_ajax_linking_nonce', false);

?>

<div class="wrap">
	<h2><?php _e('Save a Question', $this -> plugin_name); ?></h2>
	<form action="?page=<?php echo $this -> sections -> questions_save; ?>" method="post">
		<?php wp_nonce_field($this -> sections -> questions_save); ?>
		<?php echo $wpfaqForm -> hidden('wpfaqQuestion.id'); ?>
		
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar">			
				<?php $side_meta_boxes = do_meta_boxes("admin_page_" . $this -> sections -> questions_save, 'side', $post); ?>
			</div>
			<div id="post-body" class="has-sidebar">
				<div id="post-body-content" class="has-sidebar-content">
					<div id="titlediv">
						<div id="titlewrap">
							<?php echo $wpfaqForm -> text('wpfaqQuestion.question', array('id' => "title", 'error' => false, 'autocomplete' => "off", 'tabindex' => 1)); ?>
						</div>
						<div class="inside">
							<?php echo $wpfaqHtml -> field_error('wpfaqQuestion.question'); ?>
						</div>
					</div>					
					<div id="<?php echo (user_can_richedit()) ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
						<?php the_editor(stripslashes($wpfaqHtml -> field_value('wpfaqQuestion.answer'))); ?>
						
						<table id="post-status-info" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td id="wp-word-count">
										<?php _e('Word count', $this -> plugin_name); ?>:
										<span id="word-count"></span>
									</td>
									<td class="autosave-info">
										<span id="autosave" style="display:none;"></span>
									</td>
								</tr>
							</tbody>
						</table>
						
						<?php echo $wpfaqHtml -> field_error('wpfaqQuestion.answer'); ?>
					</div>
					
					<?php do_meta_boxes("admin_page_" . $this -> sections -> questions_save, 'normal', $post); ?>
                    <?php do_meta_boxes("admin_page_" . $this -> sections -> questions_save, 'advanced', $post); ?>
				</div>
			</div>
			<br class="clear" />
		</div>
	</form>
</div>

<script type="text/javascript">
try{ jQuery('#title').focus(); } catch(e) {  };
</script>