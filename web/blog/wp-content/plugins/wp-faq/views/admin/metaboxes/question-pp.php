<p>
	<label style="font-weight:bold;" for="<?php echo $wpfaqQuestion -> model; ?>_pp_none"><?php _e('Create a Post/Page', $this -> plugin_name); ?></label><br/>
    
    <?php $createpage = array('none' => __('No', $this -> plugin_name), 'post' => __('Post', $this -> plugin_name), 'page' => __('Page', $this -> plugin_name)); ?>
    <?php echo $wpfaqForm -> radio('wpfaqQuestion.pp', $createpage, array('separator' => false, 'default' => "none", 'onclick' => "change_createpp(this.value);")); ?>
    
    <script type="text/javascript">
	function change_createpp(pp) {
		jQuery('#pp_div').hide();
		jQuery('#pp_page_div').hide();
		jQuery('#pp_post_div').hide();
		
		if (pp != "none") {
			jQuery('#pp_div').show();
			
			if (pp == "page") {
				jQuery('#pp_page_div').show();	
			} else if (pp == "post") {
				jQuery('#pp_post_div').show();	
			}
		}
	}
	</script>
</p>

<div id="pp_div" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqQuestion.pp') == "post" || $wpfaqHtml -> field_value('wpfaqQuestion.pp') == "page") ? 'block' : 'none'; ?>;">
	<?php echo $wpfaqForm -> hidden('wpfaqQuestion.pp_id'); ?>

	<div id="pp_page_div" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqQuestion.pp') == "page") ? 'block' : 'none'; ?>;">
        <!-- Page Parent -->
        <p>
        	<label style="font-weight:bold;" for="wpfaqQuestion.pp_parent"><?php _e('Page Parent', $this -> plugin_name); ?></label><br/>
            <select id="wpfaqQuestion.pp_parent" name="wpfaqQuestion[pp_parent]">
                <option value="0">- <?php _e('Main (no parent)', $this -> plugin_name); ?> -</option>
                <?php parent_dropdown($wpfaqHtml -> field_value('wpfaqQuestion.pp_parent')); ?>
            </select>
        </p>
    </div>
    
    <!-- Post/Page Title -->
    <p>
    	<label style="font-weight:bold;" for="wpfaqQuestion.pp_title"><?php _e('Post/Page Title', $this -> plugin_name); ?></label><br/>
        <?php /*<input type="text" name="wpfaqQuestion[pp_title]" value="<?php echo esc_attr(stripslashes($wpfaqHtml -> field_value('wpfaqQuestion.pp_title'))); ?>" id="wpfaqQuestion.pp_title" />*/ ?>
        <?php echo $wpfaqForm -> text('wpfaqQuestion.pp_title'); ?>
    </p>
    
    <div id="pp_post_div" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqQuestion.pp') == "post") ? 'block' : 'none'; ?>;">
    	<!-- Post Stuff -->
        <p>
        	<label for="" style="font-weight:bold;"><?php _e('Post Categories', $this -> plugin_name); ?></label>
            <?php if ($categories = get_categories(array('hide_empty' => 0, 'pad_counts' => 1))) : ?>
            	<?php $pp_categories = maybe_unserialize($wpfaqHtml -> field_value('wpfaqQuestion.pp_categories')); ?>
            	<div>
                    <input type="checkbox" name="categoriesselectall" value="1" id="checkboxall" onclick="jqCheckAll(this, '<?php echo $this -> sections -> settings; ?>', 'latestposts_categories');" />
                    <label for="categoriesselectall"><strong><?php _e('Select All', $this -> plugin_name); ?></strong></label>
                </div>
                <div style="max-height:200px; overflow:auto;">
                    <?php foreach ($categories as $category) : ?>
                        <label><input <?php echo (!empty($pp_categories) && in_array($category -> cat_ID, $pp_categories)) ? 'checked="checked"' : ''; ?> type="checkbox" name="wpfaqQuestion[pp_categories][]" value="<?php echo $category -> cat_ID; ?>" id="checklist<?php echo $category -> cat_ID; ?>" /> <?php echo $category -> cat_name; ?></label><br/>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
            	<div class="<?php echo $this -> pre; ?>error"><?php _e('No categories are available.', $this -> plugin_name); ?></div>
            <?php endif; ?>
        </p>
        
        <!-- Post Comments -->
        <p>
        	<?php $pp_comments = $wpfaqHtml -> field_value('wpfaqQuestion.pp_comments'); ?>
        	<label for="pp_comments_closed" style="font-weight:bold;"><?php _e('Comment Status', $this -> plugin_name); ?></label><br/>
            <label><input <?php echo (!empty($pp_comments) && $pp_comments == "open") ? 'checked="checked"' : ''; ?> type="radio" name="wpfaqQuestion[pp_comments]" value="open" id="pp_comments_open" /> <?php _e('Open', $this -> plugin_name); ?></label>
            <label><input <?php echo (empty($pp_comments) || (!empty($pp_comments) && $pp_comments == "closed")) ? 'checked="checked"' : ''; ?> type="radio" name="wpfaqQuestion[pp_comments]" value="closed" id="pp_comments_closed" /> <?php _e('Closed', $this -> plugin_name); ?></label>
        </p>
    </div>
</div>