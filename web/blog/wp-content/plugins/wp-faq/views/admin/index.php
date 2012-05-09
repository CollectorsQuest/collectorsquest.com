<div class="wrap">
	<div class="icon32">
		<img src="<?php echo $this -> url(); ?>/images/icon-36.png" alt="<?php echo $this -> pre; ?>" />
	</div>

	<h2><?php _e('FAQs', $this -> plugin_name); ?> <?php echo $this -> version; ?></h2>
	
	<?php if (WPFAQ_SHOW_RSS == true || WPFAQ_SHOW_SUPPORT == true) : ?>	
		<div class="tablenav">
			<div class="alignleft actions">
				<?php if (WPFAQ_SHOW_RSS) : ?><a href="http://feeds.feedburner.com/TribulantSoftwareBlogFeed" target="_blank" title="Tribulant News Blog RSS" class="button"><img src="<?php echo get_option('siteurl'); ?>/<?php echo WPINC; ?>/images/rss.png" alt="rss" style="width:10px; height:10px;" /> Tribulant RSS</a><?php endif; ?>
				<?php if (WPFAQ_SHOW_SUPPORT) : ?><a href="http://tribulant.com/support/" target="_blank" title="Tribulant Support" class="button">Get Support</a><?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('Module/Section', $this -> plugin_name); ?></th>
				<th><?php _e('Count', $this -> plugin_name); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><?php _e('Module/Section', $this -> plugin_name); ?></th>
				<th><?php _e('Count', $this -> plugin_name); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<tr class="alternate">
				<th><?php _e('Question Groups', $this -> plugin_name); ?></th>
				<td>
					<?php $wpfaqDb -> model = $wpfaqGroup -> model; ?>
					<?php $count = $wpfaqDb -> count(); ?>
					<?php echo $wpfaqHtml -> link($count, '?page=' . $this -> sections -> groups, array('title' => __('Manage FAQ Groups', $this -> plugin_name))); ?>
				</td>
			</tr>
			<tr>
				<th><?php _e('FAQ Questions', $this -> plugin_name); ?></th>
				<td>
					<?php $wpfaqDb -> model = $wpfaqQuestion -> model; ?>
					<?php $count = $wpfaqDb -> count(); ?>
					<?php echo $wpfaqHtml -> link($count, '?page=' . $this -> sections -> questions, array('title' => __('Manage FAQ Questions', $this -> plugin_name))); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>