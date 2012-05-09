<?php

class wpfaqHtmlHelper extends wpFaqPlugin {

	var $name = 'Html';
	
	function wpfaqHtmlHelper() {
		
		return true;
	}
	
	function link($name = null, $href = '/', $args = array()) {
		$defaults = array(
			'title'			=>	(empty($args['title'])) ? $title : $args['title'],
			'target'		=>	"_self",
			'class' 		=>	"wpco",
			'rel'			=>	"",
			'onclick'		=>	"",
		);
		
		$r = wp_parse_args($args, $defaults);
		extract($r, EXTR_SKIP);
		
		ob_start();
		
		?><a class="<?php echo $class; ?>" rel="<?php echo $rel; ?>" onclick="<?php echo $onclick; ?>" href="<?php echo $href; ?>" target="<?php echo $target; ?>" title="<?php echo $title; ?>"><?php echo $name; ?></a><?php
		
		$link = ob_get_clean();
		return $link;
	}
	
	function image($filename = null, $filepath = null, $atts = array()) {	
		if (!empty($filename) && !empty($filepath)) {
			$defaults = array(
				'alt'			=>	$this -> sanitize($filename),
				'src'			=>	$filepath . $filename,
			);
			
			$r = wp_parse_args($atts, $defaults);
			extract($r, EXTR_SKIP);
			
			ob_start();
			
			?><img src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" /><?php
			
			$image = ob_get_clean();
			return $image;
		}
		
		return false;
	}
	
	function admin_groups_url() {
		return rtrim(get_bloginfo('wpurl'), '/') . '/wp-admin/admin.php?page=faqs-groups';
	}

	function admin_questions_url() {
		return rtrim(get_bloginfo('wpurl'), '/') . '/wp-admin/admin.php?page=faqs-questions';
	}
	
	function admin_gu_save($group_id = '') {
		return rtrim(get_bloginfo('wpurl'), '/') . '/wp-admin/admin.php?page=groups-save&amp;method=save&amp;id=' . $group_id;
	}
	
	function admin_qu_save($question_id = '') {
		return rtrim(get_bloginfo('wpurl'), '/') . '/wp-admin/admin.php?page=faqs-questions-save&amp;id=' . $question_id;
	}
	
	function admin_qu_delete($question_id = null) {
		return rtrim(get_bloginfo('wpurl'), '/') . '/wp-admin/admin.php?page=faqs-questions&amp;method=delete&amp;id=' . $question_id;
	}
	
	function admin_save($section = 'groups', $record_id = '') {
		switch ($section) {
			case 'questions'		:
				return rtrim(get_bloginfo('wpurl'), '/') . '/wp-admin/admin.php?page=faqs-questions-save&amp;id=' . $record_id;
				break;
			default					:
				return rtrim(get_bloginfo('wpurl'), '/') . '/wp-admin/admin.php?page=faqs-groups&amp;method=save&amp;id=' . $record_id;
				break;
		}
		//return call_user_method('admin_' . $section . '_url', $this) . '&amp;method=save&amp;id=' . $record_id;
	}
	
	function admin_delete($section = 'groups', $record_id = '') {
		return call_user_method('admin_' . $section . '_url', $this) . '&amp;method=delete&amp;id=' . $record_id;
	}
	
	function retainquery($add = '', $old_url = null, $endslash = true) {
		$url = (empty($old_url)) ? $_SERVER['REQUEST_URI'] : rtrim($old_url, '&');
		$add = ltrim($add, '&');

		if (($urls = @explode("?", $url)) !== false) {
			if (!empty($urls[1])) {
				if (!empty($add)) {				
					if (($adds = explode("&", str_replace("&amp;", "&", $add))) !== false) {					
						foreach ($adds as $qstring) {
							if (($qparts = @explode("=", $qstring)) !== false) {
								if (!empty($qparts[0])) {
									if (preg_match("/\&?" . $qparts[0] . "\=([0-9a-z-_+]*)/i", $urls[1], $matches)) {
										$urls[1] = preg_replace("/^\&?" . $qparts[0] . "\=([0-9a-z-_+]*)/i", "", $urls[1]);
									}									
								}
							}
						}
					}
				}
			}
		}
		
		$urls[1] = preg_replace("/\&?" . $this -> pre . "message\=(.*)/i", "", $urls[1]);
		$urls[1] = preg_replace("/\&?" . $this -> pre . "error\=(.*)/i", "", $urls[1]);
		$urls[1] = preg_replace("/\&?" . $this -> pre . "updated\=(.*)/i", "", $urls[1]);
		
		$url = $urls[0];
		$url .= '?';
		$url .= (empty($urls[1])) ? '' : $urls[1];
		
		if (!empty($add)) {
			$url .= '&' . $add;
		}
				
		return preg_replace("/\?(\&)?/si", "?", $url);
	}
	
	function alert($message = null) {
		if (!empty($message)) {
			?>
			
			<script type="text/javascript">
				alert('<?php echo $message; ?>');
			</script>
			
			<?php
		}
		
		return false;
	}
	
	function strip_ext($filename = null, $return = 'ext') {
		if (!empty($filename)) { 
			$extArray = split("[/\\.]", $filename); 
			
			if ($return == 'ext') {
				$p = count($extArray) - 1; 
				$extension = $extArray[$p]; 
				return $extension;
			} else {
				$p = count($extArray) - 2;
				$filename = $extArray[$p];
				return $filename;
			}
		}
		
		return false;
	}
	
	function truncate($string = null, $length = 125, $append = '...') {
		if (!empty($string)) {
			$newstring = substr(strip_tags($string), 0, $length);
			
			if (strlen($string) > $length) {
				$newstring .= $append;
			}
			
			return $newstring;
		}
		
		return false;
	}
	
	function gen_date($wpcoFormat = "Y-m-d H:i:s", $time = false) {
		$time = (empty($time)) ? time() : $time;
		$date = date($wpcoFormat, $time);
		
		return $date;
	}
	
	function array_to_object($array = array()) {
		if (!empty($array)) {
			$object = false;
		
			foreach ($array as $akey => $aval) {
				$object -> {$akey} = $aval;
			}
			
			return $object;
		}
	
		return false;
	}
	
	function field_name($name = null) {
		if (!empty($name)) {
			if ($mn = $this -> strip_mn($name)) {
				return $mn[1] . '[' . $mn[2] . ']';
			}
		}
	
		return $name;
	}
	
	function field_error($name = null, $el = "p") {
		if (!empty($name)) {
			if ($mn = $this -> strip_mn($name)) {
				global ${$mn[1]};
				
				if (!empty(${$mn[1]} -> errors[$mn[2]])) {
					$error = '<' . $el . ' class="' . $this -> pre . 'error">' . ${$mn[1]} -> errors[$mn[2]] . '</' . $el . '>';
					
					return $error;
				}
			}
		}
		
		return false;
	}
	
	function field_type($type = null) {
		if (!empty($type)) {
			switch($type) {
				case 'text'				:
					$fieldtype = __('Text Input', $this -> plugin_name);
					break;
				case 'checkbox'			:
					$fieldtype = __('Checkboxes List', $this -> plugin_name);
					break;
				case 'radio'			:
					$fieldtype = __('Radio Buttons List', $this -> plugin_name);
					break;
				case 'select'			:
					$fieldtype = __('Select Drop Down', $this -> plugin_name);
					break;
				case 'textarea'			:
					$fieldtype = __('Textarea Box', $this -> plugin_name);
					break;
			}
		
			return $fieldtype;
		}
		
		return false;
	}
	
	function field_value($name = null) {
		if ($mn = $this -> strip_mn($name)) {
			global ${$mn[1]};
			$value = ${$mn[1]} -> data -> {$mn[2]};
			
			return $value;
		}
		
		return false;
	}
	
	function strip_mn($name = null) {
		if (!empty($name)) {
			if (preg_match("/^(.*?)\.(.*?)$/i", $name, $matches)) {
				return $matches;
			}
		}
	
		return false;
	}
	
	function sanitize($string = null, $sep = '-') {
		if (!empty($string)) {
			$string = ereg_replace("[^0-9a-z" . $sep . "]", "", strtolower(str_replace(" ", $sep, $string)));
			$string = preg_replace("/" . $sep . "[" . $sep . "]*/i", $sep, $string);
			
			return $string;
		}
	
		return false;
	}
}

?>