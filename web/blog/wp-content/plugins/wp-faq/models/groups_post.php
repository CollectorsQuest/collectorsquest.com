<?php

class wpfaqGroupsPost extends wpFaqPlugin {
	
	var $name = 'wpfaqGroupsPost';
	var $model = 'wpfaqGroupsPost';
	var $controller = 'groupsposts';
	var $table;
	
	var $group_id = '';
	var $post_id = '';
	
	var $errors = array();
	var $data = array();

	var $fields = array(
		'group_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
		'post_id'			=>	"INT(11) NOT NULL DEFAULT '0'",
		'created'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'modified'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'key'				=>	"KEY `group_id` (`group_id`,`post_id`)",
	);
	
	function wpfaqGroupsPost($data = array()) {
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
			'group_id'		=>	0,
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
		
		if (empty($group_id)) { $this -> errors['group_id'] = __('No group was specified', $this -> plugin_name); }
		if (empty($post_id)) { $this -> errors['post_id'] = __('No post/page was specified', $this -> plugin_name); }
		
		return $this -> errors;
	}
	
	function check_group($group_id = '') {
		global $wpdb;
		
		if (!empty($group_id)) {
			if ($groupspost = $wpdb -> get_row("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "groupsposts` WHERE `group_id` = '" . $group_id . "'")) {
				$newgroupspost = $this -> init_class($this -> model, $groupspost);				
				return $newgroupspost -> post_id;
			}
		}
		
		return false;
	}
	
	function posttitle_by_group($group_id = '') {
		global $wpdb;
		
		if (!empty($group_id)) {
			if ($groupspost = $wpdb -> get_row("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "groupsposts` WHERE `group_id` = '" . $group_id . "'")) {
				$post = get_post($groupspost -> post_id);
				return $post -> post_title;
			}
		}
		
		return false;
	}
	
	function delete_by_group($group_id = '') {
		global $wpdb;
		
		if (!empty($group_id)) {
			$query = "DELETE FROM `" . $wpdb -> prefix . $this -> prefix . "groupsposts` WHERE `group_id` = '" . $group_id . "'";
		
			if ($wpdb -> query($query)) {
				return true;
			}
		}
		
		return false;
	}
	
	function save($data = array(), $validate = true) {
		global $wpdb;
		
		if ($validate == true) {
			if (empty($data['group_id'])) { $this -> errors[] = 'No group was specified'; }
			if (empty($data['post_id'])) { $this -> errors[] = 'No post was specified'; }
			if ($this -> check_group($data['group_id'])) { $this -> errors[] = 'Record for this group exists'; }
		}
		
		if (empty($this -> errors)) {
			$query = "INSERT INTO `" . $wpdb -> prefix . $this -> prefix . "groupsposts` (`group_id`, `post_id`) VALUES ('" . $data['group_id'] . "', '" . $data['post_id'] . "');";
		
			if ($wpdb -> query($query)) {
				return true;
			}
		}
		
		return false;
	}
}

?>