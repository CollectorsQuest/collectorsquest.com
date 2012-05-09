<?php

class wpfaqQuestionsPost extends wpFaqPlugin {
	
	var $name = 'wpfaqQuestionsPost';
	var $model = 'wpfaqQuestionsPost';
	var $controller = 'questionsposts';
	var $table;
	
	var $question_id = '';
	var $post_id = '';
	
	var $errors = array();
	var $data = array();

	var $fields = array(
		'question_id'		=>	"INT(11) NOT NULL DEFAULT '0'",
		'post_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
		'created'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'modified'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'key'				=>	"KEY `question_id` (`question_id`,`post_id`)",
	);
	
	function wpfaqQuestionsPost($data = array()) {
		global $wpfaqDb;
	
		$this -> table = $this -> pre . $this -> controller;
		$this -> check_table($this -> controller);
		
		if (!empty($data)) {
			foreach ($data as $key => $val) {
				$this -> {$key} = $val;
			}
		}
		
		$wpfaqDb -> model = $this -> model;
		return true;
	}
	
	function defaults() {
		global $wpfaqHtml;
		
		$defaults = array(
			'question_id'	=>	0,
			'post_id'		=>	0,
			'created'		=>	$wpfaqHtml -> gen_date(),
			'modified'		=>	$wpfaqHtml -> gen_date(),
		);
		
		return $defaults;
	}
	
	function validate($data = array()) {
		$this -> errors = array();
		
		$data = (empty($data[$this -> model])) ? $data : $data[$this -> model];
		extract($data, EXTR_SKIP);
		
		if (empty($question_id)) { $this -> errors['question_id'] = __('No question was specified', $this -> plugin_name); }
		if (empty($post_id)) { $this -> errors['post_id'] = __('No post/page was specified', $this -> plugin_name); }
		
		return $this -> errors;
	}
	
	function check_question($question_id = '') {
		global $wpdb;
		
		if (!empty($question_id)) {
			if ($questionspost = $wpdb -> get_row("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "questionsposts` WHERE `question_id` = '" . $question_id . "'")) {
				$newquestionspost = $this -> init_class($this -> model, $questionspost);				
				return $newquestionspost -> post_id;
			}
		}
		
		return false;
	}
	
	function posttitle_by_question($question_id = '') {
		global $wpdb;
		
		if (!empty($question_id)) {
			if ($questionspost = $wpdb -> get_row("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "questionsposts` WHERE `question_id` = '" . $question_id . "'")) {
				$post = get_post($questionspost -> post_id);
				return $post -> post_title;
			}
		}
		
		return false;
	}
	
	function delete_by_question($question_id = '') {
		global $wpdb;
		
		if (!empty($question_id)) {
			$query = "DELETE FROM `" . $wpdb -> prefix . $this -> prefix . "questionsposts` WHERE `question_id` = '" . $question_id . "'";
		
			if ($wpdb -> query($query)) {
				return true;
			}
		}
		
		return false;
	}
	
	function save($data = array(), $validate = true) {
		global $wpdb;
		
		if ($validate == true) {
			if (empty($data['question_id'])) { $this -> errors[] = 'No question was specified'; }
			if (empty($data['post_id'])) { $this -> errors[] = 'No post was specified'; }
			if ($this -> check_question($data['question_id'])) { $this -> errors[] = 'Record for this question exists'; }
		}
		
		if (empty($this -> errors)) {
			$query = "INSERT INTO `" . $wpdb -> prefix . $this -> prefix . "questionsposts` (`question_id`, `post_id`) VALUES ('" . $data['question_id'] . "', '" . $data['post_id'] . "');";
		
			if ($wpdb -> query($query)) {
				return true;
			}
		}
		
		return false;
	}
}

?>