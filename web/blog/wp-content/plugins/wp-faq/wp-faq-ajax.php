<?php

if (!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }

$root = __FILE__;
for ($i = 0; $i < 4; $i++) $root = dirname($root);
require_once($root . DS . 'wp-config.php');
include_once(ABSPATH . 'wp-admin' . DS . 'admin-functions.php');

class wpFaqAjax extends wpFaqPlugin {

	var $url = '';
	
	var $safecommands = array(
		'search',
		'ask',
	);

	function wpFaqAjax($cmd = null, $id = null) {
		if (!in_array($cmd, $this -> safecommands)) {
			global $user_ID;
			
			if ($userdata = get_userdata($user_ID)) {
				$accesslevel_option = $this -> get_option('accesslevel');
				$accesslevel = (!empty($accesslevel_option)) ? $accesslevel_option : 10;

				if (!empty($userdata) && !empty($userdata -> user_level)) {			
					if ($userdata -> user_level < $accesslevel) {
						echo '<p>' . __('Sorry... you do not have the permission to access this page', $this -> plugin_name) . '</p>';
						exit();
					}
				}
			}
		}
		
		$this -> url = rtrim(get_bloginfo('wpurl'), '/') . '/wp-admin/admin.php?page=faqs';
		$this -> register_plugin('wp-faq', __FILE__);
	
		if (method_exists($this, $cmd)) {
			$this -> {$cmd}($id);
		} else {
			echo __('Class method "' . $cmd . '" does not exist', $this -> plugin_name);
		}
	}
	
	function search() {	
		header("Content-Type: text/html; charset=UTF-8", true);
	
		//global variables
		global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion;
		//unique number of the Ajax call
		$number = $_REQUEST['uninumber'];
		//set the Db model to Group
		$wpfaqDb -> model = $wpfaqGroup -> model;

		if (!empty($number)) {
			//if (!empty($_REQUEST[$number]['group_id']) && $group = $wpfaqDb -> find(array('id' => $_REQUEST[$number]['group_id']))) {
				$conditions = array('approved' => "= 'Y");
				
				if (!empty($_REQUEST[$number]['s'])) {
					$conditions['approved'] .= "' AND (`question` LIKE '%" . $_REQUEST[$number]['s'] . "%'";
					
					if ($this -> get_option('searchcontext') == "full") {
						$conditions['approved'] .= " OR `answer` LIKE '%" . $_REQUEST[$number]['s'] . "%'";
					}
					
					$conditions['approved'] .= " OR `id` LIKE '%" . $_REQUEST[$number]['s'] . "%')";
				}
				
				if (!empty($_REQUEST[$number]['group'])) { $conditions['AND `group_id`'] = $_REQUEST[$number]['group']; };
				
				$group = false;
				if (!empty($_REQUEST[$number]['group_id'])) {
					$wpfaqDb -> model = $wpfaqGroup -> model;
					$group = $wpfaqDb -> find(array('id' => $_REQUEST[$number]['group_id']));
				}
				
				//set the Db model to Question	
				$wpfaqDb -> model = $wpfaqQuestion -> model;
				$questions = $wpfaqDb -> find_all($conditions, false, array('order', "ASC"));
				
				$accactive = $this -> get_option('accactive');
				$this -> update_option('accactive', "1");			
				$this -> render('questions' . DS . 'loop', array('group' => $group, 'questions' => $questions, 'number' => $number), 'default', true);			
				$this -> update_option('accactive', $accactive);
			//}
		}
			
		return true;
	}
	
	function ask() {		
		global $wpdb, $wpfaqField, $wpfaqDb, $wpfaqGroup, $wpfaqQuestion, $user_ID;
		$number = $_REQUEST['uninumber'];
		$errors = false;
		$message = false;
		
		if (empty($_REQUEST)) { $errors[] = __('No data was posted', $this -> plugin_name); }
		if (empty($number)) { $errors[] = __('No identification number was passed, please try again', $this -> plugin_name); }
		else {
			if ($this -> get_option('requireemail') == "Y") {
				if (empty($_REQUEST[$number]['email'])) { $errors[] = __('Please fill in your email address', $this -> plugin_name); }
				elseif (!$this -> check_email($_REQUEST[$number]['email'])) { $errors[] = __('Please fill in a valid email address', $this -> plugin_name); }
			}
			
			if (empty($_REQUEST[$number]['question'])) { $errors[] = __('Please fill in a question', $this -> plugin_name); }
			if (empty($_REQUEST[$number]['group_id'])) { $errors[] = __('No FAQ group was specified', $this -> plugin_name); }
			else {
				$wpfaqDb -> model = $wpfaqGroup -> model;
				if (!$group = $wpfaqDb -> find(array('id' => $_REQUEST[$number]['group_id']))) { $errors[] = __('FAQ group cannot be read', $this -> plugin_name); }
			}
			
			if ($this -> use_captcha()) {
				$captcha = new ReallySimpleCaptcha();
				
				if (empty($_REQUEST[$number]['captcha_code'])) { $errors[] = __('Please fill in the code in the image.', $this -> plugin_name); }
				elseif (!$captcha -> check($_REQUEST[$number]['captcha_prefix'], $_REQUEST[$number]['captcha_code'])) { $errors[] = __('Your code does not match the code in the image.', $this -> plugin_name); }
			}
			
			/* Custom Fields */
			$fieldsquery = "SELECT id, title, slug, required, errormessage FROM " . $wpdb -> prefix . $wpfaqField -> table . " WHERE `required` = 'Y'";
			if ($fields = $wpdb -> get_results($fieldsquery)) {
				foreach ($fields as $field) {
					if (empty($_REQUEST[$number][$field -> slug])) {
						$errors[] = stripslashes($field -> errormessage);
					}
				}
			}
		}
		
		if (!$user_ID && $this -> get_option('askregistered') == "Y") { $errors[] = __('Please login before submitting questions', $this -> plugin_name); }
		
		if (empty($errors)) {
			$_REQUEST['content'] = __('Please fill in an answer', $this -> plugin_name);
		
			$data = array(
				'wpfaqQuestion'		=>	array(
					'question'			=>	$_REQUEST[$number]['question'],
					'answer'			=>	__('Please fill in an answer', $this -> plugin_name),
					'approved'			=>	"N",
					'email'				=>	$_REQUEST[$number]['email'],
					'group_id'			=>	$_REQUEST[$number]['group_id'],
					'order'				=>	"0",
				),	
			);
			
			/* Custom Fields */
			$fieldsquery = "SELECT * FROM " . $wpdb -> prefix . $wpfaqField -> table . "";
			if ($fields = $wpdb -> get_results($fieldsquery)) {
				foreach ($fields as $field) {
					if (!empty($_REQUEST[$number][$field -> slug]) || $_REQUEST[$number][$field -> slug] == "0") {
						$fieldvalue = $_REQUEST[$number][$field -> slug];
						$data['wpfaqQuestion'][$field -> slug] = $fieldvalue;
					}
				}
			}
			
			$wpfaqDb -> model = $wpfaqQuestion -> model;
			if ($wpfaqDb -> save($data, true)) {
				$question = $wpfaqDb -> find(array('id' => $wpfaqQuestion -> data -> id));
				
				if ($this -> get_option('adminnotify') == "Y") {
					$to = $this -> get_option('adminemail');					
					$subject = __('New FAQ Question', $this -> plugin_name);
					$email = $this -> render('question', array('question' => $question), 'email', false);
					$headers = 'Content-Type: text/html; charset="UTF-8"' . "\r\n";
					$this -> execute_mail($to, $subject, $email, $headers);	

					if (!empty($group -> adminnotify) && $group -> adminnotify == "Y")	//neha
					{
						if($group -> email != $to)
						{
							$to = $group->email;
							$subject = __('New FAQ Question', $this -> plugin_name);
							$email = $this -> render('question', array('question' => $question), 'email', false);
							$headers = 'Content-Type: text/html; charset="UTF-8"' . "\r\n";
							$this -> execute_mail($to, $subject, $email, $headers);
						}
					}
				}
				
				
				if (!empty($_REQUEST[$number]['email'])) {
					$to = $_REQUEST[$number]['email'];	
					$subject = __('Question Asked', $this -> plugin_name);
					$email = $this -> render('ask', array('question' => $question), 'email', false);
					$headers = 'Content-Type: text/html; charset="UTF-8"' . "\r\n";
					$this -> execute_mail($to, $subject, $email, $headers);
				}
			
				$_REQUEST[$number] = false;				
				$message = __('Your question has been submitted for answering', $this -> plugin_name);
			} else {
				$errors[] = __('Your question cannot be saved. Please try again', $this -> plugin_name);
			}
		}
		
		$_POST = $_REQUEST;
		
		$this -> render('askbox', array('number' => $number, 'group' => $group, 'errors' => $errors, 'message' => $message), 'default', true);		
		return true;
	}
	
	function questions_by_group() {	
		if (!empty($_REQUEST['group_id'])) {
			global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion;			
			$wpfaqDb -> model = $wpfaqQuestion -> model;
			
			if ($questions = $wpfaqDb -> find_all(array('group_id' => $_REQUEST['group_id']), false, array('question', "ASC"))) {
				foreach ($questions as $question) {
					?><option value="<?php echo $question -> id; ?>"><?php echo $question -> question; ?></option><?php
				}
			}
		}
		
		return false;
	}
	
	function groups_order() {
		global $wpfaqDb, $wpfaqGroup;
	
		if (!empty($_REQUEST)) {
			if (!empty($_REQUEST['item'])) {
				foreach ($_REQUEST['item'] as $order => $group_id) {
					$wpfaqDb -> model = $wpfaqGroup -> model;
					$wpfaqDb -> save_field('order', $order, array('id' => $group_id));
				}
				
				$message = __('Groups have been ordered', $this -> plugin_name);
			} else {
				$message = __('No sortable items are available', $this -> plugin_name);
			}
		} else {
			$message = __('No data was posted', $this -> plugin_name);
		}
		
		if (!empty($message)) { ?><p class="<?php echo $this -> pre; ?>error"><?php echo $message; ?></p><?php }
		return false;
	}
	
	function questions_order($group_id = null) {
		global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion, $wpfaqQuestionsGroup;
	
		if (!empty($_REQUEST)) {
			if (!empty($group_id)) {
				if (!empty($_REQUEST['item'])) {					
					foreach ($_REQUEST['item'] as $order => $question_id) {
						//$wpfaqDb -> model = $wpfaqQuestion -> model;
						//$wpfaqDb -> save_field('order', $order, array('id' => $question_id));
						
						$wpfaqDb -> model = $wpfaqQuestionsGroup -> model;
						$wpfaqDb -> save_field('order', $order, array('question_id' => $question_id, 'group_id' => $group_id));
					}
					
					$message = __('Questions have been ordered.', $this -> plugin_name);
				} else {
					$message = __('No sortable items are available.', $this -> plugin_name);
				}
			} else {
				if (!empty($_REQUEST['item'])) {
					foreach ($_REQUEST['item'] as $order => $question_id) {
						$wpfaqDb -> model = $wpfaqQuestion -> model;
						$wpfaqDb -> save_field('order', $order, array('id' => $question_id));	
					}
					
					$message = __('Questions have been ordered.', $this -> plugin_name);
				}
				
				//$message = __('No group was specified', $this -> plugin_name);
			}
		} else {
			$message = __('No data was posted', $this -> plugin_name);
		}
		
		if (!empty($message)) { ?><p class="<?php echo $this -> pre; ?>error"><?php echo $message; ?></p><?php }
		
		return false;
	}
	
	function questions_related() {		
		if (!empty($_REQUEST['id'])) {
			global $wpfaqDb, $wpfaqQuestionsQuestion;
			$question_id = $_REQUEST['id'];
			
			$wpfaqDb -> model = $wpfaqQuestionsQuestion -> model;
			$wpfaqDb -> delete_all(array('question_id' => $question_id));
		
			if (!empty($_REQUEST['related'])) {			
				foreach ($_REQUEST['related'] as $key => $related_id) {
					if (is_numeric($related_id)) {
						//$data = array($wpfaqQuestionsQuestion -> model => array('question_id' => $question_id, 'rel_id' => $related_id));
						$data = array('question_id' => $question_id, 'rel_id' => $related_id);
						$wpfaqDb -> model = $wpfaqQuestionsQuestion -> model;
						$wpfaqDb -> save($data, true);
					}
				}
				
				_e('Related questions have been updated', $this -> plugin_name);
			}
		}
		
		return false;
	}
}

$cmd = $_GET['cmd'];
$id = $_GET['id'];
$wpFaqAjax = new wpFaqAjax($cmd, $id);

?>