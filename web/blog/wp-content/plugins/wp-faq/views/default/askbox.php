<h3><?php _e('Ask a Question', $this -> plugin_name); ?></h3>
<p><?php _e('Use the form below to ask a question', $this -> plugin_name); ?></p>

<form action="<?php echo rtrim(get_bloginfo('home'), '/'); ?>?<?php echo $this -> pre; ?>ask" id="<?php echo $this -> pre; ?>askform<?php echo $number; ?>" onsubmit="<?php echo $this -> pre; ?>_ask('<?php echo $number; ?>'); return false;" class="<?php echo $this -> pre; ?> <?php echo $this -> pre; ?>askform">
	<input type="hidden" name="<?php echo $number; ?>[group_id]" value="<?php echo $group -> id; ?>" />

	<div id="<?php echo $this -> pre; ?>askformi<?php echo $number; ?>" class="<?php echo $this -> pre; ?>askformi">
	
		<?php
		
		global $wpdb;
		$fieldsquery = "SELECT id FROM " . $wpdb -> prefix . $wpfaqField -> table . " ORDER BY `order` ASC";
		if ($fields = $wpdb -> get_results($fieldsquery)) {
			foreach ($fields as $field) {
				$this -> render_field($field -> id, true, $number, true);
			}
		}
		
		?>
	
    	<?php if ($this -> get_option('requireemail') == "Y") : ?>
        	<p class="<?php echo $this -> pre; ?>askemail">
                <label>
                    <?php _e('Email Address', $this -> plugin_name); ?>:<br/>
                    <input class="<?php echo $this -> pre; ?>ask" type="text" name="<?php echo $number; ?>[email]" value="<?php echo esc_attr(stripslashes($_REQUEST[$number]['email'])); ?>" id="<?php echo $this -> pre; ?>email<?php echo $number; ?>" />
                </label>
            </p>
        <?php endif; ?>
    
    	<p class="<?php echo $this -> pre; ?>asktext">
            <label for="<?php echo $this -> pre; ?>asktext<?php echo $number; ?>">
                <?php _e('Your Question', $this -> plugin_name); ?>:<br/>
                <textarea rows="3" cols="100%" class="<?php echo $this -> pre; ?>ask" name="<?php echo $number; ?>[question]" id="<?php echo $this -> pre; ?>asktext<?php echo $number; ?>"><?php echo stripslashes($_REQUEST[$number]['question']); ?></textarea>
            </label>
        </p>
        
        <?php if ($this -> use_captcha()) : ?>
        	<?php $captcha = new ReallySimpleCaptcha(); ?>
            <?php $captcha_word = $captcha -> generate_random_word(); ?>
        	<?php $captcha_prefix = mt_rand(); ?>
        	<p class="<?php echo $this -> pre; ?>captcha">
            	<input type="hidden" name="<?php echo $number; ?>[captcha_prefix]" value="<?php echo $captcha_prefix; ?>" />
                <?php $captcha_filename = $captcha -> generate_image($captcha_prefix, $captcha_word); ?>
                <?php $captcha_file = rtrim(get_bloginfo('wpurl'), '/') . '/wp-content/plugins/really-simple-captcha/tmp/' . $captcha_filename; ?>
                
                <label for="<?php echo $this -> pre; ?>captcha_code<?php echo $number; ?>"><?php _e('Please fill in the code below:', $this -> plugin_name); ?></label><br/>
                <img src="<?php echo $captcha_file; ?>" alt="captcha" /><br/>
                <input class="<?php echo $this -> pre; ?>captchacode" type="text" name="<?php echo $number; ?>[captcha_code]" id="<?php echo $this -> pre; ?>captcha_code<?php echo $number; ?>" value="" />
            </p>
        <?php endif; ?>
		
		<p class="<?php echo $this -> pre; ?>submit">
			<input class="<?php echo $this -> pre; ?>submitbtn" type="submit" name="submit" value="<?php _e('Ask Question', $this -> plugin_name); ?>" />
			<span id="<?php echo $this -> pre; ?>askloading<?php echo $number; ?>" style="display:none; float:right;"><img border="0" style="border:none;" src="<?php echo $this -> url(); ?>/images/loading.gif" alt="loading" /></span>
		</p>
		
		<?php $this -> render('errors', array('errors' => $errors), 'default', true); ?>
		
		<?php if (!empty($message)) : ?>
			<p><small class="<?php echo $this -> pre; ?>grn <?php echo $this -> pre; ?>italic"><?php echo $message; ?></small></p>
		<?php endif; ?>
	</div>
</form>