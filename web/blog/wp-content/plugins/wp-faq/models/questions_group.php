<?php

class wpfaqQuestionsGroup extends wpFaqPlugin {
	
	var $name = 'wpfaqQuestionsGroup';
	var $model = 'wpfaqQuestionsGroup';
	var $controller = 'questionsgroups';
	var $table;
	
	var $question_id = '';
	var $group_id = '';
	
	var $errors = array();
	var $data = array();

	var $fields = array(
		'question_id'		=>	"INT(11) NOT NULL DEFAULT '0'",
		'group_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
		'order'				=>	"INT(11) NOT NULL DEFAULT '0'",
		'created'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'modified'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'key'				=>	"KEY `question_id` (`question_id`,`post_id`)",
	);
	
	function wpfaqQuestionsGroup($data = array()) {
		global $wpfaqDb;
	
		$this -> table = $this -> pre . $this -> controller;
		$this -> check_table($this -> controller);
		
		if (!empty($data)) {
			foreach ($data as $key => $val) {
				$this -> {$key} = stripslashes_deep($val);
			}
		}
		
		$wpfaqDb -> model = $this -> model;
		return true;
	}
	
	function defaults() {
		global $wpfaqHtml;
		
		$defaults = array(
			'question_id'	=>	0,
			'group_id'		=>	0,
			'order'			=>	0,
			'created'		=>	$wpfaqHtml -> gen_date(),
			'modified'		=>	$wpfaqHtml -> gen_date(),
		);
		
		return $defaults;
	}
	
	function validate($data = array()) {
		global $wpdb, $wpfaqDb, $wpfaqQuestion, $wpfaqGroup;
		$this -> errors = array();
		
		$data = (empty($data[$this -> model])) ? $data : $data[$this -> model];
		extract($data, EXTR_SKIP);
		
		if (empty($question_id)) { $this -> errors['question_id'] = __('No question was specified', $this -> plugin_name); }
		if (empty($group_id)) { $this -> errors['post_id'] = __('No post/page was specified', $this -> plugin_name); }
		
		if (empty($this -> errors)) {
			$wpfaqDb -> model = $this -> model;
			
			if ($wpfaqDb -> find(array('question_id' => $question_id, 'group_id' => $group_id))) {
				$this -> errors[] = __('This question/group association already exists.', $this -> plugin_name);	
			}
		}
		
		return $this -> errors;
	}
}

?>