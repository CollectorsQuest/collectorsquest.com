<?php
/**
 * Individual slide markup
 * 
 * SlideDeck for WordPress 1.4.8 - 2011-12-14
 * Copyright (c) 2011 digital-telepathy (http://www.dtelepathy.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * @package SlideDeck
 * @subpackage SlideDeck for WordPress
 * 
 * @author digital-telepathy
 * @version 1.4.8
 * 
 * @uses slidedeck_get_option()
 * @uses slidedeck_dir()
 */

?>
<div id="slide_editor_<?php echo $count; ?>" class="postbox slide">
    
    <div title="Click to toggle" class="handlediv">&nbsp;</div>
    
	<h3 class="hndle"><span><?php echo empty( $slide['title'] ) ? "Slide " . $slide['slide_order'] : $slide['title']; ?></span></h3>
	
	<div class="inside">
	    
		<?php if( isset( $slide['id']) && !empty( $slide['id'] ) ): ?>
			<input type="hidden" name="slide[<?php echo $count; ?>][id]" value="<?php echo $slide['id']; ?>" />
		<?php endif; ?>
        
		<div class="add-delete-controls">
			<a href="#<?php echo $count; ?>" class="slide-delete">Delete Slide</a>
		</div>
        
		<input type="hidden" name="slide[<?php echo $count; ?>][slide_order]" value="<?php echo $slide['slide_order']; ?>" class="slide-order" />
        
		<ol class="formRows">
		    
			<li>
				<label>Slide Title:</label>
				<input type="text" name="slide[<?php echo $count; ?>][title]" value="<?php echo empty( $slide['title'] ) ? 'Slide ' . $count : $slide['title']; ?>" size="40" maxlength="255" class="slide-title" />
			</li>
            
			<li class="editor-area">
				<?php $editor_id = "slide_{$count}_content"; ?>

				<?php if( SLIDEDECK_USE_OLD_TINYMCE_EDITOR ): ?>
                    <span class="horizontal-slide-media">
    				    <?php include('_editor_media_buttons.php'); ?>
                    </span>
    				
    				<div class="editor-container">
                        <textarea name="slide[<?php echo $count; ?>][content]" cols="80" rows="10" class="horizontal<?php echo !$is_vertical ? ' slide-content' : ''; ?>" id="<?php echo $editor_id; ?>"><?php echo htmlspecialchars( slidedeck_process_slide_content( $slide['content'], true ), ENT_QUOTES ); ?></textarea>
    				</div>
                <?php else: ?>
                    <?php
                        wp_editor( slidedeck_process_slide_content( $slide['content'], true, $slidedeck['new_format'] ), $editor_id, array(
                            'wpautop' => true,
                            'media_buttons' => true,
                            'textarea_name' => "slide[{$count}][content]",
                            'textarea_rows' => 10,
                            'editor_class' => "horizontal",
                            'teeny' => false,
                            'dfw' => false,
                            'tinymce' => true,
                            'quicktags' => true
                        ) );
                    ?>
				<?php endif; ?>
			</li>
                                    
		</ol>
        
	</div>
    
</div>