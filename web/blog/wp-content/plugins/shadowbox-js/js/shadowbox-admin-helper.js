jQuery(document).ready(function (){
	var sbadvshow = shadowboxJsHelperL10n.advConfShow;
	var sbadvhide = shadowboxJsHelperL10n.advConfHide;
	jQuery('#sbadvancedbtn').show();
	jQuery('#sbadvancedtitle').hide();
	jQuery('#sbadvanced').hide();
	jQuery('#sbadvancedbtn').click(function(){
		jQuery('#sbadvanced').toggle();
		jQuery(this).attr('value',jQuery(this).attr('value') == sbadvshow ? sbadvhide : sbadvshow);
	});
	jQuery('#enableFlv').change(function(){
		if (jQuery(this).val() == 'true') {
			var response = confirm(shadowboxJsHelperL10n.messageConfirm);
			if ( ! response ) {
				jQuery(this).val('false');
			} else {
				jQuery('#flvplayer').show();
				jQuery('#autoflv').removeAttr('disabled');
			}
		}
	});
});
