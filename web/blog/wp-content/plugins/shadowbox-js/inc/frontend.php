<?php
/**
 * Shadowbox class for frontend actions
 *
 * This class contains all functions and actions required for Shadowbox to work on the frontend of WordPress
 *
 * @since 3.0.0.1
 * @package shadowbox-js
 * @subpackage Frontend
 */
 class ShadowboxFrontend extends Shadowbox {

	/**
	 * PHP 4 Style constructor which calls the below PHP5 Style Constructor
	 *
	 * @since 3.0.0.1
	 * @return none
	 */
	function ShadowboxFrontend () {
		$this->__construct();
	}

	/**
	 * Setup frontend functionality for WordPress
	 *
	 * @return none
	 * @since 3.0.0.0
	 */
	function __construct () {
		Shadowbox::__construct ();

		// Only add these actions when we have options in the database
		// This allows for selecting to delete options from the admin page
		if ( ! empty ( $this->options ) ) {
			add_action ( 'init' , array ( &$this , 'styles' ) , 7 );
			add_action ( 'init' , array ( &$this , 'scripts' ) , 8 );
			if ( $this->get_option ( 'smartLoad' ) == 'false' )
				add_action ( 'wp_footer' , array ( &$this , 'configure' ) , 21 );

			// Automatically add Shadowbox to links
			if ( $this->is_automatic ( 'any' ) ) {
				if ( $this->get_option ( 'smartLoad' ) == 'false' )
					add_filter ( 'the_content', array ( &$this, 'add_attr_to_link' ) , 12 , 2 );
				else
					add_filter ( 'the_posts' , array ( &$this , 'process_posts_early' ) , 10 , 2 );
				add_filter ( 'wp_get_attachment_link' , array ( &$this , 'add_attr_to_link' ) , 12 , 2 );
			}

			// If we are automating for images make sure we link directly to the attachment
			if ( $this->is_automatic ( 'img' ) )
				add_filter ( 'attachment_link' , array ( &$this , 'attachment_direct_linkage' ) , 10 , 2 );

			if ( ! function_exists ( 'get_admin_url' ) )
				add_filter ( 'admin_url' , array ( &$this , 'admin_url_scheme' ) );
		}
	}

	/**
	 * Do processing on the_posts instead of the_content, this allows us to determine
	 * if we should enqueue JS and CSS early enough to not have to perform "hacks" to
	 * get JS to load in the footer and to keep the CSS from loading when we don't
	 * need it
	 *
	 * @since 3.0.3.3
	 * @param array $posts posts
	 * @param object $context provided context from the apply_filters call
	 * @return array
	 */
	function process_posts_early ( $posts, $context ) {
		if ( ! empty ( $posts ) ) {
			for ( $i = 0; $i < count ( $posts ); $i++ ) {
				$posts[$i]->post_content = $this->add_attr_to_link ( $posts[$i]->post_content , $posts[$i] );
				if ( preg_match ( '%(?<!\[)\[gallery[ \]]%i', $posts[$i]->post_content ) && $this->get_option ( 'smartLoad' ) == 'true' )
					add_action( 'wp_enqueue_scripts', array( &$this, 'possibly_enqueue' ) );
				if ( ! empty ( $posts[$i]->post_excerpt ) ) {
					$posts[$i]->post_excerpt = $this->add_attr_to_link ( $posts[$i]->post_excerpt , $posts[$i] );
					if ( preg_match ( '%(?<!\[)\[gallery[ \]]%i', $posts[$i]->post_excerpt ) && $this->get_option ( 'smartLoad' ) == 'true' )
						add_action( 'wp_enqueue_scripts', array( &$this, 'possibly_enqueue' ) );
				}
			}
		}
		return $posts;
	}

	/**
	 * Replace the URL scheme with the correct scheme.
	 *
	 * Versions of WP before 3.0 do not support telling admin_url which scheme to use,
	 * so we filter it, to make sure it is using the correct one.
	 *
	 * @since 3.0.3.3
	 * @param string $url The url to filter
	 * @return string
	 */
	function admin_url_scheme ( $url ) {
		$scheme = is_ssl() ? 'https' : 'http';
		return str_replace ( array ( 'http://' , 'https://' ) , "$scheme://" , $url );
	}

	/**
	 * Enqueue Shadowbox CSS
	 *
	 * @return none
	 * @since 2.0.3
	 */
	function styles () {
		$plugin_url = $this->plugin_url ();
		$uploads = wp_upload_dir ();
		if ( empty( $uploads['error'] ) && ! empty( $uploads['basedir'] ) ) {
			$baseurl = $uploads['baseurl'];
			$basedir = $uploads['basedir'];
		} else {
			$baseurl = WP_CONTENT_URL . '/uploads';
			$basedir = WP_CONTENT_DIR . '/uploads';
		}

		$shadowbox_css = apply_filters ( 'shadowbox-css' , $baseurl . '/shadowbox-js/src/shadowbox.css' );
		wp_register_style ( 'shadowbox-css' , $shadowbox_css , false , $this->sbversion , 'screen' );
		wp_register_style ( 'shadowbox-extras' , apply_filters ( 'shadowbox-extras' , $plugin_url . '/css/extras.css' ) , false , $this->version , 'screen' );

		if ( $this->get_option ( 'smartLoad' ) != 'true' ) {
			wp_enqueue_style ( 'shadowbox-css' );
			wp_enqueue_style ( 'shadowbox-extras' );
		}
	}

	/**
	 * Enqueue Shadowbox javascript and dependencies
	 *
	 * @return none
	 * @since 2.0.3
	 */
	function scripts () {
		$plugin_url = $this->plugin_url ();
		$adapter = $this->get_option ( 'library' );
		$language = $this->get_option ( 'language' );
		$players = $this->get_option ( 'players' );

		// Make sure we don't run into cross site scripting or similar errors by calling from the correct scheme
		$scheme = is_ssl() ? 'https' : 'http';

		$md5 = $this->md5 ();
		$uploads = wp_upload_dir ();
		if ( empty( $uploads['error'] ) && ! empty( $uploads['basedir'] ) ) {
			$baseurl = $uploads['baseurl'];
			$basedir = $uploads['basedir'];
		} else {
			$baseurl = WP_CONTENT_URL . '/uploads';
			$basedir = WP_CONTENT_DIR . '/uploads';
		}
		$shadowbox_url = "$baseurl/shadowbox-js/$md5.js";
		$shadowbox_file = "$basedir/shadowbox-js/$md5.js";

		wp_register_script ( 'yui' , apply_filters ( 'yui ' , "$scheme://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js" ) , false , apply_filters ( 'yui-version' , '2.8.0' ) , true );
		wp_register_script ( 'mootools' , apply_filters ( 'mootools' , "$scheme://ajax.googleapis.com/ajax/libs/mootools/1.2.4/mootools-yui-compressed.js" ) , false , apply_filters ( 'mootools-version' , '1.2.4' ) , true );

		// Check if we have a cached shadowbox.js from build_shadowbox, else use admin-ajax.php and build and delivery on the fly
		if ( $this->is_world_readable ( $shadowbox_file ) && $this->get_option ( 'useCache' ) == 'true' ) {
			$shadowbox = $shadowbox_url;
		} else {
			$shadowbox = add_query_arg ( array ( 'action' => 'shadowboxjs' , 'cache' => $md5 ) , admin_url ( 'admin-ajax.php' , $scheme ) );
		}

		$dependency = $adapter != 'base' ? array ( $adapter ) : false;
		wp_register_script ( 'shadowbox' , apply_filters ( 'shadowbox-js' , $shadowbox ) , $dependency , $this->sbversion , true );

		// If we aren't smart loading go ahead and enqueue, otherwise we will enqueue later
		if ( $this->get_option ( 'smartLoad' ) != 'true' )
			wp_enqueue_script ( 'shadowbox' );
	}

	/**
	 * Possibly enqueue a script to the footer if it is not already enqueued
	 *
	 * Used to enqueue a script to the footer in mid page as long as the
	 * script is not already enqueued.
	 *
	 * @return none
	 * @since 3.0.0.0
	 */
	function possibly_enqueue ( $deprecated = '' ) {
		if ( did_action ( 'wp_head' ) )
			return;

		wp_enqueue_style ( 'shadowbox-css' );
		wp_enqueue_style ( 'shadowbox-extras' );

		wp_enqueue_script ( $this->get_option ( 'library' ) );
		wp_enqueue_script ( 'shadowbox' );
		add_action ( 'wp_footer' , array ( &$this , 'configure' ) , 21 );
	}

	/**
	 * Echo Shadowbox configuration and initialization scripts
	 *
	 * @return none
	 * @since 0.1
	 */
	function configure () {
		$plugin_url = $this->plugin_url ();
		$library = $this->get_option ( 'library' );
		$start =  "\n<!-- Begin Shadowbox JS v{$this->version} -->\n";
		$start .= "<!-- Selected Players: " . implode ( ', ', (array) $this->get_option ( 'players' ) ) . " -->\n";
		$end = "<!-- End Shadowbox JS -->\n\n";

		// Shadowbox initialization options
		foreach ( array_keys ( $this->options ) as $key )
			if ( ! in_array ( $key , $this->protected_options () ) )
				$params[$key] = $this->get_option ( $key );

		$init_vars = apply_filters ( 'shadowbox_conf' , $params );

		$init_opts = '';
		foreach ( $init_vars as $key => $value ) {
			if ( in_array ( $key , array ( 'flashParams' , 'flashVars' ) ) ) {
				$init_opts .= "\t\t$key: " . $this->esc ( $value , 'htmledit' ) . ",\n";
			} else if ( in_array ( $value , array ( 'true' , 'false' ) ) || is_int ( $value ) ) {
				$init_opts .= "\t\t$key: " . $this->esc ( $value ) . ",\n";
			} else if ( is_string ( $value ) ) {
				$init_opts .= "\t\t$key: \"" . $this->esc ( $value ) . "\",\n";
			}
		}

		// The full Shadowbox configuration
		$init  = "<script type=\"text/javascript\">\n";
		$init .= "/* <![CDATA[ */\n";
		$init .= "	var shadowbox_conf = {\n";
		$init .= rtrim ( $init_opts , " ,\n" ) . "\n";
		$init .= "	};\n";
		$init .= "	Shadowbox.init(shadowbox_conf);\n";
		$init .= "/* ]]> */\n";
		$init .= "</script>\n";

		echo $start . $init . $end;
	}

	/**
	 * This function is called by the add_filter WordPress function to 
	 * link the gallery images directly to their full size counterpart
	 *
	 * @param string $link The link of the attachment
	 * @param integer $id The id of the post
	 * @return string
	 * @since 2.0.1
	 */
	function attachment_direct_linkage ( $link , $id ) {
		$mimetypes = array ( 'image/jpeg' , 'image/png' , 'image/gif' );
		$post = get_post ( $id );
		if ( in_array ( $post->post_mime_type , $mimetypes ) )
			return wp_get_attachment_url ( $id );
		else
			return $link;
	}

	/**
	 * This function is called by the add_filter WordPress function to add
	 * the rel="shadowbox[post-123]" attribute to all links of a specified
	 * type.
	 *
	 * @param string $content The content of the post
	 * @return string
	 * @since 2.0.3
	 */
	function add_attr_to_link ( $content , $post_or_id = null ) {
		if ( empty ( $content ) )
			return $content;

		// Set the album ID, if the image is coming from a gallery use the gallery ID, otherwise use the ID of the post
		if ( is_object ( $post_or_id ) ) {
			$albumid = "sbpost-{$post_or_id->ID}";
		} elseif ( is_int ( $post_or_id ) && $post_or_id != 0 ) {
			$p = get_post ( $post_or_id );
			$albumid = "sbalbum-{$p->post_parent}";
		} else {
			global $post;
			if ( isset ( $post) && is_object ( $post ) && is_int ( $post->ID ) )
				$albumid = "sbpost-{$post->ID}";
			else
				$albumid = "sbgroup-" . time();
		}

		$img_pattern    = '/<a(.*?)href=([\'"])([^>]*)\.(bmp|gif|jpe|jpe?g|png)\\2(.*?)>/i';
		$mov_pattern    = '/<a(.*?)href=([\'"])([^>]*)\.(swf|dv|moo?v|movie|asf|wmv?|avi|mpe?g)\\2(.*?)>/i';
		$aud_pattern    = '/<a(.*?)href=([\'"])([^>]*)\.(mp3|aac)\\2(.*?)>/i';
		$tube_pattern   = '/<a(.*?)href=([\'"])([^>]*)(youtube\.com\/(watch|v\/)|video\.google\.com\/googleplayer.swf)(.*?)\\2(.*?)>/i';
		$flv_pattern    = '/<a(.*?)href=([\'"])([^>]*)\.(flv|f4v|mp4)\\2(.*?)>/i';
		$master_pattern = '/<a(.*?)href=([\'"])([^>]*)(\.(bmp|gif|jpe|jpe?g|png|swf|flv|f4v|dv|moo?v|movie|mp4|asf|wmv?|avi|mpe?g|mp3|aac)\\2|(youtube\.com\/(watch|v\/)|video\.google\.com\/googleplayer.swf))(.*?)>/i';

		// Rel attrs for different file types
		$img_rel_attr  = 'rel=$2shadowbox[' . $albumid . '];player=img;$2';
		$mov_rel_attr  = 'rel=$2shadowbox[' . $albumid . '];width=' . $this->get_option ( 'genericVideoWidth' ) . ';height=' . $this->get_option ( 'genericVideoHeight' ) . ';$2';
		$aud_rel_attr  = 'rel=$2shadowbox[' . $albumid . '];player=flv;width=500;height=0;$2';
		$tube_rel_attr = 'rel=$2shadowbox[' . $albumid . '];player=swf;width=' . $this->get_option ( 'genericVideoWidth' ) . ';height=' . $this->get_option ( 'genericVideoHeight' ) . ';$2';
		$flv_rel_attr  = 'rel=$2shadowbox[' . $albumid . '];player=flv;width=' . $this->get_option ( 'genericVideoWidth' ) . ';height=' . $this->get_option ( 'genericVideoHeight' ) . ';$2';

		// Replacement patterns
		$img_replace  = '<a$1href=$2$3.$4$2 ' . $img_rel_attr . '$5>';
		$mov_replace  = '<a$1href=$2$3.$4$2 ' . $mov_rel_attr . '$5>';
		$aud_replace  = '<a$1href=$2$3.$4$2 ' . $aud_rel_attr . '$5>';
		$tube_replace = '<a$1href=$2$3$4$6$2 ' . $tube_rel_attr . '$7>';
		$flv_replace  = '<a$1href=$2$3.$4$2 ' . $flv_rel_attr . '$5>';

		// Non specific search patterns
		$rel_pattern       = '/\ rel=([\'"])(.*?)\\1/i';
		$box_rel_pattern   = '/\ rel=([\'"])(.*?)(shadow|light|no)box(.*?)\\1/i';
		$slbox_rel_pattern = '/\ rel=([\'"])(.*?)(shadow|light)box(.*?)\\1/i';

		if ( preg_match_all ( $master_pattern , $content , $links ) ) {
			$modify = false;

			foreach ( $links[0] as $link ) {
			
				if ( preg_match ( $img_pattern , $link ) && $this->get_option ( 'autoimg' ) == "true" ) {
					$link_pattern = $img_pattern;
					$rel_attr     = $img_rel_attr;
					$link_replace = $img_replace;
					$modify = true;
				} else if ( preg_match ( $mov_pattern , $link ) && $this->get_option ( 'automov' ) == "true" ) {
					$link_pattern = $mov_pattern;
					$rel_attr     = $mov_rel_attr;
					$link_replace = $mov_replace;
					$modify = true;
				} else if ( preg_match ( $aud_pattern , $link ) && $this->get_option ( 'autoaud' ) == "true" ) {
					$link_pattern = $aud_pattern;
					$rel_attr     = $aud_rel_attr;
					$link_replace = $aud_replace;
					$modify = true;
				} else if ( preg_match ( $tube_pattern , $link ) && $this->get_option ( 'autotube' ) == "true" ) {
					$link_pattern = $tube_pattern;
					$rel_attr     = $tube_rel_attr;
					$link_replace = $tube_replace;
					$modify = true;
				} else if ( preg_match ( $flv_pattern , $link ) && $this->get_option ( 'autoflv' ) == "true" ) {
					$link_pattern = $flv_pattern;
					$rel_attr     = $flv_rel_attr;
					$link_replace = $flv_replace;
					$modify = true;
				}

				if ( ! preg_match ( $rel_pattern , $link ) && $modify === true ) {
					$link_replace = preg_replace ( $link_pattern , $link_replace , $link );
					$content      = str_replace ( $link , $link_replace , $content );
				} else if ( ! preg_match ( $box_rel_pattern , $link ) && $modify === true ) {
					preg_match ( $rel_pattern , $link , $link_rel );
					$link_no_rel  = preg_replace( $rel_pattern , '' , $link );
					$link_replace = preg_replace ( $link_pattern , $link_replace , $link_no_rel );
					$content      = str_replace ( $link , $link_replace , $content );
				}

				$modify = false;
			}
		}

		// Check to see if we need to try to load shadowbox and dependencies into the footer
		if ( preg_match ( $slbox_rel_pattern , $content ) && $this->get_option ( 'smartLoad' ) == 'true' ) {
			$this->possibly_enqueue ();
		}

		return $content;
	}
}
