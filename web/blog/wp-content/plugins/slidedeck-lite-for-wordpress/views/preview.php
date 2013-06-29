<?php
/**
 * Preview SlideDeck tempalte
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
 * @uses slidedeck()
 * @uses admin_url()
 */
?>
<div id="slidedeck_preview_mask"></div>
<div id="slidedeck_preview_window" class="preview-window"<?php if( $first_preview ) echo ' style="visibilty:hidden;"'; ?>>
	<div style="margin:0 auto 20px;padding:0;width:<?php echo $preview_w; ?>;position:relative;overflow:hidden;">
		<?php slidedeck( $slidedeck_id, array( 'width' => $preview_w, 'height' => $preview_h ), false ); ?>
	</div>
	<div id="slidedeck_preview_window_form">
		<h4><strong>Preview Your SlideDeck at a Different Size</strong></h4>
		<input type="hidden" name="slidedeck_id" value="<?php echo $slidedeck_id; ?>" />
        <input type="hidden" name="refresh" value="0" /> 
		<label>
		    <?php if( ( $skin['meta']['Skin Type'] == "fixed" ) ): ?>
                Width:
            <?php else: ?>
                Dimensions:
            <?php endif; ?>
        </label> <input type="text" name="preview_w" value="<?php echo $preview_w; ?>" id="preview_w" onkeyup="updateSlideDeckPreview(this);" onblur="updateSlideDeckPreview(this);" />
			 <?php if( ( $skin['meta']['Skin Type'] == "fixed" ) ): ?>
                 <?php if( $skin['meta']['Skin Type'] == "fixed" ): $preview_h = $skin['meta']['Skin Height']; endif; ?>
			 	 <input type="hidden" name="preview_h" value="<?php echo $preview_h; ?>" id="preview_h" />
			 <?php else: ?>
                  x <input type="text" name="preview_h" value="<?php echo $preview_h; ?>" id="preview_h" onkeyup="updateSlideDeckPreview(this);" onblur="updateSlideDeckPreview(this);" />
			 <?php endif; ?>
         <a href="<?php echo admin_url('admin-ajax.php'); ?>?action=slidedeck_preview&preview_w=<?php echo $preview_w; ?>&preview_h=<?php echo $preview_h; ?>&slidedeck_id=<?php echo $slidedeck_id; ?>&width=<?php echo $width; ?>&height=<?php echo $height; ?>" id="btn_slidedeck_preview_submit" class="thickbox button-primary" onclick="cleanUpSlideDecks();" title="Preview SlideDeck">Update Preview</a>
	</div>
    <p id="preview_note"><em><strong>NOTE:</strong> This is only a preview, your mileage may vary. Place this SlideDeck in a post and preview the post for a more accurate preview.</em></p>
</div>
<script type="text/javascript">
(function($){
    if($('#slidedeck_preview_mask').length){
        setTimeout(function(){
            updateTBSize();
            
            var slidedeckPreviewWindow = $('#slidedeck_preview_window');
            var classes = slidedeckPreviewWindow.find('.slidedeck_frame')[0].className;
            var classes = classes.split(' ');
            var namespace = "";
            for(var i = 0; i < classes.length; i++){
                if(classes[i].match('skin-([a-zA-Z0-9\-_]+)')){
                    namespace = classes[i].replace('skin-', "");
                }
            }
            if(typeof(SlideDeckSkin) != 'undefined'){
                if(typeof(SlideDeckSkin[namespace]) == 'function'){
                    slidedeckPreviewWindow.find('.slidedeck').each(function(){
                        if(!$.data(this, 'skin-' + namespace)){
                            $.data(this, 'skin-' + namespace, new SlideDeckSkin[namespace](this));
                        }
                    });
                }
            }

            $('#slidedeck_preview_mask').fadeOut();
        }, 500);
    }
})(jQuery);
</script>