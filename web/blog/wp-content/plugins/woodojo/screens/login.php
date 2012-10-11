<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}

	$redirect_to = admin_url( 'admin.php?page=' . $this->model->config->token );
	if ( isset( $_GET['redirect_to'] ) ) { $redirect_to = $_GET['redirect_to']; }
	else if ( isset( $_POST['redirect_to'] ) ) { $redirect_to = $_POST['redirect_to']; }

	$component = '';
	if ( isset( $_GET['component'] ) ) {
		$component = $_GET['component'];
	} else if ( isset( $_POST['component'] ) ) {
		$component = $_POST['component'];
	}

	$component_id = '';
	if ( isset( $_GET['component_id'] ) ) {
		$component_id = $_GET['component_id'];
	} else if ( isset( $_POST['component_id'] ) ) {
		$component_id = $_POST['component_id'];
	}
?>
<div id="woodojo" class="wrap">
	<div id="icon-dojo" class="icon32"><br></div>
	<h2><?php echo esc_html( $this->name . ' ' . __( 'Login', 'woodojo' ) ); ?></h2>
	<p class="powered-by-woo"><?php _e( 'Powered by', 'woodojo' ); ?><a href="http:www/woothemes.com" title="WooThemes"><img src="<?php echo $this->assets_url; ?>images/woothemes.png" alt="WooThemes" /></a></p>
	<p><?php _e( 'Login with your WooThemes.com account to download additional features within WooDojo.', 'woodojo' ); ?></p>
	<p><?php printf( __( 'If you don\'t have a free WooThemes.com account, %sclick here to register%s.', 'woodojo' ), '<a href="' . esc_url( admin_url( 'admin.php?page=' . $this->model->config->token . '&screen=register' ) ) . '">', '</a>' ); ?></p>
	<form name="<?php echo $this->token; ?>-login" id="<?php echo $this->token; ?>-login" action="" method="post">
		<fieldset>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="username"><?php _e( 'WooThemes Username', 'woodojo' ); ?>:</label></th>
						<td><input type="text" class="input-text input-woo_user regular-text" name="username" id="woo_user" value="" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="password"><?php _e( 'WooThemes Password', 'woodojo' ); ?>:</label></th>
						<td><input type="password" class="input-text input-woo_pass regular-text" name="password" id="woo_pass" value="" /></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		
		<fieldset>
			<p class="submit">
				<button type="submit" name="woo_login" id="woo_login" class="button-primary"><?php _e( 'Login', 'woodojo' ); ?></button>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->token . '&screen=register&component=' . urlencode( $component ) . '&component_id=' . urlencode( $component_id ) . '&redirect_to=' . esc_attr( urlencode( $redirect_to ) ) ) ); ?>"><?php _e( 'Register', 'woodojo' ); ?></a>
			</p>
			<input type="hidden" name="action" value="woodojo-login" />
			<input type="hidden" name="page" value="woodojo" />
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( esc_url( $redirect_to ) ); ?>" />
			<?php if ( $component != '' ) { wp_nonce_field( esc_attr( trim( $component ) ) ); } ?>
			<?php if ( $component_id != '' ) {?>
			<input type="hidden" name="component_id" value="<?php echo esc_attr( trim( $component_id ) ); ?>" />
			<?php } ?>
		</fieldset>
	</form>
	<br class="clear" />
</div><!--/#woodojo .wrap-->