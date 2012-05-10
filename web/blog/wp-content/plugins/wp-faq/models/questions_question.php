<?php

class wpfaqQuestionsQuestion extends wpFaqPlugin {
	
	var $name = 'wpfaqQuestionsQuestion';
	var $model = 'wpfaqQuestionsQuestion';
	var $controller = 'questionsquestions';
	var $table;
	
	var $question_id;
	var $rel_id;
	
	var $data = array();
	var $errors = array();
	
	var $fields = array(
		'question_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
		'rel_id'				=>	"INT(11) NOT NULL DEFAULT '0'",
		'created'				=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'modified'				=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'key'					=>	"KEY `question_id` (`question_id`,`rel_id`)",					
	);
	
	function wpfaqQuestionsQuestion($data = array()) {
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
			'rel_id'		=>	0,
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
		if (empty($rel_id)) { $this -> errors['rel_id'] = __('No related question was specified', $this -> plugin_name); }
		
		return $this -> errors;
	}
}

?>