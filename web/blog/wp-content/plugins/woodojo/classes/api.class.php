<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}

/**
 * WooDojo API Class
 *
 * All functionality pertaining to the WooDojo API interactions.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category API
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * var $api_url
 * var $token_expire_time
 * var $products_expire_time
 *
 * - __construct()
 * - request()
 * - auth()
 * - request_token()
 * - save_tokens()
 * - has_token()
 * - is_valid_token()
 * - get_products()
 * - get_products_by_type()
 * - request_remote_file()
 * - register()
 * - get_purchases()
 * - get_stored_purchases()
 * - save_purchase_data()
 * - refresh_purchase_data()
 * - get_settings()
 * - refresh()
 * - has_purchased()
 */
class WooDojo_API {
	public $token;
	public $api_url;
	private $token_expire_time;
	private $products_expire_time;
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct ( $token ) {
		$this->token = $token;
		$this->api_url = 'http://www.woothemes.com/woo-dojo-api/';
		$this->token_expire_time = 60 * 60 * 4; // 4 hours.
		$this->products_expire_time = 60 * 60 * 24 * 7; // 1 week.
		$this->settings_expire_time = 60 * 60 * 12; // 12 hours.
	} // End __construct()
	
	/**
	 * request function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $params
	 * @uses global $woodojo->base->token
	 * @return array $data
	 */
	public function request ( $params = array() ) {
		global $woodojo;

		$params['woodojo-version'] = $woodojo->version;

		$response = wp_remote_post( $this->api_url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(
				'user-agent'	=> 'WooDojo/' . $woodojo->version,
			),
			'body' => $params,
			'cookies' => array()
		    )
		);

		if( is_wp_error( $response ) ) {
		  $data = new StdClass();
		  $data->response->code = 0;
		  $data->response->message = __( 'WooDojo Request Error', 'woodojo' );
		} else {
			$data = $response['body'];
		}
		
		$data = json_decode( $data );

		delete_transient( $woodojo->base->token . '-request-error' );
		// Store errors in a transient, to be cleared on each request.
		if ( isset( $data->response->code ) && ( $data->response->code == 0 ) ) {
			set_transient( $woodojo->base->token . '-request-error', $data->response->message );
		}
		
		return $data;
	} // End request()
	
	/**
	 * auth function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses global $woodojo
	 * @return boolean $is_valid
	 */
	public function auth ( $redirect_to = '' ) {
		global $woodojo;
		
		$is_valid = false;
		$is_valid = $this->has_token();

		if ( ! $is_valid ) {} else {
			$is_valid = $this->is_valid_token();
		}

		if ( ! $is_valid ) {
			$redirect_url = '';
			if ( $redirect_to != '' ) {
				$redirect_url = '&redirect_to=' . urlencode( $redirect_to );
			}

			$component = WooDojo_Utils::get_or_post( 'component' );
			$component_id = WooDojo_Utils::get_or_post( 'component_id' );

			wp_redirect( admin_url( 'admin.php?page=' . $woodojo->base->token . '&screen=login&component=' . esc_attr( urlencode( $component ) ) . '&component_id=' . esc_attr( urlencode( $component_id ) ) . $redirect_url ) );
			exit;
		}
		
		return $is_valid;
	} // End auth()
	
	/**
	 * request_token function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @param string $user
	 * @param string $pass
	 * @uses global $woodojo->base->token
	 * @return boolean $is_valid
	 */
	public function request_token ( $user, $pass ) {
		global $woodojo;
		$is_valid = false;
		
		if ( $user != '' && $pass != '' ) {
			$args = array( 'action' => 'requesttoken', 'username' => esc_attr( $user ), 'password' => esc_attr( $pass ) );
			$response = $this->request( $args );

			if ( isset( $response->response->code ) ) { $is_valid = $response->response->code; }

			if ( $is_valid == 1 ) {
				$this->save_tokens( $response->payload->token, $response->payload->secret );
			}
		}
		
		return $is_valid;
	} // End request_token()
	
	/**
	 * save_tokens function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param string $token
	 * @param string $secret
	 * @uses global $woodojo->base->token
	 * @return void
	 */
	public function save_tokens ( $token, $secret ) {
		global $woodojo;
		if ( $token != '' && $secret != '' ) {
			set_transient( $woodojo->base->token . '-token', esc_attr( strip_tags( $token ) ), $this->token_expire_time );
			set_transient( $woodojo->base->token . '-secret', esc_attr( strip_tags( $secret ) ), $this->token_expire_time );
		}
	} // End save_tokens()
	
	/**
	 * has_token function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @uses global $woodojo->base->token.
	 * @return boolean $has_token
	 */
	private function has_token () {
		global $woodojo;
		$has_token = false;

		if ( ( false === ( $token = get_transient( $woodojo->base->token . '-token' ) ) ) || ( false === ( $secret = get_transient( $woodojo->base->token . '-secret' ) ) ) ) {
			$has_token = false;
		} else {
			$has_token = true;
		}
		
		return $has_token;
	} // End has_token()
	
	/**
	 * is_valid_token function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @uses global $woodojo->base->token
	 * @return boolean $is_valid
	 */
	public function is_valid_token () {
		global $woodojo;

		$is_valid = false;
		
		$is_valid = $this->has_token();

		if ( $is_valid == true ) {
			$args = array( 'action' => 'validatetoken' );
			$args['token'] = get_transient( $woodojo->base->token . '-token' );
			$args['secret'] = get_transient( $woodojo->base->token . '-secret' );
			
			$response = $this->request( $args );

			if ( isset( $response->response->code ) && ( $response->response->code == 1 ) ) {
				$is_valid = true;
			} else {
				$is_valid = false;
			}
		}

		return $is_valid;
	} // End is_valid_token()
	
	/**
	 * clear_tokens function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @uses global $woodojo->base->token
	 * @return void
	 */
	public function clear_tokens () {
		global $woodojo;
		delete_transient( $woodojo->base->token . '-token' );
		delete_transient( $woodojo->base->token . '-secret' );
		delete_transient( $woodojo->base->token . '-products' );
	} // End clear_tokens()
	
	/**
	 * get_products function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses $this->request()
	 * @uses global $woodojo->base->token
	 * @return array $products
	 */
	private function get_products () {
		global $woodojo;
		
		$products = array();
		$transient_key = $woodojo->base->token . '-products';
		
		if ( false === ( $products = get_transient( $transient_key ) ) ) {
			$args = array( 'action' => 'getwoodojoproducts' );
			$response = $this->request( $args );
			
			if ( isset( $response->response->code ) && ( $response->response->code == 1 ) && ( isset( $response->payload ) ) ) {
				$products = (array)$response->payload;
				set_transient( $transient_key, $products, $this->products_expire_time );
			}
		}

		return $products;
	} // End get_products()
	
	/**
	 * get_products_by_type function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param string $type (default: 'bundled')
	 * @return array $response
	 */
	public function get_products_by_type ( $type = 'bundled' ) {
		if ( ! in_array( $type, array( 'standalone', 'downloadable', 'bundled' ) ) ) { return array(); }
		
		$response = array();
		$products = $this->get_products();
		
		if ( count( (array)$products ) > 0 ) {
			foreach ( (array)$products as $k => $v ) {
				if ( isset( $v->type ) && ( $v->type == $type ) ) {
					$slug = $v->slug;
					
					$filepath = $slug . '/' . $slug . '.php';
					$v->filepath = $filepath;
					$response[$slug] = $v;
				}
			}
		}

		return $response;
	} // End get_products_by_type()
	
	/**
	 * request_remote_file function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param int $id
	 * @uses global $woodojo->base->token
	 * @return string $path
	 */
	public function request_remote_file ( $id ) {
		global $woodojo;

		$path = '';
		
		$args = array( 'action' => 'getwoodojodownload', 'product_id' => intval( $id ) );
		$args['token'] = get_transient( $woodojo->base->token . '-token' );
		$args['secret'] = get_transient( $woodojo->base->token . '-secret' );
	
		$response = $this->request( $args );

		// Check if the download is allowed or if there was an error.
		if ( isset( $response->response->code ) && ( $response->response->code == 1 ) && isset( $response->payload->download_url ) ) {
			$path = esc_url( $response->payload->download_url );
		}

		return $path;
	} // End request_remote_file()
	
	/**
	 * register function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param array $data
	 * @return array $response
	 */
	public function register ( $data ) {
		$args = array( 'action' => 'register' );
		$args = array_merge( $data, $args );

		$response = $this->request( $args );

		if ( isset( $response->response->code ) && $response->response->code == 1 && isset( $response->payload->token ) && isset( $response->payload->secret ) ) {
			$this->save_tokens( $response->payload->token, $response->payload->secret );
		}
		
		return $response;
	} // End register()

	/**
	 * get_purchases function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @uses $this->request()
	 * @uses global $woodojo->base->token
	 * @return array $purchases
	 */
	private function get_purchases () {
		global $woodojo;
		
		$purchases = array();
		
		$args = array( 'action' => 'getwoodojopurchases' );

		$token = get_transient( $woodojo->base->token . '-token' );
		$secret = get_transient( $woodojo->base->token . '-secret' );

		if ( $token != '' ) { $args['token'] = $token; }
		if ( $secret != '' ) { $args['secret'] = $secret; }

		if ( isset( $args['token'] ) && isset( $args['secret'] ) ) {
			$response = $this->request( $args );

			if ( isset( $response->response->code ) && ( $response->response->code == 1 ) && ( isset( $response->payload ) ) ) {
				$purchases = (array)$response->payload;
			}
		}

		return $purchases;
	} // End get_purchases()

	/**
	 * get_stored_purchases function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses global $woodojo->base->token
	 * @uses $this->get_purchases()
	 * @uses $this->save_purchase_data()
	 * @return array $response
	 */
	public function get_stored_purchases () {
		global $woodojo;

		$key = $woodojo->base->token . '-purchases';

		if ( get_option( $key ) == '' ) {
			$purchases = $this->get_purchases();
			$this->save_purchase_data( $purchases );

			$ids = array();

			foreach ( $purchases as $k => $v ) {
				if ( isset( $v->product_id ) ) { $ids[] = $v->product_id; }
			}

			$response = $ids;

		} else {
			$response = get_option( $key, array() );
		}

		return $response;
	} // End get_stored_purchases()

	/**
	 * save_purchase_data function.
	 * 
	 * @access private
	 * @since 1.0.0
	 * @uses global $woodojo->base->token
	 * @return boolean $is_updated
	 */
	private function save_purchase_data ( $purchases ) {
		global $woodojo;

		$is_updated = false;
		$key = $woodojo->base->token . '-purchases';
		$ids = array();
		
		if ( is_array( $purchases ) && ( count( $purchases ) > 0 ) ) {
			foreach ( $purchases as $k => $v ) {
				if ( isset( $v->product_id ) ) { $ids[] = $v->product_id; }
			}
		}

		$is_updated = update_option( $key, $ids );

		return $is_updated;
	} // End save_purchase_data()

	/**
	 * refresh_purchase_data function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses global $woodojo->base->token
	 * @return boolean $is_refreshed
	 */
	public function refresh_purchase_data () {
		$is_refreshed = false;

		$purchases = $this->get_purchases();
		$is_refreshed = $this->save_purchase_data( $purchases );

		return $is_refreshed;
	} // End refresh_purchase_data()

	/**
	 * get_settings function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @uses $this->request()
	 * @return array $settings
	 */
	public function get_settings () {
		$settings = array();
		$transient_key = $this->token . '-settings';

		if ( false === ( $settings = get_transient( $transient_key ) ) ) {

			$args = array( 'action' => 'woodojosettings' );
			$response = $this->request( $args );

			if ( ( $response->response->code == 1 ) && ( isset( $response->payload ) ) ) {
				$settings = $response->payload;

				set_transient( $transient_key, $settings, $this->settings_expire_time );
			}
		}

		return $settings;
	} // End get_settings()

	/**
	 * refresh function.
	 *
	 * @description Refresh everything (product data).
	 * @access public
	 * @since 1.0.0
	 * @uses global $woodojo->base->token
	 * @return boolean $is_refreshed
	 */
	public function refresh () {
		global $woodojo;

		$is_refreshed = false;

		delete_transient( $woodojo->base->token . '-products' );

		$this->get_products();

		$settings = $this->get_settings();

		$settings->refresh = 0;
		$is_refreshed = set_transient( $woodojo->base->token . '-settings', $settings, $this->settings_expire_time );

		return $is_refreshed;
	} // End refresh()

	/**
	 * has_purchased function.
	 *
	 * @description Check if a customer has purchased a specified product.
	 * @access public
	 * @since 1.0.0
	 * @param int $product_id
	 * @uses global $woodojo->base->token
	 * @return boolean $has_purchased
	 */
	public function has_purchased ( $product_id ) {
		global $woodojo;

		$has_purchased = false;

		$args = array( 'action' => 'woodojopurchased' );
		$args['token'] = get_transient( $woodojo->base->token . '-token' );
		$args['secret'] = get_transient( $woodojo->base->token . '-secret' );
		$args['product_id'] = intval( $product_id );
		
		$response = $this->request( $args );

		if ( isset( $response->response->code ) && ( $response->response->code == 1 ) ) {
			$has_purchased = true;
		} else {
			$has_purchased = false;
		}

		return $has_purchased;
	} // End has_purchased()
}
?>