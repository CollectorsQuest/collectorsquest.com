<?php
/**
 * Shadowbox class for admin actions
 *
 * This class contains all functions and actions required for Shadowbox to work in the admin of WordPress
 *
 * @package shadowbox-js
 * @subpackage Admin
 * @since 3.0.0.1
 */
class ShadowboxAdmin extends Shadowbox {

	/**
	 * Full file system path to the main plugin file
	 *
	 * @since 3.0.0.0
	 * @var string
	 */
	var $plugin_file;

	/**
	 * Path to the main plugin file relative to WP_CONTENT_DIR/plugins
	 *
	 * @since 3.0.0.0
	 * @var string
	 */
	var $plugin_basename;

	/**
	 * Name of options page hook
	 *
	 * @since 3.0.0.1
	 * @var string
	 */
	var $options_page_hookname;

	/**
	 * PHP 4 Style constructor which calls the below PHP5 Style Constructor
	 *
	 * @since 3.0.0.1
	 * @return none
	 */
	function ShadowboxAdmin () {
		$this->__construct();
	}

	/**
	 * Setup backend functionality in WordPress
	 *
	 * @return none
	 * @since 3.0.0.0
	 */
	function __construct () {
		Shadowbox::__construct ();

		if ( version_compare ( $this->get_option ( 'version' ) , $this->dbversion , '!=' ) && ! empty ( $this->options ) )
			$this->check_upgrade ();

		// Full path and plugin basename of the main plugin file
		$this->plugin_file = dirname ( dirname ( __FILE__ ) ) . '/shadowbox-js.php';
		$this->plugin_basename = plugin_basename ( $this->plugin_file );

		// ajax hooks so that we can build/output shadowbox.js
		add_action ( 'wp_ajax_shadowboxjs' , array ( &$this , 'build_shadowbox' ) );
		add_action ( 'wp_ajax_nopriv_shadowboxjs' , array ( &$this , 'build_shadowbox' ) );

		add_action ( 'wp_ajax_getshadowboxsrc' , array ( &$this , 'ajax_get_src' ) );

		// Load localizations if available
		load_plugin_textdomain ( 'shadowbox-js' , false , 'shadowbox-js/localization' );

		// Activation hook
		register_activation_hook ( $this->plugin_file , array ( &$this , 'init' ) );

		// Whitelist options
		add_action ( 'admin_init' , array ( &$this , 'register_settings' ) );

		// Activate the options page
		add_action ( 'admin_menu' , array ( &$this , 'add_page' ) ) ;

		add_filter ( 'explain_nonce_getshadowboxcreds' , 'nonce_oops' );
		add_filter ( 'explain_nonce_getshadowboxsrc' , 'nonce_oops' );

		if ( get_option ( 'shadowbox-js-missing-src' ) )
			add_action ( 'admin_notices' , array ( &$this , 'missing_src_notice' ) );
	}

	/**
	 * Callback function for explaining the failure of a nonce check specific to this plugin
	 *
	 * @return string
	 * @since 3.0.3.10
	 */
	function nonce_oops () {
		return __( "Oops, looks like you landed somewhere you shouldn't be." );
	}

	/**
	 * Display an admin notice if the Shadowbox JS source files are missing
	 *
	 * @since 3.0.3.10
	 */
	function missing_src_notice () {
		global $hook_suffix;
		if ( $hook_suffix == $this->options_page_hookname )
			return;

		$url = menu_page_url ( 'shadowbox-js', false );
		?>
		<div class="error">
			<p><?php _e( sprintf ( "<strong>The Shadowbox JS source files are missing</strong>. Please visit the <a href='%s'>Shadowbox JS Settings page</a> to resolve this issue." , $url ) , 'shadowbox-js' ); ?><p>
		</div>
		<?php
	}

	/**
	 * Whitelist the shadowbox-js options
	 *
	 * @since 3.0.0.1
	 * @return none
	 */
	function register_settings () {
		register_setting ( 'shadowbox' , 'shadowbox' , array ( &$this , 'update' ) );
	}

	/**
	 * Enqueue javascript required for the admin settings page
	 *
	 * @return none
	 * @since 2.0.3
	 */
	function admin_js () {
		wp_enqueue_script ( 'jquery' );
		wp_enqueue_script ( 'shadowbox-js-helper' , $this->plugin_url () . '/js/shadowbox-admin-helper.js' , array ( 'jquery' ) , $this->version , true );
		wp_localize_script ( 'shadowbox-js-helper' , 'shadowboxJsHelperL10n' , array (
			'advConfShow'	 => __( 'Show Advanced Configuration' , 'shadowbox-js' ) ,
			'advConfHide'	 => __( 'Hide Advanced Configuration' , 'shadowbox-js' ) ,
			'messageConfirm' => __( 'Do you agree that you are not using FLV support for commercial purposes or have already purchased a license for JW FLV Media Player?' , 'shadowbox-js' )
		) );
	}

	/**
	 * Output JS to listen for button clicks to retrieve the source files.
	 * This is only used when the user doesn't have to enter credentials for WP_Filesystem.
	 *
	 * @since 3.0.3.10
	 */
	function admin_footer_js () {
		?>
		<script type="text/javascript">
		/* <![CDATA[ */
			jQuery('#sbajaxgetsrc').click(function(e) {
				e.preventDefault();
				jQuery('#sbgetsrcinfo').load(
					ajaxurl,
					{
						action: 'getshadowboxsrc',
						_wpnonce: '<?php echo wp_create_nonce ( "getshadowboxsrc" ); ?>'
					}
				);
			});
		/* ]]> */
		</script>
		<?php
	}

	/**
	 * Enqueue CSS required for the admin settings page
	 *
	 * @return none
	 * @since 3.0.3
	 */
	function admin_css () {
		wp_enqueue_style ( 'shadowbox-admin-css' , apply_filters ( 'shadowbox-admin-css' , $this->plugin_url () . '/css/admin.css' ) , false , $this->version , 'screen' );
	}

	/**
	 * Return a list of the languages available to shadowbox.js
	 *
	 * @since 2.0.3.3
	 * @return array
	 */
	function languages () {
		$languages = array (
			'ar' ,    // Arabic
			'ca' ,    // Catalan
			'cs' ,    // Czech
			'da' ,    // Danish
			'de-CH' , // Swiss German
			'de-DE' , // German
			'en' ,    // English
			'es' ,    // Spanish
			'et' ,    // Estonian
			'fi' ,    // Finnish
			'fr' ,    // French
			'gl' ,    // Galician
			'he' ,    // Hebrew
			'hu' ,    // Hungarian
			'id' ,    // Indonesian
			'is' ,    // Icelandic
			'it' ,    // Italian
			'ja' ,    // Japanese
			'ko' ,    // Korean
			'my' ,    // Burmese
			'nl' ,    // Dutch
			'no' ,    // Norwegian
			'pl' ,    // Polish
			'pt-BR' , // Brazilian Portuguese
			'pt-PT' , // Portuguese
			'ro' ,    // Romanian
			'ru' ,    // Rusian
			'sk' ,    // Slovak
			'sv' ,    // Swedish
			'tr' ,    // Turkish
			'zh-CN' , // Chinese (Simplified)
			'zh-TW'   // Chinese (Traditional)
		);
		return $languages;
	}

	/**
	 * Try to set Shadowbox language based on defined language for WordPress
	 *
	 * @since 2.0.3.3
	 * @return string
	 */
	function set_lang () {
		if ( defined ( 'WPLANG' ) )
			$wp_lang = WPLANG;
		else
			$wp_lang = 'en';

		switch ( $wp_lang ) {
			case 'ar' :
				$lang = 'ar';    // Arabic
				break;
			case 'ca' :
				$lang = 'ca';    // Catalan
				break;
			case 'cs_CZ' :
				$lang = 'cs';    // Czech
				break;
			case 'da_DK' :
				$lang = 'da';    // Danish
				break;
			case 'de_DE' :
				$lang = 'de-DE'; // German
				break;
			case 'es_ES' :
				$lang = 'es';    // Spanish
				break;
			case 'et' :
				$lang = 'et';    // Estonian
				break;
			case 'fi' :
			case 'fi_FI':
				$lang = 'fi';    // Finnish
				break;
			case 'fr_BE' :
			case 'fr_FR' :
				$lang = 'fr';    // French
				break;
			case 'gl_ES' :
				$lang = 'gl';    // Galician
				break;
			case 'he_IL' :
				$lang = 'he';    // Hebrew
				break;
			case 'hu_HU' :
				$lang = 'hu';    // Hungarian
				break;
			case 'id_ID' :
				$lang = 'id';    // Indonesian
				break;
			case 'is_IS' :
				$lang = 'is';    // Icelandic
				break;
			case 'it_IT' :
				$lang = 'it';    // Italian
				break;
			case 'ja' :
				$lang = 'ja';    // Japanese
				break;
			case 'ko_KR' :
				$lang = 'ko';    // Korean
				break;
			case 'my_MM' :
				$lang = 'my';    // Burmese
				break;
			case 'nl' :
			case 'nl_NL' :
				$lang = 'nl';    // Dutch
				break;
			case 'nn_NO' :
				$lang = 'no';    // Norwegian
				break;
			case 'pl_PL' :
				$lang = 'pl';    // Polish
				break;
			case 'pt_BR' :
				$lang = 'pt-BR'; // Brazilian Portuguese
				break;
			case 'pt_PT' :
				$lang = 'pt-PT'; // Portuguese
				break;
			case 'ro' :
				$lang = 'ro';    // Romanian
				break;
			case 'ru_RU' :
			case 'ru_UA' :
				$lang = 'ru';    // Rusian
				break;
			case 'sk' :
				$lang = 'sk';    // Slovak
				break;
			case 'sv_SE' :
				$lang = 'sv';    // Swedish
				break;
			case 'tr' :
				$lang = 'tr';    // Turkish
				break;
			case 'zh_CN' :
				$lang = 'zh-CN'; // Chinese (Simplified)
				break;
			default :
				$lang = 'en';    // English
				break;
		}
		return $lang;
	}

	/**
	 * Return the default list of enabled players available with Shadowbox
	 *
	 * @return array
	 * @since 3.0.0.0
	 */
	function players () {
		$players = array (
			'html' ,
			'iframe' ,
			'img' ,
			'qt' ,
			'swf' ,
			'wmp'
		);
		return $players;
	}

	/**
	 * Return the default options
	 *
	 * @return array
	 * @since 2.0.3
	 */
	function defaults () {
		$defaults = array (
			'version'           => $this->dbversion ,
			'library'           => 'base' ,
			'language'          => $this->set_lang () ,
			'smartLoad'         => 'false' ,
			'useCache'          => 'true' ,
			'autoimg'           => 'true' ,
			'automov'           => 'true' ,
			'autotube'          => 'true' ,
			'autoaud'           => 'true' ,
			'autoflv'           => 'false' ,
			'enableFlv'         => 'false' ,
			'genericVideoWidth' => 640 ,
			'genericVideoHeight'=> 385 ,
			'autoDimensions'    => 'false' ,
			'animateFade'       => 'true' ,
			'animate'           => 'true' ,
			'animSequence'      => 'sync' ,
			'autoplayMovies'    => 'true' ,
			'continuous'        => 'false' ,
			'counterLimit'      => 10 ,
			'counterType'       => 'default' ,
			'displayCounter'    => 'true' ,
			'displayNav'        => 'true' ,
			'enableKeys'        => 'true' ,
			'fadeDuration'      => 0.35 ,
			'flashBgColor'      => '#000000' ,
			'flashParams'       => '{bgcolor:"#000000", allowFullScreen:true}' ,
			'flashVars'         => '{}' ,
			'flashVersion'      => '9.0.0' ,
			'handleOversize'    => 'resize' ,
			'handleUnsupported' => 'link' ,
			'initialHeight'     => 160 ,
			'initialWidth'      => 320 ,
			'modal'             => 'false' ,
			'overlayColor'      => '#000' ,
			'overlayOpacity'    => 0.8 ,
			'players'           => $this->players () ,
			'resizeDuration'    => 0.35 ,
			'showMovieControls' => 'true' ,
			'showOverlay'       => 'true' ,
			'skipSetup'         => 'false' ,
			'slideshowDelay'    => 0 ,
			'useSizzle'         => 'false' ,
			'viewportPadding'   => 20
		);
		return $defaults;
	}

	/**
	 * Initialize the default options during plugin activation
	 *
	 * @return none
	 * @since 2.0.3
	 */
	function init () {
		if ( ! get_option ( 'shadowbox' ) ) {
			$this->options = $this->defaults ();
			add_option ( 'shadowbox' , $this->options );
		} else {
			$this->check_upgrade ();
		}
		//$this->build_shadowbox ( true ); // Attempt to build and cache shadowbox.js
		if ( ! $this->check_for_src_file () )
			update_option ( 'shadowbox-js-missing-src', true );
	}

	/**
	 * Check if an upgraded is needed
	 *
	 * @return none
	 * @since 3.0.0.1
	 */
	function check_upgrade () {
		if ( $this->version_compare ( array ( '3.0.0.0' => '<' ) ) )
			$this->upgrade ( '3.0.0.0' );
		else if ( $this->version_compare ( array ( '3.0.0.0' => '>' , '3.0.0.2' => '<' ) ) )
			$this->upgrade ( '3.0.0.2' );
		else if ( $this->version_compare ( array ( '3.0.0.2' => '>' , '3.0.3' => '<' ) ) )
			$this->upgrade ( '3.0.3' );
		else if ( $this->version_compare ( array ( '3.0.3' => '>' , '3.0.3.3' => '<' ) ) )
			$this->upgrade ( '3.0.3.3' );
	}

	/**
	 * Compare Versions
	 *
	 * @param array Array of the version you want to compare to the version stored in the database as the key and the operator as the value
	 * @return bool
	 * @since 3.0.0.2
	 */
	function version_compare ( $versions ) {
		foreach ( $versions as $version => $operator ) {
			if ( version_compare ( $this->get_option ( 'version' ) , $version , $operator ) )
				$response = true;
			else
				$response = false;
		}
		return $response;
	}

	/**
	 * Check to see whether the user must be prompted for connection details by WP_Filesystem
	 *
	 * @since 3.0.3.10
	 * @return bool
	 */
	function can_modify_fs () {
		ob_start();
		if ( false !== ( $credentials = request_filesystem_credentials ( '' ) ) ) {
			ob_end_clean ();
			return true;
		} else {
			ob_end_clean ();
			return false;
		}
	}

	/**
	 * Check to see if the Shadowbox source files exist and that they are the correct version.
	 *
	 * @return bool
	 * @since 3.0.3.10
	 */
	function check_for_src_file () {
		$uploads = wp_upload_dir ();
		if ( empty( $uploads['error'] ) && ! empty( $uploads['basedir'] ) )
			$basedir = $uploads['basedir'];
		else
			$basedir = WP_CONTENT_DIR . '/uploads';

		$status = true;
		if ( ! file_exists ( $basedir . '/shadowbox-js/src/intro.js' ) )
			$status = false;

		if ( version_compare ( trim ( @file_get_contents ( $basedir . '/shadowbox-js/src/VERSION' ) ) , $this->sbversion ) !== 0 )
			$status = false;

		if ( $status === false )
			update_option ( 'shadowbox-js-missing-src', true );

		return $status;
	}

	/**
	 * Upgrade options
	 *
	 * @return none
	 * @since 3.0.0.0
	 */
	function upgrade ( $ver ) {
		if ( $ver == '3.0.0.0' ) { // Upgrades for versions below 3.0.0.0
			$newopts = array (
				'version'           => '3.0.0.0' ,
				'smartLoad'         => 'false' ,
				'enableFlv'         => 'false' ,
				'tubeWidth'         => 640 ,
				'tubeHeight'        => 385 ,
				'players'           => $this->players () ,
				'autoDimensions'    => 'false' ,
				'showOverlay'       => 'true' ,
				'skipSetup'         => 'false' ,
				'flashParams'       => '{bgcolor:"#000000", allowFullScreen:true}' ,
				'flashVars'         => '{}' ,
				'flashVersion'      => '9.0.0'
			);
			unset ( $this->options['ie8hack'] , $this->options['skin'] );
			$this->options = array_merge ( $this->options , $newopts );
			update_option ( 'shadowbox' , $this->options );
		} else if ( $ver == '3.0.0.2' ) { // Upgrades for versions below 3.0.0.2
			$newopts = array (
				'version'           => '3.0.0.2' ,
				'useSizzle'         => 'false' ,
				'genericVideoHeight'=> $this->options['tubeHeight'] ,
				'genericVideoWidth' => $this->options['tubeWidth']
			);
			if ( $this->options['enableFlv'] == 'true' )
				$newopts['autoflv'] = 'true';
			else
				$newopts['autoflv'] = 'false';
			unset ( $this->options['tubeHeight'] , $this->options['tubeWidth'] );
			$this->options = array_merge ( $this->options , $newopts );
			update_option ( 'shadowbox' , $this->options );
		} else if ( $ver == '3.0.3' ) { // Upgrades for versions below 3.0.3
			$this->options['version'] = '3.0.3';
			if ( in_array ( $this->options['library'] , array( 'ext' , 'dojo') ) )
				$this->options['library'] = 'base';
			update_option ( 'shadowbox', $this->options );
		} else if ( $ver == '3.0.3.3' ) { // Upgrades for versions below 3.0.3.3
			$this->options['version'] = '3.0.3.3';
			$this->options['useCache'] = 'true';
			update_option ( 'shadowbox' , $this->options );
		}
		$this->check_upgrade ();
	}

	/**
	 * Update/validate the options in the options table from the POST
	 *
	 * @since 3.0.0.1
	 * @return none
	 */
	function update ( $options ) {
		// Make sure there are no empty values, seems users like to clear out options before saving
		foreach ( $this->defaults () as $key => $value ) {
			if ( ( ! isset ( $options[$key] ) || empty ( $options[$key] ) ) && $key != 'delete' && $key != 'default' && $key != 'players' )
				$options[$key] = $value;
		}
		if ( isset ( $options['delete'] ) && $options['delete'] == 'true' ) { // Check if we are supposed to remove options
			delete_option ( 'shadowbox' );
		} else if ( isset ( $options['default'] ) && $options['default'] == 'true' ) { // Check if we are supposed to reset to defaults
			$this->options = $this->defaults ();
			$this->build_shadowbox ( true ); // Attempt to build and cache shadowbox.js
			return $this->options;
		} else {
			if ( ! isset ( $options['autoflv'] ) || $options['enableFlv'] == 'false' )
				$options['autoflv'] = 'false';
			unset ( $options['delete'] , $options['default'] );
			$this->options = $options;
			$this->build_shadowbox ( true ); // Attempt to build and cache shadowbox.js
			return $this->options;
		}
	}

	/**
	 * Build the JS output for shadowbox.js
	 *
	 * Shadowbox.js is now built in a very specific order,
	 * so to dynamically load what we want, we need to build
	 * the JavaScript dynamically, this causes issues with
	 * determining the path to shadowbox.js also, so we have to
	 * do some hacks further down too.
	 *
	 * @since 3.0.3
	 * @param $tofile Boolean write output to file instead of echoing
	 * @return mixed false if the file could not be built and a string of the file location if successful
	 */
	function build_shadowbox ( $tofile = false ) {
		// If the user is filtering the url for shadowbox.js just bail out here
		if ( has_filter ( 'shadowbox-js' ) )
			return;

		$plugin_url = $this->plugin_url ();
		//$plugin_dir = WP_PLUGIN_DIR . '/' . dirname ( $this->plugin_basename );

		$uploads = wp_upload_dir ();
		if ( empty( $uploads['error'] ) && ! empty( $uploads['basedir'] ) )
			$basedir = $uploads['basedir'];
		else
			$basedir = WP_CONTENT_DIR . '/uploads';

		$shadowbox_dir = "$basedir/shadowbox-js/";
		$shadowbox_src_dir = "{$shadowbox_dir}src";

		// Ouput correct content-type, and caching headers
		if ( ! $tofile )
			cache_javascript_headers();

		$output = wp_cache_get ( $this->md5 () , 'shadowbox-js' );

		if ( empty ( $output ) ) {

			$output = '';

			// Start build
			foreach ( array ( 'intro' , 'core' , 'util' ) as $include ) {
				// Replace S.path with the correct path, so we don't have to rely on autodetection which is broken with this method
				if ( $include == 'core' )
					$output .= str_replace ( 'S.path=null;' , "S.path='$plugin_url/shadowbox/';" , file_get_contents ( "$shadowbox_src_dir/$include.js" ) );
				else
					$output .= file_get_contents ( "$shadowbox_src_dir/$include.js" );
			}

			$library = $this->get_option ( 'library' );
			$output .= file_get_contents ( "$shadowbox_src_dir/adapters/$library.js" );

			foreach ( array ( 'load' , 'plugins' , 'cache' ) as $include )
				$output .= file_get_contents ( "$shadowbox_src_dir/$include.js" );

			if ( $this->get_option ( 'useSizzle' ) == 'true' && $this->get_option ( 'library' ) != 'jquery' )
				$output .= file_get_contents ( "$shadowbox_src_dir/find.js" );

			$players = (array) $this->get_option ( 'players' );
			if ( in_array ( 'flv' , $players ) || in_array ( 'swf' , $players ) )
				$output .= file_get_contents ( "$shadowbox_src_dir/flash.js" );

			$language = $this->get_option ( 'language' );
			$output .= file_get_contents ( "$shadowbox_src_dir/languages/$language.js" );

			foreach ( $players as $player )
				$output .= file_get_contents ( "$shadowbox_src_dir/players/$player.js" );

			foreach ( array ( 'skin' , 'outro' ) as $include )
				$output .= file_get_contents ( "$shadowbox_src_dir/$include.js" );

			wp_cache_set ( $this->md5 () , 'shadowbox-js' );

		}

		// if we are supposed to write to a file then do so
		if ( $tofile && $this->get_option ( 'useCache' ) == 'true' ) {
			$shadowbox_file = $shadowbox_dir . $this->md5 () . '.js';

			if ( ! is_dir ( $shadowbox_dir ) && is_writable ( $basedir ) )
				wp_mkdir_p ( $shadowbox_dir );

			if ( ! file_exists ( $shadowbox_file ) && is_dir ( $shadowbox_dir ) && is_writable ( $shadowbox_dir ) ) {
				$fh = @fopen ( $shadowbox_file, 'w+' );
				@fwrite ( $fh , $output );
				@fclose ( $fh );
				$chmod = defined ( 'FS_CHMOD_FILE' ) ? FS_CHMOD_FILE : 0644;
				if ( ( fileperms ( $shadowbox_dir ) & 0777 ) != $chmod )
					@chmod ( $shadowbox_file , $chmod );
			}

			if ( ! file_exists ( $shadowbox_file ) )
				return false;
			else
				return $shadowbox_file;

		} else if ( ! $tofile ) { // otherwise just echo (backup call to admin-ajax.php for on the fly building)
			die ( $output );
		}
	}

	/**
	 * AJAX provileged callback for retrieving the shadowbox source, hands off to ShadowboxAdmin::get_src()
	 *
	 * @since 3.0.3.10
	 */
	function ajax_get_src () {
		if ( ! current_user_can ( 'update_plugins' ) || ! check_admin_referer ( 'getshadowboxsrc' ) )
			die ( __( "Uh, Oh! You've been a bad boy!" , 'shadowbox-js' ) );

		die ( $this->get_src () );
	}

	/**
	 * This function will retrieve the shadowbox source via the HTTP API and move the files
	 * into place using WP_Filesystem
	 *
	 * @param string credentials from request_filesystem_credentials()
	 * @since 3.0.3.10
	 */
	function get_src ( $creds = '' ) {
		global $wp_filesystem;

		if ( empty ( $creds ) )
			$creds = request_filesystem_credentials ( '' );

		if ( ! WP_Filesystem ( $creds ) )
			die ( '<p>' . __( 'Could not setup WP_Filesystem' ) . '</p>' );

		echo "<p>" . __( sprintf ( "Downloading source package from <span class='code'>http://dl.sivel.net/wordpress/plugin/shadowbox-js-src.%s.zip</span>&#8230;", $this->sbversion ) , 'shadowbox-js' ) . "</p>";
		$tempfname = download_url ( "http://dl.sivel.net/wordpress/plugin/shadowbox-js-src.{$this->sbversion}.zip" );

		if ( is_wp_error ( $tempfname ) )
			die ( "<p>" . __( sprintf ( "Could not download the file (%s)" , $tempfname->get_error_message () ) , 'shadowbox-js' ) . "</p>" );

		if ( ! file_exists ( $tempfname ) )
			die ( '<p>' . __( 'File downloaded but does not appear to exist, or is unreadable' , 'shadowbox-js' ) . '</p>' );

		$dlmd5response = wp_remote_get ( "http://dl.sivel.net/wordpress/plugin/shadowbox-js-src.{$this->sbversion}.md5" );
		if ( is_wp_error ( $dlmd5response ) || (int) wp_remote_retrieve_response_code ( $dlmd5response ) !== 200 ) {
			echo "<p>" . __( sprintf ( "Failed to download the md5 checksum file. Continuing&#8230; (%s)" , $dlmd5response->get_error_message () ) , 'shadowbox-js' ) . "</p>";
		} else {
			if ( md5_file ( $tempfname ) != current ( explode ( '  ' , wp_remote_retrieve_body ( $dlmd5response ) ) ) )
				die ( '<p>' . __( 'MD5 checksum failed. Exiting&#8230;' , 'shadowbox-js' ) . '</p>' );
		}

		$uploads = wp_upload_dir ();
		if ( empty ( $uploads['error'] ) && ! empty ( $uploads['basedir'] ) )
			$basedir = $uploads['basedir'];
		else
			$basedir = WP_CONTENT_DIR . '/uploads';

		$basedir = trailingslashit ( untrailingslashit ( $wp_filesystem->find_folder ( dirname ( $basedir ) ) ) ) . basename ( $basedir );

		$srcdir = $basedir . '/shadowbox-js/src';

		$srcfiles = $wp_filesystem->dirlist ( $srcdir );
		if ( ! empty ( $srcfiles ) ) {
			echo '<p>' . __( 'Clearing out the current Shadowbox JS source directory&#8230;' , 'shadowbox-js' ) . '</p>';
			foreach ( $srcfiles as $file )
				$wp_filesystem->delete ( $srcdir . $file['name'] , true );
		}

		if ( $wp_filesystem->is_dir ( $srcdir ) ) {
			echo '<p>' . __( 'Removing the current Shadowbox JS source directory&#8230;' , 'shadowbox-js' ) . '</p>';
			$wp_filesystem->delete ( $srcdir , true );
		}

		echo "<p>" . __( sprintf ( "Unpacking the Shadowbox JS source files to <span class='code'>%s</span>&#8230;" , $srcdir ) , 'shadowbox-js' ) . "</p>";
		$result = unzip_file ( $tempfname , dirname ( $srcdir ) );
		unlink ( $tempfname );

		if ( $result === true ) {
			echo '<p>' . __( 'Successfully retrieved and extracted the <strong>Shadowbox JS</strong> source files' , 'shadowbox-js' ) . '</p>';
			update_option ( 'shadowbox-js-missing-src' , false );
		} else {
			die ( "<p>" . __( sprintf ( "Failed to extract the <strong>Shadowbox JS</strong> source files (%s)" , $result->get_error_data () ) , 'shadowbox-js' ) . "</p>" );
		}

		echo '<p>' . __( 'Attempting to build and cache the compiled shadowbox.js...' , 'shadowbox-js' ) . '</p>';
		$buildresult = $this->build_shadowbox ( true );
		if ( $buildresult )
			echo "<p>" . __( sprintf ( "Successfully built and cached the compiled shadowbox.js to <span class='code'>%s</span>" , $buildresult ) , 'shadowbox-js' ) . "</p>";
		else
			echo '<p>' . __( 'Failed to build and cache the compiled shadowbox.js. This is ok, the plugin should still be able to function' , 'shadowbox-js' ) . '</p>';
	}

	/**
	 * Add the options page
	 *
	 * @return none
	 * @since 2.0.3
	 */
	function add_page () {
		if ( current_user_can ( 'manage_options' ) ) {
			$callback = 'admin_page';
			if ( isset ( $_GET['sbgetsrc'] ) && (int) $_GET['sbgetsrc'] === 1 )
				$callback = 'get_src_admin_page';

			$this->options_page_hookname = add_options_page ( __( 'Shadowbox JS' , 'shadowbox-js' ) , __( 'Shadowbox JS' , 'shadowbox-js' ) , 'manage_options' , 'shadowbox-js' , array ( &$this , $callback ) );
			add_action ( "admin_print_scripts-{$this->options_page_hookname}" , array ( &$this , 'admin_js' ) );
			add_action ( "admin_print_styles-{$this->options_page_hookname}" , array ( &$this , 'admin_css' ) );
			add_filter ( "plugin_action_links_{$this->plugin_basename}" , array ( &$this , 'filter_plugin_actions' ) );

			add_action ( "admin_footer-{$this->options_page_hookname}" , array ( &$this , 'admin_footer_js' ) );

			if ( ! $this->check_for_src_file () )
				add_meta_box ( 'sbgetsrcinfometabox' , __( 'Missing Shadowbox JS Source Files!' , 'shadowbox-js' ) , array ( &$this , 'srcinfo_meta_box' ) , 'shadowbox-js' , 'normal' , 'high' );
		}
	}

	/**
	 * Callback for the shadowbox-js meta box display, to inform the user that
	 * the Shadowbox source files must be downloaded
	 *
	 * @since 3.0.3.10
	 */
	function srcinfo_meta_box () {
		?>
		<div id="sbgetsrcinfo">
			<p><?php _e( '<strong>You are missing the Shadowbox source files. Do you wish to retrieve them? You will likely be unable to use this plugin until you do.</strong>', 'shadowbox-js' ); ?></p>
			<p><?php _e( sprintf ( '<strong>NOTE:</strong> This action will cause this plugin to download the source from this plugin authors site.<br />
			If you are concerned about this action "phoning home" you can download the source from<br />
			<span class="code"><a href="http://dl.sivel.net/wordpress/plugin/shadowbox-js-src.%1$s.zip">http://dl.sivel.net/wordpress/plugin/shadowbox-js-src.%1$s.zip</a></span> and extract to <span class="code">wp-content/uploads/shadowbox-js/src</span>' , $this->sbversion ) , 'shadowbox-js' ); ?></p>
			<p><?php _e( 'Shadowbox is licensed under the terms of the <a href="http://shadowbox-js.com/LICENSE" target="_blank">Shadowbox.js License</a>. This license grants personal, non-commercial users the right to use Shadowbox without paying a fee. It also provides an option for users who wish to use Shadowbox for commercial purposes. You are encouraged to review the terms of the license before using Shadowbox. If you would like to use Shadowbox for commercial purposes, you can purchase a license from <spann class="code"><a href="http://www.shadowbox-js.com/" target="_blank">http://www.shadowbox-js.com/</a></span>.' ); ?></p>
			<p><?php _e( 'This plugin also makes use of the <a href="http://www.longtailvideo.com/players/jw-flv-player/" target="_blank">JW FLV Player</a>. JW FLV Player is licensed under the terms of the <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported License</a>. If you would like to use JW FLV Player for commercial purposes, you can purchase a license from <span class="code"><a href="https://www.longtailvideo.com/players/order2" target="_blank">https://www.longtailvideo.com/players/order2</a></span>.' ); ?></p>
			<?php if ( $this->can_modify_fs () ) : ?>
			<p><a class="button" id="sbajaxgetsrc" href="#"><?php _e( 'Get Shadowbox Source Files' , 'shadowbox-js' ); ?></a></p>
			<?php else: ?>
			<p><a class="button" href="<?php echo wp_nonce_url ( add_query_arg ( array ( 'sbgetsrc' => 1 ) , menu_page_url ( 'shadowbox-js' , false ) ) , 'getshadowboxsrc' ); ?>"><?php _e( 'Get Shadowbox Source Files' , 'shadowbox-js' ); ?></a></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Add a settings link to the plugin actions
	 *
	 * @param array $links Array of the plugin action links
	 * @return array
	 * @since 2.0.3
	 */
	function filter_plugin_actions ( $links ) {
		$settings_link = '<a href="' . menu_page_url ( 'shadowbox-js' , false ) . '">' . __( 'Settings' , 'shadowbox-js' ) . '</a>';
		array_unshift ( $links, $settings_link );
		return $links;
	}

	/**
	 * Output the options page
	 *
	 * @return none
	 * @since 2.0.3
	 */
	function admin_page () {
		if ( ! @include ( 'options-page.php' ) ) {
			_e ( sprintf ( '<div id="message" class="updated fade"><p>The options page for the <strong>Shadowbox JS</strong> cannot be displayed.  The file <strong>%s</strong> is missing.  Please reinstall the plugin.</p></div>' , dirname ( __FILE__ ) . '/options-page.php' ) );
		}
	}

	/**
	 * Execute request_filesystem_credentials to get the connection credentials and verify them
	 *
	 * @return string
	 * @since 3.0.3.10
	 */
	function request_fs_credentials () {
		global $wp_filesystem;

		check_admin_referer ( 'getshadowboxsrc' );

		$url = wp_nonce_url ( add_query_arg ( array ( 'sbgetsrc' => 1 ) , menu_page_url ( 'shadowbox-js' , false ) ) , 'getshadowboxsrc' );
		if ( false === ( $creds = request_filesystem_credentials ( $url , '' , false , false ) ) )
			return true;

		if ( ! WP_Filesystem ( $creds ) ) {
			request_filesystem_credentials ( $url , '' , true , false );
			return true;
		}

		return $creds;
	}

	/**
	 * Admin "options" page callback to handle the retrieval of Shadowbox source when
	 * filesystem connection credentials were required of the user
	 *
	 * @since 3.0.3.10
	 */
	function get_src_admin_page () {
		if ( $this->request_fs_credentials () === true )
			return;

		check_admin_referer ( 'getshadowboxsrc' );
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br /></div>
			<h2><?php _e( 'Retrieving Shadowbox JS source package' , 'shadowbox-js' ); ?></h2>
		<?php
			$this->get_src ( $creds );

			$url = menu_page_url ( 'shadowbox-js' , false );
		?>
			<p><a href='<?php echo $url; ?>'><?php _e( 'Return to the Shadowbox JS settings page' , 'shadowbox-js' ); ?></a></p>
		</div>
		<?php
	}
}
