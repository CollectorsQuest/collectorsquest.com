<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
			<?php
			if($request == "renew") {
				$request_name = __('Renewal of', 'adrotate');
				$example = __('- I\'d want my ad renewed for 1 year. Quote me!', 'adrotate').'<br />'.__('- Renew my ad, but i want the weight set higher.', 'adrotate');
			} else if($request == "remove") {
				$request_name = __('Removal of', 'adrotate');
				$example = __('- This ad doesn\'t perform, please remove it.', 'adrotate').'<br />'.__('- The budget is spent, please remove the ad when it expires.', 'adrotate');
			} else if($request == "other") {
				$request_name = __('About', 'adrotate');
				$example = __('- The ad is not in the right place. I\'d like....', 'adrotate').'<br />'.__('- This ad works great for me!!', 'adrotate');
			} else if($request == "issue") {
				$request_name = __('Complaint or problem', 'adrotate');
				$example = __('- My ads do not show, what\'s going on?', 'adrotate').'<br />'.__('- Why can\'t i see any clicks?', 'adrotate');
			}
	
			$user = get_userdata($user->ID); 
			if(strlen($user->first_name) < 1) $firstname = $user->user_login;
				else $firstname = $user->first_name;
			if(strlen($user->last_name) < 1) $lastname = ''; 
				else $lastname = $user->last_name;
			if(strlen($user->user_email) < 1) $email = __('No address specified', 'adrotate'); 
				else $email = $user->user_email;
			?>
			<form name="request" id="post" method="post" action="admin.php?page=adrotate-advertiser">
		    	<input type="hidden" name="adrotate_id" value="<?php echo $request_id;?>" />
		    	<input type="hidden" name="adrotate_request" value="<?php echo $request;?>" />
		    	<input type="hidden" name="adrotate_username" value="<?php echo $firstname." ".$lastname;?>" />
		    	<input type="hidden" name="adrotate_email" value="<?php echo $email;?>" />

				<h4><?php _e('Contact your Publisher', 'adrotate'); ?></h4>

				<table class="widefat" style="margin-top: .5em">
					<thead>
						<tr>
							<th colspan="3"><?php _e('Put in a request for renewal, removal or report an issue with this ad.', 'adrotate'); ?></th>
						</tr>
					</thead>
					<tbody>
					    <tr>
							<th width="15%"><?php _e('Subject', 'adrotate'); ?></th>
							<td colspan="2">
								<?php
								if($request == "issue") {
									echo $request_name;
								} else {
									echo $request_name." ".__('ad')." ".$request_id;
								}
								?>
							</td>
						</tr>
					    <tr>
							<td valign="top"><p><strong><?php _e('Short message/Reason', 'adrotate'); ?></strong></p></td>
							<td><textarea tabindex="1" name="adrotate_message" cols="50" rows="5"></textarea></td>
							<td>
								<p><strong><?php _e('Examples:', 'adrotate'); ?></strong></p>
								<p><em><?php echo $example; ?></em></p>
							</td>
						</tr>
					</tbody>
				</table>

		    	<p class="submit">
					<input tabindex="2" type="submit" name="adrotate_request_submit" class="button-primary" value="<?php _e('Send', 'adrotate'); ?>" />
					<a href="admin.php?page=adrotate-advertiser&view=stats" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
		    	</p>

			</form>