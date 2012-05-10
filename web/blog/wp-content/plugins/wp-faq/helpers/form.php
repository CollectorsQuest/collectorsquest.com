<?php

class wpfaqFormHelper extends wpFaqPlugin {

	var $name = 'Form';
	
	function wpfaqFormHelper() {
		return true;
	}
	
	function hidden($name = null, $args = array()) {
		global $wpfaqHtml;
		
		$defaults = array(
			'value' 		=> 	(empty($args['value'])) ? $wpfaqHtml -> field_value($name) : $args['value'],
		);
		
		$r = wp_parse_args($args, $defaults);
		extract($r, EXTR_SKIP);
		
		ob_start();
		
		?><input type="hidden" name="<?php echo $wpfaqHtml -> field_name($name); ?>" value="<?php echo $value; ?>" id="<?php echo $name; ?>" /><?php
		
		$hidden = ob_get_clean();
		return $hidden;
	}
	
	function file($name = null, $args = array()) {
		global $wpfaqHtml;
		
		$defaults = array('error' => true);
		$r = wp_parse_args($args, $defaults);
		extract($r, EXTR_SKIP);
		
		ob_start();
		
		?>
		
		<div><input id="<?php echo $name; ?>" type="file" name="<?php echo $wpfaqHtml -> field_name($name); ?>" /></div>
		
		<?php
		
		if ($error == true) {
			echo $wpfaqHtml -> field_error($name);
		}
		
		$file = ob_get_clean();
		return $file;
	}
	
	function radio($name = null, $buttons = array(), $args = array()) {
		global $wpfaqHtml;
		
		$defaults = array(
			'error'			=>	true,
			'onclick'		=>	'return;',
			'separator'		=>	'<br/>',
		);
		
		$r = wp_parse_args($args, $defaults);
		extract($r, EXTR_SKIP);
		
		ob_start();
		
		?>
		
		<?php foreach ($buttons as $bkey => $bval) : ?>
			<label><input onclick="<?php echo $onclick; ?>" <?php echo ($wpfaqHtml -> field_value($name) == $bkey || ($wpfaqHtml -> field_value($name) == "" && !empty($default) && $default == $bkey)) ? 'checked="checked"' : ''; ?> type="radio" name="<?php echo $wpfaqHtml -> field_name($name); ?>" value="<?php echo $bkey; ?>" id="<?php echo $name; ?><?php echo $bkey; ?>" /> <?php echo $bval; ?></label><?php echo $separator; ?>
		<?php endforeach; ?>
		
		<?php
		
		if ($error == true) {
			echo $wpfaqHtml -> field_error($name);
		}
		
		$radio = ob_get_clean();
		return $radio;
	}
	
	function text($name = null, $args = array()) {
		global $wpfaqHtml;
	
		$defaults = array(
			'id'			=>	(empty($args['id'])) ? $name : $args['id'],
			'width'			=>	'100%',
			'error'			=>	true,
			'value'			=>	(empty($args['value'])) ? $wpfaqHtml -> field_value($name) : $args['value'],
			'autocomplete'	=>	"on",
			'onkeyup'		=>	"return false;",
			'tabindex'		=>	"",
			'disabled'		=>	"",
		);
		
		$r = wp_parse_args($args, $defaults);
		extract($r, EXTR_SKIP);
		
		ob_start();
		
		?><input tabindex="<?php echo $tabindex; ?>" <?php if (!empty($disabled)) : ?>disabled="<?php echo $disabled; ?>"<?php endif; ?> type="text" onkeyup="<?php echo $onkeyup; ?>" autocomplete="<?php echo $autocomplete; ?>" style="width:<?php echo $width; ?>" name="<?php echo $wpfaqHtml -> field_name($name); ?>" value="<?php echo esc_attr(stripslashes($value)); ?>" id="<?php echo $id; ?>" /><?php
		
		if ($error == true) {
			echo $wpfaqHtml -> field_error($name);
		}
		
		$text = ob_get_clean();
		return $text;
	}
	
	function textarea($name = null, $args = array()) {
		global $wpfaqHtml;
		
		$defaults = array(
			'error'			=>	true,
			'width'			=>	'97%',
			'rows'			=>	4,
			'cols'			=>	50,
		);
		
		$r = wp_parse_args($args, $defaults);
		extract($r, EXTR_SKIP);
		
		ob_start();
		
		?><textarea name="<?php echo $wpfaqHtml -> field_name($name); ?>" rows="<?php echo $rows; ?>" style="width:<?php echo $width; ?>;" cols="<?php echo $cols; ?>" id="<?php echo $name; ?>"><?php echo $wpfaqHtml -> field_value($name); ?></textarea><?php
		
		if ($error == true) {
			echo $wpfaqHtml -> field_error($name);
		}
		
		$textarea = ob_get_clean();
		return $textarea;
	}
	
	function select($name = null, $options = array(), $args = array()) {
		global $wpfaqHtml, $wpfaqDb;
		
		$defaults = array(
			'error'			=>	true,
			'onchange'		=>	"return;",
			'width'			=>	(empty($args['width'])) ? "100%" : $args['width'],
			'default'		=>	$args['default'],
		);
		
		$r = wp_parse_args($args, $defaults);
		extract($r, EXTR_SKIP);
		
		ob_start();
		
		?>
		
		<select class="widefat" style="width:<?php echo $width; ?>;" onchange="<?php echo $onchange; ?>" id="<?php echo $name; ?>" name="<?php echo $wpfaqHtml -> field_name($name); ?>">
			<option value="">- <?php _e('Select', $this -> plugin_name); ?> -</option>	
			<?php if (!empty($options)) : ?>
				<?php foreach ($options as $okey => $oval) : ?>				
					<option <?php echo ($wpfaqHtml -> field_value($name) == $okey || $default == $okey) ? 'selected="selected"' : ''; ?> value="<?php echo $okey; ?>"><?php echo $oval; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
		</select>
		
		<?php
		
		if ($error == true) {
			echo $wpfaqHtml -> field_error($name);
		}
		
		$select = ob_get_clean();
		return $select;
	}
	
	function submit($name = null, $args = array()) {
		global $wpfaqHtml;
		
		$defaults = array('class' => "button-primary");
		$r = wp_parse_args($args, $defaults);
		extract($r, EXTR_SKIP);
		
		ob_start();
		
		?><input class="<?php echo $class; ?>" type="submit" name="<?php echo $wpfaqHtml -> sanitize($name); ?>" value="<?php echo $name; ?>" /><?php
		
		$submit = ob_get_clean();
		return $submit;
	}
}

?>