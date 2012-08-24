			<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
			$user = get_userdata($current_user->ID); 
			if(strlen($user->first_name) < 1) $firstname = $user->user_login;
				else $firstname = $user->first_name;
			if(strlen($user->last_name) < 1) $lastname = ''; 
				else $lastname = $user->last_name;
			if(strlen($user->user_email) < 1) $email = __('No address specified', 'adrotate'); 
				else $email = $user->user_email;
			?>
			<form name="request" id="post" method="post" action="admin.php?page=adrotate-beta">
		    	<input type="hidden" name="adrotate_username" value="<?php echo $firstname." ".$lastname;?>" />
		    	<input type="hidden" name="adrotate_email" value="<?php echo $email;?>" />
		    	<input type="hidden" name="adrotate_version" value="<?php echo ADROTATE_BETA;?>" />

				<table class="widefat" style="margin-top: .5em">
					<thead>
						<tr>
							<th colspan="3">Submission form</th>
						</tr>
					</thead>
					<tbody>
					    <tr>
							<td valign="top"><p><strong><?php _e('Feedback/bugs', 'adrotate'); ?></strong></p></td>
							<td><textarea tabindex="1" name="adrotate_message" cols="100" rows="25"></textarea></td>
							<td>
								<p><strong>We're interested to hear about:</strong></p>
								<p><em>Your thoughts on new features, performance, ease of use, menu structures, naming convention, idea's/comments/suggestions and any problems you may run into while using the plugin.</em></p>
								<p><strong>As many as you feel is nessesary:</strong></p>
								<p><em>Do not hestitate to send in more than one email or multiple in short succession. More information is better!! But please only send in reports about the use (or results of using) of AdRotate!</em></p>
								<p><strong>Bugs:</strong></p>
								<p><em>If you think you found a bug please include any errors, steps (if any) to reproduce this bug. If there are no visible errors please include the lines out the error_log of your webserver at the time the problem occured. You can find the error_log in your webhosts dashboard.</em></p>
								<p><strong>When you send this report the following data will be submitted:</strong></p>
								<p><em>Your website url, account email address, your name, WordPress version, charset and language.<br />We need this information so we know who sent the report what sort of blog you're testing on. And ofcourse so we can contact you if nessesary.<br />This information is mandatory and is <strong>NOT</strong> stored in a database, sold or used for anything other than the AdRotate beta program.<br />You'll receive a copy of the message in your inbox.</em></p>
							</td>
						</tr>
						<tr>
							<th colspan="3"><h3>Thanks for your efforts to make AdRotate a better plugin!</h3></th>
						</tr>
					</tbody>
				</table>

		    	<p class="submit">
					<input tabindex="2" type="submit" name="adrotate_beta_submit" class="button-primary" value="Send report" />
					<a href="admin.php?page=adrotate-beta" class="button">Cancel</a>
		    	</p>

			</form>