<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}

/**
 * WooDojo Register Model
 *
 * The model for the "Register" screen.
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
 * var $errors
 * var $posted
 * private $sanitized_data
 *
 * - __construct()
 * - process_registration()
 * - admin_notice()
 * - validate_registration()
 * - add_error()
 * - maybe_store_errors()
 * - get_fields()
 * - remember_posted_data()
 * - sanitize_userdata()
 */
class WooDojo_Model_Register extends WooDojo_Model {
	private $errors;
	private $sanitized_data;
	
	public function __construct() {
		parent::__construct();
		$this->errors = array();
		$this->posted = array();
		$this->sanitized_data = array();

		$this->remember_posted_data( $_POST );

		if ( isset( $_GET['component'] ) ) {
			$this->posted['component'] = $_GET['component'];
		} else if ( isset( $_POST['component'] ) ) {
			$this->posted['component'] = $_POST['component'];
		}

		if ( isset( $_GET['component_id'] ) ) {
			$this->posted['component_id'] = $_GET['component_id'];
		} else if ( isset( $_POST['component_id'] ) ) {
			$this->posted['component_id'] = $_POST['component_id'];
		}

		if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'woodojo-register' ) ) {
			$this->process_registration();
		}  
	} // End __construct()
	
	/**
	 * process_registration function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses global $woodojo->api->save_tokens()
	 * @uses global $woodojo->api->refresh_purchase_data()
	 * @uses global $woodojo->api->refresh()
	 * @return void
	 */
	public function process_registration () {
		global $woodojo;

		$is_valid = $this->validate_registration( $_POST );

		if ( $is_valid == true ) {
			$response = $this->perform_api_registration( $this->sanitized_data );

			// Only perform the redirect if the registration was successful.
			if ( isset( $response->response->code ) && $response->response->code == 1 && ! isset( $response->payload->errors ) ) {
				// Store the username.
				$this->set_username( $this->sanitized_data['username'] );
				
				$redirect_to = admin_url( 'admin.php?page=' . $this->config->token );
				
				if ( isset( $_POST['redirect_to'] ) && ( $_POST['redirect_to'] != '' ) ) {
					$redirect_to = urldecode( $_POST['redirect_to'] );
				}
				
				// Save the tokens.
				$woodojo->api->save_tokens( $response->payload->token, $response->payload->secret );
				$woodojo->api->refresh_purchase_data();
				$woodojo->api->refresh();

				$redirect_to .= '&registration=success';
				
				wp_redirect( $redirect_to );
				exit();
			}
		}
		
		if ( isset( $_POST['woo_register'] ) ) {
			add_action( 'admin_notices', array( &$this, 'admin_notice' ) );
		}
	} // End process_login()
	
	/**
	 * admin_notice function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_notice () {
		if ( false === ( $data = get_transient( $this->config->token . '-registration-errors' ) ) ) {
			echo '<div class="updated fade"><p>' . __( 'Registration successful.', 'woodojo' ) . '</p></div>' . "\n";
		} else {
			foreach ( (array)$data as $k => $v ) {
				echo '<div class="error fade"><p>' . $v . '</p></div>' . "\n";
			}
		}
		delete_transient( $this->config->token . '-registration-errors' );
	} // End admin_notice()
	
	/**
	 * validate_registration function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $data
	 * @return boolean $is_valid
	 */
	public function validate_registration ( $data ) {
		$is_valid = false;
		
		$fields = $this->get_fields();
		
		// Basic "is it there" validation.			   
		foreach ( $fields as $k => $v ) {
			if ( in_array( $k, array( 'component', 'component_id' ) ) ) { continue; }

			if ( ! isset( $data[$k] ) || ( $data[$k] == '' ) ) {
				$this->add_error( sprintf( __( '%s is a required field', 'woodojo' ), $v ) );
			}
		}

		// Field-specific validation.
		if ( $data['pass1'] != '' || $data['pass2'] != '' ) {
			// Password mismatch.
			if ( $data['pass1'] != $data['pass2'] ) {
				$this->add_error( __( 'Your passwords don\'t match.', 'woodojo' ) );
			} else {
				// Password less than 6.
				if ( strlen( $data['pass1'] ) < 7 ) {
					$this->add_error( __( 'Please set your password to be seven or more characters.', 'woodojo' ) );
				}
			}
		}
		
		// Valid e-mail address.
		if ( ( $data['email'] != '' ) && ! is_email( $data['email'] ) ) {
			$this->add_error( __( 'Please supply a valid e-mail address', 'woodojo' ) );
		}
		
		if ( count( $this->errors ) == 0 ) {
			$is_valid = true;
			// Store the sanitized user data.
			$this->sanitized_data = $this->sanitize_userdata( $data );
		}
		
		// Keep track of errors, if there are any.
		$this->maybe_store_errors();
		
		return $is_valid;
	} // End validate_registration()
	
	/**
	 * perform_api_registration function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @uses global $woodojo->api->register()
	 * @param array $data
	 * @return array $data
	 */
	private function perform_api_registration ( $data ) {
		global $woodojo;
		
		$response = $woodojo->api->register( $data );

		if ( isset( $response->response->code ) && $response->response->code == 0 && isset( $response->payload->errors ) ) {
			foreach ( (array)$response->payload->errors as $k => $v ) {
				$this->add_error( $v );
			}
		}
		
		// Keep track of error's, if there are any.
		$this->maybe_store_errors();
		
		return $response;
	} // End perform_api_registration()
	
	/**
	 * add_error function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param string $message
	 * @return void
	 */
	private function add_error ( $message ) {
		foreach ( (array)$message as $m ) {
			$this->errors[] = esc_attr( $m );
		}
	} // End add_error()
	
	/**
	 * maybe_store_errors function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function maybe_store_errors () {
		if ( count( $this->errors ) == 0 ) {
			delete_transient( $this->config->token . '-registration-errors' );
		} else {
			set_transient( $this->config->token . '-registration-errors', $this->errors );
		}
	} // End maybe_store_errors()
	
	/**
	 * get_fields function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @return array $fields
	 */
	private function get_fields () {
		$fields = array(
						'first_name' => __( 'First Name', 'woodojo' ), 
						'last_name' => __( 'Last Name', 'woodojo' ), 
						'username' => __( 'Username', 'woodojo' ), 
						'email' => __( 'E-mail Address', 'woodojo' ), 
						'pass1' => __( 'Password', 'woodojo' ), 
						'pass2' => __( 'Password Repeat', 'woodojo' ), 
						'component' => __( 'Component', 'woodojo' ), 
						'component_id' => __( 'Component ID', 'woodojo' )
					   );
					   
		return $fields;
	} // End get_fields()
	
	/**
	 * remember_posted_data function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @param array $data
	 * @return void
	 */
	private function remember_posted_data ( $data ) {
		$fields = $this->get_fields();
		$data = (array)$data;
		
		foreach ( $fields as $k => $v ) {
			if ( isset( $data[$k] ) && $data[$k] != '' && ! in_array( $k, array( 'pass1', 'pass2' ) ) ) {
				$this->posted[$k] = $data[$k];
			} else {
				$this->posted[$k] = '';
			}
			if ( in_array( $k, array( 'component', 'component_id' ) ) ) {
				$this->posted[$k] = $data[$k];
			}
		}
	} // End remember_posted_data()
	
	/**
	 * sanitize_userdata function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @param array $data
	 * @return array $data
	 */
	private function sanitize_userdata ( $data ) {
		foreach ( $data as $k => $v ) {
			$data[$k] = esc_attr( strip_tags( trim( $v ) ) );
		}
		return $data;
	} // End sanitize_userdata()
} // End Class
?>