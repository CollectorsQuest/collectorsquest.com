<?php

global $post_ID, $user_ID, $post;
$post_ID = 1;

wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);

?>

<div class="wrap">
	<h2><?php _e('FAQs Configuration', $this -> plugin_name); ?></h2>
	
	<form action="?page=<?php echo $this -> sections -> settings; ?>" method="post">
		<?php wp_nonce_field($this -> sections -> settings); ?>
	
		<div id="poststuff" class="metabox-holder has-right-sidebar">			
			<div id="side-info-column" class="inner-sidebar">			
				<?php $side_meta_boxes = do_meta_boxes("faqs_page_" . $this -> sections -> settings, 'side', $post); ?>
			</div>
			<div id="post-body" class="has-sidebar">
				<div id="post-body-content" class="has-sidebar-content">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<?php do_meta_boxes("faqs_page_" . $this -> sections -> settings, 'normal', $post); ?>
						<?php do_meta_boxes("faqs_page_" . $this -> sections -> settings, 'advanced', $post); ?>
					</div>
				</div>
			</div>
			<br class="clear" />
		</div>
	</form>
</div>