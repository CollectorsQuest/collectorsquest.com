jQuery(document).ready(function(){
	jQuery("input[id*=checkboxall]").click(function() {
		var checked_status = this.checked;
		jQuery("input[id*=checklist]").each(function() {
			this.checked = checked_status;
		});
	});
	
	jQuery("input[id*=checkinvert]").click(function() {
		this.checked = false;
	
		jQuery("input[id*=checklist]").each(function() {
			var status = this.checked;
			
			if (status == true) {
				this.checked = false;
			} else {
				this.checked = true;
			}
		});
	});
});

function wpfaq_submitserial(form) {
	jQuery('#wpfaq_submitserial_loading').show();
	var formdata = jQuery(form).serialize();

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl + '?action=wpfaqserialkey', formdata, function(response) {
		jQuery('#wpfaqsubmitserial').html(response);
		jQuery.colorbox.resize();
	});
}

function wpfaq_search(number) {
	jQuery("#wpfaqsearchloading" + number).show();

	jQuery("#wpfaqquestions" + number).load(wpfaqAjax + "?cmd=search&uninumber=" + number, jQuery("#wpfaqsearchform" + number).serialize(), function() {
		jQuery("#wpfaqsearchloading" + number).hide();
		wpfaq_scroll(jQuery("#wpfaqsearch" + number));
	});
	
	return false;
}

function wpfaq_ask(number) {
	jQuery('#wpfaqaskloading' + number).show();
	
	jQuery('#wpfaqask' + number).load(wpfaqAjax + "?cmd=ask&uninumber=" + number, jQuery("#wpfaqaskform" + number).serialize(), function() { 
		jQuery("#wpfaqaskloading" + number).hide();
		wpfaq_scroll(jQuery("#wpfaqask" + number));
	});
	
	return false;
}

function wpfaq_scroll(selector) {
	var targetOffset = jQuery(selector).offset().top;
    jQuery('html,body').animate({scrollTop: targetOffset}, 500);
}