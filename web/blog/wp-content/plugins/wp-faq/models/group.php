<?php

class wpfaqGroup extends wpFaqPlugin {

	var $id = '';
	var $title = '';
	var $created = '0000-00-00 00:00:00';
	var $modified = '0000-00-00 00:00:00';
	var $post_id = 0;
	var $searchbox = 'Y';
	var $askbox = 'N';
	
	var $model = 'wpfaqGroup';
	var $controller = 'groups';
	var $table = '';
	
	var $errors = array();
	var $data = array();
	
	var $fields = array(
		'id'				=>	"INT(11) NOT NULL AUTO_INCREMENT",
		'order'				=>	"INT(11) NOT NULL DEFAULT '0'",
		'active'			=>	"ENUM('Y','N') NOT NULL DEFAULT 'N'",
		'name'				=>	"VARCHAR(100) NOT NULL DEFAULT ''",
		'pp'				=>	"ENUM('none','post','page') NOT NULL DEFAULT 'none'",
		'pp_id'				=>	"INT(11) NOT NULL DEFAULT '0'",
		'pp_parent'			=>	"INT(11) NOT NULL DEFAULT '0'",
		'pp_title'			=>	"VARCHAR(255) NOT NULL DEFAULT ''",
		'pp_categories'		=>	"TEXT NOT NULL",
		'adminnotify'		=>	"ENUM('Y','N') NOT NULL DEFAULT 'Y'",		//neha
		'email'				=>	"VARCHAR(150) NOT NULL DEFAULT ''",			//neha
		'searchbox'			=>	"ENUM('Y','N') NOT NULL DEFAULT 'Y'",
		'groupsmenu'		=>	"ENUM('Y','N') NOT NULL DEFAULT 'N'",
		'keywords'			=>	"TEXT NOT NULL",
		'askbox'			=>	"ENUM('Y','N') NOT NULL DEFAULT 'N'",
		'created'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'modified'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'key'				=>	"PRIMARY KEY (`id`)",
	);
	
	function wpfaqGroup($data = array()) {
		global $wpfaqDb, $wpfaqQuestion, $wpfaqGroupsPost;
	
		$this -> table = $this -> pre . $this -> controller;
		$this -> check_table($this -> controller);
	
		if (!empty($data)) {
			foreach ($data as $dkey => $dval) {
				$this -> {$dkey} = stripslashes_deep($dval);
				
				switch ($dkey) {
					default			:
						//do nothing...
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
			'active'		=>	"N",
			'pp'			=>	"none",
			'pp_id'			=>	0,
			'pp_parent'		=>	0,
			'searchbox'		=>	"N",
			'groupsmenu'	=>	"N",
			'keywords'		=>	"",
			'askbox'		=>	"N",
			'created'		=>	$wpfaqHtml -> gen_date(),
			'modified'		=>	$wpfaqHtml -> gen_date()
		);
		
		return $defaults;
	}
	
	function validate($data = array()) {
		$this -> errors = array();
		
		$data = (empty($data[$this -> model])) ? $data : $data[$this -> model];
		extract($data, EXTR_SKIP);
		
		if (empty($name)) { $this -> errors['name'] = __('Please fill in a name for this group', $this -> plugin_name); }
		
		if (empty($adminnotify)) { $this -> errors['adminnotify'] = __('Please specify whether admin notification is required', $this -> plugin_name); }
		elseif ($adminnotify == "Y" && !$this -> email_validate($email)) { $this -> errors['email'] = __('Please fill in correct email', $this -> plugin_name); } 
		

		if (empty($searchbox)) { $this -> errors['searchbox'] = __('Please specify whether a search box should be provided', $this -> plugin_name); }
		elseif ($searchbox == "Y" && empty($groupsmenu)) { $this -> errors['groupsmenu'] = __('Please choose whether or not to show a groups dropdown menu', $this -> plugin_name); }
		
		if (empty($askbox)) { $this -> errors['askbox'] = __('Please specify whether a submission box should be shown', $this -> plugin_name); }
		if (empty($keywords)) { $this -> errors['keywords'] = __('Please fill in at least one keyword', $this -> plugin_name); }
		
		if (empty($pp)) { $this -> errors['pp'] = __('No post/page action was specified', $this -> plugin_name); }
		elseif ($pp == "post" || $pp == "page") {
			if (empty($pp_title)) { $this -> errors['pp_title'] = __('Please fill in a title for this post/page', $this -> plugin_name); } 
		}
		
		return $this -> errors;
	}
	
	function get_title($group_id = '') {
		global $wpdb;
	
		if (!empty($group_id)) {
			if ($group = $wpdb -> get_row("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "groups` WHERE `id` = '" . $group_id . "'")) {
				$newgroup = new wpfaqGroup($group);
				return $newgroup -> name;
			}
		}
		
		return 'none';
	}
	
	function count() {
		global $wpdb;
		
		if ($count = $wpdb -> get_var("SELECT COUNT(`id`) FROM `" . $wpdb -> prefix . $this -> prefix . "groups`")) {
			return $count;
		} else {
			return 0;
		}
	}
	
	function select($group_id = '') {
		global $wpdb;
		$questionClass = $this -> init_class('Question');
		$groupClass = $this -> init_class('wpfaqGroup');
		$groups = $groupClass -> get_all();
		
		if (!empty($groups)) {
			$html = '<select class="widefat" name="Question[group_id]">';
			$html .= '<option value="">- Select Group -</option>';
			
			foreach ($groups as $group) {
				$selected = ($group -> id == $group_id) ? 'selected="selected"' : '';
				$html .= '<option ' . $selected . ' value="' . $group -> id . '">' . $group -> name . ' (' . $questionClass -> count_by_group($group -> id) . ' questions)</option>';
			}
			
			$html .= '</select>';
			
			return $html;
		} else {
			return false;
		}
	}
	
	function get($group_id = '') {
		global $wpdb;
		
		if (!empty($group_id)) {
			if ($group = $wpdb -> get_row("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "groups` WHERE `id` = '" . $group_id . "' LIMIT 1")) {
				return new wpfaqGroup($group);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function get_all() {
		global $wpdb;
		
		if ($groups = $wpdb -> get_results("SELECT * FROM `" . $wpdb -> prefix . $this -> prefix . "groups` ORDER BY `name` ASC")) {
			if (!empty($groups)) {
				$data = array();
				
				foreach ($groups as $group) {
					$data[] = new wpfaqGroup($group);
				}
				
				return $data;
			}
		}
		
		return false;
	}
	
	function save($data = array(), $validate = true) {
		global $wpdb;
	
		if (!empty($data)) {		
			if ($validate == true) {
				if (empty($data['name'])) { $this -> errors[] = 'Please fill in a name!'; }
				
				if (!empty($data['page'])) {
					if ($data['page'] == "Y") {
						if (empty($data['pagetitle'])) { $this -> errors[] = 'Please fill in a page title'; }
					}
				}
				
				if (empty($data['searchbox'])) { $this -> errors[] = 'Please choose whether you want a searchbox or not'; }
				if (empty($data['askbox'])) { $this -> errors[] = 'Please choose whether you want a user submission box or not'; }
			}
			
			if (empty($this -> errors)) {
				$nowdate = $this -> gen_date();
			
				if (empty($data['id'])) {
					$query = "INSERT INTO `" . $wpdb -> prefix . $this -> prefix . "groups` (`id`, `name`, `page`, `pagetitle`, `pageparent`, `searchbox`, `askbox`, `created`, `modified`) VALUES ('', '" . $data['name'] . "', '" . $data['page'] . "', '" . $data['pagetitle'] . "', '" . $data['pageparent'] . "', '" . $data['searchbox'] . "', '" . $data['askbox'] . "', '" . $nowdate . "', '" . $nowdate . "');";
				} else {
					$query = "UPDATE `" . $wpdb -> prefix . $this -> prefix . "groups` SET `name` = '" . $data['name'] . "', `page` = '" . $data['page'] . "', `pagetitle` = '" . $data['pagetitle'] . "', `pageparent` = '" . $data['pageparent'] . "', `searchbox` = '" . $data['searchbox'] . "', `askbox` = '" . $data['askbox'] . "', `modified` = '" . $nowdate . "' WHERE `id` = '" . $data['id'] . "'";
				}
				
				if ($wpdb -> query($query)) {
					$groupid = (empty($data['id'])) ? $wpdb -> insert_id : $data['id'];
					
					$groupspostClass = $this -> init_class('GroupsPost');
				
					if ($data['page'] == "Y") {					
						$pagedata = array(
							'post_title'		=>	$data['pagetitle'],
							'post_name'			=>	sanitize_title($data['pagetitle']),
							'post_status'		=>	'publish',
							'post_type'			=>	'page',
							'post_content'		=>	'{wpfaqgroup' . $groupid . '}',
							'post_parent'		=>	$data['pageparent'],
						);
					
						
						if ($post_id = $groupspostClass -> check_group($groupid)) {
							$pagedata['ID'] = $post_id;
						}
						
						if ($postid = wp_insert_post($pagedata)) {
							$gpdata = array(
								'group_id'		=>	$groupid,
								'post_id'		=>	$postid,
							);
						
							$groupspostClass -> save($gpdata, true);
						}
					} else {
						//check if the Group had a page before.
						if ($groupspostClass -> check_group($groupid)) {
							//if so...delete the page for this group.
							$groupspostClass -> delete_by_group($groupid);
						}
					}
				
					//return the ID of the Group record
					return $group_id;
				} else {
					$this -> data = $data;
					return false;
				}
			} else {
				$this -> data = $data;
				return false;
			}
		} else {
			$this -> data = $data;
			return false;
		}
	}
	
	function delete($id = '') {
		global $wpdb;
		
		$groupspostClass = $this -> init_class('GroupsPost');
		
		if (!empty($id)) {
			if ($wpdb -> query("DELETE FROM `" . $wpdb -> prefix . $this -> prefix . "groups` WHERE `id` = '" . $id . "'")) {
				$wpdb -> query("DELETE FROM `" . $wpdb -> prefix . $this -> prefix . "questions` WHERE `group_id` = '" . $id . "'");
				
				if ($post_id = $groupspostClass -> check_group($id)) {
					$groupspostClass -> delete_by_group($id);
					wp_delete_post($post_id);
				}
				
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function delete_array($groups = array()) {
		global $wpdb;
	
		if (!empty($groups)) {
			foreach ($groups as $group_id) {
				$this -> delete($group_id);
			}
			
			return true;
		} else {
			return false;
		}
	}
}

?>