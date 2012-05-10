<script type="text/javascript">
var wpfaqAjax = "<?php echo $this -> url(); ?>/wp-faq-ajax.php";
$<?php echo $this -> pre; ?> = jQuery.noConflict();

function wpfaq_change_approved(approved) {	
	if (approved != "") {
		var expires = "<?php echo $wpfaqHtml -> gen_date($this -> get_option('cookieformat'), strtotime("+30 days")); ?> UTC";
		
		if (approved == "all") {
			var expires = "<?php echo $wpfaqHtml -> gen_date($this -> get_option('cookieformat'), strtotime("-30 days")); ?> UTC";
		}
		
		document.cookie = "<?php echo $this -> pre; ?>approved=" + approved + "; expires=<?php echo $expires; ?>; path=/";
		window.location = "<?php echo $wpfaqHtml -> retainquery('changeapproved=1'); ?>";
	}
}
</script>