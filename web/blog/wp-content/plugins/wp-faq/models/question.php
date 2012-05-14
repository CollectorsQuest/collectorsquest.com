<?php

class wpfaqQuestion extends wpFaqPlugin {

	var $model = 'wpfaqQuestion';
	var $controller = 'questions';
	var $table = '';
	var $id = '';
	var $question = '';
	var $answer = '';
	var $group_id = '';
	var $created = '0000-00-00 00:00:00';
	var $modified = '0000-00-00 00:00:00';
	var $errors = array();
	var $data = array();
	
	var $fields = array(
		'id'			=>	"INT(11) NOT NULL AUTO_INCREMENT",
		'question'		=>	"TEXT NOT NULL",
		'answer'		=>	"LONGTEXT NOT NULL",
		'approved'		=>	"ENUM('Y','N') NOT NULL DEFAULT 'N'",
		'group_id'		=>	"INT(11) NOT NULL DEFAULT '0'",
		'order'			=>	"INT(11) NOT NULL DEFAULT '0'",
		'pp'			=>	"ENUM('none','post','page') NOT NULL DEFAULT 'none'",
		'pp_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
		'pp_parent'		=>	"INT(11) NOT NULL DEFAULT '0'",
		'pp_title'		=>	"VARCHAR(255) NOT NULL DEFAULT ''",
		'pp_categories'	=>	"TEXT NOT NULL",
		'pp_comments'	=>	"ENUM('open','closed') NOT NULL DEFAULT 'closed'",
		'email'			=>	"VARCHAR(150) NOT NULL DEFAULT ''",
		'created'		=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'modified'		=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'key'			=>	"PRIMARY KEY (`id`)",
	);
	
	function wpfaqQuestion($data = array()) {
		global $wpdb, $wpfaqField, $wpfaqDb, $wpfaqGroup;
	
		$this -> table = $this -> pre . $this -> controller;
		$this -> check_table('questions');
	
		if (!empty($data)) {
			foreach ($data as $dkey => $dval) {
				$this -> {$dkey} = stripslashes_deep($dval);
				
				switch ($dkey) {
					case 'group_id'			:
						$wpfaqDb -> model = $wpfaqGroup -> model;
						$this -> group = $wpfaqDb -> find(array('id' => $dval));
						break;
				}
			}
			
			/* Custom Fields */
			$this -> fields = array();
			$fieldsquery = "SELECT * FROM " . $wpdb -> prefix . $wpfaqField -> table . " ORDER BY `order` ASC";
			if ($fields = $wpdb -> get_results($fieldsquery)) {
				foreach ($fields as $field) {
					if (!empty($this -> {$field -> slug}) || $this -> {$field -> slug} == "0") {
						switch ($field -> fieldtype) {
							case 'radio'				:
							case 'select'				:
								$fieldoptions = maybe_unserialize($field -> fieldoptions);
								$value = stripslashes_deep($fieldoptions[($this -> {$field -> slug})]);
								break;
							case 'checkbox'				:
								$fieldoptions = maybe_unserialize($field -> fieldoptions);
								$fieldvalues = maybe_unserialize($this -> {$field -> slug});
								
								if (!empty($fieldvalues) && is_array($fieldvalues)) {
									$value = "";
								
									foreach ($fieldvalues as $fieldvalue) {
										$value .= "&raquo; " . stripslashes($fieldoptions[$fieldvalue]) . "\r\n";
									}
								}
								break;
							case 'text'					:
							case 'textarea'				:
							default						:
								$value = stripslashes($this -> {$field -> slug});
								break;
						}
					
						$this -> fields[$field -> id] = array(
							'id'					=>	$field -> id,
							'slug'					=>	$field -> slug,
							'title'					=>	$field -> title,
							'value'					=>	$value,
						);
					}
				}
			}
		}
		
		$wpfaqDb -> model = "wpfaqQuestion";
		return true;
	}
	
	function defaults() {
		global $wpfaqHtml;
		
		$defaults = array(
			'pp'			=>	"none",
			'pp_id'			=>	0,
			'approved'		=>	"Y",
			'group_id'		=>	0,
			'email'			=>	"",
			'created'		=>	$wpfaqHtml -> gen_date(),
			'modified'		=>	$wpfaqHtml -> gen_date(),
		);
		
		return $defaults;
	}
	
	function validate($data = array()) {
		$this -> errors = array();
		
		$data = (empty($data[$this -> model])) ? $data : $data[$this -> model];
		extract($data, EXTR_SKIP);
		
		if ($this -> get_option('requireemail') == "Y") {
			//if (empty($email)) { $this -> errors['email'] = __('Please fill in an email address', $this -> plugin_name); }
			//elseif (!$this -> check_email($email)) { $this -> errors['email'] = __('Please fill in a valid email address', $this -> plugin_name); }
		}
		
		if (empty($question)) { $this -> errors['question'] = __('Please fill in a question', $this -> plugin_name); }
		if (empty($answer)) { $this -> errors['answer'] = __('Please fill in an answer', $this -> plugin_name); }
		if (empty($group_id)) { $this -> errors['group_id'] = __('Please choose a group', $this -> plugin_name); }
		
		if (empty($pp)) {
			$this -> errors['pp'] = __('Please select whether or not a post/page should be created', $this -> plugin_name);	
		} else {
			if ($pp == "post") {
				if (empty($pp_title)) { $this -> errors['pp_title'] = __('Please fill in a post/page title', $this -> plugin_name); }	
			} elseif ($pp == "page") {
				if (empty($pp_title)) { $this -> errors['pp_title'] = __('Please fill in a post/page title', $this -> plugin_name); }
				if (empty($pp_parent) && $pp_parent != "0") { $this -> errors['pp_parent'] = __('Please select a parent page', $this -> plugin_name); }
			}
		}
		
		return $this -> errors;
	}
	
	function count() {
		global $wpdb;
		
		if ($count = $wpdb -> get_var("SELECT COUNT(`id`) FROM `" . $wpdb -> prefix . $this -> prefix . "questions`")) {
			return $count;
		} else {
			return 0;
		}
	}
	
	function search($searchterm = '', $searchgroup = 'all') {
		global $wpdb;
	
		if (!empty($searchterm)) {
			$searchterm = strtolower($searchterm);
		
			if ($searchgroup != "all") {
				$groupClass = $this -> init_class('wpfaqGroup');
				
				if ($group = $groupClass -> get($searchgroup)) {
					$query = "SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "questions` WHERE (`question` LIKE '%" . $searchterm . "%' OR `answer` LIKE '%" . $searchterm . "%') AND (`group_id` = '" . $searchgroup . "') ORDER BY `order` ASC";
				} else {
					return false;
				}
			} else {
				$query = "SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "questions` WHERE `question` LIKE '%" . $searchterm . "%' OR `answer` LIKE '%" . $searchterm . "%' ORDER BY `order` ASC";
			}
			
			if (!empty($query)) {
				if ($questions = $wpdb -> get_results($query)) {
					if (!empty($questions)) {
						$data = array();
						
						foreach ($questions as $question) {
							$data[] = new Question($question);
						}
						
						return $data;
					}
				}
			}
		}
		
		return false;
	}
	
	function save($data = array(), $validate = true) {
		global $wpdb;
		
		if (!empty($data)) {
			if ($validate == true) {
				if (empty($data['question'])) { $this -> errors[] = 'Please fill in a question'; }
				if (empty($data['answer'])) { $this -> errors[] = 'Please fill in an answer'; }
				if (empty($data['approved'])) { $this -> errors[] = 'Please select approved status'; }
				if (empty($data['group_id'])) { $this -> errors[] = 'Please select a group'; }
			}
			
			if (empty($this -> errors)) {
				$nowdate = $this -> gen_date();
			
				if (!empty($data['id'])) {
					$query = "UPDATE `" . $wpdb -> prefix . $this -> prefix . "questions` SET `question` = '" . $data['question'] . "', `answer` = '" . $data['answer'] . "', `approved` = '" . $data['approved'] . "', `group_id` = '" . $data['group_id'] . "', `modified` = '" . $nowdate . "' WHERE `id` = '" . $data['id'] . "'";
				} else {
					$query = "INSERT INTO `" . $wpdb -> prefix . $this -> prefix . "questions` (`id`, `question`, `answer`, `approved`, `group_id`, `created`, `modified`) VALUES ('', '" . $data['question'] . "', '" . $data['answer'] . "', '" . $data['approved'] . "', '" . $data['group_id'] . "', '" . $nowdate . "', '" . $nowdate . "')";
				}
				
				if ($wpdb -> query($query)) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function save_field($field = '', $value = '', $id = '') {
		global $wpdb;
		
		$question_id = (empty($id)) ? $this -> id : $id;
		
		if (!empty($field)) {
			$query = "UPDATE `" . $wpdb -> prefix . $this -> prefix . "questions` SET `" . $field . "` = '" . $value . "' WHERE `id` = '" . $question_id . "' LIMIT 1";
		
			if ($wpdb -> query($query)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function get_all() {
		global $wpdb;
		
		if ($questions = $wpdb -> get_results("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix.  "questions` ORDER BY `order` ASC")) {
			if (!empty($questions)) {
				$data = array();
				
				foreach ($questions as $question) {
					$data[] = new Question($question);
				}
				
				return $data;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function get_by_group($group_id = '') {
		global $wpdb, $wpfaqDb, $wpfaqGroup, $wpfaqQuestion;
		
		if (!empty($group_id)) {
			$wpfaqDb -> model = $wpfaqGroup -> model;
		
			if ($group = $wpfaqDb -> find(array('id' => $group_id))) {
				$wpfaqDb -> model = $wpfaqQuestion -> model;
			
				if ($questions = $wpfaqDb -> find_all(array('group_id' => $group -> id, 'approved' => "Y"), "*", array('order', "ASC"))) {
					if (!empty($questions)) {
						$data = array();
						
						foreach ($questions as $question) {
							$data[] = $this -> init_class('wpfaqQuestion', $question);
						}
						
						return $data;
					}
				}
			}
		}
		
		return false;
	}
	
	function get($question_id = '') {
		global $wpdb;
		
		if (!empty($question_id)) {
			if ($question = $wpdb -> get_row("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "questions` WHERE `id` = '" . $question_id . "'")) {
				return new Question($question);
			}
		}
		
		return false;
	}
	
	function count_pending() {
		global $wpdb;
		
		if ($count = $wpdb -> get_var("SELECT COUNT(*) FROM `" . $wpdb -> prefix . $this -> prefix . "questions` WHERE `approved` = 'N'")) {
			return $count;
		} else {
			return 0;
		}
	}
	
	function count_by_group($group_id = '') {
		global $wpdb;
	
		if (!empty($group_id)) {
			if ($count = $wpdb -> get_var("SELECT COUNT(`id`) FROM `" . $wpdb -> prefix . $this -> prefix . "questions` WHERE `group_id` = '" . $group_id . "'")) {
				return $count;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	function delete($question_id = '') {
		global $wpdb;
	
		if (!empty($question_id)) {
			if ($wpdb -> query("DELETE FROM `" . $wpdb -> prefix . $this -> prefix . "questions` WHERE `id` = '" . $question_id . "' LIMIT 1")) {
				return true;
			}
		}
		
		return false;
	}
	
	function delete_array($questions = array()) {
		global $wpdb;
		
		if (!empty($questions)) {
			foreach ($questions as $id) {
				$wpdb -> query("DELETE FROM `" . $wpdb -> prefx . $this -> prefix . "questions` WHERE `id` = '" . $id . "' LIMIT 1");
			}
			
			return true;
		}
		
		return false;
	}
}

?>