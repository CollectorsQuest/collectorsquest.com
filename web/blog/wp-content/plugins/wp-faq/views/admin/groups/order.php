<div class="wrap">
	<h2><?php _e('Order Groups', $this -> plugin_name); ?></h2>
	
	<div class="subsubsub" style="float:none;"><?php echo $wpfaqHtml -> link(__('&larr; Back to Groups', $this -> plugin_name), $this -> url); ?></div>
	
	<p>
		<?php _e('Drag and drop the groups below to sort them.', $this -> plugin_name); ?><br/>
		<?php _e('You can embed this list of groups into a post/page with', $this -> plugin_name); ?> <code>[<?php echo $this -> pre; ?>groups]</code>.<br/>
		<?php _e('This order of the FAQ groups is used for the sidebar widget(s) as well.', $this -> plugin_name); ?>
	</p>

	<?php if (!empty($groups)) : ?>	
		<div style="max-height:200px; overflow:auto;">
			<ul id="<?php echo $this -> pre; ?>groups">
				<?php foreach ($groups as $group) : ?>
					<li style="width:85%; display:block;" id="item_<?php echo $group -> id; ?>" class="wpfaqlineitem"><?php echo $group -> name; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		
		<div id="<?php echo $this -> pre; ?>message" style="display:none;"></div>
		
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("ul#<?php echo $this -> pre; ?>groups").sortable({
				start: function(request) {
					jQuery("#<?php echo $this -> pre; ?>message").slideUp();
				},
				stop: function(request) {					
					jQuery("#<?php echo $this -> pre; ?>message").load(wpfaqAjax + "?cmd=groups_order", jQuery("ul#<?php echo $this -> pre; ?>groups").sortable('serialize')).slideDown("slow");
				},
				axis: "y"
			});
		});
		</script>
	<?php else : ?>
		<p class="<?php echo $this -> pre; ?>error"><?php _e('No groups were found. Only groups with posts/pages will be shown here.', $this -> plugin_name); ?></p>
	<?php endif; ?>
</div>