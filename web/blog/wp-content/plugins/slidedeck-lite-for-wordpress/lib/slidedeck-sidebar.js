jQuery(document).ready(function(){
    jQuery('#slidedeck_tinymce_dialog').dialog({
        autoOpen: false,
        buttons: {
            "Insert": function(){
                insertSelectedDeck();
            },
            "Cancel": function(){
                jQuery(this).dialog('close');
            }
        },
        open: function(){
            if( jQuery('#slidedeck_tinymce_dialog tbody tr.selected').length == 0  ){
                jQuery('#slidedeck_tinymce_dialog tbody tr:first').addClass('selected');
				if( jQuery('#slidedeck_tinymce_dialog tbody tr:first').hasClass('dynamic') ){
					jQuery('#slidedeck_tinymce_dimension_h').val('370px');
				}else{
					jQuery('#slidedeck_tinymce_dimension_h').val('300px');
				}
            }
        },
        width: 450,
        height: 'auto',
        draggable: false,
        resizable: false,
        title: 'Insert SlideDeck',
        dialogClass: parseInt(jQuery().jquery.split(".")[1]) === 2 ? 'ui-slidedeck-2' : 'ui-slidedeck'
    }).find('tbody tr').click(function(event){
        event.preventDefault();
        jQuery('#slidedeck_tinymce_dialog tbody tr').removeClass('selected');
        jQuery(this).addClass('selected');
        if( jQuery(this).hasClass('dynamic') ){
            jQuery('#slidedeck_tinymce_dimension_h').val('370px');
        }else{
            jQuery('#slidedeck_tinymce_dimension_h').val('300px');
        }
        
    });
    
    function insertSelectedDeck(){
        var slidedeck_id = jQuery('#slidedeck_tinymce_dialog tbody tr.selected')[0].id.split("_")[2];
        var width = jQuery('#slidedeck_tinymce_dimension_w').val();
        var height = jQuery('#slidedeck_tinymce_dimension_h').val();
    
        var slidedeck_str = " [SlideDeck id='" + slidedeck_id + "'";
        if(width.replace(/^\s+|\s+$/g,"") != ""){
            slidedeck_str += " width='" + width + "'";
        }
        if(height.replace(/^\s+|\s+$/g,"") != ""){
            slidedeck_str += " height='" + height + "'";
        }
        slidedeck_str += "] ";
        
        if (typeof(tinyMCE) != 'undefined' && (ed = tinyMCE.activeEditor) && !ed.isHidden()) {
            ed.focus();
            if (tinymce.isIE) {
                ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
            }
            ed.execCommand('mceInsertContent', false, slidedeck_str);
        } else {
            edInsertContent(edCanvas, slidedeck_str);
        }
        
        jQuery('#slidedeck_tinymce_dialog').dialog('close');
    }
    
    jQuery('#slidedeck-meta-sidebar a.slidedeck-sidebar-insert').bind('click', function(event){
        event.preventDefault();
        jQuery('#slidedeck_tinymce_dialog').dialog('open');
    });
    
    jQuery("#ed_toolbar").append('<input type="button" class="ed_button insertSlidedeck" value="SlideDeck" />');
    jQuery("#ed_toolbar .insertSlidedeck").click(function(){
        jQuery('#slidedeck_tinymce_dialog').dialog('open');
    });
    
});