<?php defined( 'ABSPATH' ) OR die( 'No direct access.' );
if ( ! class_exists( 'WP_External_Links' ) ):

/**
 * Class WP_External_Links
 * @package WordPress
 * @since
 * @category WordPress Plugins
 */
final class WP_External_Links {

	/**
	 * Admin object
	 * @var Admin_External_Links
	 */
	private $admin = NULL;

	/**
	 * Array of ignored links
	 * @var type
	 */
	private $ignored = array();


	/**
	 * Constructor
	 */
	public function __construct() {
		// set admin object
		$this->admin = new Admin_External_Links();

		// add actions
		add_action( 'wp', array( $this, 'call_wp' ) );
	}

	/**
	 * Quick helper method for getting saved option values
	 * @param string $key
	 * @return mixed
	 */
	public function get_opt( $key ) {
		$lookup = $this->admin->save_options;

		foreach ( $lookup as $option_name => $values ) {
			$value = $this->admin->form->value( $key, '___NONE___', $option_name );

			if ($value !== '___NONE___')
				return $value;
		}

		throw new Exception('Option with key "' . $key . '" does not exist.');
	}

	/**
	 * wp callback
	 */
	public function call_wp() {
		if ( ! is_admin() && ! is_feed() ) {
			// Include phpQuery
			if ( ! class_exists( 'phpQuery' ) ) {
				require_once( 'phpQuery.php' );
			}

			// add wp_head for setting js vars and css style
			add_action( 'wp_head', array( $this, 'call_wp_head' ) );

			// add stylesheet
			wp_enqueue_style( 'wp-external-links', plugins_url( 'css/external-links.css', WP_EXTERNAL_LINKS_FILE ), FALSE, WP_EXTERNAL_LINKS_VERSION );

			// set js file
			if ( $this->get_opt( 'use_js' ) )
				wp_enqueue_script( 'wp-external-links', plugins_url( 'js/external-links.js', WP_EXTERNAL_LINKS_FILE ), array( 'jquery' ), WP_EXTERNAL_LINKS_VERSION );

			// filters
			if ( $this->get_opt( 'filter_page' ) ) {
				// filter body
				ob_start( array( $this, 'call_filter_content' ) );

			} else {
				// set filter priority
				$priority = 1000000000;

				// content
				if ( $this->get_opt( 'filter_posts' ) ) {
					add_filter( 'the_title', array( $this, 'call_filter_content' ), $priority );
					add_filter( 'the_content', array( $this, 'call_filter_content' ), $priority );
					add_filter( 'get_the_excerpt', array( $this, 'call_filter_content' ), $priority );
					// redundant:
					//add_filter( 'the_excerpt', array( $this, 'call_filter_content' ), $priority );
				}

				// comments
				if ( $this->get_opt( 'filter_comments' ) ) {
					add_filter( 'get_comment_text', array( $this, 'call_filter_content' ), $priority );
					// redundant:
					//add_filter( 'comment_text', array( $this, 'call_filter_content' ), $priority );

					add_filter( 'comment_excerpt', array( $this, 'call_filter_content' ), $priority );
					// redundant:
					//add_filter( 'get_comment_excerpt', array( $this, 'call_filter_content' ), $priority );

					add_filter( 'comment_url', array( $this, 'call_filter_content' ), $priority );
					add_filter( 'get_comment_author_url', array( $this, 'call_filter_content' ), $priority );
					add_filter( 'get_comment_author_link', array( $this, 'call_filter_content' ), $priority );
					add_filter( 'get_comment_author_url_link', array( $this, 'call_filter_content' ), $priority );
				}

				// widgets
				if ( $this->get_opt( 'filter_widgets' ) ) {
					if ( $this->admin->check_widget_content_filter() ) {
						// only if Widget Logic plugin is installed and 'widget_content' option is activated
						add_filter( 'widget_content', array( $this, 'call_filter_content' ), $priority );
					} else {
						// filter text widgets
						add_filter( 'widget_title', array( $this, 'call_filter_content' ), $priority );
						add_filter( 'widget_text', array( $this, 'call_filter_content' ), $priority );
					}
				}
			}
		}
	}

	/**
	 * wp_head callback
	 */
	public function call_wp_head() {
		// set ignored
		$ignored = $this->get_opt( 'ignore' );
		$ignored = trim( $ignored );
		$ignored = explode( "\n", $ignored );
		$ignored = array_map( 'trim', $ignored );
		$ignored = array_map( 'strtolower', $ignored );
		$this->ignored = $ignored;

		if ( $this->get_opt( 'use_js' ) AND $this->get_opt( 'target' ) != '_none' ):
			// set exclude class
			$excludeClass = ( $this->get_opt( 'no_icon_same_window' ) AND $this->get_opt( 'no_icon_class' ) )
							? $this->get_opt( 'no_icon_class' )
							: '';
?>
<script type="text/javascript">/* <![CDATA[ */
/* WP External Links Plugin */
var wpExtLinks = { baseUrl: '<?php echo get_bloginfo( 'wpurl' ) ?>', target: '<?php echo $this->get_opt( 'target' ) ?>', excludeClass: '<?php echo $excludeClass ?>' };
/* ]]> */</script>
<?php
		endif;
	}

	/**
	 * Filter content
	 * @param string $content
	 * @return string
	 */
	public function call_filter_content( $content ) {
		if ( $this->get_opt( 'fix_js' ) ) {
			// fix js problem by replacing </a> by <\/a>
			$content = preg_replace_callback( '/<script([^>]*)>(.*?)<\/script[^>]*>/is', array( $this, 'call_fix_js' ), $content );
		}

		if ( $this->get_opt( 'phpquery' ) ) {
			return $this->filter_phpquery( $content );
		} else {
			return $this->filter( $content );
		}
	}

	/**
	 * Fix </a> in JavaScript blocks (callback for regexp)
	 * @param array $matches Result of a preg call in filter_content()
	 * @return string Clean code
	 */
	public function call_fix_js( $matches ) {
		return str_replace( '</a>', '<\/a>', $matches[ 0 ] );
	}

	/**
	 * Check if link is external
	 * @param string $href
	 * @param string $rel
	 * @return boolean
	 */
	private function is_external( $href, $rel ) {
		// check if this links should be ignored
		for ( $x = 0, $count = count($this->ignored); $x < $count; $x++ ) {
			if ( strrpos( $href, $this->ignored[ $x ] ) !== FALSE )
				return FALSE;
		}

		return ( isset( $href ) AND ( strpos( $rel, 'external' ) !== FALSE
												OR  ( strpos( $href, strtolower( get_bloginfo( 'wpurl' ) ) ) === FALSE )
														AND ( substr( $href, 0, 7 ) == 'http://'
																OR substr( $href, 0, 8 ) == 'https://'
																OR substr( $href, 0, 6 ) == 'ftp://' ) ) );
	}

	/**
	 * Filter content
	 * @param string $content
	 * @return string
	 */
	private function filter( $content ) {
		// replace links
		$content = preg_replace_callback( '/<a([^>]*)>(.*?)<\/a[^>]*>/is', array( $this, 'call_parse_link' ), $content );

		// remove style when no icon classes are found
		if ( strpos( $content, 'ext-icon-' ) === FALSE ) {
			// remove style with id wp-external-links-css
			$content = preg_replace( '/<link ([^>]*)wp-external-links-css([^>]*)\/>[\s+]*/i', '', $content );
		}

		return $content;
	}

	/**
	 * Make a clean <a> code (callback for regexp)
	 * @param array $matches Result of a preg call in filter_content()
	 * @return string Clean <a> code
	 */
	public function call_parse_link( $matches ) {
		$attrs = $matches[ 1 ];
		$attrs = stripslashes( $attrs );
		$attrs = shortcode_parse_atts( $attrs );

		$href = strtolower( $attrs[ 'href' ] );
		$rel = ( isset( $attrs[ 'rel' ] ) ) ? strtolower( $attrs[ 'rel' ] ) : '';

		// check if it is an external link and not excluded
		if ( ! $this->is_external( $href, $rel ) )
			return $matches[ 0 ];

		// set rel="external" (when not already set)
		if ( $this->get_opt( 'external' ) )
			$this->add_attr_value( &$attrs, 'rel', 'external' );

		// set rel="nofollow" when doesn't have "follow" (or already "nofollow")
		if ( $this->get_opt( 'nofollow' ) AND strpos( $rel, 'follow' ) === FALSE )
			$this->add_attr_value( &$attrs, 'rel', 'nofollow' );

		// set title
		$title = $this->get_opt( 'title' );
		$attrs[ 'title' ] = str_replace( '%title%', $attrs[ 'title' ], $title );

		// set user-defined class
		$class = $this->get_opt( 'class_name' );
		if ( $class )
			$this->add_attr_value( &$attrs, 'class', $class );

		// set icon class, unless no-icon class isset or another icon class ('ext-icon-...') is found or content contains image
		if ( $this->get_opt( 'icon' ) > 0
					AND ( ! $this->get_opt( 'no_icon_class' ) OR strpos( $attrs[ 'class' ], $this->get_opt( 'no_icon_class' ) ) === FALSE )
					AND strpos( $attrs[ 'class' ], 'ext-icon-' ) === FALSE
					AND !( $this->get_opt( 'image_no_icon' ) AND (bool) preg_match( '/<img([^>]*)>/is', $matches[ 2 ] )) ){
			$icon_class = 'ext-icon-'. $this->get_opt( 'icon', 'style' );
			$this->add_attr_value( &$attrs, 'class', $icon_class );
		}

		// set target
		if ( ! $this->get_opt( 'use_js' ) AND ( ! $this->get_opt( 'no_icon_same_window' )
					OR ! $this->get_opt( 'no_icon_class' )
					OR strpos( $attrs[ 'class' ], $this->get_opt( 'no_icon_class' ) ) === FALSE ) ) {
			if ( $this->get_opt( 'target' ) == '_none' ) {
				unset( $attrs[ 'target' ] );
			} else {
				$attrs[ 'target' ] =  $this->get_opt( 'target' );
			}
		}

		// create element code
		$link = '<a ';

		foreach ( $attrs AS $key => $value )
			$link .= $key .'="'. $value .'" ';

		// remove last space
		$link = substr( $link, 0, -1 );

		$link .= '>'. $matches[ 2 ] .'</a>';

		return $link;
	}

	/**
	 * Add value to attribute
	 * @param array  $attrs
	 * @param string $attr
	 * @param string $value
	 * @param string $default  Optional, default NULL which means tje attribute will be removed when (new) value is empty
	 * @return New value
	 */
	private function add_attr_value( $attrs, $attr_name, $value, $default = NULL ) {
		if ( key_exists( $attr_name, $attrs ) )
			$old_value = $attrs[ $attr_name ];

		if ( empty( $old_value ) )
			$old_value = '';

		$split = split( ' ', strtolower( $old_value ) );

		if ( in_array( $value, $split ) ) {
			$value = $old_value;
		} else {
			$value = ( empty( $old_value ) )
								? $value
								: $old_value .' '. $value;
		}

		if ( empty( $value ) AND $default === NULL ) {
			unset( $attrs[ $attr_name ] );
		} else {
			$attrs[ $attr_name ] = $value;
		}

		return $value;
	}

	/**
	 * Experimental phpQuery...
	 */

	/**
	 * Filter content
	 * @param string $content
	 * @return string
	 */
	private function filter_phpquery( $content ) {
		// Workaround: remove <head>-attributes before using phpQuery
		$regexp_head = '/<head(>|\s(.*?)>)>/is';
		$clean_head = '<head>';

		// set simple <head> without attributes
		preg_match( $regexp_head, $content, $matches );
		$original_head = $matches[ 0 ];
		$content = str_replace( $original_head, $clean_head, $content );

		//phpQuery::$debug = true;

		// set document
		$doc = phpQuery::newDocument( $content );

		/*
		$regexp_xml = '/<\?xml(.*?)\?>/is';
		$regexp_xhtml = '/<!DOCTYPE(.*?)xhtml(.*?)>/is';

		if ( preg_match( $regexp_xml, $content ) > 0 ) {
			$doc = phpQuery::newDocumentXML( $content, get_bloginfo( 'charset' ) );
		} elseif ( preg_match( $regexp_xhtml, $content ) > 0 ) {
			$doc = phpQuery::newDocumentXHTML( $content, get_bloginfo( 'charset' ) );
		} else {
			$doc = phpQuery::newDocumentHTML( $content, get_bloginfo( 'charset' ) );
		}
		*/

		$excl_sel = $this->get_opt( 'filter_excl_sel' );

		// set excludes
		if ( ! empty( $excl_sel ) ) {
			$excludes = $doc->find( $excl_sel );
			$excludes->filter( 'a' )->attr( 'excluded', true );
			$excludes->find( 'a' )->attr( 'excluded', true );
		}

		// get <a>-tags
		$links = $doc->find( 'a' );

		// set links
		$count = count( $links );

		for( $x = 0; $x < $count; $x++ ) {
			$a = $links->eq( $x );

			if ( ! $a->attr( 'excluded' ) )
				$this->set_link_phpquery( $links->eq( $x ) );
		}

		// remove excluded
		if ( ! empty( $excl_sel ) ) {
			$excludes = $doc->find( $excl_sel );
			$excludes->filter( 'a' )->removeAttr( 'excluded' );
			$excludes->find( 'a' )->removeAttr( 'excluded' );
		}

		// remove style when no icon classes are found
		if ( strpos( $doc, 'ext-icon-' ) === FALSE ) {
			// remove icon css
			$css = $doc->find( 'link#wp-external-links-css' )->eq(0);
			$css->remove();
		}

		// get document content
		$content = (string) $doc;

		// recover original <head> with attributes
		$content = str_replace( $clean_head, $original_head, $content );

		return $content;
	}

	/**
	 * Set link...
	 * @param Node $a
	 * @return Node
	 */
	public function set_link_phpquery( $a ) {
		$href = strtolower( $a->attr( 'href' ) . '' );
		$rel = strtolower( $a->attr( 'rel' ) . '' );

		// check if it is an external link and not excluded
		if ( ! $this->is_external( $href, $rel ) )
			return $a;

		// add "external" to rel-attribute
		if ( $this->get_opt( 'external' ) ){
			$this->add_attr_value_phpquery( $a, 'rel', 'external' );
		}

		// add "nofollow" to rel-attribute, when doesn't have "follow"
		if ( $this->get_opt( 'nofollow' ) AND strpos( $rel, 'follow' ) === FALSE ){
			$this->add_attr_value_phpquery( $a, 'rel', 'nofollow' );
		}

		// set title
		$title = str_replace( '%title%', $a->attr( 'title' ), $this->get_opt( 'title' ) );
		$a->attr( 'title', $title );

		// add icon class, unless no-icon class isset or another icon class ('ext-icon-...') is found
		if ( $this->get_opt( 'icon' ) > 0 AND ( ! $this->get_opt( 'no_icon_class' ) OR strpos( $a->attr( 'class' ), $this->get_opt( 'no_icon_class' ) ) === FALSE ) AND strpos( $a->attr( 'class' ), 'ext-icon-' ) === FALSE  ){
			$icon_class = 'ext-icon-'. $this->get_opt( 'icon' );
			$a->addClass( $icon_class );
		}

		// add user-defined class
		if ( $this->get_opt( 'class_name' ) ){
			$a->addClass( $this->get_opt( 'class_name' ) );
		}

		// set target
		if ( $this->get_opt( 'target' ) != '_none' AND ! $this->get_opt( 'use_js' ) AND ( ! $this->get_opt( 'no_icon_same_window' ) OR ! $this->get_opt( 'no_icon_class' ) OR strpos( $a->attr( 'class' ), $this->get_opt( 'no_icon_class' ) ) === FALSE ) )
			$a->attr( 'target', $this->get_opt( 'target' ) );

		return $a;
	}

	/**
	 * Add value to attribute
	 * @param Node   $node
	 * @param string $attr
	 * @param string $value
	 * @return New value
	 */
	private function add_attr_value_phpquery( $node, $attr, $value ) {
		$old_value = $node->attr( $attr );

		if ( empty( $old_value ) )
			$old_value = '';

		$split = split( ' ', strtolower( $old_value ) );

		if ( in_array( $value, $split ) ) {
			$value = $old_value;
		} else {
			$value = ( empty( $old_value ) )
								? $value
								: $old_value .' '. $value;
		}

		$node->attr( $attr, $value );

		return $value;
	}

} // End WP_External_Links Class

endif;