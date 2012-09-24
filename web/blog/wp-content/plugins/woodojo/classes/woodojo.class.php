<?php
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'Please do not load this screen directly. Thanks!' );
}

/**
 * WooDojo Class
 *
 * The main WooDojo class.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category Core
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * var $version
 * var $base
 * var $admin
 * var $frontend
 * var $settings
 *
 * - __construct()
 * - load_localisation()
 * - load_plugin_textdomain()
 * - activation()
 * - register_plugin_version()
 */
class WooDojo {
	private $file;
	public $version;
	public $base;
	public $admin;
	public $frontend;
	public $settings;
	public $updater;

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct( $file ) {
		$this->version = '';
		$this->file = $file;

		require_once( 'base.class.php' );
		require_once( 'api.class.php' );
		require_once( 'utils.class.php' );
		require_once( 'settings-api.class.php' );
		require_once( 'updater.class.php' );
		
		$this->base = new WooDojo_Base();
		$this->api = new WooDojo_API( $this->base->token );
		$this->updater = new WooDojo_Updater( $file );
		
		$this->load_plugin_textdomain();
		add_action( 'init', array( &$this, 'load_localisation' ), 0 );

		if ( is_admin() ) {
			require_once( 'admin.class.php' );
			$this->admin = new WooDojo_Admin();
		} else {
			require_once( 'frontend.class.php' );
			$this->frontend = new WooDojo_Frontend();
		}

		// Run this on activation.
		register_activation_hook( $file, array( &$this, 'activation' ) );
	} // End __construct()

	/**
	 * load_localisation function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_localisation () {
		$lang_dir = trailingslashit( str_replace( 'classes', 'lang', basename( dirname(__FILE__) ) ) );
		load_plugin_textdomain( 'woodojo', false, $lang_dir );
	} // End load_localisation()

	/**
	 * Load the plugin textdomain from the main WordPress "languages" folder.
	 * @since  1.2.3
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'woodojo';
	    // The "plugin_locale" filter is also used in load_plugin_textdomain()
	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	 
	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain()

	/**
	 * activation function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * register_plugin_version function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( $this->base->token . '-version', $this->version );
		}
	} // End register_plugin_version()
} // End Class
?>