jQuery(document).ready(function(){
	
	// Run only if we have images to display
	if (jQuery('#media-items > *').length == 0) 
		return;

	var $include = '', $is_update = false, $is_checked;
	
	// Add Gallery include All or None, for easier selection
	jQuery('#sort-buttons').prepend('Include in gallery: <a id="gallery-include-all" href="#">All</a> | <a id="gallery-include-none" style="margin-right:2em;" href="#">None</a>');
	jQuery('#gallery-include-all').click(function() {
		jQuery('#media-items input[type=checkbox]').each(function() {
			jQuery(this).attr('checked', 'checked');
		});		
	});
	jQuery('#gallery-include-none').click(function() {
		jQuery('#media-items input[type=checkbox]').each(function() {
			jQuery(this).removeAttr('checked');
		});
	});
	
	// Select parent editor, read existing gallery data	
	w = wpgallery.getWin();
	editor = w.tinymce.EditorManager.activeEditor;

	if (editor !== null) {
		gal = editor.selection.getNode();
	
		if (editor.dom.hasClass(gal, 'wpGallery')) {
			$include = editor.dom.getAttrib(gal, 'title').match(/include=['"]([^'"]+)['"]/i);
			var $is_update = true;
			if ($include != null)
				$include = $include[1];
		} else {
			jQuery('#insert-gallery').show();
			jQuery('#update-gallery').hide();
		}
	}
	
	// Check which images have been selected for inclusion
	jQuery('#media-items .media-item').each(function($count) {
		var $imgid = jQuery(this).attr('id').split('-')[2];
		if ($include != null && $include.indexOf($imgid) != -1)
			$is_checked = ' checked="checked" ';
		else
			$is_checked = '';	
		jQuery('.menu_order', this).append(' <label class="include-in-gallery"><input type="checkbox" title="Include image in this gallery" id="include-in-gallery-'+$imgid+'" '+$is_checked+' value="" /></label>');
	});		
	
	jQuery('#insert-gallery').attr('onmousedown', '');
	
	// Insert or update the actual shortcode
	jQuery('#update-gallery, #insert-gallery, #save-all').mousedown(function() {
		var $to_include = '';
		if (editor !== null)
			var orig_gallery = editor.dom.decode(editor.dom.getAttrib(gal, 'title'));
		else
			var orig_gallery = '';

		// Check which images have been selected to be included
		jQuery('#media-items .media-item').each(function($count) {
			$imgid = jQuery(this).attr('id').split('-')[2];
			
			if (jQuery('#include-in-gallery-'+$imgid+':checked', this).val() != null)
				$to_include += $imgid + ', ';
		});
		
		if ($to_include.length > 2) {
			$to_include = $to_include.substr(0, $to_include.length - 2); // remove the last comma
			$to_include = ' include="' + $to_include + '" ';
		}
		
		if (jQuery(this).attr('id') == 'insert-gallery') {
			w.send_to_editor('[gallery' + wpgallery.getSettings() + $to_include + ']');
		}
		
		// Update existing shortcode
		if ($is_update) {
			if ($to_include != '' && orig_gallery.indexOf(' include=') == -1)
				editor.dom.setAttrib(gal, 'title', orig_gallery + $to_include);
			else if (orig_gallery.indexOf(' include=') != -1)
				editor.dom.setAttrib(gal, 'title', orig_gallery.replace(/include=['"]([^'"]+)['"]/i, $to_include));
			else
				editor.dom.setAttrib(gal, 'title', orig_gallery.replace(/include=['"]([^'"]+)['"]/i, ''));
		}
	});

});