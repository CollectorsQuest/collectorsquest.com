<div class="wrap">
	<h2><?php _e('Order Questions', $this -> plugin_name); ?></h2>
		
    <?php if (!empty($group)) : ?>
		<div class="subsubsub" style="float:none;"><?php echo $wpfaqHtml -> link(__('&larr; Back to Group', $this -> plugin_name), '?page=' . $this -> sections -> groups . '&amp;method=view&amp;id=' . $group -> id); ?></div>
    <?php else : ?>
    	<div class="subsubsub" style="float:none;"><?php echo $wpfaqHtml -> link(__('&larr; Back to Questions', $this -> plugin_name), '?page=' . $this -> sections -> questions); ?></div>
    <?php endif; ?>
	
	<p>
		<?php _e('Drag and drop the questions below to order them', $this -> plugin_name); ?>
        <?php if (!empty($group)) : ?>
        	<br/><?php _e('This changes the order of questions inside this group for the <code>[wpfaqgroup id="' . $group -> id . '"]</code> shortcode.', $this -> plugin_name); ?>
        <?php else : ?>
        	<br/><?php _e('This changes the order of all questions for the <code>[wpfaqs]</code> shortcode.', $this -> plugin_name); ?>
        <?php endif; ?>
   	</p>

	<?php if (!empty($questions)) : ?>	
		<div style="max-height:200px; overflow:auto;">
			<ul id="<?php echo $this -> pre; ?>questions" style="display:block;">
				<?php foreach ($questions as $question) : ?>
					<li style="display:block; width:85%;" id="item_<?php echo $question -> id; ?>" class="wpfaqlineitem"><?php echo $question -> question; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		
		<div id="<?php echo $this -> pre; ?>message" style="display:none;"></div>
		
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("ul#<?php echo $this -> pre; ?>questions").sortable({
				start: function(request) {
					jQuery("#<?php echo $this -> pre; ?>message").slideUp();
				},
				stop: function(request) {					
					jQuery("#<?php echo $this -> pre; ?>message").load(wpfaqAjax + "?cmd=questions_order&id=<?php echo $group -> id; ?>", jQuery("ul#<?php echo $this -> pre; ?>questions").sortable('serialize')).slideDown("slow");
				},
				axis: "y",
			});
		});
		</script>
	<?php else : ?>
		<p class="<?php echo $this -> pre; ?>error"><?php _e('No questions were found', $this -> plugin_name); ?></p>
	<?php endif; ?>
</div>