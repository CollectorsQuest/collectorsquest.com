<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}

/**
 * WooDojo Login Model
 *
 * The model for the "Login" screen.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category Administration
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * var $admin_page_hook ( sent from the main admin class )
 * var $component ( array to hold component data )
 *
 * - __construct()
 * - process_login()
 * - invalid_login_notice()
 */
class WooDojo_Model_Login extends WooDojo_Model {
	var $component;
	
	function __construct() {
		parent::__construct();
		$this->component = array();
		
		if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'woodojo-login' ) ) {
			$this->process_login();
		}  
	} // End __construct()
	
	/**
	 * process_login function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses global $woodojo->api->refresh_purchase_data()
	 * @uses global $woodojo->api->refresh()
	 * @return void
	 */
	function process_login () {
		global $woodojo;

		$username = esc_attr( trim( $_POST['username'] ) );
		$password = esc_attr( trim( $_POST['password'] ) );
		
		$is_valid = $this->is_valid_login( $username, $password );

		if ( $is_valid == true ) {
			$redirect_to = admin_url( 'admin.php?page=' . $this->config->token );
			
			if ( isset( $_POST['redirect_to'] ) && ( $_POST['redirect_to'] != '' ) ) {
				$redirect_to = urldecode( $_POST['redirect_to'] );
			}
			
			// Store the username.
			$this->set_username( $username );
			$woodojo->api->refresh_purchase_data();
			$woodojo->api->refresh();
			
			wp_redirect( $redirect_to );
			exit();
		} else {
			add_action( 'admin_notices', array( &$this, 'invalid_login_notice' ) );
		}
	} // End process_login()
	
	/**
	 * is_valid_login function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param string $username
	 * @param string $password
	 * @uses global $woodojo->api->request_token()
	 * @return boolean $is_valid
	 */
	function is_valid_login ( $username, $password ) {
		global $woodojo;
		$is_valid = false;
		
		$is_valid = $woodojo->api->request_token( $username, $password );
		
		return $is_valid;
	} // End is_valid_login()
	
	/**
	 * invalid_login_notice function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	function invalid_login_notice () {
		echo '<div class="error fade"><p>' . __( 'Invalid login. Please try again.', 'woodojo' ) . '</p></div>' . "\n";
	} // End invalid_login_notice()
} // End Class
?>