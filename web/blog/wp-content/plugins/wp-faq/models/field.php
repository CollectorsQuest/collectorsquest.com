<?php

class wpfaqField extends wpFaqPlugin {
	
	var $model = 'wpfaqField';
	var $controller = 'fields';
	var $table = '';
	
	var $errors = array();
	var $data = array();
	
	var $fields = array(
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
	);
	
	function wpfaqField($data = array()) {
		global $wpfaqDb;
	
		$this -> table = $this -> pre . $this -> controller;
		$this -> check_table($this -> controller);
	
		if (!empty($data)) {
			foreach ($data as $dkey => $dval) {
				$this -> {$dkey} = stripslashes_deep($dval);
				
				switch ($dkey) {
					default			:
						//do nothing…
						break;
				}
			}
		}
		
		$wpfaqDb -> model = $this -> model;
		return true;
	}
	
	function defaults() {
		global $wpfaqHtml;
		
		$defaults = array(
			'required'		=>	"N",
			'errormessage'	=>	"",
			'created'		=>	$wpfaqHtml -> gen_date(),
			'modified'		=>	$wpfaqHtml -> gen_date()
		);
		
		return $defaults;
	}
	
	function slug_exists($slug) {
		global $wpfaqDb, $wpfaqHtml;
		$wpfaqDb -> model = $this -> model;
		
		if (!empty($slug)) {			
			if ($wpfaqDb -> find(array('slug' => $slug), array('id'), false, false, false)) {
				return true;
			}
		}
	
		return false;
	}
	
	function validate($data = array()) {
		global $wpdb, $wpfaqDb, $wpfaqHtml;
		$this -> errors = array();
		
		$data = (empty($data[$this -> model])) ? $data : $data[$this -> model];
		extract($data, EXTR_SKIP);
		
		if (!empty($id)) {
			$oldfieldquery = "SELECT id, title, slug FROM " . $wpdb -> prefix . $this -> table . " WHERE id = '" . $id . "' LIMIT 1";
			$oldfield = $wpdb -> get_row($oldfieldquery);
		}
		
		if (empty($title) || $title == "Enter field title here") { $this -> errors['title'] = __('Please fill in a title.', $this -> plugin_name); }
		elseif (strlen($wpfaqHtml -> sanitize($title, '_')) > 64) { $this -> errors['title'] = __('Title cannot be longer than 64 characters, please make it shorter.', $this -> plugin_name); }
		
		if (empty($slug)) { $this -> errors['slug'] = __('Please fill in a slug.', $this -> plugin_name); }
		elseif (empty($id) && empty($oldfield) && $this -> slug_exists($slug)) { $this -> errors['slug'] = __('Slug with this name already exists, please change it.', $this -> plugin_name); }
		elseif (!empty($oldfield) && $slug != $oldfield -> slug && $this -> slug_exists($slug)) { $this -> errors['slug'] = __('Slug with this name already exists, please change it.', $this -> plugin_name); }
		
		if (empty($fieldtype)) { $this -> errors['fieldtype'] = __('Please choose a field type.', $this -> plugin_name); }
		elseif ($fieldtype == "checkbox" || $fieldtype == "radio" || $fieldtype == "select") {
			if (empty($fieldoptions)) { $this -> errors['fieldoptions'] = __('Please enter field options, each on a newline.', $this -> plugin_name); }
			else {				
				$fieldoptions = explode("\n", $fieldoptions);
				$newoptions = array();
				
				if (!empty($fieldoptions)) {
					$n = 1;
									
					foreach ($fieldoptions as $option) {
						$option = trim($option);
						
						if (!empty($option)) {
							$newoptions[$n] = $option;
							$n++;
						}
					}
				}
				
				if (!empty($newoptions)) {
					$fieldoptions = serialize($newoptions);
				} else {
					$fieldoptions = '';
					$this -> errors['fieldoptions'] = __('Please fill in some options', $this -> name);
				}
				
				$this -> data -> fieldoptions = $fieldoptions;
			}
		}
		
		if (!empty($required) && $required == "Y" && (empty($errormessage) || $errormessage == "Enter error message here")) { $this -> errors['errormessage'] = __('Please fill in an error message to display.', $this -> plugin_name); }
		
		return $this -> errors;
	}
}

?>