<?php

class wpfaqDbHelper extends wpFaqPlugin {

	var $name = 'Db';
	var $model = '';
	
	function wpfaqDbHelper() {
		return true;
	}
	
	function count($conditions = array()) {
		global $wpdb, ${$this -> model};
		$count = 0;
		
		if (!empty($this -> model)) {
			$query = "SELECT COUNT(`id`) FROM `" . $wpdb -> prefix . "" . ${$this -> model} -> table . "`";
			
			if (!empty($conditions) && is_array($conditions)) {
				$query .= " WHERE";
				$c = 1;
				
				foreach ($conditions as $ckey => $cval) {
					if (!empty($cval) || $cval == "0") {
						$query .= " `" . $ckey . "` = '" . $cval . "'";
					}
					
					if ($c < count($conditions)) {
						$query .= " AND";
					}
					
					$c++;
				}
			}
			
			if ($newcount = $wpdb -> get_var($query)) {
				$count = $newcount;
			}
		}
		
		return $count;
	}
	
	function find($conditions = array(), $fields = "*", $order = array('modified', "DESC"), $assign = true) {
		global $wpdb, ${$this -> model};
		
		$newfields = (!empty($fields) && (is_array($fields) || is_object($fields))) ? implode(", ", $fields) : $fields;
		$query = "SELECT " . $newfields . " FROM `" . $wpdb -> prefix . "" . ${$this -> model} -> table . "`";
		
		if (!empty($conditions) && is_array($conditions)) {
			$query .= " WHERE";
			$c = 1;
			
			foreach ($conditions as $ckey => $cval) {
				if (!empty($cval) || $cval == "0") {
					$query .= " `" . $ckey . "` = '" . $cval . "'";
					
					if ($c < count($conditions)) {
						$query .= " AND";
					}
				}
				
				$c++;
			}
		}
		
		$query .= " LIMIT 1";
		
		if ($record = $wpdb -> get_row($query)) {
			if (!empty($record)) {
				$data = $this -> init_class(${$this -> model} -> model, $record);
				
				if ($assign == true) {
					${$this -> model} -> data = $data;
				}

				return $data;
			}
		}
		
		return false;
	}
	
	function find_all($conditions = array(), $fields = false, $order = array('modified', "DESC"), $limit = false, $assign = false) {
		global $wpdb, ${$this -> model};
		
		$fields = "*";
		$query = "SELECT " . $fields . " FROM `" . $wpdb -> prefix . "" . ${$this -> model} -> table . "`";
		
		if (!empty($conditions) && is_array($conditions)) {
			$query .= " WHERE";
			$c = 1;
			
			foreach ($conditions as $ckey => $cval) {
				if ((!empty($cval) && $cval !== false && $cval != "") || $cval === "0") {
					if (eregi("LIKE", $cval)) {
						$query .= " " . $ckey . " " . $cval . "";
					} elseif (eregi("\!\=", $cval)) {
						$query .= " " . $ckey . " " . $cval . "";
					} else {
						$query .= " " . $ckey . " = '" . $cval . "'";
					}
					
					if ($c < count($conditions) && !eregi("OR", $cval)) {
						$query .= " AND";
					}
				}
				
				$c++;
			}
		}
		
		$order = (empty($order)) ? array('id', "DESC") : $order;
		list($ofield, $odir) = $order;
		$query .= " ORDER BY `" . $ofield . "` " . $odir . "";
		$query .= (empty($limit)) ? '' : " LIMIT " . $limit . "";
		
		if ($records = $wpdb -> get_results($query)) {
			if (!empty($records)) {
				$data = array();
			
				foreach ($records as $record) {
					$data[] = $this -> init_class(${$this -> model} -> model, $record);
				}
				
				if ($assign == true) {
					${$this -> model} -> data = $data;
				}
				
				return $data;
			}
		}
		
		return false;
	}
	
	function delete($record_id = null) {
		global $wpdb, ${$this -> model}, $wpfaqHtml, $wpfaqField, $wpfaqGroup, $wpfaqQuestion, $wpfaqGroupsPost, $wpfaqQuestionsGroup;
		
		if (!empty($record_id) && $record = $this -> find(array('id' => $record_id))) {
			$query = "DELETE FROM `" . $wpdb -> prefix . "" . ${$this -> model} -> table . "` WHERE `id` = '" . $record_id . "' LIMIT 1";
			
			if ($wpdb -> query($query)) {
				switch (${$this -> model} -> model) {
					case 'wpfaqField'				:
						$this -> delete_field($wpfaqField -> table, $record -> slug);
						break;
					case 'wpfaqGroup'				:
						//delete all the Questions of this Group
						$this -> model = $wpfaqQuestion -> model;
						$this -> delete_all(array('group_id' => $record_id));
						
						$this -> model = $wpfaqGroupsPost -> model;
						if ($groupsposts = $this -> find_all(array('group_id' => $record_id))) {
							foreach ($groupsposts as $gp) {
								wp_delete_post($gp -> post_id);
							}
						}
						
						wp_delete_post($record -> pp_id);
						
						$this -> model = $wpfaqGroupsPost -> model;
						$this -> delete_all(array('group_id' => $record_id));
						
						$this -> model = $wpfaqQuestionsGroup -> model;
						$this -> delete_all(array('group_id' => $record_id));
						break;
					case 'wpfaqQuestion'			:
						global $wpfaqQuestionsQuestion, $wpfaqQuestionsPost;
						
						$this -> model = $wpfaqQuestionsQuestion -> model;
						$this -> delete_all(array('question_id' => $record_id));
						
						$this -> model = $wpfaqQuestionsPost -> model;
						if ($questionsposts = $this -> find_all(array('question_id' => $record_id))) {
							foreach ($questionsposts as $qp) {
								$this -> model = $wpfaqQuestionsPost -> model;
								$this -> delete($qp -> id);
								wp_delete_post($qp -> post_id);
							}
						}
						
						$this -> model = $wpfaqQuestionsGroup -> model;
						$this -> delete_all(array('question_id' => $record_id));
						break;
				}
				
				return true;
			}
		}
		
		return false;
	}
	
	function delete_all($conditions = array()) {
		global $wpdb, ${$this -> model};
		
		if (!empty($conditions)) {
			$query = "DELETE FROM `" . $wpdb -> prefix . "" . ${$this -> model} -> table . "` WHERE";
			$c = 1;
			
			foreach ($conditions as $ckey => $cval) {
				$query .= " `" . $ckey . "` = '" . $cval . "'";
				
				if ($c < count($conditions)) {
					$query .= " AND";
				}
				
				$c++;
			}
			
			if ($wpdb -> query($query)) {
				return true;
			}
		}
	
		return false;
	}
	
	function field($field = null, $conditions = array()) {
		if (!empty($this -> model)) {
			global $wpdb, ${$this -> model};

			if (!empty($field)) {			
				if (!empty($conditions) && is_array($conditions)) {
					$query = "SELECT `" . $field . "` FROM `" . $wpdb -> prefix . "" . ${$this -> model} -> table . "` WHERE";
					$c = 1;
					
					foreach ($conditions as $ckey => $cval) {
						if (eregi("LIKE", $cval)) {
							$query .= " `" . $ckey . "` " . $cval . "";
						} else {
							$query .= " `" . $ckey . "` = '" . $cval . "'";
						}
						
						if ($c < count($conditions)) {
							$query .= " AND";
						}
						
						$c++;
					}
					
					if ($value = $wpdb -> get_var($query)) {
						return $value;
					}
				}
			}
		}
		
		return false;
	}
	
	function save_field($field = null, $value = null, $conditions = array()) {
		if (!empty($this -> model)) {
			global $wpdb, ${$this -> model};
			
			if (!empty($field)) {
				$query = "UPDATE `" . $wpdb -> prefix . "" . ${$this -> model} -> table . "` SET `" . $field . "` = '" . $value . "'";
				
				if (!empty($conditions) && is_array($conditions)) {
					$query .= " WHERE";
					$c = 1;
					
					foreach ($conditions as $ckey => $cval) {
						$query .= " `" . $ckey . "` = '" . $cval . "'";
						
						if ($c < count($conditions)) {
							$query .= " AND";
						}
						
						$c++;
					}
				}
				
				if ($wpdb -> query($query)) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	function save($data = array(), $validate = true) {
		global $wpdb, $user_ID, ${$this -> model}, $wpfaqGroupsPost, $wpfaqQuestionsPost, $wpfaqQuestionsGroup, $wpfaqGroup, $wpfaqQuestion, $wpfaqHtml, $wpfaqAuth;
		
		$defaults = (method_exists(${$this -> model}, 'defaults')) ? ${$this -> model} -> defaults() : false;
		$data = (empty($data[${$this -> model} -> model])) ? $data : $data[${$this -> model} -> model];
		
		switch ($this -> model) {
			case 'wpfaqQuestion'		:
				$data['answer'] = $_REQUEST['content'];
				break;
		}
		
		$r = wp_parse_args($data, $defaults);
		${$this -> model} -> data = $wpfaqHtml -> array_to_object($r);
		
		if ($validate == true) {
			if (method_exists(${$this -> model}, 'validate')) {
				${$this -> model} -> validate($r);
			}
		}
		
		$post_status = 'publish';
		
		if (empty(${$this -> model} -> errors)) {
			switch ($this -> model) {
				case 'wpfaqField'			:
					if (!empty(${$this -> model} -> data -> id)) {
						$oldfieldquery = "SELECT id, title, slug FROM " . $wpdb -> prefix . ${$this -> model} -> table . " WHERE id = '" . ${$this -> model} -> data -> id . "' LIMIT 1";
						$oldfield = $wpdb -> get_row($oldfieldquery);
					}
					break;
				case 'wpfaqGroup'			:
					if (!empty(${$this -> model} -> data -> active) && ${$this -> model} -> data -> active == "N") {
						//${$this -> model} -> data -> pp = "none";
						$post_status = "draft";						
					}
					break;
				case 'wpfaqQuestion'		:
					if (!empty(${$this -> model} -> data -> approved) && ${$this -> model} -> data -> approved == "N") {
						//${$this -> model} -> data -> pp = "none";	   
						$post_status = "draft";
					}
					break;
				default						:
					//do nothing...
					break;
			}
			
			//the MySQL query
			$query = (empty(${$this -> model} -> data -> id)) ? $this -> insert_query($this -> model) : $this -> update_query($this -> model);
			
			if ($wpdb -> query($query)) {
			
				$isediting = false;
				if (!empty(${$this -> model} -> data -> id)) {
					$isediting = ${$this -> model} -> data -> id;
				}
			
				${$this -> model} -> insertid = $insertid = (empty(${$this -> model} -> data -> id)) ? $wpdb -> insert_id : ${$this -> model} -> data -> id;
				${$this -> model} -> data -> id = $insertid;
				
				//global variables
				global $wpfaqGroupsPost;
				
				switch ($this -> model) {
					case 'wpfaqField'		:
						global $wpfaqQuestion;
					
						if (!empty($isediting)) {
							$this -> change_field($wpfaqQuestion -> table, $oldfield -> slug, ${$this -> model} -> data -> slug);
						} else {
							$this -> add_field($wpfaqQuestion -> table, ${$this -> model} -> data -> slug);
						}
						break;
					case 'wpfaqGroup'		:
						//post/page status. either "none", "post" or "page"
						$pp = ${$this -> model} -> data -> pp;
					
						if (!empty($pp) && ($pp == "post" || $pp == "page")) {					
							$pagedata = array(
								'post_title'		=>	${$this -> model} -> data -> pp_title,
								'post_name'			=>	sanitize_title(${$this -> model} -> data -> pp_title),
								'post_status'		=>	$post_status,
								'post_type'			=>	${$this -> model} -> data -> pp,
								'post_content'		=>	'[wpfaqgroup id=' . $insertid . ']',
								'post_author'		=>	$user_ID,
								'tags_input'		=>	${$this -> model} -> data -> keywords,
							);
							
							switch ($pp) {
								case 'post'		:
									$pagedata['post_category'] = maybe_unserialize(${$this -> model} -> data -> pp_categories);
									break;
								case 'page'		:
									$pagedata['post_parent'] = ${$this -> model} -> data -> pp_parent;
									break;
							}
							
							if (!empty(${$this -> model} -> data -> pp_id)) {
								$post_id = ${$this -> model} -> data -> pp_id;
							
								if ($post = get_post($post_id)) {
									$pagedata['ID'] = $post_id;
									$pagedata['post_date'] = $post -> post_date;
								}
							}
							
							if ($post_id = wp_insert_post($pagedata)) {
								$wpfaqGroupsPost -> delete_by_group($insertid);
							
								$gpdata = array(
									'group_id'		=>	$insertid,
									'post_id'		=>	$post_id,
								);
							
								$this -> model = $wpfaqGroupsPost -> model;
								$this -> save($gpdata, true);
								
								//save the "pp_id" field of the Group
								$this -> model = $wpfaqGroup -> model;
								$this -> save_field('pp_id', $post_id, array('id' => $insertid));
							}
						} else {
							if ($wpfaqGroupsPost -> check_group($groupid)) {
								$wpfaqGroupsPost -> delete_by_group($groupid);
							}
						}
						
						$this -> groups_resavepp();
						break;
					case 'wpfaqQuestion'				:
						/* Save the Question/Group Association */
						$oldmodel = $this -> model;
						$this -> model = $wpfaqQuestionsGroup -> model;
						
						$questionsgroup = array(
							'question_id'			=>	${$oldmodel} -> insertid,
							'group_id'				=>	${$oldmodel} -> data -> group_id,
							'order'					=>	0,
						);
						
						$this -> save($questionsgroup, true);						
						$this -> model = $oldmodel;
					
						/* User Notification */
						if (!empty(${$this -> model} -> data -> email)) {
							if (!empty(${$this -> model} -> data -> notifyuser) && ${$this -> model} -> data -> notifyuser == "Y") {
								$to = ${$this -> model} -> data -> email;	
								$subject = __('Question Answered', $this -> plugin_name);
								$email = $this -> render('notify', array('question' => ${$this -> model} -> data), 'email', false);
								$headers = 'Content-Type: text/html; charset="UTF-8"' . "\r\n";
								$this -> execute_mail($to, $subject, $email, $headers);
							}
						}					
					
						//post/page status. either "none", "post" or "page"
						$pp = ${$this -> model} -> data -> pp;
					
						if (!empty($pp) && ($pp == "post" || $pp == "page")) {					
							$pagedata = array(
								'post_title'		=>	${$this -> model} -> data -> pp_title,
								'post_name'			=>	sanitize_title(${$this -> model} -> data -> pp_title),
								'post_status'		=>	$post_status,
								'post_type'			=>	${$this -> model} -> data -> pp,
								'post_content'		=>	'[wpfaqquestion id=' . $insertid . ']',
								'post_category'	    =>	maybe_unserialize(${$this -> model} -> data -> pp_categories),
								'post_author'		=>	$user_ID,
								'comment_status'	=>	${$this -> model} -> data -> pp_comments,
							);
							
							switch ($pp) {
								case 'post'		:
									//$pagedata['post_category'] = false;
									break;
								case 'page'		:
									$pagedata['post_parent'] = ${$this -> model} -> data -> pp_parent;
									break;
							}
							
							if (!empty(${$this -> model} -> data -> pp_id)) {
								$post_id = ${$this -> model} -> data -> pp_id;
							
								if ($post = get_post($post_id)) {
									$pagedata['ID'] = $post_id;
									$pagedata['post_date'] = $post -> post_date;
								}
							}
							
							if ($post_id = wp_insert_post($pagedata)) {
								$wpfaqQuestionsPost -> delete_by_question($insertid);
							
								$qpdata = array(
									'question_id'	=>	$insertid,
									'post_id'		=>	$post_id,
								);
							
								$this -> model = $wpfaqQuestionsPost -> model;
								$this -> save($qpdata, true);
								
								//save the "pp_id" field of the Group
								$this -> model = $wpfaqQuestion -> model;
								$this -> save_field('pp_id', $post_id, array('id' => $insertid));
							}
						} else {
							if ($wpfaqQuestionsPost -> check_question($insertid)) {
								$wpfaqQuestionsPost -> delete_by_question($insertid);
							}
						}
						
						$this -> questions_resavepp();
						break;
				}
				
				return true;
			}
		}
		
		return false;
	}
	
	function insert_query($model = null) {	
		if (!empty($model)) {
			global ${$model}, $wpdb, $wpfaqField;
			
			if (!empty(${$model} -> data)) {			
				if (empty(${$model} -> data -> id)) {
					$query1 = "INSERT INTO `" . $wpdb -> prefix . "" . ${$model} -> table . "` (";
					$query2 = "";
					$c = 1;
					unset(${$model} -> fields['key']);
					
					switch ($model) {
						case 'wpfaqQuestion'				:
							$fieldsquery = "SELECT * FROM " . $wpdb -> prefix . $wpfaqField -> table . "";
							if ($fields = $wpdb -> get_results($fieldsquery)) {
								foreach ($fields as $field) {
									${$model} -> fields[$field -> slug] = $field -> slug;
								}
							}
							break;
					}
					
					foreach (array_keys(${$model} -> fields) as $field) {					
						if (!empty(${$model} -> data -> {$field}) || ${$model} -> data -> {$field} == "0") {						
							if (is_array(${$model} -> data -> {$field}) || is_object(${$model} -> data -> {$field})) {
								$value = serialize(${$model} -> data -> {$field});
							} else {
								$value = ${$model} -> data -> {$field};
							}
							
							//escape the value
							$value = $wpdb -> escape($value);
				
							$query1 .= "`" . $field . "`";
							$query2 .= "'" . $value . "'";
							
							if ($c < count(${$model} -> fields)) {
								$query1 .= ", ";
								$query2 .= ", ";
							}
						}
						
						$c++;
					}
					
					$query1 .= ") VALUES (";
					$query = $query1 . "" . $query2 . ");";
					
					return $query;
				} else {
					$query = $this -> update_query($model);
					
					return $query;
				}
			}
		}
	
		return false;
	}
	
	function update_query($model = null) {	
		if (!empty($model)) {
			global ${$model}, $wpdb;
			
			if (!empty(${$model} -> data)) {			
				$query = "UPDATE `" . $wpdb -> prefix . "" . ${$model} -> table . "` SET ";
				$c = 1;
				
				unset(${$model} -> fields['key']);
				unset(${$model} -> fields['created']);
				
				foreach (array_keys(${$model} -> fields) as $field) {
					if (!empty(${$model} -> data -> {$field}) || ${$model} -> data -> {$field} == "0") {
						if (is_array(${$model} -> data -> {$field}) || is_object(${$model} -> data -> {$field})) {
							$value = serialize(${$model} -> data -> {$field});
						} else {
							$value = ${$model} -> data -> {$field};
						}
						
						//escape the value
						$value = $wpdb -> escape($value);
					
						$query .= "`" . $field . "` = '" . $value . "'";
						
						if ($c < count(${$model} -> fields)) {
							$query .= ", ";
						}
					}
					
					$c++;
				}
				
				$query .= " WHERE `id` = '" . ${$model} -> data -> id . "' LIMIT 1";
				
				return $query;
			}
		}
	
		return false;
	}
}

?>