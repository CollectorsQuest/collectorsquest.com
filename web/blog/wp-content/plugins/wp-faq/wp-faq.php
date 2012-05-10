<?php

/*
Plugin Name: FAQs
Description: Manage frequently asked questions with a full featured administration panel and embed question groups into your website front-end by hardcoding them, embedding them directly into posts/pages or using sidebar widget(s).
Version: 1.4.6
Plugin URI: http://tribulant.com/plugins/view/8/wordpress-faq-plugin
Author: Tribulant Software
Author URI: http://tribulant.com
*/

define('WP_MEMORY_LIMIT', "256M");

//directory separator constant
if (!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }

require_once(dirname(__FILE__) . DS . 'includes' . DS . 'checkinit.php');
require_once(dirname(__FILE__) . DS . 'wp-faq-plugin.php');

//RSS and Support Links
define('WPFAQ_SHOW_RSS', true);
define('WPFAQ_SHOW_SUPPORT', true);

class wpFaq extends wpFaqPlugin {

	var $url = '';
	var $name = 'wp-faq';
	
	function sc_groups($atts = array(), $content = null) {
		global $wpfaqDb, $wpfaqGroup, $wpfaqHtml;
		$wpfaqDb -> model = $wpfaqGroup -> model;
		
		$defaults = array('orderby' => "order", 'order' => "ASC");
		$r = shortcode_atts($defaults, $atts);
		extract($r, EXTR_SKIP);
		
		if ($groups = $wpfaqDb -> find_all(array('active' => "Y", 'pp' => "!= 'none'"), null, array($orderby, $order))) {
			if (!empty($groups)) {
				ob_start();
				
				?>
				
				<div class="<?php echo $this -> pre; ?>groups">
				<ul>
				
				<?php
				
				foreach ($groups as $group) {
					if (!empty($group -> pp) && $group -> pp != "none" && !empty($group -> pp_id)) {
						?><li><?php echo $wpfaqHtml -> link($group -> name, get_permalink($group -> pp_id)); ?></li><?php
					}
				}
				
				?>
				
				</ul>
				</div>
				
				<?php
				
				$content = ob_get_clean();
			}
		}
		
		return $content;
	}
	
	function sc_faqs($atts = array(), $content = null) {
		global $wpfaqDb, $wpfaqQuestion;
		$wpfaqDb -> model = $wpfaqQuestion -> model;
		$output = "";		
		$defaults = array('order' => "ASC", 'orderby' => "order");
		
		$r = shortcode_atts($defaults, $atts);
		extract($r, EXTR_SKIP);
		
		if ($questions = $wpfaqDb -> find_all(array('approved' => "Y"), false, array($orderby, $order))) {
			$output = $this -> render('questions' . DS . 'loop', array('questions' => $questions), 'default', false);
		}
		
		return $output;
	}
	
	function sc_group($atts = array(), $content = null) {	
		if (!empty($atts['id'])) {
			global $wpdb, $wpfaqDb, $wpfaqGroup, $wpfaqQuestion, $wpfaqQuestionsGroup;
			$defaults = array('id' => 0);
		
			$r = shortcode_atts($defaults, $atts);
			extract($r, EXTR_SKIP);
			$wpfaqDb -> model = $wpfaqGroup -> model;
			
			if ($group = $wpfaqDb -> find(array('id' => $id, 'active' => "Y"))) {		
				$wpfaqDb -> model = $wpfaqQuestion -> model;
				//$questions = $wpfaqDb -> find_all(array('group_id' => $id, 'approved' => "Y"), false, array('order', "ASC"));
				
				$questions_table = $wpdb -> prefix . $wpfaqQuestion -> table;
				$questionsgroups_table = $wpdb -> prefix . $wpfaqQuestionsGroup -> table;
				
				$query = 
				"SELECT * FROM " . $questions_table . " LEFT JOIN " . $questionsgroups_table
				. " ON " . $questions_table . ".id = " . $questionsgroups_table . ".question_id"
				. " WHERE " . $questions_table . ".approved = 'Y' AND " . $questions_table . ".group_id = '" . $id . "' 
				GROUP BY " . $questions_table . ".id ORDER BY " . $questionsgroups_table . ".order ASC";
				
				$questions = stripslashes_deep($wpdb -> get_results($query));				
				$content = $this -> render('groups' . DS . 'view', array('group' => $group, 'questions' => $questions), 'default', false);
			}
		}
		
		return $content;
	}
	
	function sc_question($atts = array(), $content = null) {
		if (!empty($atts['id'])) {
			global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion, $wpfaqQuestionsQuestion;
			$defaults = array('id' => 0);
			
			$r = shortcode_atts($defaults, $atts);
			extract($r, EXTR_SKIP);
			$wpfaqDb -> model = $wpfaqQuestion -> model;
			
			if ($question = $wpfaqDb -> find(array('id' => $id))) {
				$wpfaqDb -> model = $wpfaqGroup -> model;
				
				if ($wpfaqDb -> field('active', array('id' => $question -> group_id)) == "Y") {
					/* Related Questions */
					$wpfaqDb -> model = $wpfaqQuestionsQuestion -> model;
					$related = $wpfaqDb -> find_all(array('question_id' => $id));
					$content = $this -> render('questions' . DS . 'view', array('question' => $question, 'related' => $related), 'default', false);
				}
			}
		}
		
		return $content;
	}
	
	function sc_questions($atts = array(), $content = null) {
		$defaults = array(
			'number'		=>	5,
			'order'			=>	'DESC',
			'orderby'		=>	'modified',
			'group_id'		=>	null,
			'approved'		=>	"Y",
		);
		
		$r = shortcode_atts($defaults, $atts);
		extract($r, EXTR_SKIP);
		
		global $wpfaqDb, $wpfaqQuestion;
		$wpfaqDb -> model = $wpfaqQuestion -> model;
		
		ob_start();
		
		if ($questions = $wpfaqDb -> find_all(array('approved' => $approved), null, array($orderby, $order), $number)) {
			?>
			
			<div class="<?php echo $this -> pre; ?>questions">
				<ul>
				
				<?php
				
				foreach ($questions as $question) {
					?><li><?php echo $question -> question; ?></li><?php
				}
				
				?>
				
				</ul>
			</div>
			
			<?php
		}
		
		$content = ob_get_clean();
		return $content;
	}
	
	function sc_search($atts = array(), $content = null) {		
		$number = substr(md5(rand(1, 999)), 0, 6);
		$defaults = array('menu' => 1, 'group_id' => null);
		$r = shortcode_atts($defaults, $atts);
		extract($r, EXTR_SKIP);
		
		$group = null;
		if (!empty($group_id)) {
			global $wpfaqDb, $wpfaqGroup;
			$wpfaqDb -> model = $wpfaqGroup -> model;
			$group = $wpfaqDb -> find(array('id' => $group_id));
		}
		
		ob_start();
		?><div id="<?php echo $this -> pre; ?>search<?php echo $number; ?>" class="<?php echo $this -> pre; ?>search"><?php
		$this -> render('searchbox', array('number' => $number, 'group' => $group, 'showgroupsmenu' => ((!empty($menu)) ? true : false)), 'default', true);
		?></div><?php
		
		?><div id="<?php echo $this -> pre; ?>questions<?php echo $number; ?>"></div><?php
		$content = ob_get_clean();
			
		return $content;
	}
	
	function sc_ask($atts = array(), $content = null) {
		$number = substr(md5(rand(1, 999)), 0, 6);
		$defaults = array('group_id' => null);
		$r = shortcode_atts($defaults, $atts);
		extract($r, EXTR_SKIP);

		if (!empty($group_id)) {
			global $wpfaqDb, $wpfaqGroup;
			$wpfaqDb -> model = $wpfaqGroup -> model;
			
			if ($group = $wpfaqDb -> find(array('id' => $group_id))) {			
				ob_start();
				?><div id="<?php echo $this -> pre; ?>ask<?php echo $number; ?>" class="<?php echo $this -> pre; ?>ask"><?php
				$this -> render('askbox', array('number' => $number, 'group' => $group), 'default', true);
				?></div><?php
				$content = ob_get_clean();
			}
		}
		
		return $content;
	}
	
	function init_textdomain() {		
		if (function_exists('load_plugin_textdomain')) {			
			//load_textdomain($this -> plugin_name, dirname(plugin_basename(__FILE__)) . DS . 'languages');
			load_plugin_textdomain($this -> plugin_name, 'wp-faq' . DS . 'languages', dirname(plugin_basename(__FILE__)) . DS . 'languages');
		}	
	}
	
	function wp_head() {		
		//render the "head.php" template file.
		$this -> render('head');
	}
	
	function admin_menu() {
		$accesslevel_option = $this -> get_option('accesslevel');
		$accesslevel = (!empty($accesslevel_option)) ? $accesslevel_option : 10;
	
		add_menu_page(__('FAQs', $this -> plugin_name), __('FAQs', $this -> plugin_name), $accesslevel, $this -> sections -> welcome, array($this, 'admin'), $this -> url() . '/images/icon-16.png');
		$this -> menus['welcome'] = add_submenu_page($this -> sections -> welcome, __('Overview', $this -> plugin_name), __('Overview', $this -> plugin_name), $accesslevel, $this -> sections -> welcome, array($this, 'admin'));
		$this -> menus['faqs-settings'] = add_submenu_page($this -> sections -> welcome, __('Configuration', $this -> plugin_name), __('Configuration', $this -> plugin_name), $accesslevel, $this -> sections -> settings, array($this, 'admin_settings'));
		$this -> menus['faqs-groups'] = add_submenu_page($this -> sections -> welcome, __('Groups', $this -> plugin_name), __('Groups', $this -> plugin_name), $accesslevel, $this -> sections -> groups, array($this, 'admin_groups'));
		$this -> menus['faqs-groups-save'] = add_submenu_page($this -> sections -> groups, __('Save a Group', $this -> plugin_name), __('Save a Group', $this -> plugin_name), $accesslevel, $this -> sections -> groups_save, array($this, 'admin_groups'));
		$this -> menus['faqs-questions'] = add_submenu_page($this -> sections -> welcome, __('Questions', $this -> plugin_name), __('Questions', $this -> plugin_name), $accesslevel, $this -> sections -> questions, array($this, 'admin_questions'));
		$this -> menus['faqs-questions-save'] = add_submenu_page($this -> sections -> questions, __('Save a Question', $this -> plugin_name), __('Save a Question', $this -> plugin_name), $accesslevel, $this -> sections -> questions_save, array($this, 'admin_questions'));
		$this -> menus['faqs-fields'] = add_submenu_page($this -> sections -> welcome, __('Custom Fields', $this -> plugin_name), __('Custom Fields', $this -> plugin_name), $accesslevel, $this -> sections -> fields, array($this, 'admin_fields'));
		
		if (WPFAQ_SHOW_SUPPORT) {
			$this -> menus['faqs-support'] = add_submenu_page($this -> sections -> welcome, __('Support &amp; Help', $this -> plugin_name), __('Support &amp; Help', $this -> plugin_name), $accesslevel, $this -> sections -> support, array($this, 'admin_support'));
		}
		
		//admin_head-X hooks
		add_action('admin_head-' . $this -> menus['faqs-questions-save'], array($this, 'admin_head_questions_save'));
		add_action('admin_head-' . $this -> menus['faqs-settings'], array($this, 'admin_head_settings'));
	}
	
	function admin_head() {
		//render the "admin/head.php" template
		$this -> render('head', false, 'admin', true);
	}
	
	function admin_head_questions_save() {
		global $wpfaqMetabox;
		
		//add meta boxes
		add_meta_box('groupdiv', __('FAQ Group', $this -> plugin_name), array($this, 'group_metabox'), "admin_page_" . $this -> sections -> questions_save, 'side', 'core');
		add_meta_box('ppdiv', __('WordPress Post/Page', $this -> plugin_name), array($wpfaqMetabox, 'questions_save_pp'), "admin_page_" . $this -> sections -> questions_save, 'side', 'core');
		add_meta_box('submitdiv', __('Save FAQ', $this -> plugin_name), array($this, 'submit_metabox'), "admin_page_" . $this -> sections -> questions_save, 'side', 'core');
		add_meta_box('fieldsdiv', __('Custom Fields', $this -> plugin_name), array($this, 'fields_metabox'), "admin_page_" . $this -> sections -> questions_save, 'normal', 'core');
		
		//do actions
		do_action('do_meta_boxes', "admin_page_" . $this -> sections -> questions_save, 'side');
		do_action('do_meta_boxes', "admin_page_" . $this -> sections -> questions_save, 'normal');
		do_action('do_meta_boxes', "admin_page_" . $this -> sections -> questions_save, 'advanced');
	}
	
	function admin_head_settings() {
		global $user_ID, $wpfaqMetabox;
		$user_info = get_userdata($user_ID);
	
		//add meta boxes
		add_meta_box('submitdiv', __('Save Settings', $this -> plugin_name), array($wpfaqMetabox, 'settings_submit'), "faqs_page_" . $this -> sections -> settings, 'side', 'core');
		add_meta_box('sections', __('Sections', $this -> plugin_name), array($wpfaqMetabox, 'settings_sections'), "faqs_page_" . $this -> sections -> settings, 'side', 'core');
		
		if (!empty($user_info -> user_level) && $user_info -> user_level == 10) {
			add_meta_box('wprelated', __('WordPress Related', $this -> plugin_name), array($wpfaqMetabox, 'settings_wprelated'), "faqs_page_" . $this -> sections -> settings, 'normal', 'core');
		}
			
		add_meta_box('general', __('General Configuration', $this -> plugin_name), array($wpfaqMetabox, 'settings_general'), "faqs_page_" . $this -> sections -> settings, 'normal', 'core');
		add_meta_box('ask', __('Ask Questions Settings', $this -> plugin_name), array($wpfaqMetabox, 'settings_ask'), "faqs_page_" . $this -> sections -> settings, 'normal', 'core');
		add_meta_box('questions', __('Questions Settings', $this -> plugin_name), array($wpfaqMetabox, 'settings_questions'), "faqs_page_" . $this -> sections -> settings, 'normal', 'core');
		add_meta_box('accordion', __('Accordion Settings', $this -> plugin_name), array($wpfaqMetabox, 'settings_accordion'), "faqs_page_" . $this -> sections -> settings, 'normal', 'core');
		add_meta_box('customcss', __('Theme &amp; Custom CSS', $this -> plugin_name), array($wpfaqMetabox, 'settings_customcss'), "faqs_page_" . $this -> sections -> settings, 'normal', 'core');
		
		//do actions
		do_action('do_meta_boxes', "faqs_page_" . $this -> sections -> settings, 'side');
		do_action('do_meta_boxes', "faqs_page_" . $this -> sections -> settings, 'normal');
		do_action('do_meta_boxes', "faqs_page_" . $this -> sections -> settings, 'advanced');
	}
	
	function tinymce() {
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;

		// Add TinyMCE buttons when using rich editor
		if (get_user_option('rich_editing') == 'true') {		
			//add_filter('tiny_mce_version', array($this, 'mceupdate')); // Move to plugin activation
			//add_filter('tiny_mce_before_init', array($this, 'my_change_mce_settings'));
			add_filter('mce_buttons', array($this, 'mcebutton'));
			add_filter('mce_buttons_3', array($this, 'mcebutton3'));
			add_filter('mce_external_plugins', array($this, 'mceplugin'));
		}
	}
	
	function mcebutton($buttons) {	
		array_push($buttons, "FAQs");		
		return $buttons;
	}
	
	function mcebutton3($buttons) {
		//Viper's Video Quicktags compatibility
		if (!empty($_GET['page']) && $_GET['page'] == $this -> sections -> questions_save) {
			if (!empty($buttons)) {
				foreach ($buttons as $bkey => $bval) {
					if (preg_match("/\v\v\q(.*)?/si", $bval, $match)) {
						unset($buttons[$bkey]);
					}
				}
			}
		}
		
		return $buttons;
	}

	function mceplugin($plugins) {
		$plugins['FAQs'] = $this -> url() . '/js/tinymce/editor_plugin.js';
		
		//Viper's Video Quicktags compatibility
		if (!empty($_GET['page']) && $_GET['page'] == $this -> sections -> questions_save) {
			if (isset($plugins['vipersvideoquicktags'])) {
				unset($plugins['vipersvideoquicktags']);
			}
		}
		
		return $plugins;
	}	

	function my_change_mce_settings( $init_array ) {
	    $init_array['disk_cache'] = false; // disable caching
	    $init_array['compress'] = false; // disable gzip compression
	    $init_array['old_cache_max'] = 3; // keep 3 different TinyMCE configurations cached (when switching between several configurations regularly)
	}

	function mceupdate($ver) {
		$ver += 3;
	  	return $ver;
	}
	
	function admin_notices() {
		if (!$this -> ci_serial_valid()) {			
			$message = __('Please fill in a serial key for the FAQ plugin to continue use.', $this -> plugin_name);
			$message .= ' <a id="' . $this -> pre . 'submitseriallink" href="" title="FAQ plugin - Serial Key">' . __('Submit Serial Key', $this -> plugin_name) . '</a>';
			$this -> render_error($message);
			
			?>
            
            <script type="text/javascript">
			jQuery(document).ready(function(e) {
                jQuery('#<?php echo $this -> pre; ?>submitseriallink').click(function() {					
					jQuery.colorbox({href:ajaxurl + "?action=<?php echo $this -> pre; ?>serialkey"});
					return false;
				});
            });
			</script>
            
            <?php
		}
		
		if (!empty($_GET[$this -> pre . 'message'])) {		
			$msg_type = (!empty($_GET[$this -> pre . 'updated'])) ? 'message' : 'error';
			call_user_method('render_' . $msg_type, $this, stripslashes(rawurldecode($_GET[$this -> pre . 'message'])));
		}
	}
	
	function init_getpost() {
		
		return true;
	}
	
	function widget_register() {		
		if (function_exists('register_sidebar_widget')) {
			if (!$options = get_option($this -> pre . '-widget')) {
				$options = array();
			}
		
			$widget_options = array('classname' => $this -> pre . '-widget', 'description' => __('Frequently asked questions in your sidebar(s)', $this -> plugin_name));	
			$control_options = array('id_base' => $this -> pre, 'width' => 350, 'height' => 300);	
			$name = __('FAQs', $this -> plugin_name);
			
			if (!empty($options)) {
				foreach ($options as $okey => $oval) {
					$id = $this -> pre . '-' . $okey;
										
					wp_register_sidebar_widget($id, $name, array($this, $this -> pre . '_widget'), $widget_options, array('number' => $okey));
					wp_register_widget_control($id, $name, array($this, $this -> pre . '_widget_control'), $control_options, array('number' => $okey));
				}
			} else {
				$id = $this -> pre . '-1';
				wp_register_sidebar_widget($id, $name, array($this, $this -> pre . '_widget'), $widget_options, array('number' => -1));
				wp_register_widget_control($id, $name, array($this, $this -> pre . '_widget_control'), $control_options, array('number' => -1));
			}
		}
	}
	
	function plugin_action_links($actions = null, $plugin_file = null, $plugin_data = null, $context = null) {
		$this_plugin = plugin_basename(__FILE__);
		
		if (!empty($plugin_file) && $plugin_file == $this_plugin) {
			$actions[] = '<a onclick="jQuery.colorbox({href:ajaxurl + \'?action=' . $this -> pre . 'serialkey\'}); return false;" href="" title="' . __('Serial Key', $this -> plugin_name) . '" class="colorbox">' . __('Serial Key', $this -> plugin_name) . '</a>';	
		}
		
		return $actions;
	}
	
	function init() {
		$wpfaqmethod = (empty($_POST[$this -> pre . 'method'])) ? null : $_POST[$this -> pre . 'method'];
		$method = (empty($_GET[$this -> pre . 'method'])) ? $wpfaqmethod : $_GET[$this -> pre . 'method'];
		
		if (!empty($method)) {
			switch ($method) {
				case 'submitserial'			:
					$errors = array();
					$success = false;
				
					if (!empty($_POST)) {
						if (empty($_POST['serialkey'])) { $errors[] = __('Please fill in a serial key', $this -> plugin_name); }
						else { $this -> update_option('serialkey', $_POST['serialkey']); }
						
						if (!$this -> ci_serial_valid()) { $errors[] = __('Serial key is invalid, please try again', $this -> plugin_name); }
						else { $success = true; }
					}
					
					$this -> render('submitserial', array('errors' => $errors, 'success' => $success), 'admin', true);
								
					exit();
					break;	
			}
		}
	}
	
	function after_plugin_row($file = null, $info = array()) {
		global $wp_version;
		
		//plugin basename
		$this_plugin = plugin_basename(__FILE__);

		if (!empty($file)) {				
			if ($file == $this_plugin) {			
				$columns = substr($wp_version, 0, 3) == "2.8" ? 3 : 5;
				$url = "http://tribulant.com/plugins/version/8";
				
				if ($response = wp_remote_fopen($url)) {
					$version = ereg_replace("[^0-9.]", "", $response);
	
					if (!empty($version)) {				
						if (version_compare($version, $this -> version) === 1) {
							$update = "";
							$update .= '<tr class="plugin-update-tr">';
							$update .= '<td colspan="' . $columns . '" class="plugin-update">';
							$update .= '<div class="update-message">';
							$update .= __('There is a new version of ' . $info['Name'] . ' available. Go to the <a href="http://tribulant.com/downloads/" title="Tribulant Software Downloads" target="_blank">downloads section</a> and get version ' . $version . '.');
							$update .= '</div>';
							$update .= '</td>';
							$update .= '</tr>';
							
							echo $update;
						}
					}
				}
			}
		}
		
		return false;
	}
	
	/* WordPress Dashboard Widget Setup */
	function wp_dashboard_setup() {
		wp_add_dashboard_widget($this -> pre . '_dashboard_widget', __('FAQs Overview', $this -> plugin_name), array($this, 'dashboard_widget'));
	}
	
	function wpfaq_widget($atts = array(), $widget_atts = array()) {		
		extract($atts, EXTR_SKIP);
		
		if (is_numeric($widget_atts)) {
			$widget_atts = array('number' => $widget_atts);
		}
			
		$widget_atts = wp_parse_args($widget_atts, array('number' => -1));
		extract($widget_atts, EXTR_SKIP);
	
		$options = get_option($this -> pre . '-widget');		
		if (empty($options[$number])) {
			return;
		}
		
		$options[$number]['number'] = $number;
		
		$questions = false;
		if (!empty($options[$number]['display']) && $options[$number]['display'] == "questions") {
			global $wpdb, $wpfaqQuestion;
			$query = "SELECT * FROM `" . $wpdb -> prefix . $wpfaqQuestion -> table . "`"
			. " WHERE approved = 'Y' AND pp_id != ''"
			. " ORDER BY `" . $options[$number]['questions_orderby'] . "` " . $options[$number]['questions_order'] . "";
			
			if (!empty($options[$number]['questions_number'])) {
				$query .= " LIMIT " . $options[$number]['questions_number'];	
			}
			
			$questions = stripslashes_deep($wpdb -> get_results($query));
		}
		
		echo $atts['before_widget'];
		$this -> render('widget-' . $options[$number]['display'], array('atts' => $atts, 'options' => $options[$number], 'questions' => $questions), 'default', true);
		echo $atts['after_widget'];
	}
	
	function wpfaq_widget_control($widget_args = array()) {
		global $wp_registered_widgets;
		static $updated = false;
		
		if (is_numeric($widget_args)) {
			$widget_args = array('number' => $widget_args);
		}
			
		$widget_args = wp_parse_args($widget_args, array('number' => -1));
		
		if (!empty($widget_args['number']) && is_array($widget_args['number'])) {
			extract($widget_args['number'], EXTR_SKIP);
		} else {
			extract($widget_args, EXTR_SKIP);
		}
		
		$options = get_option($this -> pre . '-widget');
		if (empty($options) || !is_array($options)) {
			$options = array();
		}
	
		if (!$updated && !empty($_POST['sidebar'])) {
			$sidebar = $_POST['sidebar'];
			$sidebars_widgets = wp_get_sidebars_widgets();
			
			if (!empty($sidebars_widgets[$sidebar])) {
				$this_sidebar = $sidebars_widgets[$sidebar];
			} else {
				$this_sidebar = array();
			}

			if (!empty($this_sidebar)) {			
				foreach ($this_sidebar as $_widget_id ) {
					if ($this -> pre . '_widget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
						$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
						
						if (!in_array($this -> pre . "-" . $widget_number, $_POST['widget-id'])) {
							unset($options[$widget_number]);
						}
					}
				}
			}

			if (!empty($_POST[$this -> pre . '-widget'])) {					
				foreach ($_POST[$this -> pre . '-widget'] as $widget_number => $widget_values) {
					if (!isset($widget_values['title']) && isset($options[$widget_number])) {
						continue;
					}
					
					$title = strip_tags(stripslashes($widget_values['title']));
					$display = $widget_values['display'];
					$questions_number = $widget_values['questions_number'];
					$questions_order = $widget_values['questions_order'];
					$questions_orderby = $widget_values['questions_orderby'];
					
					$options[$widget_number] = compact('title', 'display', 'questions_number', 'questions_order', 'questions_orderby');
				}
			}
	
			update_option($this -> pre . '-widget', $options);
			$updated = true;
		}
		
		if (-1 == $number) {
			$number = '%i%';
		}
		
		if (empty($_POST)) {
			$this -> render('widget', array('options' => $options, 'number' => $number), 'admin', true);
		}
	}
	
	function admin() {
		global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion, $wpfaqHtml;
		$wpfaqDb -> model = $wpfaqQuestion -> model;
		
		if ($unapproved = $wpfaqDb -> count(array('approved' => "N"))) {
			if (!empty($unapproved)) {
				$message = __('You have <b>' . $wpfaqHtml -> link($unapproved, '?page=' . $this -> sections -> questions . '&approved=N', array('onclick' => "wpfaq_change_approved('N');")) . '</b> unapproved question(s)', $this -> plugin_name);
				$this -> render_message($message);
			}
		}
		
		//render the admin dashboard
		$this -> render('index', false, 'admin', true);
	}
	
	function admin_groups() {
		global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion;
		$wpfaqDb -> model = $wpfaqGroup -> model;
		
		//the method
		$method = (empty($_GET['method'])) ? preg_replace("/faqs\-groups\-/si", "", $_GET['page']) : $_GET['method'];
			
		switch ($method) {
			case 'save'		:
				if (!empty($_POST)) {
					if ($wpfaqDb -> save($_POST) !== false) {
						$message = __('Group has been successfully saved', $this -> plugin_name);
						$this -> redirect('?page=' . $this -> sections -> groups, 'message', $message);
					} else {
						$this -> render('groups' . DS . 'save', false, 'admin', true);
					}
				} else {
					if (!empty($_GET['id'])) { $wpfaqDb -> find(array('id' => $_GET['id'])); }
					$this -> render('groups' . DS . 'save', array('group' => $group), 'admin', true);
				}
				break;
			case 'order'	:
				$wpfaqDb -> model = $wpfaqGroup -> model;
				$groups = $wpfaqDb -> find_all(array('active' => "Y", 'pp' => "!= 'none'"), false, array('order', "ASC"));
				$this -> render('groups' . DS . 'order', array('groups' => $groups), 'admin', true);
				break;
			case 'view'		:
				if (!empty($_GET['id'])) {
					if ($group = $wpfaqDb -> find(array('id' => $_GET['id']))) {
						$perpage = (isset($_COOKIE[$this -> pre . 'questionsperpage'])) ? $_COOKIE[$this -> pre . 'questionsperpage'] : 10;
						
						$conditions = array();
						$conditions['group_id'] = $_GET['id'];
						
						if (isset($_COOKIE[$this -> pre . 'approved'])) {
							$approved = $_COOKIE[$this -> pre . 'approved'];					
							if (!empty($approved) && $approved != "all") {						
								$conditions['approved'] = $approved;
							}
						}
						
						$data = $this -> paginate($wpfaqQuestion -> model, "*", $this -> pre . 'groups&amp;method=view&amp;id=' . $_GET['id'], $conditions, false, $perpage, array('order', "ASC"));
						$this -> render('groups' . DS . 'view', array('group' => $group, 'questions' => $data[$wpfaqQuestion -> model], 'paginate' => $data['Paginate']), 'admin', true);	
					} else {
						$message = __('Group cannot be read', $this -> plugin_name);
						$this -> redirect('?page=' . $this -> sections -> groups, 'error', $message);
					}
				} else {
					$message = __('No group was specified', $this -> plugin_name);
					$this -> redirect('?page=' . $this -> sections -> groups, 'error', $message);
				}
				break;
			case 'delete'	:
				if (!empty($_GET['id'])) {
					$wpfaqDb -> model = $wpfaqGroup -> model;
				
					if ($wpfaqDb -> delete($_GET['id'])) {
						$msg_type = 'message';
						$message = __('Group and all its questions have been removed', $this -> plugin_name);
					} else {
						$msg_type = 'error';
						$message = __('Group cannot be removed', $this -> plugin_name);
					}
				} else {
					$msg_type = 'error';
					$message = __('No group was specified', $this -> plugin_name);
				}
				
				$this -> redirect('?page=' . $this -> sections -> groups, $msg_type, $message);
				break;
			case 'mass'		:
				if (!empty($_POST)) {
					if (!empty($_POST['groupslist'])) {
						if (!empty($_POST['action'])) {
							$groups = $_POST['groupslist'];
							
							switch ($_POST['action']) {
								case 'delete'			:
									foreach ($groups as $id) {
										$wpfaqDb -> model = $wpfaqGroup -> model;
										$wpfaqDb -> delete($id);
									}
									
									$msg_type = 'message';
									$message = __('Selected groups have been removed', $this -> plugin_name);
									break;
								case 'activate'			:
									foreach ($groups as $group_id) {
										$wpfaqDb -> model = $wpfaqGroup -> model;
										$wpfaqDb -> save_field('active', "Y", array('id' => $group_id));
									}
									
									$msg_type = 'message';
									$message = __('Selected groups have been activated', $this -> plugin_name);
									break;
								case 'deactivate'		:
									foreach ($groups as $group_id) {
										$wpfaqDb -> model = $wpfaqGroup -> model;
										$wpfaqDb -> save_field('active', "N", array('id' => $group_id));
									}
									
									$msg_type = 'message';
									$message = __('Selected groups have been deactivated', $this -> plugin_name);
									break;
							}
						} else {
							$msg_type = 'error';
							$message = __('No bulk action was selected', $this -> plugin_name);
						}
					} else {
						$msg_type = 'error';
						$message = __('No groups were selected', $this -> plugin_name);
					}
				}
				
				$this -> redirect('?page=' . $this -> sections -> groups, $msg_type, $message);
				break;
			default			:
				$searchterm = (empty($_GET[$this -> pre . 'searchterm'])) ? false : $_GET[$this -> pre . 'searchterm'];
				$searchterm = (empty($_POST['searchterm'])) ? $searchterm : $_POST['searchterm'];
			
				if (!empty($_POST['searchterm'])) {
					$this -> redirect('?page=' . $this -> sections -> groups . '&' . $this -> pre . 'searchterm=' . urlencode($searchterm) . '');
				}
				
				$conditions = false;
				if (!empty($searchterm)) {
					$conditions = array('name' => "LIKE '%" . $searchterm . "%'");
				}
				
				$ofield = (isset($_COOKIE[$this -> pre . 'groupssorting'])) ? $_COOKIE[$this -> pre . 'groupssorting'] : 'order';
				$odir = (isset($_COOKIE[$this -> pre . 'groups' . $ofield . 'dir'])) ? $_COOKIE[$this -> pre . 'groups' . $ofield . 'dir'] : "ASC";
				$order = array($ofield, $odir);
			
				$perpage = (isset($_COOKIE[$this -> pre . 'groupsperpage'])) ? $_COOKIE[$this -> pre . 'groupsperpage'] : 10;
				
				$data = array();
				if (!empty($_GET['showall'])) {
					$wpfaqDb -> model = $wpfaqGroup -> model;
					$groups = $wpfaqDb -> find_all(false, "*", $order);
					$data[$wpfaqGroup -> model] = $groups;
					$data['Paginate'] = false;
				} else {
					$data = $this -> paginate($wpfaqGroup -> model, "*", $this -> pre . 'groups', $conditions, $searchterm, $perpage, $order);
				}
				
				$this -> render('groups' . DS . 'index', array('groups' => $data[$wpfaqGroup -> model], 'paginate' => $data['Paginate']), 'admin', true);
				break;
		}
	}
	
	function admin_questions() {
		global $wpdb, $wpfaqDb, $wpfaqField, $wpfaqQuestion, $wpfaqQuestionsQuestion, $wpfaqQuestionsGroup, $wpfaqGroup;
		$wpfaqDb -> model = $wpfaqQuestion -> model;
		
		//the method
		$method = (empty($_GET['method'])) ? preg_replace("/faqs\-questions\-/si", "", $_GET['page']) : $_GET['method'];
		
		switch ($method) {
			case 'save'			:
				$wpfaqDb -> model = $wpfaqGroup -> model;
				if (!$groups = $wpfaqDb -> find()) {
					$message = __('Please create a group first', $this -> plugin_name);
					$this -> redirect('?page=' . $this -> sections -> groups_save, "error", $message);
				}
			
				$wpfaqDb -> model = $wpfaqQuestion -> model;
				if (!empty($_POST)) {
					if ($wpfaqDb -> save($_POST, true)) {
						$message = __('Question has been successfully saved', $this -> plugin_name);
						$this -> redirect('?page=' . $this -> sections -> questions, 'message', $message);
					} else {
						$message = __('Question cannot be saved', $this -> plugin_name);
						$this -> render_error($message);
						$this -> render('questions' . DS . 'save', false, 'admin', true);
					}
				} else {
					$wpfaqDb -> model = $wpfaqQuestion -> model;					
					
					if (!empty($_GET['id'])) { 
						$wpfaqDb -> find(array('id' => $_GET['id'])); 
					}
					
					if (!empty($_GET['group_id'])) {
						$wpfaqQuestion -> data = false;
						$wpfaqQuestion -> data -> group_id = $_GET['group_id'];
						$wpfaqQuestion -> group_id = $_GET['group_id'];	
					}
						
					$this -> render('questions' . DS . 'save', false, 'admin', true);
				}
				break;
			case 'order'		:
				if (!empty($_GET['group_id'])) {
					$wpfaqDb -> model = $wpfaqGroup -> model;
					
					if ($group = $wpfaqDb -> find(array('id' => $_GET['group_id']))) {
						$wpfaqDb -> model = $wpfaqQuestion -> model;
						//$questions = $wpfaqDb -> find_all(array('group_id' => $group -> id), false, array('order', "ASC"));
						
						$query = "SELECT " . $wpdb -> prefix . $wpfaqQuestion -> table . ".id, " .
						$wpdb -> prefix . $wpfaqQuestion -> table . ".question, " .
						$wpdb -> prefix . $wpfaqQuestionsGroup -> table . ".order FROM " . $wpdb -> prefix . $wpfaqQuestion -> table . 
						" LEFT JOIN " . $wpdb -> prefix . $wpfaqQuestionsGroup -> table . " ON " . 
						$wpdb -> prefix . $wpfaqQuestion -> table . ".id = " . $wpdb -> prefix . $wpfaqQuestionsGroup -> table . ".question_id " .
						"WHERE " . $wpdb -> prefix . $wpfaqQuestionsGroup -> table . ".group_id = '" . $group -> id . "' " .
						"ORDER BY " . $wpdb -> prefix . $wpfaqQuestionsGroup -> table . ".order ASC";
						
						$questions = $wpdb -> get_results($query);						
						$this -> render('questions' . DS . 'order', array('group' => $group, 'questions' => $questions), 'admin', true);
					}
				} else {
					$wpfaqDb -> model = $wpfaqQuestion -> model;
					$questions = $wpfaqDb -> find_all(false, false, array('order', "ASC"));
					$this -> render('questions' . DS . 'order', array('questions' => $questions), 'admin', true);	
				}
				break;
			case 'delete'		:
				if (!empty($_GET['id'])) {
					$wpfaqDb -> model = $wpfaqQuestion -> model;
					
					if ($wpfaqDb -> delete($_GET['id'])) {
						$msg_type = 'message';
						$message = __('Question has been removed', $this -> plugin_name);
					} else {
						$msg_type = 'error';
						$message = __('Question cannot be removed', $this -> plugin_name);
					}
				} else {
					$msg_type = 'error';
					$message = __('No question was specified', $this -> plugin_name);
				}
				
				//redirect...
				$this -> redirect('?page=' . $this -> sections -> questions, $msg_type, $message);
				break;
			case 'related'	:
				if (!empty($_GET['id'])) {
					$wpfaqDb -> model = $wpfaqQuestion -> model;
					
					if ($question = $wpfaqDb -> find(array('id' => $_GET['id']))) {
						$wpfaqDb -> model = $wpfaqQuestion -> model;
						$questions = $wpfaqDb -> find_all();
						
						$wpfaqDb -> model = $wpfaqQuestionsQuestion -> model;
						$related = $wpfaqDb -> find_all(array('question_id' => $question -> id));
						
						$this -> render('questions' . DS . 'related', array('question' => $question, 'questions' => $questions, 'related' => $related), 'admin', true);		
					} else {
						$message = __('Question cannot be read', $this -> plugin_name);
						$this -> redirect('?page=' . $this -> sections -> questions, 'error', $message);
					}
				} else {
					$message = __('No question was specified', $this -> plugin_name);
					$this -> redirect('?page=' . $this -> sections -> questions, 'error', $message);
				}
				break;
			case 'mass'			:
				if (!empty($_POST['questions'])) {
					if (!empty($_POST['export'])) {
						$questionids = $_POST['questions'];
						$conditions = array();
						$conditions['id'] = "";
						$c = 1;
						
						foreach ($questionids as $questionid) {
							$conditions['id'] .= $questionid . "";
							
							if ($c < count($questionids)) { $conditions['id'] .= "' OR id = '"; }
							$c++;
						}
						
						$conditions['id'] .= "";
						
						$wpfaqDb -> model = $wpfaqQuestion -> model;
						if ($questions = $wpfaqDb -> find_all($conditions)) {
							$fieldsquery = "SELECT * FROM " . $wpdb -> prefix . $wpfaqField -> table . " ORDER BY `order` ASC";
							$fields = $wpdb -> get_results($fieldsquery);
												
							$data = "";
							$data .= '"' . __('ID', $this -> plugin_name) . '",';
							$data .= '"' . __('Question', $this -> plugin_name) . '",';
							$data .= '"' . __('Answer', $this -> plugin_name) . '",';
							$data .= '"' . __('Group', $this -> plugin_name) . '",';
							
							if (!empty($fields)) {
								foreach ($fields as $field) {
									$data .= '"' . $field -> title . '",';
								}
							}
							
							$data .= '"' . __('Created Date', $this -> plugin_name) . '",';
							$data .= '"' . __('Modified Date', $this -> plugin_name) . '"';
							$data .= "\r\n";
						
							foreach ($questions as $question) {
								$data .= '"' . $question -> id . '",';
								$data .= '"' . $question -> question . '",';
								$data .= '"' . $question -> answer . '",';
								$data .= '"' . $question -> group -> name . '",';
								
								if (!empty($fields)) {
									foreach ($fields as $field) {
										if (!empty($question -> fields[$field -> id])) {
											$data .= '"' . $question -> fields[$field -> id]['value'] . '",';
										} else {
											$data .= '" ",';
										}
									}
								}
								
								$data .= '"' . $question -> created . '",';
								$data .= '"' . $question -> modified . '"';
								$data .= "\r\n";
							}
							
							$fileName = $this -> pre . 'questions_' . date("Y-m-d", time()) . '.csv';
							$filePath = ABSPATH . 'wp-content' . DS . 'uploads' . DS;
							$fileFull = $filePath . $fileName;
							
							if ($fh = fopen($fileFull, "w")) {
								fwrite($fh, $data);
								fclose($fh);
								
								$msg_type = 'message';
								$message = count($questions) . ' ' . __('Questions have been exported, download your file here:', $this -> plugin_name) . ' <a href="' . rtrim(get_bloginfo('wpurl'), '/') . '/wp-content/uploads/' . $fileName . '">' . __('Download Questions CSV', $this -> plugin_name) . '</a>';
							} else {
								$msg_type = 'error';
								$message = __('File could not be opened for writing. Please check "wp-content/uploads/" permissions!', $this -> plugin_name);
							}
						} else {
							$msg_type = 'error';
							$message = __('No questions could be read.', $this -> plugin_name);
						}
						
						$this -> redirect($this -> referer, $msg_type, $message);
					} else {
						if (!empty($_POST['action'])) {
							$questions = $_POST['questions'];
							
							switch ($_POST['action']) {
								case 'delete'		:
									foreach ($questions as $id) {
										$wpfaqDb -> model = $wpfaqQuestion -> model;
										$wpfaqDb -> delete($id);
									}
									
									$msg_type = 'message';
									$message = __('Selected questions have been removed', $this -> plugin_name);
									break;
								case 'movetogroup'	:
									if (!empty($_POST['group_id'])) {								
										foreach ($questions as $id) {
											$wpfaqDb -> model = $wpfaqQuestion -> model;
											$wpfaqDb -> save_field('group_id', $_POST['group_id'], array('id' => $id));
										}
										
										$msg_type = 'message';
										$message = __('Selected questions have been successfully moved', $this -> plugin_name);
									} else {
										$msg_type = 'error';
										$message = __('No group was selected to move to', $this -> plugin_name);
									}
									break;
								case 'approved'		:
									foreach ($questions as $id) {
										$wpfaqDb -> model = $wpfaqQuestion -> model;
										$wpfaqDb -> save_field('approved', "Y", array('id' => $id));
									}
									
									$msg_type = 'message';
									$message = __('Selected questions have been marked as approved', $this -> plugin_name);
									break;
								case 'unapproved'	:
									foreach ($questions as $id) {
										$wpfaqDb -> model = $wpfaqQuestion -> model;
										$wpfaqDb -> save_field('approved', "N", array('id' => $id));
									}
									
									$msg_type = 'message';
									$message = __('Selected questions have been marked as unapproved', $this -> plugin_name);
									break;
							}
						} else {
							$msg_type = 'error';
							$message = __('No bulk action was selected', $this -> plugin_name);
						}
					}
				} else {
					$msg_type = 'error';
					$message = __('No questions have been selected', $this -> plugin_name);
				}
				
				$this -> redirect('?page=' . $this -> sections -> questions, $msg_type, $message);
				break;
			default				:
				$searchterm = (empty($_GET[$this -> pre . 'searchterm'])) ? false : $_GET[$this -> pre . 'searchterm'];
				$searchterm = (empty($_POST['searchterm'])) ? $searchterm : $_POST['searchterm'];
				$searchterm = strtolower($searchterm);
			
				if (!empty($_POST['searchterm'])) {
					$this -> redirect('?page=' . $this -> sections -> questions . '&' . $this -> pre . 'searchterm=' . urlencode($searchterm) . '');
				}
				
				$conditions = array();
				if (!empty($searchterm)) {
					$conditions = array('(question)' => "LIKE '%" . $searchterm . "%' OR LOWER(answer) LIKE '%" . $searchterm . "%'");
				}
				
				//per page parameter
				$perpage = (isset($_COOKIE[$this -> pre . 'questionsperpage'])) ? $_COOKIE[$this -> pre . 'questionsperpage'] : 10;
				
				//the $order parameter
				$ofield = (isset($_COOKIE[$this -> pre . 'questionssorting'])) ? $_COOKIE[$this -> pre . 'questionssorting'] : 'order';
				$odir = (isset($_COOKIE[$this -> pre . 'questions' . $ofield . 'dir'])) ? $_COOKIE[$this -> pre . 'questions' . $ofield . 'dir'] : "ASC";
				$order = array($ofield, $odir);
				
				if (!empty($_GET['approved'])) {
					$conditions['approved'] = $_GET['approved'];
				} elseif (isset($_COOKIE[$this -> pre . 'approved'])) {
					$approved = $_COOKIE[$this -> pre . 'approved'];					
					if (!empty($approved) && $approved != "all") {						
						$conditions['approved'] = $approved;
					}
				}
				
				$data = array();
				if (!empty($_GET['showall'])) {
					$wpfaqDb -> model = $wpfaqQuestion -> model;
					$questions = $wpfaqDb -> find_all(false, "*", $order);
					$data[$wpfaqQuestion -> model] = $questions;
					$data['Paginate'] = false;
					unset($_COOKIE[$this -> pre . 'approved']);
				} else {
					$data = $this -> paginate($wpfaqQuestion -> model, "*", $this -> pre . 'questions', $conditions, $searchterm, $perpage, $order);
				}
				
				$this -> render('questions' . DS . 'index', array('questions' => $data[$wpfaqQuestion -> model], 'paginate' => $data['Paginate']), 'admin', true);
				break;
		}
	}
	
	function admin_fields() {
		global $wpdb, $wpfaqDb, $wpfaqField;
		$wpfaqDb -> model = $wpfaqField -> model;
		
		//the method
		$method = (empty($_GET['method'])) ? preg_replace("/faqs\-fields\-/si", "", $_GET['page']) : $_GET['method'];
		
		switch ($method) {
			case 'save'				:			
				if (!empty($_POST)) {
					if ($wpfaqDb -> save($_POST, true)) {
						$message = __('Field has been successfully saved', $this -> plugin_name);
						$this -> redirect('?page=' . $this -> sections -> fields, 'message', $message);
					} else {
						$message = __('Field cannot be saved', $this -> plugin_name);
						$this -> render_error($message);
						$this -> render('fields' . DS . 'save', false, 'admin', true);
					}
				} else {
					if (!empty($_GET['id'])) { 
						$wpfaqDb -> find(array('id' => $_GET['id'])); 
					}
						
					$this -> render('fields' . DS . 'save', false, 'admin', true);
				}		
				break;	
			case 'delete'			:
				if (!empty($_GET['id'])) {
					$wpfaqDb -> model = $wpfaqField -> model;
					
					if ($wpfaqDb -> delete($_GET['id'])) {
						$msg_type = 'message';
						$message = __('Field has been removed', $this -> plugin_name);
					} else {
						$msg_type = 'error';
						$message = __('Field cannot be removed', $this -> plugin_name);
					}
				} else {
					$msg_type = 'error';
					$message = __('No field was specified', $this -> plugin_name);
				}
				
				//redirect...
				$this -> redirect('?page=' . $this -> sections -> fields, $msg_type, $message);
				break;	
			default					:
				$perpage = (isset($_COOKIE[$this -> pre . 'questionsperpage'])) ? $_COOKIE[$this -> pre . 'questionsperpage'] : 10;
				
				$conditions = array();
				$searchterm = false;
				
				$ofield = (isset($_COOKIE[$this -> pre . 'questionssorting'])) ? $_COOKIE[$this -> pre . 'questionssorting'] : 'order';
				$odir = (isset($_COOKIE[$this -> pre . 'questions' . $ofield . 'dir'])) ? $_COOKIE[$this -> pre . 'questions' . $ofield . 'dir'] : "ASC";
				$order = array($ofield, $odir);
				
				if (!empty($_GET['showall'])) {
				
				} else {
					$data = $this -> paginate($wpfaqField -> model, "*", $this -> sections -> fields, $conditions, $searchterm, $perpage, $order);
				}
				
				$this -> render('fields' . DS . 'index', array('fields' => $data[$wpfaqField -> model], 'paginate' => $data['Paginate']), 'admin', true);
				break;
		}
	}
	
	function admin_settings() {
		global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion;
	
		switch ($_REQUEST['method']) {
			case 'reset'				:				
				if ($this -> groups_resavepp() && $this -> questions_resavepp()) {
					$msg_type = 'message';
					$message = __('All FAQ group and question posts/pages have been resaved.', $this -> plugin_name);
				} else {
					$msg_type = 'error';
					$message = __('No FAQ groups are available.', $this -> plugin_name);
				}
				
				call_user_method('render_' . $msg_type, $this, $message);
				break;
			default						:
				if (!empty($_POST)) {
					unset($_POST['saveconfig']);
					
					foreach ($_POST as $option => $value) {						
						switch ($option) {
							case 'customcss'				:
							case 'customcsscode'			:
								if (!empty($_POST['customcss']) && $_POST['customcss'] == "Y") {
									$this -> update_option('customcss', "Y");
									$this -> update_option('customcsscode', $_POST['customcsscode']);
								} else {
									$this -> update_option('customcss', "N");	
								}
								break;
							default							:
								$this -> update_option($option, $value);	
								break;
						}
					}
					
					$this -> render_message(__('Configuration settings have been saved', $this -> plugin_name));
				}
				break;
		}
		
		$this -> render('settings', false, 'admin', true);
		return true;
	}
	
	function admin_support() {
		$this -> render('support', false, 'admin', true);
		return true;
	}
	
	function wpFaq() {
		$url = explode("&", $_SERVER['REQUEST_URI']);
		$this -> url = $url[0];
		if (!empty($_SERVER['HTTP_REFERER'])) { $this -> referer = $_SERVER['HTTP_REFERER']; }
	
		//register the plugin name and base.
		$this -> register_plugin($this -> name, __FILE__);
		register_activation_hook(plugin_basename(__FILE__), array($this, 'initialize_options'));
	}
}

//include the required helpers, models, etc...
require_once(ABSPATH . WPINC . DS . 'pluggable.php');
require_once(dirname(__FILE__) . DS . 'helpers' . DS . 'html.php');
require_once(dirname(__FILE__) . DS . 'helpers' . DS . 'db.php');
require_once(dirname(__FILE__) . DS . 'helpers' . DS . 'form.php');
require_once(dirname(__FILE__) . DS . 'helpers' . DS . 'metabox.php');
require_once(dirname(__FILE__) . DS . 'models' . DS . 'question.php');
require_once(dirname(__FILE__) . DS . 'models' . DS . 'questions_post.php');
require_once(dirname(__FILE__) . DS . 'models' . DS . 'questions_group.php');
require_once(dirname(__FILE__) . DS . 'models' . DS . 'group.php');
require_once(dirname(__FILE__) . DS . 'models' . DS . 'groups_post.php');
require_once(dirname(__FILE__) . DS . 'models' . DS . 'questions_question.php');
require_once(dirname(__FILE__) . DS . 'models' . DS . 'field.php');

//initialize the wpFaq class
$wpFaq = new wpFaq();

?>