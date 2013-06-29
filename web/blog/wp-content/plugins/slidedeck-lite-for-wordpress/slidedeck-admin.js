/**
 * SlideDeck for WordPress 1.4.8 - 2011-12-14
 * Copyright 2011 digital-telepathy  (email : support@digital-telepathy.com)
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
 * More information on this project:
 * http://www.slidedeck.com/
 * 
 * Full Usage Documentation: http://www.slidedeck.com/usage-documentation 
 * 
 * @package SlideDeck
 * @subpackage SlideDeck for WordPress
 * 
 * @author digital-telepathy
 * @version 1.4.8
 */

var SlideDeckSlides = {
    processing: false,
    namespace: 'slidedeck',
    
    updateTitle: function(e){
        var element = e;
        if (this.timer) {
            clearTimeout(element.timer);
        }
        this.timer = setTimeout(function(){
            jQuery('#hndle_for_' + jQuery(element).parents('.slide')[0].id).text(element.value);
            jQuery(element).parents('.slide').find('h3.hndle').text(element.value);
            document.getElementById('slide-start').options[jQuery(element).parents('.slide').find('input.slide-order')[0].value - 1].text = element.value;
        },100);
        return true;
    },
    
    addSlide: function(e){
        var self = this;
        
        if(this.processing === false){
            this.processing = true;
            
            var el = e;
            var url = typeof(ajaxurl) != 'undefined' ? ajaxurl : e.href.split('?')[0].replace(document.location.protocol + '//' + document.location.hostname, "");
            
            // Create array of existing indexes and increment if necessary to prevent ID duplication
            var slideCount = parseInt(jQuery('.slide').length);
            var existingIndexes = [];
            for(var i=0, hSlides=jQuery('.slide textarea.horizontal.slide-content'); i<hSlides.length; i++){
                existingIndexes.push(parseInt(hSlides[i].id.split('_')[1], 10));
            }
            // Descending sort to get highest present index value first 
            existingIndexes.sort(function(a, b){
                return a < b;
            });
            if(existingIndexes[0] > slideCount){
                slideCount = existingIndexes[0];
            }
    
            jQuery.ajax({
                url: url,
                type: 'get',
                data: {
                    action: 'slidedeck_add_slide',
                    count: slideCount,
                    gallery_id: jQuery('#slidedeck_gallery_id').val()
                },
                complete: function(data){
                    var row_id = "slide_editor_" + (slideCount + 1),
                        editor_id = "slide_" + (slideCount + 1) + "_content";
                    
                    jQuery('.slides').append(data.responseText);
                    jQuery('#re-order-slides .slide-order').append('<li><a href="#' + row_id + '" class="hndle" id="hndle_for_slide_editor_' + (slideCount + 1) + '">Slide ' + (slideCount + 1) + '</a></li>');
                    jQuery('#slide-start').append('<option value="' + (slideCount + 1) + '">Slide ' + (slideCount + 1) + '</option>');
                    
                    if(SLIDEDECK_USE_OLD_TINYMCE_EDITOR ===  true){
                        tinyParams = tinyMCEPreInit.mceInit;
                        tinyParams.mode = "exact";
                        tinyParams.elements = editor_id;
                    } else {
                        var i = 0;
                        for(var k in tinyMCEPreInit.mceInit){
                            if(i == 0) tinyParams = tinyMCEPreInit.mceInit[k];
                            i++;
                        }
                        tinyParams.mode = "exact";
                        tinyParams.elements = editor_id;
                        
                        quicktags({
                            id: editor_id,
                            buttons: "",
                            disabled_buttons: ""
                        });
                        QTags._buttonsInit();
                        jQuery('#wp-' + editor_id + '-wrap').removeClass('html-active').addClass('tmce-active');
                    }
                    
                    tinyMCE.init(tinyParams);
    
                    tb_init(jQuery('#' + row_id + ' .horizontal-slide-media a.thickbox'));
                    tb_init(jQuery('#' + row_id + ' .vertical-slide-media a.thickbox'));
                    
                    self.updateEditorControls(jQuery('#' + editor_id)); // html element textarea.

                    self.processing = false;
                }
            });
        }
    },
    
    updateEditorControls: function(e,row_id){
        var slide = e.parents('.slide');
        
        slide.find('.slide-title').unbind('keyup.' + this.namespace).bind('keyup.' + this.namespace, function(){
            SlideDeckSlides.updateTitle(this);
        });
        
        slide.find('.editor-nav a.mode').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(event){
            event.preventDefault();
            SlideDeckSlides.editorNavigation(this);
        });
        
        slide.find('.slide-delete').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(event){
            event.preventDefault();
            SlideDeckSlides.deleteSlide(this);
        });
                
        slide.find('.handlediv').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(event){
            event.preventDefault();
            jQuery(this).parent().find('.inside').toggle();
        });
        
        slide.find('.slide-delete').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(event){
            event.preventDefault();
            SlideDeckSlides.deleteSlide(this);
        });

        slide.find('.media-buttons, .add_media').show();
        slide.find('.media-buttons a.thickbox, a.add_media').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(){
            SlideDeckSlides.tb_click(this);
        });
        
        slide.find('textarea').each(function(){
            SlideDeckSlides.updateUploadInsertLinks(this);
        })
    },
    
    deleteSlide: function(e){
        if(confirm("Are you sure you would like to delete this slide?")){
            var slide = jQuery(e).parents('.slide');
            var slide_id = slide.attr('id').split('_')[2];
            var textareas = slide.find('textarea');
            
            // Clean up tinymce editors
            textareas.each(function(){
                tinyMCE.execCommand('mceRemoveControl', false, this.id);
            });
            
            jQuery('#hndle_for_slide_editor_' + slide_id).parents('li').remove();
            jQuery('#slide-start').find('option[value="' + slide_id + '"]').remove();
            
            slide.remove();
        }        
    },
    
    tb_click: function(e){
        if ( typeof tinyMCE != 'undefined' && tinyMCE.activeEditor ) {
            var url = jQuery(e).attr('href');
            url = url.split('editor=');
            if(url.length>1){
                url = url[1];
                url = url.split('&');
                if(url.length>1){
                    editorid = url[0];
                }
            }
            // If data-editor is set on Add Media button - use this value as the prev. Href check will fail - for more recent versions of WordPress
            if ( jQuery(e).data('editor') )
                editorid = jQuery(e).data('editor');
                
            tinyMCE.get(editorid).focus();
            tinyMCE.activeEditor.windowManager.bookmark = tinyMCE.activeEditor.selection.getBookmark('simple');
            jQuery(window).resize();
        }
    },

    editorNavigation: function(e){
        var p = jQuery(e).parents('li:eq(0)');
        var navs = p.find('.editor-nav a');
        navs.removeClass('active');
        jQuery(e).addClass('active');

        var editor = e.href.split("#")[1];
        var textarea = p.find('textarea.slide-content')[0];
        
        switch(editor){
            case "visual":
                this.switchEditorNav( textarea.id, 'tinymce' );
            break;
            
            case "html":
                this.switchEditorNav( textarea.id, 'html' );
            break;
        }
    },
    
    switchEditorNav: function( textarea_id, mode ){
        if(SLIDEDECK_USE_OLD_TINYMCE_EDITOR === false){
            return false;
        }
        
        var editor = false;
        if(typeof(tinyMCE) != 'undefined'){
            editor = tinyMCE.get(textarea_id);
        }
        var textarea = jQuery('#' + textarea_id);
        
        switch(mode){
            case "tinymce":
                textarea.css('color','#fff').val(switchEditors.wpautop(textarea.val()));
                editor.show();
                tinyMCE.execCommand('mceAddControl', false, textarea_id);
                textarea.css('color','#000');
            break;
            
            case "html":
                textarea.css('color','#000');
                editor.hide();
            break;
        }
    },
    
    updateUploadInsertLinks: function(textarea){
        // Add editor attribute to all upload/insert buttons for WYSIWYG editors
        if(SLIDEDECK_USE_OLD_TINYMCE_EDITOR === false){
            var $textarea = jQuery(textarea);
            var $parent = $textarea.closest('.wp-editor-wrap'); 
            var $editor_tools = $parent.find('.wp-editor-tools');
            var $upload_insert = $editor_tools.find('a.add_media');
            
            var href = $upload_insert.attr('href');
            
            if(href.match(/editor\=/)){
                href = href.replace(/editor\=([a-zA-Z0-9\-_]+)/, "editor=" + $textarea.attr('id'));
            } else {
                href = href.replace("TB_iframe=1", "editor=" + $textarea.attr('id') + "&TB_iframe=1");
            }
            
            $upload_insert.attr('href', href);
        }
    }
};


var legacy_send_to_editor = function(h){
    var ed;
    var editorid;
    var url = jQuery('#TB_window iframe').attr('src');
    url = url.split('editor=');
    if (url.length > 1) {
        url = url[1];
        url = url.split('&');
        if (url.length > 1) {
            editorid = url[0];
        }
    }
    
    if (typeof(tinyMCE) != 'undefined' && (ed = tinyMCE.get(editorid)) && !ed.isHidden()) {
        ed.focus();
        if (tinymce.isIE) 
            ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
        
        if ( h.indexOf('[caption') === 0 ) {
            if ( ed.plugins.wpeditimage )
                h = ed.plugins.wpeditimage._do_shcode(h);
        } else if ( h.indexOf('[gallery') === 0 ) {
            if ( ed.plugins.wpgallery )
                h = ed.plugins.wpgallery._do_gallery(h);
        } else if ( h.indexOf('[embed') === 0 ) {
            if ( ed.plugins.wordpress )
                h = ed.plugins.wordpress._setEmbed(h);
        }
        
        ed.execCommand('mceInsertContent', false, h);
        
    }
    else {
        if(typeof(edInsertContent) == 'function') {
            edInsertContent(document.getElementById(editorid), h);
        }else if(editorid.indexOf('_content') != -1 ) {
            jQuery('#' + editorid).val( jQuery('#' + editorid).val() + h);
        }
    }

    tb_remove();
}
var override_send_to_editor = function(h) {
    var ed, mce = typeof(tinymce) != 'undefined', qt = typeof(QTags) != 'undefined', editorid, url = jQuery('#TB_window iframe').attr('src');
    
    if( url != undefined ) {
        url = url.split('editor=');
        if (url.length > 1) {
            url = url[1];
            url = url.split('&');
            if (url.length > 1) {
                wpActiveEditor = editorid = url[0];
            }
        }
    }
    
    if ( !wpActiveEditor ) {
        if ( mce && tinymce.activeEditor ) {
            ed = tinymce.activeEditor;
            wpActiveEditor = ed.id;
        } else if ( !qt ) {
            return false;
        }
    } else if ( mce ) {
        // Removed Full-Screen editor check as that button/feature was removed
        ed = tinymce.activeEditor;
    }

    if ( ed && !ed.isHidden() ) {
        // restore caret position on IE
        if ( tinymce.isIE && ed.windowManager.insertimagebookmark )
            ed.selection.moveToBookmark(ed.windowManager.insertimagebookmark);

        if ( h.indexOf('[caption') === 0 ) {
            if ( ed.plugins.wpeditimage )
                h = ed.plugins.wpeditimage._do_shcode(h);
        } else if ( h.indexOf('[gallery') === 0 ) {
            if ( ed.plugins.wpgallery )
                h = ed.plugins.wpgallery._do_gallery(h);
        } else if ( h.indexOf('[embed') === 0 ) {
            if ( ed.plugins.wordpress )
                h = ed.plugins.wordpress._setEmbed(h);
        }

        ed.execCommand('mceInsertContent', false, h);
    } else if ( qt ) {
        QTags.insertContent(h);
    } else {
        document.getElementById(wpActiveEditor).value += h;
    }

    try{tb_remove();}catch(e){};
}
send_to_editor = (SLIDEDECK_USE_OLD_TINYMCE_EDITOR === true) ? legacy_send_to_editor : override_send_to_editor;
jQuery(document).ready(function(){
    send_to_editor = (SLIDEDECK_USE_OLD_TINYMCE_EDITOR === true) ? legacy_send_to_editor : override_send_to_editor;
});
jQuery(window).ready(function(){
    send_to_editor = (SLIDEDECK_USE_OLD_TINYMCE_EDITOR === true) ? legacy_send_to_editor : override_send_to_editor;
});


function updateSlideDeckPreview(el){
    var btn = document.getElementById('btn_slidedeck_preview_submit');
    
    var params_raw = btn.href.split('?')[1].split('&');
    var params = {};
    for(var p in params_raw){
        var param = params_raw[p].split('=');
        params[param[0]] = param[1];
    }
    
    params[el.id] = el.value;
    switch(el.id){
        case "preview_w":
            params['width'] = Math.max(630,params[el.id].match(/([0-9]+)/g)[0]) + 20;
        break;
        case "preview_h":
            params['height'] = parseInt(params[el.id].match(/([0-9]+)/g)[0]) + 200;
        break;
    }

    var href = btn.href.split('?')[0];
    var sep = "?";
    for(var k in params){
        href += sep + k + "=" + params[k];
        sep = "&";
    }

    btn.href = href;
}


function closePreviewWatcher(){
    var timer;
    timer = setInterval(function(){
        if(document.getElementById('TB_closeWindowButton')){
            clearInterval(timer);
            jQuery('#TB_closeWindowButton, #TB_overlay').bind('mouseup', function(event){
                cleanUpSlideDecks();
            });
        }
    }, 20);
}


function cleanUpSlideDecks(){
    jQuery('body > a').filter(function(){
        return (this.id.indexOf('SlideDeck_Bug') != -1);
    }).remove();
}


var updateImageOptions = {
    options: [],
    
    values: {
        post: ['none', 'content', 'gallery', 'thumbnail'],
        rss: ['none', 'content']
    },
    
    getSelected: function(){
        this.selected = jQuery(this.el).val();
    },
    
    removeOptions: function(){
        // Array must loop backwards since items are being removed from it as the loop runs
        for(var i=this.el_options.length - 1; i>=0; i--){
            this.el.remove(i);
        }
    },
    
    update: function(){
        this.getSelected();
        this.removeOptions();
        
        var post_type = jQuery('input[name*="type"]:checked').val() == "rss" ? "rss" : "post";

        for(var i=0; i<this.options.length; i++){
            var option = this.options[i];
            if(jQuery.inArray(option.value, this.values[post_type]) != -1){
                var newOption = document.createElement('OPTION');
                    newOption.text = option.text;
                    newOption.value = option.value;
                
                if(this.selected == option.value){
                    newOption.selected = "selected";
                }
                
                try {
                    this.el.add(newOption, null);    // Standards compliant, non-IE browsers
                } catch(e) {
                    this.el.add(newOption);          // IE browsers only
                }
            }
        }
    },
    
    initialize: function(){
        var self = this;
        
        this.namespace = SlideDeckSlides.namespace;
        this.el = document.getElementById('slidedeck_image_source');

        if(this.el){
            this.el_options = this.el.options;
            this.getSelected();
            
            for(var i=0; i<this.el_options.length; i++){
                var option = this.el_options[i];
                this.options.push({
                    id: option.id,
                    value: option.value,
                    text: option.text
                });
            }
            
            jQuery(this.el).bind('change.' + this.namespace, function(event){
                self.selected = jQuery(this).val();
            });
            
            jQuery('#smart_slidedeck_type_of_content input[type="radio"]').bind('click.' + this.namespace, function(event){
                self.update();
                this.blur();
            });

            this.update();
        }
    }
};


var updateTBSize = function(){
    var tbWindow = jQuery('#TB_window'), tbTitle = jQuery('#TB_title'), width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width, adminbar_height = 0;
    var tbtitle_height = tbTitle.height();
    
    if(jQuery('body.admin-bar').length) adminbar_height = jQuery('#wpadminbar').height();
    
    if(tbWindow.size()){
        if(tbWindow.find('#slidedeck_preview_window').length){
            var ajaxContent = jQuery('#TB_ajaxContent');
            var slidedeckFrame = tbWindow.find('.slidedeck_frame');
            var slidedeckPreviewWindowWrapper = slidedeckFrame.closest('div:not(.slidedeck_frame)');
            
            var frame = {
                borderLeft: parseInt(slidedeckFrame.css('border-left-width')),
                borderRight: parseInt(slidedeckFrame.css('border-right-width')),
                paddingLeft: parseInt(slidedeckFrame.css('padding-left')),
                paddingRight: parseInt(slidedeckFrame.css('padding-right'))
            };
            for(var k in frame){
                frame[k] = isNaN(frame[k]) ? 0 : frame[k];
            }
            var previewWidth = parseInt(jQuery('#preview_w').val());
            
            W = previewWidth + frame.borderLeft + frame.borderRight + frame.paddingLeft + frame.paddingRight;
            H = ajaxContent.outerHeight();
            tbWindow.width(W + 40).height(H + tbtitle_height);
            ajaxContent.width(W + 10);
            slidedeckPreviewWindowWrapper.width(W);
        } else {
            tbWindow.width(W - 50).height(H - 45 - adminbar_height);
        }
        jQuery('#TB_iframeContent').width(W - 50).height(H - 75 - adminbar_height);
        tbWindow.css({
            'margin-left': '-' + parseInt((tbWindow.width() / 2), 10) + 'px'
        });
        if(typeof document.body.style.maxWidth != 'undefined'){
            tbWindow.css({
                'top': (20 + adminbar_height) + 'px',
                'margin-top': '0'
            });
        }
    }
    
    return jQuery('.media-buttons a.thickbox, a.slide-background-upload').each(function(){
        var href = this.href;
        if(!href) return;
        href = href.replace(/&width=[0-9]+/g, '');
        href = href.replace(/&height=[0-9]+/g, '');
        this.href = href + '&width=' + (W - 80) + '&height=' + (H - 85 - adminbar_height);
    });
};
var tb_position = updateTBSize;


(function($){
    window.SkinOptions = {
        elems: {},
        
        previousSkin: "",
        selectedSkin: "",
        
        previousTransition: "",
        selectedTransition: "",
        
        defaultOptions: {
            autoPlay: false,
            autoPlayInterval: 5,
            cycle: false,
            keys: false,
            scroll: true,
            continueScrolling: false,
            useNewVertical: true,
            hideSpines: false,
            slideTransition: 'slide'
        },
        
        transitionOptions: {
            'stack': {
                activeCorners: false,
                hideSpines: true
            },
            'fade': {
                activeCorners: false,
                hideSpines: true
            },
            'flipHorizontal': {
                activeCorners: false,
                hideSpines: true
            },
            'flip': {
                activeCorners: false,
                hideSpines: true
            },
            'slide': {}
        },
        
        skinOptions: {
            'stacked-nav': {
                activeCorners: false,
                hideSpines: true,
                useNewVertical: true
            },
            'stacked-nav-arrow': {
                activeCorners: false,
                hideSpines: true,
                useNewVertical: true
            },
            'flip': {
                hideSpines: true,
                slideTransition: 'flip'
            },
            'flip-chrome': {
                hideSpines: true,
                slideTransition: 'flip'
            },
            'flip-glass': {
                hideSpines: true,
                slideTransition: 'flip'
            },
            'flip-wood': {
                hideSpines: true,
                slideTransition: 'flip'
            },
            'fullwidth-sexy': {
                hideSpines: true
            },
            'fullwidth-sexy-black': {
                hideSpines: true
            },
            'fullwidth-sexy-brown': {
                hideSpines: true
            },
            'fullwidth-sexy-cyan': {
                hideSpines: true
            },
            'fullwidth-sexy-gray': {
                hideSpines: true
            },
            'fullwidth-sexy-green': {
                hideSpines: true
            },
            'fullwidth-sexy-light-gray': {
                hideSpines: true
            },
            'fullwidth-sexy-orange': {
                hideSpines: true
            },
            'fullwidth-sexy-red': {
                hideSpines: true
            },
            'fullwidth-sexy-silver': {
                hideSpines: true
            },
            'fullwidth-sexy-white': {
                hideSpines: true
            },
            'pagecurl': {
                hideSpines: true,
                slideTransition: 'stack'
            },
            'pagecurl-variation-1': {
                hideSpines: true,
                slideTransition: 'stack'
            },
            'pagecurl-variation-2': {
                hideSpines: true,
                slideTransition: 'stack'
            },
            'simple-slider': {
                hideSpines: true
            },
            'simple-slider-chrome': {
                hideSpines: true
            },
            'simple-slider-elegant': {
                hideSpines: true
            },
            'simple-slider-paper': {
                hideSpines: true
            },
            'simple-slider-ruby': {
                hideSpines: true
            },
            'simple-slider-smoke': {
                hideSpines: true
            },
            'thumbnail': {
                hideSpines: true
            },
            'thumbnail-silver': {
                hideSpines: true
            },
            'thumbnail-slate': {
                hideSpines: true
            },
            'thumbnail-snow': {
                hideSpines: true
            },
            'thumbnail-wood': {
                hideSpines: true
            }
        },
        
        resetOptions: function(){
            if(typeof(this.transitionOptions[this.previousTransition])){
                for(var k in this.transitionOptions[this.previousTransition]){
                    if(typeof(this.elems[k]) != 'undefined'){
                        this.setOption(k, this.defaultOptions[k]);
                        
                        this.elems[k].next('input[type="hidden"][name="' + this.elems[k].attr('name') + '"]').remove();
                        this.elems[k].removeClass('disabled')[0].disabled = false;
                        this.elems[k].closest('label').removeClass('disabled');
                    }
                }
            }
            if(typeof(this.skinOptions[this.previousSkin]) != 'undefined'){
                for(var k in this.skinOptions[this.previousSkin]){
                    if(typeof(this.elems[k]) != 'undefined'){
                        this.setOption(k, this.defaultOptions[k]);
                        
                        this.elems[k].next('input[type="hidden"][name="' + this.elems[k].attr('name') + '"]').remove();
                        this.elems[k].removeClass('disabled')[0].disabled = false;
                        this.elems[k].closest('label').removeClass('disabled');
                    }
                }
            }
        },
        
        setOption: function(fieldName, value){
            if(typeof(this.elems[fieldName]) != 'undefined'){
                var field = this.elems[fieldName];
                var fieldVal = field.val();
                
                switch(field[0].nodeName){
                    case "INPUT":
                        switch(field.attr('type')){
                            case "checkbox":
                            case "radio":
                                if(value == true){
                                    field.attr('checked', 'checked')[0].checked = true;
                                } else {
                                    field.removeAttr('checked')[0].checked = false;
                                }
                            break;
                            
                            case "text":
                                field.val(value);
                            break;
                        }
                    break;
                    
                    case "SELECT":
                        field.find('option').removeAttr('selected', 'selected').each(function(){
                            if(this.value == value){
                                this.selected = true;
                            }
                        });
                        
                        fieldVal = field.find('option:selected').val();
                    break;
                }
                
                field.attr('disabled', 'disabled')[0].disabled = true;
                if(field.next('input[type="hidden"][name="' + field.attr('name') + '"]').length){
                    field.next('input[type="hidden"][name="' + field.attr('name') + '"]').val(fieldVal);
                } else {
                    field.after('<input type="hidden" name="' + field.attr('name') + '" value="' + fieldVal + '" />');
                }
                field.closest('label').addClass('disabled');
            }
        },
        
        updateOptions: function(skin){
            var self = this;
            this.updateSelectedSkin();
            
            this.resetOptions();
            
            if(typeof(this.skinOptions[this.selectedSkin]) != 'undefined'){
                for(var k in this.skinOptions[this.selectedSkin]){
                    if(typeof(this.elems[k]) != 'undefined'){
                        this.setOption(k, this.skinOptions[this.selectedSkin][k]);
                    }
                }
            }
            
            this.updateSelectedTransition();
            
            if(typeof(this.transitionOptions[this.selectedTransition]) != 'undefined'){
                for(var k in this.transitionOptions[this.selectedTransition]){
                    if(typeof(this.elems[k]) != 'undefined'){
                        this.setOption(k, this.transitionOptions[this.selectedTransition][k]);
                    }
                }
            }
        },
        
        updateSelectedSkin: function(){
            this.previousSkin = this.selectedSkin;
            this.selectedSkin = this.elems.skinSelector.find('option:selected').val();
        },
        
        updateSelectedTransition: function(){
            this.previousTransition = this.selectedTransition;
            this.selectedTransition = this.elems.transitionSelector.find('option:selected').val();
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.skinSelector = $('#slide-skin');
            this.elems.transitionSelector = $('#slide-slideTransition');
            
            // Fail silently if this page does not have the skin selector
            if(this.elems.skinSelector.length < 1){
                return false;
            }
            
            for(var k in this.defaultOptions){
                this.elems[k] = $('[name="slidedeck_options[' + k + ']"]');
            }
            
            this.updateSelectedSkin();
            this.updateSelectedTransition();
            
            this.elems.skinSelector.bind('change', function(event){
                self.updateOptions();
            });
            
            this.elems.transitionSelector.bind('change', function(event){
                self.updateOptions();
            });
            
            // Set initial options based off the chosen skin
            this.updateOptions();
        }
    };
    

    function overviewWrap(){
        if($('.overview-options').length){
            if($(document).width() < 1240) {
                $('.overview-options').find('.rss-feed').css({
                    width: 'auto',
                    'float': 'none'
                })
                $('#overview_options_form')[0].style.width = '100%';
            } else {
                $('.overview-options').find('.rss-feed').css({
                    width: "45%",
                    'float': "right"
                })
                $('#overview_options_form')[0].style.width = '';
            }
        }
    }
    
    $(document).ready(function(){
        $('.button.slide-convert-vertical.disabled').bind('click', function(event){
            jQuery('#vertical_slides_pro').dialog({
                width: 450,
                height: 'auto',
                draggable: false,
                resizable: false,
                modal: true,
                title: 'Vertical Slides',
                dialogClass: parseInt(jQuery().jquery.split(".")[1]) === 2 ? 'ui-slidedeck-2 purpleHead' : 'ui-slidedeck purpleHead'
            });
        });
        
        $('.slide-background-url input, .slide-background-url .button.disabled, .slide-background-url label.disabled').bind('click', function(event){
            jQuery('#background_images_pro').dialog({
                width: 450,
                height: 'auto',
                draggable: false,
                resizable: false,
                modal: true,
                title: 'Background Images',
                dialogClass: parseInt(jQuery().jquery.split(".")[1]) === 2 ? 'ui-slidedeck-2 purpleHead' : 'ui-slidedeck purpleHead'
            });
        });
                       
        $('.slide-title').bind('keyup.' + SlideDeckSlides.namespace, function(){
            SlideDeckSlides.updateTitle(this);
        });

        $('.editor-nav a.mode').bind('click.' + SlideDeckSlides.namespace, function(event){
            event.preventDefault();
            SlideDeckSlides.editorNavigation(this);
        });

        $('.slide-delete').bind('click.' + SlideDeckSlides.namespace, function(event){
            event.preventDefault();
            SlideDeckSlides.deleteSlide(this);
        });
        
        $('.slide .handlediv').bind('click.' + SlideDeckSlides.namespace, function(event){
            event.preventDefault();
            $(this).parent().find('.inside').toggle();
        });

        $('.media-buttons a.thickbox, a.add_media').bind('click.' + SlideDeckSlides.namespace, function(){
            SlideDeckSlides.tb_click(this);
        });

        $('#btn_add-another-slide').bind('click.' + SlideDeckSlides.namespace, function(event){
            event.preventDefault();
            SlideDeckSlides.addSlide(this);
        });
        
        if($('.slide-order').length){
            $('.slide-order').sortable({
                update: function(event,ui){
                    $('ul.slide-order').find('li:not(.ui-sortable-helper)').each(function(inc){
                        var target = $(this).find('a.hndle').attr('href').split("#")[1];
                        $('#' + target).find('input.slide-order').val(inc + 1);
                    });
                }
            });
        }
        
        $('a.navigation-type').bind('click', function(event){
            event.preventDefault();
            if(!$(this).hasClass('disabled')){
                var slug = this.href.split("#")[1];
                $('a.navigation-type').removeClass('active');
                $(this).addClass('active');
                $('#slidedeck_navigation_type').val(slug);
            }
        });
        
        // Event listener for showing/hiding RSS feed entry field.
        $('#smart_slidedeck_type_of_content :radio').change(function(event){
            // Show the filter posts by category option and children.
            $('#filter_posts_by_category').slideDown();
        });
        
        // Save editor tab states when saving the deck.
        $('#slidedeck-options #publishing-action input').bind('click', function(event){
            var editors = jQuery('.editor-area:not(:hidden), .vertical-editor-wrapper:not(:hidden)');
            var editorStates = [];
            for ( var i=0 ; i < editors.length ; i++ ) {
                // Make accommodations for WordPress 3.3 wp_editor() changes
                if(SLIDEDECK_USE_OLD_TINYMCE_EDITOR === true){
                    var active = jQuery(editors[i]).find('.editor-nav .mode.active').attr('href').split('#')[1];
                } else {
                    var active = jQuery(editors[i]).find('.wp-editor-wrap').hasClass('tmce-active') ? "visual" : "html";
                }
                editorStates.push(active);
            };
            jQuery.cookie( 'slidedeck_editor_state_' + jQuery('#slidedeck_id').val(), editorStates, { expires: 365 } );
        });
        
        $('#slidedeck_filter_by_category').bind('click.' + SlideDeckSlides.namespace, function(event){
            if(this.checked == true){
                $('#category_filter_categories').slideDown();
            } else {
                $('#category_filter_categories').slideUp();
            }
        });
        
        $('#slidedeck_total_slides').bind('change.' + SlideDeckSlides.namespace, function(){
            if(this.value > 5){
                $('#navigation_simple-dots').click();
                $('#navigation_dates, #navigation_post-titles').addClass('disabled');
            } else {
                $('#navigation_dates, #navigation_post-titles').removeClass('disabled');
            }
        });
        $('#slidedeck_total_slides').trigger('change');
        
        $('a.skin-thumbnail').bind('click', function(event){
            event.preventDefault();
            var slug = this.href.split("#")[1];
            $('a.skin-thumbnail').removeClass('active');
            $(this).addClass('active');
            $('#slidedeck_skin').val(slug);
            setNavigation(slug);
        });
        $('a.skin-thumbnail.active').each(function(){
            var slug = this.href.split("#")[1];
            setNavigation(slug);
        });
        
        function setNavigation(slug){
            switch(slug){
                case "image_caption_bottom":
                case "image_caption_top":
                case "image_no_caption":
                case "vertical-dark":
                case "vertical-light":
                case "vertical-stacked-arrow":
                case "vertical-stacked":
                case "simple-slider":
                case "thumbnail":
                  $('a.navigation-type').addClass('disabled');
                break;
                
                default:
                    if($('#slidedeck_total_slides').val() > 5){
                        $('#navigation_simple-dots').click();
                        $('#navigation_simple-dots').removeClass('disabled');
                        $('#navigation_dates, #navigation_post-titles').addClass('disabled');
                    } else {
                        $('a.navigation-type').removeClass('disabled');
                    }
                break;
            }
        };      
        
        if($('#form_action').val() == "create"){
            $('#titlewrap #title').css({
                color: '#999',
                fontStyle: 'italic'
            }).focus(function(event){
                this.style.color = "";
                this.style.fontStyle = "";
                if(this.value == this.defaultValue){
                    this.value = "";
                }
            });
        }

        $('a.slidedeck-action.delete, a.submitdelete.deletion').bind('click.' + SlideDeckSlides.namespace, function(event){
            event.preventDefault();
            
            if(confirm("Are you sure you want to delete this SlideDeck?\nThis CANNOT be undone.")){
                var callback;
                if($(this).hasClass('submitdelete')){
                    var href = this.href.split("&")[0];
                    callback = function(){
                        document.location.href = href;
                    };
                } else {
                    var row = $(this).parents('tr');
                    callback = function(){
                        row.fadeOut(500,function(){
                            row.remove();
                        });
                    };
                }
                $.get(this.href,function(){
                    callback();
                });
            }
        });
        
        $('#template_snippet_w, #template_snippet_h').bind('keyup.' + SlideDeckSlides.namespace, function(event){
            var element = this;
            if (this.timer) {
                clearTimeout(element.timer);
            }
            this.timer = setTimeout(function(){
                var w = $('#template_snippet_w').val(),
                    h = $('#template_snippet_h').val(),
                    slidedeck_id = $('#slidedeck_id').val();
                
                var snippet = "<" + "?php slidedeck( " + slidedeck_id + ", array( 'width' => '" + w + "', 'height' => '" + h + "' ) ); ?" + ">";
                
                $('#slidedeck-template-snippet').val(snippet);
            },100);
            return true;
        });
        
        $('#slidedeck-template-snippet').focus(function(){
            this.select();
        });
        
        updateTBSize();
        
        overviewWrap();
        
        var overviewFeed = $('.overview-options .rss-feed');
        if(overviewFeed.length){
            $.ajax({
                url: ajaxurl,
                data: "action=slidedeck_blog_feed",
                type: 'GET',
                complete: function(data){
                    var response = data.responseText;
                    var feedBlock = $('#slidedeck-blog-rss-feed');
                    
                    if(response != "false"){
                        feedBlock.html(data.responseText);
                    } else {
                        feedBlock.text("Unable to connect to feed!");
                        setTimeout(function(){
                            overviewFeed.fadeOut();
                        }, 1000);
                    }
                }
            });
        }

        
        // Add editor attribute to all upload/insert buttons for WYSIWYG editors
        if(SLIDEDECK_USE_OLD_TINYMCE_EDITOR === false){
            $('.slide textarea').each(function(){
                SlideDeckSlides.updateUploadInsertLinks(this);
            });
        }
    });
    
    
    $(window).load(function(){
        // Load editor states when the dom is ready.
        var editors = jQuery('.editor-area:not(:hidden), .vertical-editor-wrapper:not(:hidden)');
        var editorModeCookie = jQuery.cookie( 'slidedeck_editor_state_' + jQuery('#slidedeck_id').val() );
        if( editorModeCookie ){
            editorModeCookie = editorModeCookie.split(',');
        }
        if( editorModeCookie ){
            for ( var i=0 ; i < editors.length ; i++ ) {
                if(SLIDEDECK_USE_OLD_TINYMCE_EDITOR === true){
                    jQuery(editors[i]).find('.editor-nav a.editor-' + editorModeCookie[i] ).trigger('click');
                } else {
                    jQuery(editors[i]).find('.wp-editor-tools a.wp-switch-editor.switch-' + ( editorModeCookie[i] == "visual" ? "tmce" : "html" ) ).trigger('click');
                }
            };
        }
        $('.ajax-masker').hide();
    });
    
    // thickbox settings
    $(window).resize(function() {
        updateTBSize();
        overviewWrap();
    });
})(jQuery);

/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
