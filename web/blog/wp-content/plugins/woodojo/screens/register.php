<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}

	$redirect_to = admin_url( 'admin.php?page=' . $this->model->config->token );
	if ( isset( $_GET['redirect_to'] ) ) { $redirect_to = $_GET['redirect_to']; }
	else if ( isset( $_POST['redirect_to'] ) ) { $redirect_to = $_POST['redirect_to']; }

	if ( $this->model->is_logged_in() ) {
		wp_redirect( esc_url( $redirect_to ) );
		exit;
	}
?>
<div id="woodojo" class="wrap">
	<div id="icon-dojo" class="icon32"><br></div>
	<h2><?php echo esc_html( $this->name . ' ' . __( 'Register', 'woodojo' ) ); ?></h2>
	<p class="powered-by-woo"><?php _e( 'Powered by', 'woodojo' ); ?><a href="http:www/woothemes.com" title="WooThemes"><img src="<?php echo $this->assets_url; ?>images/woothemes.png" alt="WooThemes" /></a></p>
	<p><?php _e( 'Register a free WooThemes.com account to download additional features within WooDojo. You can use this account to access our public support forums as well.', 'woodojo' ); ?></p>
	<p><?php printf( __( 'If you already have a WooThemes.com account, %sclick here to login%s.', 'woodojo' ), '<a href="' . esc_url( admin_url( 'admin.php?page=' . $this->model->config->token . '&screen=login' ) ) . '">', '</a>' ); ?></p>
	<form name="<?php echo $this->token; ?>-register" id="<?php echo $this->token; ?>-register" action="" method="post">
		<fieldset>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="first_name"><?php _e( 'First Name', 'woodojo' ); ?>:</label></th>
						<td><input type="text" class="input-text input-woo_first_name regular-text" name="first_name" id="woo_first_name" value="<?php echo esc_attr( $this->model->posted['first_name'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="last_name"><?php _e( 'Last Name', 'woodojo' ); ?>:</label></th>
						<td><input type="text" class="input-text input-woo_last_name regular-text" name="last_name" id="woo_last_name" value="<?php echo esc_attr( $this->model->posted['last_name'] ); ?>" /></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="username"><?php _e( 'Username', 'woodojo' ); ?>:</label></th>
						<td><input type="text" class="input-text input-woo_user regular-text" name="username" id="user_login" value="<?php echo esc_attr( $this->model->posted['username'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="email"><?php _e( 'E-mail Address', 'woodojo' ); ?>:</label></th>
						<td><input type="text" class="input-text input-woo_email regular-text" name="email" id="woo_email" value="<?php echo esc_attr( $this->model->posted['email'] ); ?>" /></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="username"><?php _e( 'Password', 'woodojo' ); ?>: <span class="description"><?php _e( '(twice, required)', 'woodojo' ); ?></span></label></th>
						<td><input name="pass1" type="password" id="pass1" autocomplete="off" />
						<br>
						<input name="pass2" type="password" id="pass2" autocomplete="off" />
						<br>
						<div id="pass-strength-result"><?php _e( 'Strength indicator', 'woodojo' ); ?></div>
						<p class="description indicator-hint"><?php _e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).', 'woodojo' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		
		<fieldset>
			<p class="submit">
				<button type="submit" name="woo_register" id="woo_register" class="button-primary"><?php _e( 'Register', 'woodojo' ); ?></button>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->token . '&screen=login&redirect_to=' . esc_attr( urlencode( $redirect_to ) ) ) ); ?>"><?php _e( 'I\'m already registered', 'woodojo' ); ?></a>
			</p>
			<input type="hidden" name="action" value="woodojo-register" />
			<input type="hidden" name="page" value="woodojo" />
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
			<?php if ( isset( $this->model->posted['component'] ) ) { wp_nonce_field( esc_attr( trim( $this->model->posted['component'] ) ) ); } ?>
			<?php if ( isset( $this->model->posted['component_id'] ) ) {?>
			<input type="hidden" name="component_id" value="<?php echo esc_attr( trim( $this->model->posted['component_id']) ); ?>" />
			<?php } ?>
		</fieldset>
	</form>
	<br class="clear" />
</div><!--/#woodojo .wrap-->
<?php wp_print_scripts( 'user-profile' ); ?>