<?php

class wpFaqPlugin extends wpFaqCheckinit {

	var $pre = 'wpfaq';
	var $prefix = "wpfaq";
	var $base = 'wp-faq';
	var $version = '1.4.6';
	
	var $debugging = false;			// set to true to turn on debugging
	var $debug_level = 2;			// set to 1 to show DB errors and to 2 for both DB and PHP errors
	
	var $menus = array();
	var $sections = array(
		'welcome'			=>	"faqs",
		'groups'			=>	"faqs-groups",
		'groups_save'		=>	"faqs-groups-save",
		'questions'			=>	"faqs-questions",
		'questions_save'	=>	"faqs-questions-save",
		'fields'			=>	"faqs-fields",
		'settings'			=>	"faqs-settings",
		'support'			=>	"faqs-support",
	);

	var $classes = array(
		'wpfaqHtml'					=>	array('type' => 'helper'),
		'wpfaqDb'					=>	array('type' => 'helper'),
		'wpfaqForm'					=>	array('type' => 'helper'),
		'wpfaqMetabox'				=>	array('type' => 'helper'),
		'Checkinit'					=>	array('type' => 'stucture', 'classname' => "wpFaqCheckinit"),
		'wpfaqGroup'				=>	array('type' => 'model'),
		'wpfaqQuestion'				=>	array('type' => 'model'),
		'wpfaqQuestionsQuestion'	=>	array('type' => 'model'),
		'wpfaqQuestionsPost'		=>	array('type' => 'model'),
		'wpfaqQuestionsGroup'		=>	array('type' => 'model'),
		'wpfaqGroupsPost'			=>	array('type' => 'model'),
		'wpfaqField'				=>	array('type' => 'model'),
	);
	
	/**
 	 * An array of SQL queries.
 	 * Generated mainly from the check_table() function in this file.
	 *
	 */
	var $table_query = array();
	
	/**
 	 * An aray of universal errors.
 	 * Can be accessed from within any model or controller.
	 *
	 */
	var $errors = array();

	var $tables_tv = array(
		'questions'		=>	array(
			'id'			=>	array("INT(11)", "NOT NULL AUTO_INCREMENT"),
			'question'		=>	array("TEXT", "NOT NULL"),
			'answer'		=>	array("LONGTEXT", "NOT NULL"),
			'approved'		=>	array("ENUM('Y','N')", "NOT NULL DEFAULT 'N'"),
			'group_id'		=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'order'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'pp'			=>	array("ENUM('none','post','page')", "NOT NULL DEFAULT 'none'"),
			'pp_id'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'pp_title'		=>	array("VARCHAR(255)", "NOT NULL DEFAULT ''"),
			'pp_parent'		=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'pp_categories'	=>	array("TEXT", "NOT NULL"),
			'pp_comments'	=>	array("ENUM('open','closed')", "NOT NULL DEFAULT 'closed'"),
			'email'			=>	array("VARCHAR(150)", "NOT NULL DEFAULT ''"),
			'created'		=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'modified'		=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'key'			=>	"PRIMARY KEY (`id`)",
			
		),
		'groups'		=>	array(
			'id'				=>	array("INT(11)", "NOT NULL AUTO_INCREMENT"),
			'order'				=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'name'				=>	array("VARCHAR(100)", "NOT NULL DEFAULT ''"),
			'pp'				=>	array("ENUM('none','post','page')", "NOT NULL DEFAULT 'none'"),
			'active'			=>	array("ENUM('Y','N')", "NOT NULL DEFAULT 'Y'"),
			'pp_id'				=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'pp_title'			=>	array("VARCHAR(255)", "NOT NULL DEFAULT ''"),
			'pp_categories'		=>	array("TEXT", "NOT NULL"),
			'pp_parent'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'adminnotify'		=>	array("ENUM('Y','N')", "NOT NULL DEFAULT 'Y'"),
			'email'				=>	array("VARCHAR(150)", "NOT NULL DEFAULT ''"),
			'searchbox'			=>	array("ENUM('Y','N')", "NOT NULL DEFAULT 'Y'"),			
			'groupsmenu'		=>	array("ENUM('Y','N')", "NOT NULL DEFAULT 'N'"),
			'keywords'			=>	array("TEXT", "NOT NULL"),
			'askbox'			=>	array("ENUM('Y','N')", "NOT NULL DEFAULT 'N'"),
			'created'			=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'modified'			=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'key'				=>	"PRIMARY KEY (`id`)",
		),
		'fields'		=>	array(
			'id'			=>	array("INT(11)", "NOT NULL AUTO_INCREMENT"),
			'title'			=>	array("VARCHAR(100)", "NOT NULL DEFAULT ''"),
			'caption'		=>	array("TEXT", "NOT NULL"),
			'slug'			=>	array("VARCHAR(100)", "NOT NULL DEFAULT ''"),
			'fieldtype'		=>	array("ENUM('text','checkbox','radio','select','textarea')", "NOT NULL DEFAULT 'text'"),
			'fieldoptions'	=>	array("TEXT", "NOT NULL"),
			'required'		=>	array("ENUM('Y','N')", "NOT NULL DEFAULT 'Y'"),
			'errormessage'	=>	array("TEXT", "NOT NULL"),
			'order'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'created'		=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'modified'		=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'key'			=>	"PRIMARY KEY (`id`)",
		),
		'groupsposts'	=>	array(
			'group_id'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'post_id'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'created'			=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'modified'			=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'key'				=>	"KEY `group_id` (`group_id`,`post_id`)",
		),
		'questionsposts'	=>	array(
			'question_id'		=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'post_id'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'created'			=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'modified'			=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'key'				=>	"KEY `question_id` (`question_id`,`post_id`)",							  
		),
		'questionsgroups'		=>	array(
			'question_id'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'group_id'				=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'order'					=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'created'				=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'modified'				=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'key'					=>	"KEY `question_id` (`question_id`,`group_id`)",
		),
		'questionsquestions'	=>	array(
			'question_id'			=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'rel_id'				=>	array("INT(11)", "NOT NULL DEFAULT '0'"),
			'created'				=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'modified'				=>	array("DATETIME", "NOT NULL DEFAULT '0000-00-00 00:00:00'"),
			'key'					=>	"KEY `question_id` (`question_id`,`rel_id`)",
		)					   
	);
	
	var $tables = array(
		'questions'		=>	array(
			'id'			=>	"INT(11) NOT NULL AUTO_INCREMENT",
			'question'		=>	"TEXT NOT NULL",
			'answer'		=>	"TEXT NOT NULL",
			'approved'		=>	"ENUM('Y','N') NOT NULL DEFAULT 'N'",
			'group_id'		=>	"INT(11) NOT NULL DEFAULT '0'",
			'order'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'pp'			=>	"ENUM('none','post','page') NOT NULL DEFAULT 'none'",
			'pp_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'pp_title'		=>	"VARCHAR(255) NOT NULL DEFAULT ''",
			'pp_parent'		=>	"INT(11) NOT NULL DEFAULT '0'",
			'pp_categories'	=>	"TEXT NOT NULL",
			'pp_comments'	=>	"ENUM('open','closed') NOT NULL DEFAULT 'closed'",
			'email'			=>	"VARCHAR(150) NOT NULL DEFAULT ''",
			'created'		=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'modified'		=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'key'			=>	"PRIMARY KEY (`id`)",
		),
		'groups'		=>	array(
			'id'				=>	"INT(11) NOT NULL AUTO_INCREMENT",
			'order'				=>	"INT(11) NOT NULL DEFAULT '0'",
			'name'				=>	"VARCHAR(100) NOT NULL DEFAULT ''",
			'pp'				=>	"ENUM('none','post','page') NOT NULL DEFAULT 'none'",
			'active'			=>	"ENUM('Y','N') NOT NULL DEFAULT 'Y'",
			'pp_id'				=>	"INT(11) NOT NULL DEFAULT '0'",
			'pp_title'			=>	"VARCHAR(255) NOT NULL DEFAULT ''",
			'pp_categories'		=>	"TEXT NOT NULL",
			'pp_parent'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'adminnotify'		=>	"ENUM('Y','N') NOT NULL DEFAULT 'Y'",
			'email'				=>	"VARCHAR(150) NOT NULL DEFAULT ''",
			'searchbox'			=>	"ENUM('Y','N') NOT NULL DEFAULT 'Y'",
			'groupsmenu'		=>	"ENUM('Y','N') NOT NULL DEFAULT 'N'",
			'keywords'			=>	"TEXT NOT NULL",
			'askbox'			=>	"ENUM('Y','N') NOT NULL DEFAULT 'N'",
			'created'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'modified'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'key'				=>	"PRIMARY KEY (`id`)",
		),
		'fields'		=>	array(
			'id'			=>	"INT(11) NOT NULL AUTO_INCREMENT",
			'title'			=>	"VARCHAR(100) NOT NULL DEFAULT ''",
			'caption'		=>	"TEXT NOT NULL",
			'slug'			=>	"VARCHAR(100) NOT NULL DEFAULT ''",
			'fieldtype'		=>	"ENUM('text','checkbox','radio','select','textarea') NOT NULL DEFAULT 'text'",
			'fieldoptions'	=>	"TEXT NOT NULL",
			'required'		=>	"ENUM('Y','N') NOT NULL DEFAULT 'Y'",
			'errormessage'	=>	"TEXT NOT NULL",
			'order'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'created'		=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'modified'		=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'key'			=>	"PRIMARY KEY (`id`)",
		),
		'groupsposts'	=>	array(
			'group_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'post_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'created'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'modified'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'key'				=>	"KEY `group_id` (`group_id`,`post_id`)",
		),
		'questionsposts'	=>	array(
			'question_id'		=>	"INT(11) NOT NULL DEFAULT '0'",
			'post_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'created'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'modified'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'key'				=>	"KEY `question_id` (`question_id`,`post_id`)",							  
		),
		'questionsgroups'		=>	array(
			'question_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'group_id'				=>	"INT(11) NOT NULL DEFAULT '0'",
			'order'					=>	"INT(11) NOT NULL DEFAULT '0'",
			'created'				=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'modified'				=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'key'					=>	"KEY `question_id` (`question_id`,`group_id`)",
		),
		'questionsquestions'	=>	array(
			'question_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
			'rel_id'				=>	"INT(11) NOT NULL DEFAULT '0'",
			'created'				=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'modified'				=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
			'key'					=>	"KEY `question_id` (`question_id`,`rel_id`)",
		)
	);

	/**
 	 * Class init method.
 	 * Does some basic configuration
	 *
	 */
	function wpFaqPlugin() {				
		return true;
	}
	
	/**
 	 * Register the plugin name and base.
 	 * @param STRING $name Sets the name of the plugin
 	 * @param STRING $base The directory basename of the plugin
	 *
	 */
	function register_plugin($name = null, $base = null) {
		$this -> plugin_name = $name;
		$this -> plugin_base = rtrim(dirname($base), DS);
		
		//convert $sections array to an object
		$this -> sections = (object) $this -> sections;
		
		$this -> initialize_classes();
		
		global $wpdb;
		$wpdb -> query("SET sql_mode = ''");
		
		if ($this -> debugging == true) {
			$wpdb -> show_errors();
			
			if ($this -> debug_level == 2) {
				error_reporting(E_ALL ^ E_NOTICE);
				@ini_set('display_errors', 1);
			}
		} else {
			$wpdb -> hide_errors();
			error_reporting(0);
			@ini_set('display_errors', 0);	
		}
		
		$this -> ci_initialize();
		add_action('wp_ajax_wpfaqserialkey', array($this, 'ajax_serialkey'));
		
		/* Check qTranslate Support */
		if ($this -> is_plugin_active('qtranslate')) {
			if ($this -> is_plugin_screen()) {
				remove_filter('the_editor', 'qtrans_modifyRichEditor');	
			}
		}
		
		$this -> plugin_name = $name;
		$this -> plugin_base = rtrim(dirname($base), DS);
		
		return true;
	}
	
	function ajax_serialkey() {
		$errors = array();
		$success = false;
	
		if (!empty($_POST)) {
			if (empty($_POST['serialkey'])) { $errors[] = __('Please fill in a serial key', $this -> plugin_name); }
			else { $this -> update_option('serialkey', $_POST['serialkey']); }
			
			if (!$this -> ci_serial_valid()) { $errors[] = __('Serial key is invalid, please try again', $this -> plugin_name); }
			else { $success = true; }
		}
		
		if (empty($_POST)) { ?><div id="<?php echo $this -> pre; ?>submitserial"><?php }
		$this -> render('submitserial', array('errors' => $errors, 'success' => $success), 'admin', true);
		if (empty($_POST)) { ?></div><?php }
	
		exit(); die();	
	}
	
	function is_plugin_active($name = null) {
		if (!empty($name)) {
			require_once ABSPATH . 'wp-admin' . DS . 'admin-functions.php';
			
			switch ($name) {
				case 'qtranslate'			:
					$path = 'qtranslate' . DS . 'qtranslate.php';
					break;
				case 'captcha'				:
					$path = 'really-simple-captcha' . DS . 'really-simple-captcha.php';
					break;
			}
			
			if (!empty($path)) {
				if (is_plugin_active(plugin_basename($path))) {
					return true;
				}	
			}
		}
		
		return false;
	}
	
	function is_plugin_screen() {
		if (!empty($_GET['page'])) {
			if (in_array($_GET['page'], (array) $this -> sections)) {
				return true;	
			}
		}
		
		return false;
	}
	
	function wpfaq_excerpt_more($more = false) {
    	global $post;
		
		if ($readmore = $this -> get_option('questionexcerptreadmore')) {
			if (!empty($readmore)) {
				return ' <a href="'. get_permalink($post -> ID) . '">' . stripslashes($readmore) . '</a>';
			}
		}
		
		return $more;
	}
	
	function initialize_options() {
		//user level that may use the plugin
		$this -> add_option('accesslevel', 10);
		$this -> add_option('askregistered', "N");
		$this -> add_option('adminemail', get_option('admin_email'));
		$this -> add_option('adminnotify', "Y");
		$this -> add_option('adminlinks', "Y");
		$this -> add_option('searchcontext', "full");
		$this -> add_option('captcha', "N");
		$this -> add_option('cookieformat', "D, j M Y H:i:s");
		$this -> add_option('acc', "Y");
		$this -> add_option('clickoc', "N");
		$this -> add_option('accbullet', "black");
		$this -> add_option('accactive', "1");
		$this -> add_option('accevent', "click");
		$this -> add_option('acccollapsible', "Y");
		
		/* Ask */
		$this -> add_option('requireemail', "Y");
		$this -> add_option('notifywhenanswered', "Y");
		
		/* Questions */
		$this -> add_option('filter_the_content', "N");
		$this -> add_option('showrelatedquestions', "Y");
		$this -> add_option('showquestionexcerpts', "N");
		$this -> add_option('questionexcerptreadmore', __('Continue Reading...', $this -> plugin_name));
		
		/* Custom CSS */
		$this -> add_option('customcss', "N");		
		$this -> add_option('theme_folder', "default");
		$this -> add_option('theme_stylesheet', "Y");
	
		return true;
	}
	
	/* WordPress Dashboard Widget Function */
	function dashboard_widget() {
		global $wpdb, $wpfaqGroup, $wpfaqQuestion;
		
		/* Groups Count */
		$groups_count_query = "SELECT COUNT(id) FROM " . $wpdb -> prefix . $wpfaqGroup -> table . "";
		$groups_count = $wpdb -> get_var($groups_count_query);
		
		/* All Questions Count */
		$allquestions_count_query = "SELECT COUNT(id) FROM " . $wpdb -> prefix . $wpfaqQuestion -> table . "";
		$allquestions_count = $wpdb -> get_var($allquestions_count_query);
		
		/* Approved Questions Count */
		$approvedquestions_count_query = "SELECT COUNT(id) FROM " . $wpdb -> prefix . $wpfaqQuestion -> table . " WHERE approved = 'Y'";
		$approvedquestions_count = $wpdb -> get_var($approvedquestions_count_query);
		
		/* Unapproved Questions Count */
		$unapprovedquestions_count_query = "SELECT COUNT(id) FROM " . $wpdb -> prefix . $wpfaqQuestion -> table . " WHERE approved = 'N'";
		$unapprovedquestions_count = $wpdb -> get_var($unapprovedquestions_count_query);
		
		/** All Counts **/
		$counts = array(
			'groups_count'				=>	$groups_count,
			'allquestions_count'		=>	$allquestions_count,
			'approvedquestions_count'	=>	$approvedquestions_count,
			'unapprovedquestions_count'	=>	$unapprovedquestions_count,
		);
		
		/* Latest Questions */
		$latest_questions_query = "SELECT id, question FROM " . $wpdb -> prefix . $wpfaqQuestion -> table . " GROUP BY id ORDER BY modified LIMIT 5";
		$latest_questions = $wpdb -> get_results($latest_questions_query);
		
		/* Render the widget content */
		$this -> render('dashboard-widget', array('counts' => $counts, 'latest_questions' => stripslashes_deep($latest_questions)), 'admin', true);
	}
	
	function updating_plugin() {
		if (!is_admin()) {
			return;	
		}
		
		if (!$this -> get_option('version')) {
			$this -> add_option('version', $this -> version);
		}
		
		if ($cur_version = $this -> get_option('version')) {		
			if ($this -> version > $cur_version) {			
				$new_version = $cur_version;
			
				if (version_compare("1.3.1", $cur_version) === 1) {
					global $wpdb, $wpfaqQuestion;				
					$query = "ALTER TABLE `" . $wpdb -> prefix . "" . $this -> pre . "questions` MODIFY `question` TEXT NOT NULL";
					$wpdb -> query($query);
					$new_version = "1.3.1";
				} elseif (version_compare("1.3.9", $cur_version) === 1) {
					global $wpdb;
					
					if (!empty($this -> tables)) {
						foreach ($this -> tables as $table_name => $table_attributes) {
							$query = "ALTER TABLE `" . $wpdb -> prefix . "" . $this -> pre . "" . $table_name . "` COLLATE utf8_general_ci;";
							$wpdb -> query($query);
						}
					}
					
					$new_version = '1.4';
				} elseif (version_compare("1.4.2", $cur_version) === 1) {
					$this -> initialize_options();
					
					if (!empty($this -> tables)) {
						global $wpdb;
						
						foreach ($this -> tables as $table_name => $table_fields) {
							foreach ($table_fields as $table_field_name => $table_field_attributes) {
								if (!empty($table_field_name) && $table_field_name != "key") {
									$query = "ALTER TABLE `" . $wpdb -> prefix . "" . $this -> pre . "" . $table_name . "` CHANGE `" . $table_field_name . "` `" . $table_field_name . "` " . $this -> tables_tv[$table_name][$table_field_name][0] . " CHARACTER SET utf8 COLLATE utf8_general_ci " . $this -> tables_tv[$table_name][$table_field_name][1] . ";";
									$wpdb -> query($query);
								}
							}
						}
					}
					
					$new_version = '1.4.2';
				} elseif (version_compare("1.4.3", $cur_version) === 1) {
					$this -> initialize_options();
					
					/* answers from TEXT to LONGTEXT */
					global $wpdb, $wpfaqQuestion;
					$query = "ALTER TABLE `" . $wpdb -> prefix . "" . $wpfaqQuestion -> table . "` CHANGE `answer` `answer` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
					$wpdb -> query($query);
					
					$new_version = "1.4.3";
				}
				
				if (version_compare($cur_version, "1.4.6") < 0) {										
					$this -> initialize_options();
					$this -> predefined_pages();
					
					$new_version = "1.4.6";
				}
				
				$this -> update_option('version', $new_version);
			}
		} else {
			$this -> add_option('version', $this -> version);
		}	
	}
	
	function isrtl() {		
		if ($text_direction = get_bloginfo('text_direction')) {
			if ($text_direction = "rtl") {
				return true;	
			}
		}
		
		return false;
	}
	
	function wp_print_styles() {		
		$this -> enqueue_styles();	
	}
	
	function enqueue_styles() {				
		//are we in the WordPress dashboard?
		if (is_admin()) {
			$src = WP_PLUGIN_URL . '/' . $this -> plugin_name . '/css/admin/' . $this -> plugin_name . '.css';			
			wp_enqueue_style($this -> pre . 'admin', $src, null, $this -> version, "all");
			
			/* Colorbox CSS */
			wp_enqueue_style('colorbox', WP_PLUGIN_URL . '/' . $this -> plugin_name . '/css/admin/colorbox.css', false, $this -> version, "all");
		} else {
			//$src = WP_PLUGIN_URL . '/' . $this -> plugin_name . '/css/default/' . $this -> plugin_name . '-css.php?1=1';
			$theme_folder = $this -> get_option('theme_folder');
			
			if ($this -> get_option('theme_stylesheet') == "Y") {
				$style_url = WP_PLUGIN_URL . '/' . $this -> plugin_name . '/views/' . $theme_folder . '/style.css';
				wp_enqueue_style($this -> pre . 'style', $style_url, null, $this -> version, "screen");
			}
			
			if ($this -> get_option('customcss') == "Y") {
				$custom_url = WP_PLUGIN_URL . '/' . $this -> plugin_name . '/css/default/' . $this -> plugin_name . '-css.php?1=1';
				wp_enqueue_style($this -> pre . 'custom', $custom_url, null, $this -> version, "screen");
			}
		}
		
		return;
	}
	
	function wp_print_scripts() {
		$this -> enqueue_scripts();	
	}
	
	function enqueue_scripts() {				
		//enqueue the jQuery JavaScript library
		wp_enqueue_script('jquery');
		//enqueue the custom JavaScript file of the plugin
		wp_enqueue_script($this -> plugin_name, WP_PLUGIN_URL . '/' . $this -> plugin_name . '/js/' . $this -> plugin_name . '.js', array('jquery'));
		
		//are we in top level of the WordPress Dashboard?
		//if so... lets see if we need some additional scripts enqueued
		if (is_admin()) {
			add_thickbox();	//enqueue Thickbox
			
			/* Colorbox JS */
			wp_enqueue_script('colorbox', WP_PLUGIN_URL . '/' . $this -> plugin_name . '/js/colorbox.js', array('jquery'), false, true);
			
			if (!empty($_GET['page'])) {
				if (in_array($_GET['page'], (array) $this -> sections)) {
					wp_enqueue_script('jquery-watermark', WP_PLUGIN_URL . '/' . $this -> plugin_name . '/js/jquery.watermark.js', array('jquery'), false, true);
				}
			
				//jQuery Sortables
				if ($_GET['page'] == $this -> sections -> questions || $_GET['page'] == $this -> sections -> groups) {				
					if (!empty($_GET['method']) && ($_GET['method'] == "order" || $_GET['method'] == "related")) {
						wp_enqueue_script('jquery-ui-sortable');
					}
				}
				
				//Meta Boxes
				if ($_GET['page'] == $this -> sections -> questions_save ||
					$_GET['page'] == $this -> sections -> settings) {
						wp_enqueue_script('common');
						wp_enqueue_script('wp-lists');
						wp_enqueue_script('postbox');
						
						switch ($_GET['page']) {
							case $this -> sections -> questions_save 	:
								wp_enqueue_script($this -> pre . 'products_save', WP_PLUGIN_URL . '/' . $this -> plugin_name . '/js/editors/products_save.js', array('jquery'));
								break;
							case $this -> sections -> settings 			:
								wp_enqueue_script($this -> pre . 'settings', WP_PLUGIN_URL . '/' . $this -> plugin_name . '/js/editors/settings.js', array('jquery'));
								break;
						}
					}
				
				//TinyMCE editor
				if ($_GET['page'] == $this -> sections -> questions_save) {				
					wp_enqueue_script('editor');
					wp_enqueue_script('word-count');
					add_action('admin_head', 'wp_tiny_mce');
					wp_enqueue_script('post');
					wp_enqueue_script('editor-functions');
					wp_enqueue_script('media-upload');
					wp_enqueue_script('jquery-ui-core');
					wp_enqueue_script('jquery-ui-tabs');
					wp_enqueue_script('tiny_mce');
				}
			}
		} else {
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-widget', WP_PLUGIN_URL . '/' . $this -> plugin_name . '/js/jquery/ui.widget.js', array('jquery', 'jquery-ui-core'), "1.8.16");
			wp_enqueue_script('jquery-ui-accordion', WP_PLUGIN_URL . '/' . $this -> plugin_name . '/js/jquery/ui.accordion.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget'), "1.8.16");
		}
		
		return;
	}
	
	function initialize_classes() {	
		if (!empty($this -> classes)) {
			foreach ($this -> classes as $name => $params) {
				global ${$name};
			
				switch ($params['type']) {
					case 'helper'			:
						$classname = $name . 'Helper';
						
						if (class_exists($classname)) {						
							${$name} = new $classname;
						}
						break;
					case 'model'			:						
						if (class_exists($name)) {
							${$name} = new $name;
						}
					default					:					
						if (!empty($params['classname']) && class_exists($params['classname'])) {
							$this -> {$name} = new $params['classname'];	
						}					
						break;	
				}
			}
		}
		
		return false;
	}
	
	function debug($var = array()) {	
		if ($this -> debugging == true) {
			echo '<pre>' . print_r($var, true) . '</pre>';
			return true;
		}
		
		return false;
	}
	
	function use_captcha() {
		if ($this -> get_option('captcha') == "Y") {
			require_once(ABSPATH . 'wp-admin' . DS . 'admin-functions.php');
			
			if (is_plugin_active(plugin_basename('really-simple-captcha' . DS . 'really-simple-captcha.php'))) {
				if (class_exists('ReallySimpleCaptcha')) {
					return true;	
				}
			}
		}
		
		return false;
	}
	
	function group_metabox() {
		$this -> render('metaboxes' . DS . 'question-group', false, 'admin', true);
		return true;
	}
	
	function submit_metabox() {
		$this -> render('metaboxes' . DS . 'question-submit', false, 'admin', true);
		return true;
	}
	
	function fields_metabox() {		
		$this -> render('metaboxes' . DS . 'question-fields', false, 'admin', true);
	}
	
	/**
 	 * Ads an option to the Wordpress database.
 	 * Makes use of the generic add_option() Wordpress function.
 	 * @param STRING $name The name of the option to add.
 	 * @param STRING $value The value of the option to add.
	 *
	 */
	function add_option($name = null, $value = null) {
		if (!empty($name) && !empty($value)) {
			add_option($this -> prefix . $name, $value);
		}
	}
	
	/**
 	 * Same as above, but makes use of the update_option() Wordpress function.
 	 * Updates an existent option, but also creates it if it doesn't exist.
	 *
	 */
	function update_option($name = null, $value = null) {
		//if (!empty($name) && (!empty($value) || $value == "0")) {
		if (!empty($name)) {
			update_option($this -> prefix . $name, $value);
		}
		//}
	}
	
	function delete_option($name = null) {
		if (!empty($name)) {
			delete_option($this -> prefix . $name);	
		}
	}
	
	function check_table($name = null) {
		//global WP variables
		global $wpdb;
	
		//ensure that a "name" was passed
		if (!empty($name)) {
			//add the WP prefix to the table name
			$oldname = $name;
			$name = $wpdb -> prefix . $this -> prefix . $name;
			
			//make sure that the table fields are available
			if (!empty($this -> tables[$oldname])) {			
				//check if the table exists. boolean value returns
				if (!$wpdb -> get_var("SHOW TABLES LIKE '" . $name . "'")) {							
					//let's start the query for a new table!
					$query = "CREATE TABLE `" . $name . "` (";
					$c = 1;
					
					//loop the table fields.
					foreach ($this -> tables[$oldname] as $field => $attributes) {
						//we might need to use a KEY declaration
						//in case not "key", continue with normal attributes set.
						if ($field != "key") {
							//append the field name and attributes
							$query .= "`" . $field . "` " . $attributes . "";
						} else {
							//this is a "key" field. declare it
							$query .= "" . $attributes . "";
						}
						
						//the last query doesn't get a comma at the end.
						//ensure that it is not the last query section.
						if ($c < count($this -> tables[$oldname])) {
							//append a comma "," to the query
							$query .= ",";
						}
						
						$c++;
					}
					
					//end the query!
					$query .= ") ENGINE=MyISAM AUTO_INCREMENT=1 CHARSET=UTF8 COLLATE=utf8_general_ci;";
					
					if (!empty($query)) {
						$this -> table_query[] = $query;
					}
				} else {
					//get the current fields of the subscribers table.
					$tablefields = mysql_list_fields(DB_NAME, $name);
					$columns = mysql_num_fields($tablefields);
					
					$field_array = array();
					for ($i = 0; $i < $columns; $i++) {
						$field_array[] = mysql_field_name($tablefields, $i);
					}
					
					//loop the fields of the table
					foreach ($this -> tables[$oldname] as $field => $attributes) {						
						if ($field != "key") {
							//make sure that the field doesn't exist
							if (!in_array($field, $field_array)) {
								//append to the "table_query" array.
								//simple ALTER TABLE and ADD the field
								$alterquery = "ALTER TABLE `" . $name . "` ADD `" . $field . "` " . $attributes . ";";
								$wpdb -> query($alterquery);
							}
						}
					}
				}
				
				//make sure that the query is not empty.
				if (!empty($this -> table_query)) {				
					//include the admin upgrade functions file.
					require_once(ABSPATH . 'wp-admin' . DS . 'upgrade-functions.php');
					//exeucte all available SQL queries
					dbDelta($this -> table_query, true);
				}
			}
		}
	}
	
	function get_fields($table = null) {	
		global $wpdb;
	
		//make sure the table nae is available
		if (!empty($table)) {
			$fullname = $wpdb -> prefix . $table;
		
			//get the current fields of this table.
			$tablefields = mysql_list_fields(DB_NAME, $fullname);
			$columns = mysql_num_fields($tablefields);
			
			$field_array = array();
			for ($i = 0; $i < $columns; $i++) {
				$fieldname = mysql_field_name($tablefields, $i);
				$field_array[] = $fieldname;
			}
			
			return $field_array;
		}
		
		return false;
	}
	
	function delete_field($table = null, $field = null) {
		global $wpdb;
		
		if (!empty($table)) {			
			if (!empty($field)) {				
				$query = "ALTER TABLE `" . $wpdb -> prefix . "" . $table . "` DROP `" . $field . "`";
				
				if ($wpdb -> query($query)) {
					return true;
				}
			}
		}

		return false;
	}
	
	function change_field($table = null, $field = null, $newfield = null, $attributes = "TEXT NOT NULL") {
		global $wpdb;
		
		if (!empty($table)) {			
			if (!empty($field)) {			
				if (!empty($newfield)) {				
					$field_array = $this -> get_fields($table);
					
					if (!empty($field_array) && in_array($field, $field_array)) {
						$query = "ALTER TABLE `" . $wpdb -> prefix . "" . $table . "` CHANGE `" . $field . "` `" . $newfield . "` " . $attributes . ";";
						if ($wpdb -> query($query)) {
							return true;
						}
					} else {
						if ($this -> add_field($table, $newfield, $attributes)) {
							return true;
						}
					}
				}
			}
		}
		
		return false;
	}
	
	function add_field($table = null, $field = null, $attributes = "TEXT NOT NULL") {
		global $wpdb;
	
		if (!empty($table)) {		
			if (!empty($field)) {			
				$field_array = $this -> get_fields($table);
				
				if (!empty($field_array)) {				
					if (!in_array($field, $field_array)) {				
						$query = "ALTER TABLE `" . $wpdb -> prefix . $table . "` ADD `" . $field . "` " . $attributes . ";";
						
						if ($wpdb -> query($query)) {
							return true;
						}
					}
				}
			}
		}
		
		return false;
	}
	
	function redirect($location = null, $msgtype = null, $message = null) {
		$url = $location;
		
		if ($msgtype == "message") {
			$url .= '&' . $this -> pre . 'updated=true';
		} elseif ($msgtype == "error") {
			$url .= '&' . $this -> pre . 'error=true';
		}
		
		if (!empty($message)) {
			$url .= '&' . $this -> pre . 'message=' . rawurlencode($message);
		}
		
		if (headers_sent()) {
			?>
			
			<script type="text/javascript">
			window.location = '<?php echo (empty($url)) ? get_option('home') : $url; ?>';
			</script>
			
			<?php
			
			flush();
		} else {
			header("Location: " . $url . "");
			exit();
		}
	}
	
	/**
 	 * Sends out an email using the "wp_mail" built in function.
 	 * @param STRING $to The person whom will be receiving the email.
 	 * @param STRING $subject The subject of the email
 	 * @param STRING $message The message to send.
 	 * @param STRING $headers Additional email headers.
 	 * @return BOOLEAN Either returns "true" or "false"
	 *
	 */
	function execute_mail($to = null, $subject = null, $message = null, $headers) {
		//make sure that a recipient address was specified.
		if (!empty($to)) {
			//ensure that the subject is not empty.
			if (!empty($subject)) {
				//ensure that the message is not empty.
				if (!empty($message)) {
					//execute the wp_mail() function with the parameters.
					/*if (wp_mail($to, $subject, $message, $headers)) {
						return true;
					}*/
					
					if (($multiple = @explode(",", $to)) !== false) {
						foreach ($multiple as $memail) {
							wp_mail($memail, $subject, $message, $headers);
						}
						
						return true;
					} else {
						if (wp_mail($to, $subject, $message, $headers)) {
							return true;
						}
					}
				}
			}
		}
		
		return false;
	}
	
	function get_option($name = null) {
		if (!empty($name)) {
			if ($value = get_option($this -> prefix . $name)) {
				return $value;
			}
		}
		
		return false;
	}
	
	function questions_resavepp() {
		global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion;
		$wpfaqDb -> model = $wpfaqQuestion -> model;
				
		if ($questions = $wpfaqDb -> find_all()) {
			foreach ($questions as $question) {
				if (!empty($question)) {
					if ((!empty($question -> pp) && $question -> pp == "none") || (!empty($question -> approved) && $question -> approved == "N")) {
						//wp_delete_post($question -> pp_id);
						$this -> question_savepp($question, "draft");
					} else {
						$this -> question_savepp($question);
					}
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	function question_savepp($question = array(), $post_status = 'publish') {
		//global WordPress variables
		global $wpdb, $user_ID, $wpfaqDb, $wpfaqGroup, $wpfaqQuestion, $wpfaqGroupsPost, $wpfaqQuestionsPost;
	
		if (!empty($question)) {
			//does this group have a Post/Page?
			if (!empty($question -> pp) && ($question -> pp == "post" || $question -> pp == "page")) {
				$pagedata = array(
					'post_title'		=>	$question -> pp_title,
					'post_name'			=>	sanitize_title($question -> pp_title),
					'post_status'		=>	$post_status,
					'post_type'			=>	$question -> pp,
					'post_content'		=>	'[wpfaqquestion id=' . $question -> id . ']',
					'post_category'		=>	maybe_unserialize($question -> pp_categories), 
					'comment_status'	=>	$question -> pp_comments,
					'post_author'		=>	$user_ID,
				);
				
				switch ($question -> pp) {
					case 'post'		:
						//$pagedata['post_category'] = false;
						break;
					case 'page'		:
						$pagedata['post_parent'] = $question -> pp_parent;
						break;
				}
				
				if (!empty($question -> pp_id)) {
					$post_id = $question -> pp_id;
					
					if ($post = get_post($post_id)) {
						$pagedata['ID'] = $post_id;
						$pagedata['post_date'] = $post -> post_date;
					}
				}
				
				if ($post_id = wp_insert_post($pagedata)) {
					$wpfaqQuestionsPost -> delete_by_question($question -> id);
				
					$qpdata = array(
						'question_id'	=>	$question -> id,
						'post_id'		=>	$post_id,
					);
				
					$wpfaqDb -> model = $wpfaqQuestionsPost -> model;
					$wpfaqDb -> save($qpdata, true);
					
					//save the "pp_id" field of the Group
					$wpfaqDb -> model = $wpfaqQuestion -> model;
					$wpfaqDb -> save_field('pp_id', $post_id, array('id' => $question -> id));
				}
				
				return true;
			}
		}
		
		return false;
	}
	
	function predefined_pages() {
		global $user_ID;
		
		/* Create a post for editor images */
		$createimagespost = false;
		if ($edimagespost = $this -> get_option('edimagespost')) {
			if ($imagespost = get_post($edimagespost)) {
				$createimagespost = false;	
			} else {
				$createimagespost = true;	
			}
		} else {
			$createimagespost = true;	
		}
		
		if ($createimagespost == true) {
			$post = array(
				'post_title'			=>	__('FAQ plugin images (do not remove)', $this -> plugin_name),
				'post_content'			=>	__('This is a placeholder for the FAQ plugin images. You may edit and reuse this post but do not remove it.', $this -> plugin_name),
				'post_type'				=>	"post",
				'post_status'			=>	"draft",
				'post_author'			=>	$user_ID,
			);	
			
			if ($post_id = wp_insert_post($post)) {
				$this -> delete_option('edimagespost');
				$this -> add_option('edimagespost', $post_id);	
				$this -> update_option('edimagespost', $post_id);
			}
		}	
	}
	
	function groups_resavepp() {
		global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion;
		$wpfaqDb -> model = $wpfaqGroup -> model;
				
		if ($groups = $wpfaqDb -> find_all()) {
			foreach ($groups as $group) {
				if (!empty($group)) {
					if ((!empty($group -> pp) && $group -> pp == "none") || (!empty($group -> active) && $group -> active == "N")) {
						//wp_delete_post($group -> pp_id);
						$this -> group_savepp($group, "draft");
					} else {
						$this -> group_savepp($group);
					}
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	function group_savepp($group = array(), $post_status = "publish") {
		//global WordPress variables
		global $wpdb, $user_ID, $wpfaqDb, $wpfaqGroup, $wpfaqQuestion, $wpfaqGroupsPost;
	
		if (!empty($group)) {
			//does this group have a Post/Page?
			if (!empty($group -> pp) && ($group -> pp == "post" || $group -> pp == "page")) {
				$pagedata = array(
					'post_title'		=>	$group -> pp_title,
					'post_name'			=>	sanitize_title($group -> pp_title),
					'post_status'		=>	$post_status,
					'post_type'			=>	$group -> pp,
					'post_content'		=>	'[wpfaqgroup id=' . $group -> id . ']',
					'post_author'		=>	$user_ID,
					'tags_input'		=>	$group -> keywords,
				);
				
				switch ($group -> pp) {
					case 'post'		:
						$pagedata['post_category'] = maybe_unserialize($group -> pp_categories);
						break;
					case 'page'		:
						$pagedata['post_parent'] = $group -> pp_parent;
						break;
				}
				
				if (!empty($group -> pp_id)) {
					$post_id = $group -> pp_id;
					
					if ($post = get_post($post_id)) {
						$pagedata['ID'] = $post_id;
						$pagedata['post_date'] = $post -> post_date;
					}
				}
				
				if ($post_id = wp_insert_post($pagedata)) {
					$wpfaqGroupsPost -> delete_by_group($group -> id);
				
					$gpdata = array(
						'group_id'		=>	$group -> id,
						'post_id'		=>	$post_id,
					);
				
					$wpfaqDb -> model = $wpfaqGroupsPost -> model;
					$wpfaqDb -> save($gpdata, true);
					
					//save the "pp_id" field of the Group
					$wpfaqDb -> model = $wpfaqGroup -> model;
					$wpfaqDb -> save_field('pp_id', $post_id, array('id' => $group -> id));
				}
				
				return true;
			}
		}
		
		return false;
	}
	
	function add_action($action = null, $function = null, $priority = 10, $params = array()) {
		if (add_action($action, array($this, (empty($function)) ? $action : $function), $priority, $params)) {
			return true;
		}
		
		return false;
	}
	
	function add_filter($filter = null, $function = null, $priority = 10, $params = array()) {
		if (add_filter($filter, array($this, (empty($function)) ? $filter : $function), $priority, $params)) {
			return true;
		}
		
		return false;
	}
	
	function plugin_base() {
		return rtrim(dirname(__FILE__), '/');
	}
	
	/**
 	 * Generates an absolute URL to the plugin directory.
 	 * Used for HREF values such as CSS and images.
 	 * @return STRING $url The absolute URL to the plugin directory.
	 *
	 */
	function url() {
		return rtrim(get_bloginfo('wpurl'), '/') . '/' . substr(preg_replace("/\\" . DS . "/si", "/", $this -> plugin_base()), strlen(ABSPATH));
	}
	
	function nrand() {
		return substr(md5(rand(1, 999)), 0, 9);
	}
	
	function render_error($message) {
		$this -> render_admin('err-top', array('message' => $message));
		return true;
	}
	
	function render_message($message) {
		$this -> render_admin('msg-top', array('message' => $message));
		return true;
	}
	
	function get_themefolders() {
		$dir = $this -> plugin_base() . DS . 'views' . DS;
		$themefolders = array();
		
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					//echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
					$filetype = filetype($dir . $file);
					if (!empty($filetype) && $filetype == "dir") {
						if ($file != "admin" && $file != "email" && $file != "." && $file != "..") {
							$themefolders[] = $file;
						}
					}
				}
				
				closedir($dh);	
			}
		}
		
		return $themefolders;
	}
	
	function render_field($field_id = null, $fieldset = true, $optinid = null, $showcaption = true) {
		global $wpdb, $wpfaqDb, $wpfaqField, $wpfaqHtml;
	
		if (!empty($field_id)) {
			$fieldquery = "SELECT * FROM " . $wpdb -> prefix . $wpfaqField -> table . " WHERE id = '" . $field_id . "' LIMIT 1";
			$field = $wpdb -> get_row($fieldquery);
		
			if ($field) {
				if ($fieldset == true) {
					echo '<fieldset class="' . $this -> pre . 'customfield ' . $this -> pre . 'customfield' . $field_id . '">';
					echo '<legend><label for="' . $this -> pre . '-' . $optinid . '' . $field -> slug . '">';
					echo $field -> title;
					if ($field -> required == "Y") { echo ' <sup class="' . $this -> pre . 'required">&#42;</sup>'; };
					echo '</label></legend>';
				}
			
				switch ($field -> fieldtype) {
					case 'text'				:
						echo '<input class="' . $this -> pre . ' widefat ' . $this -> pre . 'text" id="' . $this -> pre . '-' . $optinid . '' . $field -> slug . '" type="text" name="' . $optinid . '[' . $field -> slug . ']" value="' . esc_attr(stripslashes($_POST[$optinid][$field -> slug])) . '" />';						
						break;
					case 'textarea'			:
						echo '<textarea class="' . $this -> pre . ' widefat ' . $this -> pre . 'textarea" id="' . $this -> pre . '-' . $optinid . '' . $field -> slug . '" rows="3" cols="100%" name="' . $optinid . '[' . $field -> slug . ']">' . strip_tags($_POST[$optinid][$field -> slug]) . '</textarea>';
						break;
					case 'select'			:
						echo '<select class="' . $this -> pre . ' widefat ' . $this -> pre . 'select" style="width:auto;" id="' . $this -> pre . '-' . $optinid . '' . $field -> slug . '" name="' . $optinid . '[' . $field -> slug . ']">';
						echo '<option value="">- ' . __('Select', $this -> plugin_name) . ' -</option>';
						
						$options = unserialize($field -> fieldoptions);
						if (!empty($options)) {
							foreach ($options as $name => $value) {
								$select = (!empty($_POST[$optinid][$field -> slug]) && $_POST[$optinid][$field -> slug] == $name) ? 'selected="selected"' : '';
								echo '<option ' . $select . ' value="' . $name . '">' . $value . '</option>';
							}
						}
						
						echo '</select>';
						break;
					case 'radio'			:
						$options = unserialize($field -> fieldoptions);
						if (!empty($options)) {
							$r = 1;
							
							foreach ($options as $name => $value) {
								$checked = ($_POST[$optinid][$field -> slug] == $name) ? 'checked="checked"' : '';
								echo '<label class="' . $this -> pre . '"><input class="' . $this -> pre . 'radio" type="radio" ' . $checked . ' name="' . $optinid . '[' . $field -> slug . ']" value="' . $name . '" /> ' . $value . '</label>';
								
								if ($r < count($options)) {
									echo '<br/>';	
								}
								
								$r++;
							}
						}
						break;
					case 'checkbox'			:
						$options = unserialize($field -> fieldoptions);
						if (!empty($options)) {
							foreach ($options as $name => $value) {
								$checked = (!empty($_POST[$optinid][$field -> slug]) && is_array($_POST[$optinid][$field -> slug]) && @in_array($name, $_POST[$optinid][$field -> slug])) ? 'checked="checked"' : '';
								echo '<label class="' . $this -> pre . '"><input class="' . $this -> pre . 'checkbox" type="checkbox" ' . $checked . ' name="' . $optinid . '[' . $field -> slug . '][]" value="' . $name . '" /> ' . $value . '</label><br/>';
							}
						}
						break;
				}
				
				if(!empty($field -> caption) && $showcaption == true) {
					echo '<br /><span class="' . $this -> pre . 'customfieldcaption">' . stripslashes($field -> caption) . '</span>';
				}
				
				if ($fieldset == true) {			
					echo '</fieldset>';
				}
			}
		}
		
		return true;
	}
	
	function render($file = null, $params = array(), $folder = 'default', $output = true) {
		if (!empty($file)) {
			$this -> plugin_name = 'wp-faq';
			
			//$this -> register_plugin($this -> base, __FILE__);
			$this -> sections = (object) $this -> sections;
		
			$filename = $file . '.php';
			$filepath = $this -> plugin_base() . DS . 'views' . DS . $folder . DS;
			$filefull = $filepath . $filename;
			
			if (file_exists($filefull)) {
				if (!empty($params)) {
					foreach ($params as $pkey => $pval) {
						${$pkey} = $pval;
					}
				}
				
				if (!empty($this -> classes)) {
					foreach ($this -> classes as $name => $args) {
						global ${$name};
					}
				}
				
				if (empty($output) || $output == false) {
					ob_start();
				}
			
				include($filefull);
				
				if (empty($output) || $output == false) {
					$data = ob_get_clean();
					return $data;
				} else {
					return true;
				}
			} else {
				_e('Rendering of "' . $filefull . '" has failed, please check if the file exists.', $this -> plugin_name);
			}
		} else {
			_e('No file was specified for rendering', $this -> plugin_name);
		}
		
		return false;
	}
	
	function render_admin($file = null, $params = array()) {
		if (!empty($file)) {
			if (!empty($params)) {
				foreach ($params as $key => $val) {
					${$key} = $val;
				}
			}
		
			if (file_exists($this -> plugin_base . '/views/admin/' . $file . '.php')) {
				include($this -> plugin_base . '/views/admin/' . $file . '.php');
				return true;
			}
		}
		
		return false;
	}
	
	/**
 	 * Logs a PHP error.
 	 * Makes use of the PHP error_log() function.
 	 * @param STRING $error The message/error to log.
 	 * @return BOOLEAN Either returns true or false
	 *
	 */
	function log_error($error = null) {
		if (!empty($error)) {
			if (error_log("WPFAQ " . $this -> gen_date() . " : " . $error)) {
				return true;
			}
		}
		
		return false;
	}
	
	function init_class($name = null, $params = array()) {
		if (!empty($name)) {
			if (!preg_match("/" . $this -> pre . "/i", $name)) {
				$name = $this -> pre . $name;
			}
		
			if (class_exists($name)) {
				if ($class = new $name($params)) {							
					return $class;
				}
			}
		}
		
		return false;
	}
	
	function paginate($model = null, $fields = '*', $sub = null, $conditions = array(), $searchterm = null, $per_page = 5, $order = array('modified', "DESC")) {
		global $wpdb, ${$model};
	
		if (!empty($model)) {
			$paginate = $this -> vendor('Paginate');
			$paginate -> table = $wpdb -> prefix . ${$model} -> table;
			$paginate -> sub = (empty($sub)) ? ${$model} -> controller : $sub;
			$paginate -> fields = (empty($fields)) ? '*' : $fields;
			$paginate -> where = (empty($conditions)) ? false : $conditions;
			$paginate -> searchterm = (empty($searchterm)) ? false : $searchterm;
			$paginate -> per_page = $per_page;
			$paginate -> order = (empty($order)) ? array('modified', "DESC") : $order;
			
			$data = $paginate -> start_paging($_GET[$this -> pre . 'page']);
			
			if (!empty($data)) {
				$newdata = array();
			
				foreach ($data as $record) {
					$newdata[] = $this -> init_class($model, $record);
				}
				
				$data = array();
				$data[$model] = $newdata;
				$data['Paginate'] = $paginate;
			}
			
			return $data;
		}
		
		return false;
	}
	
	function vendor($name = null, $folder = null) {
		if (!empty($name)) {
			$filename = 'class.' . strtolower($name) . '.php';
			$filepath = rtrim(dirname(__FILE__), '/') . DS . 'vendors' . DS . $folder . '';
			$filefull = $filepath . $filename;
		
			if (file_exists($filefull)) {
				require_once($filefull);
				$class = $this -> pre . $name;
				
				if (${$name} = new $class) {
					return ${$name};
				}
			}
		}
	
		return false;
	}
	
	function check_email($email = null) {
		if (!empty($email)) {
			if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email)) {
				return false;	
			}
		} else { 
			return false; 
		}
		
		return true;
	}
	
	/**
 	 * Generates a date/time string
 	 * Uses a unix timestamp and a time string format to generate this.
 	 * @param INT $time The unix timestamp. When this is left empty, the current time will be used.
 	 * @param STRING $format The format of the time string to generate.
	 *
	 */
	function gen_date($time = null, $format = "Y-m-d H:i:s") {
		$timestamp = (empty($time)) ? time() : $time;
		return date($format, $timestamp);
	}

	function email_validate($email = null) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			return false;
		}
		
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
				return false;
			}
		}
		
		return true;
	}
}

?>