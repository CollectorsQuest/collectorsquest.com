<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}

/**
 * WooDojo Purchase Model
 *
 * The model for the product purchase screen.
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
 * var $load_screen
 * var $component_type
 * var $component_token
 *
 * - __construct()
 * - parse_component_data()
 * - get_component_data()
 * - get_payment_url()
 * - enqueue_scripts()
 * - redirect_after_purchase()
 */
class WooDojo_Model_Purchase extends WooDojo_Model {
	var $component;
	var $components;
	var $load_screen;
	var $has_purchased;

	private $component_type;
	private $component_token;
	private $payment_url;
	
	public function __construct() {
		global $woodojo;

		parent::__construct();
		$this->load_screen = true;
		$this->has_purchased = false;

		$this->component = array();

		$this->payment_url = esc_url( $woodojo->settings->checkout_url );

		$this->load_components();
		$this->parse_component_data();
		$this->get_component_data();

		$purchase_status = '';
		if ( isset( $_GET['purchase-status'] ) ) {
			$purchase_status = $_GET['purchase-status'];
		} else if ( isset( $_POST['purchase-status'] ) ) {
			$purchase_status = $_POST['purchase-status'];
		}

		if ( $purchase_status != '' && in_array( $purchase_status, array( 'success', 'error' ) ) ) {
			add_action( 'admin_head', array( &$this, 'redirect_after_purchase' ) );
			$this->load_screen = false;
		} else {
			$this->has_purchased = $woodojo->api->has_purchased( $this->component->product_id );
		}

		if ( ! $this->has_purchased ) {
			add_action( 'admin_print_styles', array( &$this, 'enqueue_scripts' ) );
		}
	} // End __construct()
	
	/**
	 * parse_component_data function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function parse_component_data () {
		$component = '';
		if ( isset( $_GET['component'] ) ) {
			$component = $_GET['component'];
		} else if ( isset( $_POST['component'] ) ) {
			$component = $_POST['component'];
		}

		$component_type = '';
		if ( isset( $_GET['component-type'] ) ) {
			$component_type = $_GET['component-type'];
		} else if ( isset( $_POST['component-type'] ) ) {
			$component_type = $_POST['component-type'];
		}

		if ( $component != '' ) {
			$this->component_token = strtolower( strip_tags( trim( esc_attr( $component ) ) ) );
		}
		
		if ( ( $component_type != '' ) && in_array( $component_type, array( 'standalone', 'downloadable', 'bundled' ) ) ) {
			$this->component_type = strtolower( strip_tags( trim( esc_attr( $component_type ) ) ) );
		}
	} // End parse_component_data()
	
	/**
	 * get_component_data function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function get_component_data () {
		$this->component = $this->components[$this->component_type][$this->component_token];
	} // End get_component_data()

	/**
	 * get_payment_url function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses global $woodojo->base->token
	 * @return void
	 */
	public function get_payment_url () {
		global $woodojo;

		$url = $this->payment_url;

		$args = array();

		$component_id = '';
		if ( isset( $_GET['component_id'] ) ) {
			$component_type = $_GET['component_id'];
		} else if ( isset( $_POST['component_id'] ) ) {
			$component_type = $_POST['component_id'];
		}

		if ( $component_id != '' ) {
			$args['product_id'] = intval( $component_id );
		}


		$args['token'] = get_transient( $woodojo->base->token . '-token' );

		$url .= '?';
		$count = 0;
		foreach ( $args as $k => $v ) {
			$count++;
			$url .= $k . '=' . $v;
			if ( $count < count( $args ) ) {
				$url .= '&';
			}
		}

		return $url;
	} // End get_payment_url()

	/**
	 * enqueue_scripts function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts () {
		wp_register_script( $this->config->token . '-purchase', $this->config->assets_url . 'js/purchase.js', array( 'jquery' ), '1.0.0' );
		
		wp_enqueue_script( $this->config->token . '-purchase' );
	} // End enqueue_scripts()

	/**
	 * redirect_after_purchase function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses global $woodojo->base->token
	 * @uses global $woodojo->api->refresh_purchase_data();
	 * @return void
	 */
	public function redirect_after_purchase () {
		global $woodojo;

		// Construct appropriate redirect URL.
		$url = admin_url( 'admin.php?page=' . $woodojo->base->token );

		$args = array();

		$purchase_status = '';
		if ( isset( $_GET['purchase-status'] ) ) {
			$purchase_status = $_GET['purchase-status'];
		} else if ( isset( $_POST['purchase-status'] ) ) {
			$purchase_status = $_POST['purchase-status'];
		}

		$component = '';
		if ( isset( $_GET['component'] ) ) {
			$component = $_GET['component'];
		} else if ( isset( $_POST['component'] ) ) {
			$component = $_POST['component'];
		}

		$component_type = '';
		if ( isset( $_GET['component-type'] ) ) {
			$component_type = $_GET['component-type'];
		} else if ( isset( $_POST['component-type'] ) ) {
			$component_type = $_POST['component-type'];
		}

		if ( $purchase_status == 'success' ) {
			$args['download-component'] = urlencode( $component );
			$args['component-type'] = urlencode( $component_type );
			$args['_wpnonce'] = wp_create_nonce( urlencode( $component ) );
		} else {
			$args['purchase-error'] = urlencode( $component );
			$args['type'] = urlencode( $component_type );
		}

		// Refresh purchase data.
		$woodojo->api->refresh_purchase_data();

		foreach ( $args as $k => $v ) {
			$url .= '&' . $k . '=' . $v;
		}

		wp_redirect( $url );
		exit;
	} // End redirect_after_purchase()
} // End Class
?>