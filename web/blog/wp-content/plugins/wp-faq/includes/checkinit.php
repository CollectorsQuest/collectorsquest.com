<?php

/* WordPress FAQ plugin v1.4.6 */

class wpFaqCheckinit {

	function wpFaqCheckinit() {
		return true;	
	}
	
	function ci_initialize() {
		if ($this -> ci_serial_valid()) {
			$this -> ci_initialization();
		} else {
			$this -> add_action('admin_print_styles', 'wp_print_styles');
			$this -> add_action('admin_print_scripts', 'wp_print_scripts');
						
			$this -> add_action('admin_notices');
			$this -> add_action('init', 'init', 10, 1);
		}
		
		return false;
	}
	
	function ci_initialization() {		
		$this -> updating_plugin();		
		$this -> initialize_classes();
	
		$this -> add_action('init', 'init', 10, 1);
		$this -> add_action('init', 'init_textdomain', 1, 1);
		//inject some code into the HEAD section of the front-end.
		$this -> add_action('wp_head');
		//executes when the administration menu is created in the dashboard.
		$this -> add_action('admin_menu');
		//inject some code into the HEAD section of the administration panel.
		$this -> add_action('admin_head');
		//TinyMCE function
		$this -> add_action('admin_init', 'tinymce');
		$this -> add_action('admin_notices');
		$this -> add_action('init', 'init_getpost', 10, 1);
		$this -> add_action('widgets_init', 'widget_register');
		$this -> add_action('after_plugin_row_' . plugin_basename(__FILE__), 'after_plugin_row', 10, 2);
		$this -> add_action('wp_dashboard_setup');
		
		$this -> add_action('wp_print_styles');
		$this -> add_action('admin_print_styles', 'wp_print_styles');
		$this -> add_action('wp_print_scripts');
		$this -> add_action('admin_print_scripts', 'wp_print_scripts');
		
		//filter the content.
		$this -> add_filter('plugin_action_links', 'plugin_action_links', 10, 4);
		
		//WordPress shortcodes
		add_shortcode($this -> pre . 's', array($this, 'sc_faqs'));
		add_shortcode($this -> pre . 'groups', array($this, 'sc_groups'));
		add_shortcode($this -> pre . 'group', array($this, 'sc_group'));
		add_shortcode($this -> pre . 'question', array($this, 'sc_question'));
		add_shortcode($this -> pre . 'questions', array($this, 'sc_questions'));
		add_shortcode($this -> pre . 'search', array($this, 'sc_search'));
		add_shortcode($this -> pre . 'ask', array($this, 'sc_ask'));
		
		if ($this -> get_option('showquestionexcerpts') == "Y") {
			add_filter('excerpt_more', array($this, $this -> pre  . '_excerpt_more'), 15);
		}
	}
	
	function ci_get_serial() {
		if ($serial = $this -> get_option('serialkey')) {
			return $serial;
		}
		
		return false;
	}
	
	function ci_serial_valid() {
		$host = $_SERVER['HTTP_HOST'];
		
		if (preg_match("/^(www\.)(.*)/si", $host, $matches)) {
			$wwwhost = $host;
			$nonwwwhost = preg_replace("/^(www\.)?/si", "", $wwwhost);
		} else {
			$nonwwwhost = $host;
			$wwwhost = "www." . $host;	
		}
		
		if ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "localhost:" . $_SERVER['SERVER_PORT']) {
			return true;	
		} else {
			if ($serial = $this -> ci_get_serial()) {			
				if ($serial == strtoupper(md5($_SERVER['HTTP_HOST'] . "wpfaq" . "mymasesoetkoekiesisfokkenlekker"))) {
					return true;
				} elseif (strtoupper(md5($wwwhost . "wpfaq" . "mymasesoetkoekiesisfokkenlekker")) == $serial || 
							strtoupper(md5($nonwwwhost . "wpfaq" . "mymasesoetkoekiesisfokkenlekker")) == $serial) {
					return true;
				}
			}
		}
		
		return false;
	}
}

?>