<?php
/**
 * SlideDeck for WordPress - Slider Widget
 * 
 * Create SlideDecks on your WordPress blogging platform. Manage SlideDeck 
 * content and insert them into templates and posts.
 * 
 * @package SlideDeck
 * @subpackage SlideDeck for WordPress
 * 
 * @global    object    $wpdb
 * 
 * @author digital-telepathy
 * @version 1.4.8
 */
/*
Plugin Name: SlideDeck for WordPress - Slider Widget
Plugin URI: http://www.slidedeck.com/wordpress
Description: Create SlideDecks on your WordPress blogging platform and insert them into templates and posts. Get started creating SlideDecks from the new SlideDeck menu in the left hand navigation. <a href="http://www.slidedeck.com/upgrade-to-pro/?utm_source=LiteUser&utm_medium=Link&utm_campaign=WPplugin" target="_blank">Upgrade to SlideDeck Pro!</a> | <a href="admin.php?page=slidedeck.php">Manage SlideDecks</a> | <a href="admin.php?page=slidedeck.php/slidedeck_add_new">Add New SlideDeck</a> | <a href="admin.php?page=slidedeck.php/slidedeck_dynamic">Add New Smart SlideDeck</a>
Version: 1.4.8
Author: digital-telepathy
Author URI: http://www.dtelepathy.com
License: GPL2

Copyright 2011 digital-telepathy  (email : support@digital-telepathy.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Adding $wpdb here because it is not loaded automatically on activation, 
 * causing prefixing to fail on database table names
 */
if ( !isset( $wpdb ) && empty( $wpdb ) ) {
    global $wpdb;
}


/**
 * Backwards compatibility code for defining constants for pathing consistency
 * http://codex.wordpress.org/Determining_Plugin_and_Content_Directories
 */
if ( ! function_exists( 'is_ssl' ) ) {
    function is_ssl() {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( 'on' == strtolower($_SERVER['HTTPS']) )
                return true;
            if ( '1' == $_SERVER['HTTPS'] )
                return true;
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        }
        return false;
    }
}

if ( version_compare( get_bloginfo( 'version' ) , '3.0' , '<' ) && is_ssl() ) {
    $wp_content_url = str_replace( 'http://' , 'https://' , get_option( 'siteurl' ) );
} else {
    $wp_content_url = get_option( 'siteurl' );
}
if( defined( 'WP_CONTENT_URL' ) ) {
    $wp_content_url = WP_CONTENT_URL;
} else {
    $wp_content_url .= '/wp-content';
}
if( defined( 'WP_CONTENT_DIR' ) ) {
    $wp_content_dir = WP_CONTENT_DIR;
} else {
    $wp_content_dir = ABSPATH . 'wp-content';
}
$wp_plugin_url = $wp_content_url . '/plugins';
$wp_plugin_dir = $wp_content_dir . '/plugins';
$wpmu_plugin_url = $wp_content_url . '/mu-plugins';
$wpmu_plugin_dir = $wp_content_dir . '/mu-plugins';

/**
 * These table references are for legacy support for any SlideDeck tables created
 * with versions of this plugin prior to 1.3
 */
define( 'SLIDEDECKS_TABLE',                         $wpdb->prefix . "slidedecks" );
define( 'SLIDEDECKS_SLIDES_TABLE',                  $wpdb->prefix . "slidedecks_slides" );


define( 'SLIDEDECK_POST_TYPE',                      'slidedeck' );
define( 'SLIDEDECK_SLIDE_POST_TYPE',                'slidedeck_slide' );
define( 'SLIDEDECK_VERSION',                        '1.4.8' );
define( 'SLIDEDECK_TITLE_LENGTH_WITH_IMAGE',        45 ); // characters
define( 'SLIDEDECK_TITLE_LENGTH_WITHOUT_IMAGE',     60 ); // characters
define( 'SLIDEDECK_EXCERPT_LENGTH_WITH_IMAGE',      30 ); // words
define( 'SLIDEDECK_EXCERPT_LENGTH_WITHOUT_IMAGE',   100 ); // words
define( 'SLIDEDECK_LEGACY_IMPORT_COMPLETE',         ( intval( get_option( 'slidedeck_legacy_import_complete', 0 ) ) == 1 ? true : false ) );
define( 'SLIDEDECK_CUSTOM_SKIN_DIR',                 $wp_plugin_dir . "/slidedeck-skins" );
define( 'SLIDEDECK_USE_OLD_TINYMCE_EDITOR',         version_compare( get_bloginfo( 'version' ), '3.2.1', '<=' ) );
define( 'SLIDEDECK_IS_AJAX_REQUEST',                ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) );
define( 'SLIDEDECK_DEFAULT_SKIN',                   'slidedeck-classic' );

global $slidedeck_global_options;
$slidedeck_global_options_defaults = array( 
    'disable_wpautop' => false,
    'enable_ssl_check' => false,
    'dont_enqueue_scrollwheel_library' => false
);
$slidedeck_global_options = get_option( 'slidedeck_global_options', $slidedeck_global_options_defaults );
$slidedeck_global_options = array_merge( $slidedeck_global_options_defaults, $slidedeck_global_options );

define( 'SLIDEDECK_USER_HASH', sha1( $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] ) );
define( 'KMAPI_KEY', "d1b65dbd653f5c7f63692c5a3a17a7ad5d8d5d4d" );

/**
 * Installation routine to run upon activation. Function creates two databases,
 * one for storing SlideDeck entries and the other for storing SlideDeck slide
 * content.
 */
if ( function_exists( 'register_activation_hook' ) ) {
    register_activation_hook( __FILE__, 'slidedeck_install' );
}
if( function_exists( 'register_deactivation_hook' ) ) {
    register_deactivation_hook( __FILE__, 'slidedeck_deactivate' );
}


/**
 * Install script to upgrade SlideDeck to the current version or install from scratch. Imports
 * all legacy stored SlideDecks to use the wp_posts table and store information using custom
 * post types
 */
function slidedeck_install() {
    global $wpdb;
    
    if( !is_dir( SLIDEDECK_CUSTOM_SKIN_DIR ) ) {
        if( is_writable( dirname( SLIDEDECK_CUSTOM_SKIN_DIR ) ) ) {
            mkdir( SLIDEDECK_CUSTOM_SKIN_DIR, 0777 );
        }
    }
    
    if ( $wpdb->get_var( "SHOW TABLES LIKE '" . SLIDEDECKS_TABLE . "'" ) == SLIDEDECKS_TABLE ) {
        if( SLIDEDECK_LEGACY_IMPORT_COMPLETE === false ) {
            $slidedecks = $wpdb->get_results( "SELECT id,dynamic,title,gallery_id,dynamic_options,slidedeck_options,skin,created_at,updated_at FROM " . SLIDEDECKS_TABLE );

            foreach( $slidedecks as $slidedeck ) {
                $slides = $wpdb->get_results( $wpdb->prepare( "SELECT id,slidedeck_id,title,content,slide_order,created_at,updated_at FROM " . SLIDEDECKS_SLIDES_TABLE . " WHERE slidedeck_id = %d;", $slidedeck->id ) );
                
                $slidedeck_id = wp_insert_post( array(
                    'post_date' => date( 'Y-m-d H:i:59', strtotime( $slidedeck->created_at ) ),
                    'post_date_gmt' => gmdate( 'Y-m-d H:i:59', strtotime( $slidedeck->created_at ) ),
                    'post_content' => "",
                    'post_title' => $slidedeck->title,
                    'post_status' => "publish",
                    'comment_status' => "closed",
                    'ping_status' => "closed",
                    'post_modified' => date( 'Y-m-d H:i:59', strtotime( $slidedeck->updated_at ) ),
                    'post_modified_gmt' => gmdate( 'Y-m-d H:i:59', strtotime( $slidedeck->updated_at ) ),
                    'post_type' => SLIDEDECK_POST_TYPE
                ) );
                
                $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET {$wpdb->posts}.post_parent = %d WHERE {$wpdb->posts}.post_parent = %d AND {$wpdb->posts}.post_type = 'attachment';", $slidedeck_id, $slidedeck->gallery_id ) );
                
                if( !empty( $slides ) ) {
                    $slidedeck_options = unserialize( $slidedeck->slidedeck_options );
                    $vertical_slide_ids = array();
                    $slide_backgrounds = array();
                    
                    foreach( $slides as $slide ) {
                        $slide_id = wp_insert_post( array(
                            'post_date' => date( 'Y-m-d H:i:59', strtotime( $slide->created_at ) ),
                            'post_date_gmt' => gmdate( 'Y-m-d H:i:59', strtotime( $slide->created_at ) ),
                            'post_content' => $slide->content,
                            'post_title' => $slide->title,
                            'post_status' => "publish",
                            'comment_status' => "closed",
                            'ping_status' => "closed",
                            'post_parent' => $slidedeck_id,
                            'menu_order' => $slide->slide_order,
                            'post_modified' => date( 'Y-m-d H:i:59', strtotime( $slide->updated_at ) ),
                            'post_modified_gmt' => gmdate( 'Y-m-d H:i:59', strtotime( $slide->updated_at ) ),
                            'post_type' => SLIDEDECK_SLIDE_POST_TYPE
                        ) );
                        
                        if( isset( $slidedeck_options['vertical_slides'] ) ) {
                            if( in_array( $slide->id, explode( ',', $slidedeck_options['vertical_slides'] ) ) ) {
                                $vertical_slide_ids[] = $slide_id;
                            }
                        }
                        
                        if( isset( $slidedeck_options['slide_backgrounds'] ) ) {
                            if( isset( $slidedeck_options['slide_backgrounds'][$slide->id] ) ) {
                                $slide_backgrounds[$slide_id] = $slidedeck_options['slide_backgrounds'][$slide->id];
                            }
                        }
                    }
                    
                    if( isset( $slidedeck_options['vertical_slides'] ) ) {
                        $slidedeck_options['vertical_slides'] = implode( ',', $vertical_slide_ids );
                    }
                    if( isset( $slidedeck_options['slide_backgrounds'] ) ) {
                        $slidedeck_options['slide_backgrounds'] = $slide_backgrounds;
                    }
                    $slidedeck->slidedeck_options = serialize( $slidedeck_options );
                }
                
                update_post_meta( $slidedeck_id, '_slidedeck_legacy_id', $slidedeck->id );
                update_post_meta( $slidedeck_id, '_slidedeck_slidedeck_options', $slidedeck->slidedeck_options );
                update_post_meta( $slidedeck_id, '_slidedeck_dynamic_options', $slidedeck->dynamic_options );
                update_post_meta( $slidedeck_id, '_slidedeck_is_dynamic', $slidedeck->dynamic );
                update_post_meta( $slidedeck_id, '_slidedeck_skin', $slidedeck->skin );
            }
        }
    }
    update_option( 'slidedeck_legacy_import_complete', 1 );
    
    wp_remote_fopen( "http://trk.kissmetrics.com/e?_k=" . KMAPI_KEY . "&_n=SlideDeck+Activated&_p=" . SLIDEDECK_USER_HASH . '&version=Lite' );
}
function slidedeck_deactivate() {
    wp_remote_fopen( "http://trk.kissmetrics.com/e?_k=" . KMAPI_KEY . "&_n=SlideDeck+Deactivated&_p=" . SLIDEDECK_USER_HASH . '&version=Lite' );
}


/**
 * Add SlideDeck to the sidebar navigation
 * 
 * @uses add_menu_page()
 * @uses slidedeck_url()
 * @uses add_submenu_page()
 */
function slidedeck_menu() {
    add_menu_page( 'SlideDeck', 'SlideDeck', 'publish_posts', basename( __FILE__ ), 'slidedeck_edit', slidedeck_url( '/images/icon.png' ) );
    add_submenu_page( basename( __FILE__ ), 'Edit SlideDeck', 'Edit', 'publish_posts', basename( __FILE__ ), 'slidedeck_edit' );
    add_submenu_page( basename( __FILE__ ), 'Add SlideDeck', 'Add New', 'publish_posts', basename( __FILE__ ) . '/slidedeck_add_new', 'slidedeck_add_new' );
    add_submenu_page( basename( __FILE__ ), 'Add Smart SlideDeck', 'Add Smart SlideDeck', 'publish_posts', basename( __FILE__ ) . '/slidedeck_dynamic', 'slidedeck_dynamic' );
    add_submenu_page( basename( __FILE__ ), 'Learn About Pro', 'Learn About Pro', 'publish_posts', basename( __FILE__ ) . '/upgrade', 'slidedeck_upgrade' );
}


/**
 * Admin panel pre-processing. Initializes JavaScripts and StyleSheets to be loaded. Also initiates any 
 * save submission and re-directs the user to the apporpriate edit page with a message to display.
 * 
 * @uses slidedeck_is_plugin()
 * @uses wp_register_style()
 * @uses wp_register_script()
 * @uses slidedeck_url()
 * @uses slidedeck_get_skins()
 * @uses slidedeck_save()
 * @uses wp_redirect()
 * @uses slidedeck_action() 
 */
function slidedeck_admin_init() {
    // Make exclusion case to only load JavaScript and CSS on the SlideDeck relevant admin pages
    if ( slidedeck_is_plugin() ) {
        if( $_GET['page'] == basename( __FILE__ ) . "/upgrade" ) {
            $variation = "Admin+Sidebar+Menu";
            if( isset( $_GET['variation'] ) && !empty( $_GET['variation'] ) ) {
                $variation = urlencode( $_GET['variation'] );
            }
            wp_remote_fopen( "http://trk.kissmetrics.com/e?_k=" . KMAPI_KEY . "&_n=Upgrade+to+SlideDeck+Pro&_p=" . SLIDEDECK_USER_HASH . '&variation=' . $variation );
            wp_redirect( "http://www.slidedeck.com/upgrade-to-pro-wp/?utm_source=" . $_SERVER['HTTP_HOST'] . "&utm_medium=in_app&utm_campaign=LiteUser" );
        }
        wp_register_style( 'slidedeck-admin-css', slidedeck_url( '/slidedeck-admin.css' ), array(), SLIDEDECK_VERSION, "screen" );
        wp_register_script( 'slidedeck-admin-js', slidedeck_url( '/slidedeck-admin.js' ), array( 'jquery', 'media-upload' ), SLIDEDECK_VERSION, !SLIDEDECK_USE_OLD_TINYMCE_EDITOR );
        wp_register_style( 'slidedeck-ui-styles', slidedeck_url( '/lib/tinymce3/slidedeck-jquery-ui.css' ) );

        wp_enqueue_style( 'slidedeck-admin-css' );
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_style( 'editor-buttons' );
        wp_enqueue_style( 'slidedeck-ui-styles' );

        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'thickbox' );
        wp_enqueue_script( 'editor' );
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'quicktags' );
        
        global $slidedeck_skin_included;
        $skins = slidedeck_get_skins();
        foreach( (array) $skins as $skin => $meta ) {
            $slidedeck_skin_included[$skin] = true;
        }
        
        // Process submission to save the SlideDeck
        if( isset( $_POST ) && !empty( $_POST ) ) {
            
            switch( $_POST['action'] ) {
                case "edit":
                    $message_id = 1;
                break;
                
                case "create":
                    $message_id = 2;
                    if( $_POST['dynamic'] == 1 ) {
                        $message_id = 3;
                    }
                break;
            }
            if( in_array( $_POST['action'], array( 'edit', 'create' ) ) ){
                $slidedeck = slidedeck_save( $_POST );    // Save SlideDeck
                wp_redirect( slidedeck_action( $slidedeck['dynamic'] == '1' ? '/slidedeck_dynamic' : '' ) . '&action=edit&id=' . $slidedeck['id'] . '&message=' . $message_id );
            }
        }
    }
}


/**
 * Load the necessary JavaScript files for the SlideDeck plugin interface
 * 
 * @uses wp_print_scripts()
 * @uses wp_enqueue_script()
 * @uses slidedeck_is_plugin()
 * @uses wp_tiny_mce()
 * @uses slidedeck_print_styles()
 */
function slidedeck_admin_head() {
    wp_print_scripts( 'jquery' );
    
    wp_enqueue_script( 'slidedeck-admin-js' );
    
    if( slidedeck_is_plugin() ) {
        if( SLIDEDECK_USE_OLD_TINYMCE_EDITOR === true ) {
            wp_tiny_mce( false, array( 'editor_selector' => 'slide-content' ) );
        }
        slidedeck_print_styles();
    }
}


/**
 * Update the tinyMCE default embed options, but only on the SlideDeck plugin pages
 * 
 * @param object $initArray
 * 
 * @return $initArray
 */
function slidedeck_change_mce_options( $initArray ) {
    // Fail silently for WordPress 3.3+ since the new wp_editor() command does not require this modification
    if( SLIDEDECK_USE_OLD_TINYMCE_EDITOR === false ) {
        return $initArray;
    }
    
    if ( slidedeck_is_plugin() ) {
        $initArray['editor_selector'] = 'slide-content';
        $initArray['mode'] = 'specific_textareas';
        $initArray['theme_advanced_buttons1'] = str_replace( ',wp_more', '', $initArray['theme_advanced_buttons1'] );
        $initArray['theme_advanced_disable'] = 'fullscreen';
    }

    return $initArray;
}


/**
 * Convenience method to determine if we are viewing a SlideDeck plugin page
 * 
 * @return boolean
 */
function slidedeck_is_plugin() {
    return (boolean) ( ( "admin.php" == basename( $_SERVER['PHP_SELF'] ) ) && ( strpos( $_GET['page'], basename( __FILE__ ) ) !== false ) );
}


/**
 * Get the root relative server path to the specified plugin file
 * 
 * @param string $str [optional] The path to a file relative to this main plugin file
 * 
 * @return string The absolute server path to the file
 */
function slidedeck_dir( $str="" ) {
    $path =  WP_PLUGIN_DIR . "/" . basename( dirname( __FILE__ ) );
    
    if ( isset( $str ) && !empty( $str ) ) {
        $sep = "/" == substr( $str, 0, 1 ) ? "" : "/";
        return $path . $sep . $str;
    } else {
        return $path;
    }
}


/**
 * Get the URL for the specified plugin file
 * 
 * @param object $str [optional] The path to the file relative to this main plugin file
 * 
 * @uses slidedeck_dir()
 * 
 * @return string The absolute URL to the file specified
 */
function slidedeck_url( $str="" ) {
    global $slidedeck_global_options;
    
    $path = WP_PLUGIN_URL . "/" . basename( dirname( __FILE__ ) );
    
    if( $slidedeck_global_options['enable_ssl_check'] == true ) {
        if( isset( $_SERVER['HTTPS'] ) && (boolean) $_SERVER['HTTPS'] === true ) {
            $path = str_replace( "http://", "https://", $path );
        }
    }
    
    if ( isset( $str ) && !empty( $str ) ) {
        $sep = "/" == substr( $str, 0, 1 ) ? "" : "/";
        return $path . $sep . $str;
    } else {
        return $path;
    }
}


/**
 * Get the URL for the specified plugin action
 * 
 * @param object $str [optional] Expects the handle passed in the menu definition
 * 
 * @uses get_option()
 * 
 * @return The absolute URL to the plugin action specified
 */
function slidedeck_action( $str=null ) {
    $path = get_bloginfo( 'wpurl' ) . "/wp-admin/admin.php?page=" . basename( __FILE__ );
    
    if ( isset( $str ) && !empty( $str ) ) {
        return $path . $str;
    } else {
        return $path;
    }
}


/**
 * Sanitize data using wp_kses() method
 * 
 * @param str $str Data to sanitize for storage
 * 
 * @uses wp_kses()
 * 
 * @return str Sanitized version of $str
 */
function slidedeck_sanitize( $str="" ) {
    if ( !function_exists( 'wp_kses' ) ) {
        require_once( ABSPATH . 'wp-includes/kses.php' );
    }
    global $allowedposttags;
    global $allowedprotocols;
    
    if ( is_string( $str ) ) {
        $str = htmlentities( stripslashes( $str ), ENT_QUOTES, 'UTF-8' );
    }
    
    $str = wp_kses( $str, $allowedposttags, $allowedprotocols );
    
    return $str;
}


/**
 * Get meta data for available skins
 * 
 * @param str $type [optional] Expects a string specifying the type of skin to filter to. Default is all skins in the skins folder.
 * 
 * @uses slidedeck_skin_meta()
 * @uses slidedeck_dir()
 * 
 * @return arr Array of skins with meta data 
 */
function slidedeck_get_skins( $type = 'all' ) {
    $skins = array();
    $all_skin_files = array();
    
    $skin_files = glob( slidedeck_dir( '/skins/*/skin.css' ) );
    foreach( (array) $skin_files as $skin_file ) {
        $key = basename( dirname( $skin_file ) );
        $all_skin_files[$key] = $skin_file;
    }
    
    if( is_dir( SLIDEDECK_CUSTOM_SKIN_DIR ) ) {
        $custom_skin_files = glob( SLIDEDECK_CUSTOM_SKIN_DIR . "/*/skin.css" );
        foreach( (array) $custom_skin_files as $custom_skin_file ) {
            $key = basename( dirname( $custom_skin_file ) );
            $all_skin_files[$key] = $custom_skin_file;
        }
    }
    
    foreach ( (array) array_values( $all_skin_files ) as $skin_file ) {
        if ( is_readable( $skin_file ) ) {
            $skin_meta = slidedeck_skin_meta( $skin_file );
            if ( $type  == 'all' || ( isset( $skin_meta['meta']['Skin Type'] ) && $skin_meta['meta']['Skin Type'] == $type ) ) {
                $skins[$skin_meta['slug']] = $skin_meta;
            }
        }
    }
    
    return $skins;
}


/**
 * Get meta data for a specific skin. Expects skin's slug
 * 
 * @param str $name [optional] Slug for skin being looked up, defaults to "default"
 * 
 * @uses slidedeck_skin_meta()
 * @uses slidedeck_dir()
 * 
 * @return arr Array of skin meta data 
 */
function slidedeck_get_skin( $name = 'default' ) {
    if( file_exists( SLIDEDECK_CUSTOM_SKIN_DIR . '/' . $name . '/skin.css' ) ) {
        $skin_file = glob( SLIDEDECK_CUSTOM_SKIN_DIR . '/' . $name . '/skin.css' );
    } else {
        $skin_file = glob( slidedeck_dir( '/skins/' . $name . '/skin.css' ) );
    }
    
    $skin = slidedeck_skin_meta( $skin_file[0] );
    
    return $skin;
}


/**
 * Process skin meta data from a skin file. Used by slidedeck_get_skin and slidedeck_get_skins
 * 
 * @param object $skin_file
 * 
 * @uses slidedeck_url()
 * 
 * @return arr Skin meta data
 */
function slidedeck_skin_meta( $skin_file ) {
    $skin_data = file_get_contents( $skin_file );
    $skin_folder = dirname( $skin_file );
    $skin_slug = basename( $skin_folder );
    
    $meta_raw = substr( $skin_data, strpos( $skin_data, "/*" ) + 2 );
    $meta_raw = trim( substr( $meta_raw, 0, strpos( $meta_raw, "*/" ) ) );
    
    if ( !empty( $meta_raw ) ) {
        $skin_meta = array();
        
        foreach ( explode( "\n", $meta_raw ) as $row ) {
            $key_val = explode( ":", $row );
            $skin_meta[trim( $key_val[0] )] = trim( $key_val[1] );
        }
        
        $skin_url = WP_PLUGIN_URL . str_replace( WP_PLUGIN_DIR, "", $skin_folder );
        
        $skin = array(
            'url' => $skin_url . "/skin.css",
            'thumbnail' => $skin_url . "/thumbnail.png",
            'slug' => $skin_slug,
            'meta' => $skin_meta
        );
        if( file_exists( $skin_folder . "/skin.ie.css" ) ) {
            $skin['ie_url'] = $skin_url . "/skin.ie.css";
        }
        if( file_exists( $skin_folder . "/skin.ie7.css" ) ) {
            $skin['ie7_url'] = $skin_url . "/skin.ie7.css";
        }
        if( file_exists( $skin_folder . "/skin.ie8.css" ) ) {
            $skin['ie8_url'] = $skin_url . "/skin.ie8.css";
        }
        if ( file_exists( $skin_folder . "/skin.js" ) ) {
            $skin['script_url'] = $skin_url . "/skin.js";
        }
        if ( file_exists( $skin_folder . "/templates" ) ) {
            $skin['templates'] = array();
            
            foreach ( glob( $skin_folder . '/templates/*.thtml' ) as $template ) {
                $template_data = file_get_contents( $template );
                $template_raw = substr( $template_data, strpos( $template_data, "/*" ) + 4 );
                $template_raw = trim( substr( $template_raw, 0, strpos( $template_raw, "*/" ) ) );
                
                $template_meta = array();
                $template_meta['slug'] = str_replace( ".thtml", "", basename( $template ) );
                $template_meta['file'] = $template;
                foreach ( explode( "\n", $template_raw ) as $row ) {
                    $key_val = explode( ":", $row );
                    $template_meta[trim( $key_val[0] )] = trim( $key_val[1] );
                }
                
                $skin['templates'][$template_meta['slug']] = $template_meta;
            }
        }
    }
    
    return $skin;
}


/**
 * Compile HTML for skin CSS tags
 * 
 * @param object $skin
 * 
 * @return string HTML markup of skin CSS tags
 */
function slidedeck_get_skin_css( $skin ) {
    $version = isset( $skin['meta']['Version'] ) && !empty( $skin['meta']['Version'] ) ? $skin['meta']['Version'] : SLIDEDECK_VERSION;
    
    $skin_css_tags = '<link rel="stylesheet" type="text/css" href="' . $skin['url'] . '?v=' . $version . '" media="screen" />';
    if( isset( $skin['ie_url'] ) && !empty( $skin['ie_url'] ) ) {
        $skin_css_tags .= '<!--[if IE]><link rel="stylesheet" type="text/css" href="' . $skin['ie_url'] . '?v=' . $version . '" media="screen" /><![endif]-->';
    }
    if( isset( $skin['ie7_url'] ) && !empty( $skin['ie7_url'] ) ) {
        $skin_css_tags .= '<!--[if IE 7]><link rel="stylesheet" type="text/css" href="' . $skin['ie7_url'] . '?v=' . $version . '" media="screen" /><![endif]-->';
    }
    if( isset( $skin['ie8_url'] ) && !empty( $skin['ie8_url'] ) ) {
        $skin_css_tags .= '<!--[if IE 8]><link rel="stylesheet" type="text/css" href="' . $skin['ie8_url'] . '?v=' . $version . '" media="screen" /><![endif]-->';
    }
    
    return $skin_css_tags;
}


/**
 * Generate an URL for the specified orderby key
 * 
 * @param str $orderby The orderby key
 * 
 * @return str URL for the specified orderby parameter
 */
function slidedeck_orderby( $orderby = 'title' ) {
    $order = 'ASC';
    
    $current_order = $order;
    if( isset( $_GET['order'] ) && !empty( $_GET['order'] ) ) {
        $current_order = $_GET['order'];
    }
    
    $current_orderby = $orderby;
    if( isset( $_GET['orderby'] ) && !empty( $_GET['orderby'] ) ) {
        $current_orderby = $_GET['orderby'];
    }
    
    $url = '&orderby=' . $orderby . '&order=';
    if( $current_orderby == $orderby ) {
        $url .= $current_order == 'ASC' ? 'DESC' : 'ASC';
    } else {
        $url .= $order;
    }
    
    return slidedeck_action( $url );
}


/**
 * Get the current orderby status for the specified key, returns a direction or FALSE if
 * the specified key isn't the current orderby method
 * 
 * @param object $orderby [optional]
 * @return str (asc|desc) or (boolean) FALSE if the specified key is not the current orderby method
 */
function slidedeck_get_current_orderby( $orderby = 'title' ) {
    $order = 'ASC';
    
    $current_order = $order;
    if( isset( $_GET['order'] ) && !empty( $_GET['order'] ) ) {
        $current_order = $_GET['order'];
    }
    
    $current_orderby = 'title';
    if( isset( $_GET['orderby'] ) && !empty( $_GET['orderby'] ) ) {
        $current_orderby = $_GET['orderby'];
    }
    
    if( $current_orderby == $orderby ) {
        return strtolower( $current_order );
    } else {
        return false;
    }
}


/**
 * Look up a SlideDeck ID and get its new wp_posts ID if it is a legacy ID
 * 
 * @param object $id The ID of the legacy SlideDeck
 * @return object $id The new wp_posts ID of the imported SlideDeck
 */
function slidedeck_legacy_id( $id ) {
    global $wpdb;
    
    // Check if this installation contains legacy shortcodes
    if( $wpdb->get_var( "SHOW TABLES LIKE '" . SLIDEDECKS_TABLE . "'" ) == SLIDEDECKS_TABLE ) {
        $legacy_deck = new WP_Query( array(
            'post_type' => SLIDEDECK_POST_TYPE,
            'meta_key' => '_slidedeck_legacy_id',
            'meta_value' => $id
        ) );
        
        if( !empty( $legacy_deck->posts ) ) {
            $id = $legacy_deck->posts[0]->ID;
        }
    }
    
    return $id;
}


/**
 * Convenience method for loading SlideDecks
 * 
 * @param int $slidedeck_id ID of the SlideDeck to retrieve
 * 
 * @uses WP_Query
 * @uses get_post_meta()
 * @uses get_the_title()
 * 
 * @return object $slidedecks Returns a SlideDeck object if a single SlideDeck was requested or an array of SlideDecks if no ID or an array of IDs were passed
 */
function slidedeck_load( $slidedeck_id = null, $orderby = 'title', $order = 'ASC', $post_status = 'publish' ) {
    $is_single = false;
    $slidedecks = array();
    $query_params = array(
        'post_type' => SLIDEDECK_POST_TYPE,
        'posts_per_page' => -1,
        'orderby' => $orderby,
        'order' => $order,
        'post__not_in' => get_option( 'sticky_posts' )
    );
    if( isset( $slidedeck_id ) ) {
        if( is_array( $slidedeck_id ) ) {
            $query_params['post__in'] = $slidedeck_id;
        } else {
            $query_params['post_status'] = $post_status;
            $is_single = true;
        }
    }
    $slidedeck_posts = new WP_Query( $query_params );
    
    foreach( (array) $slidedeck_posts->posts as $post ) {
        $post_id = $post->ID;
        
        $dynamic_options = unserialize( get_post_meta( $post_id, '_slidedeck_dynamic_options', true ) );
        $slidedeck_options = unserialize( get_post_meta( $post_id, '_slidedeck_slidedeck_options', true ) );
        
        /**
         * Older versions of BuddyPress use version 1.1.1 of the jQuery ScrollTo library. There is a known
         * bug with this version of the ScrollTo library breaking the "swing" transition built into jQuery.
         * The latest versions of the jQuery ScrollTo library resolve this issue, but we are also doing a
         * check here to force the transition to "linear" instead of "swing" if BuddyPress is running.
         */
        global $bp;
        if( !empty( $bp ) ) {
            $slidedeck_options['transition'] = 'linear';
        }
        
        $slidedecks[] = array(
            'id' => $post_id,
            'dynamic' => get_post_meta( $post_id, '_slidedeck_is_dynamic', true ),
            'title' => get_the_title( $post_id ),
            'gallery_id' => $post_id,
            'dynamic_options' => $dynamic_options,
            'slidedeck_options' => $slidedeck_options,
            'skin' => get_post_meta( $post_id, '_slidedeck_skin', true ),
            'new_format' => get_post_meta( $post_id, '_slidedeck_new_format', true ),
            'created_at' => $post->post_date,
            'updated_at' => $post->post_modified
        );
    }
    
    if( $is_single === true ) {
        $single_deck = false;
        foreach( $slidedecks as $slidedeck ) {
            if( $slidedeck['id'] == $slidedeck_id ) {
                $single_deck = $slidedeck;
            }
        }
        $slidedecks = $single_deck;
    }
    
    return $slidedecks;
}


/**
 * Get the slides for the SlideDeck ID passed
 * 
 * @param object $slidedeck_id
 * 
 * @uses get_the_title()
 * 
 * @return array Array of slides
 */
function slidedeck_load_slides( $slidedeck_id ) {
    $slide_posts = new WP_Query( array(
        'post_type' => SLIDEDECK_SLIDE_POST_TYPE,
        'post_parent' => $slidedeck_id,
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'post__not_in' => get_option( 'sticky_posts' )
    ) );
    
    $slides = array();
    foreach( (array) $slide_posts->posts as $post ) {
        $post_id = $post->ID;
        
        $slides[] = array(
            'id' => $post_id,
            'slidedeck_id' => $slidedeck_id,
            'title' => get_the_title( $post_id ),
            'content' => $post->post_content,
            'slide_order' => $post->menu_order,
            'created_at' => $post->post_date,
            'updated_at' => $post->post_modified
        );
    }
    
    return $slides;
}


/**
 * Run the the_content filters on the passed in text
 * 
 * @param object $content The content to process
 * 
 * @uses apply_filters()
 * 
 * @return object $content The formatted content
 */
function slidedeck_process_slide_content( $content, $editing = false, $new_format = "" ) {
    global $slidedeck_global_options, $slidedeck;
    
    $content = stripslashes( $content );
    if( empty( $new_format ) || SLIDEDECK_USE_OLD_TINYMCE_EDITOR ) {
        $content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );
    }
    
    if( $editing === false ) {
        $content = do_shortcode( $content );
    }
    
    if( 'true' == get_user_option( 'rich_editing' ) || ( $editing === false ) ){
        if( $slidedeck_global_options['disable_wpautop'] != true ) {
            $content = wpautop( $content );
        }
    }
    
    $content = str_replace( ']]>', ']]&gt;', $content );
    
    return $content;
}


/**
 * Get SlideDeck options for a SlideDeck. Used to make adding new SlideDeck JavaScript library
 * features easier (database values do not need to be pre-defined).
 * 
 * @param object $slidedeck SlideDeck object
 * @param string $option Option to return
 * 
 * @return $slidedeck['slidedeck_options'] value or default value
 */
function slidedeck_get_option( $slidedeck, $option ) {
    // Defaults for the $slidedeck['slidedeck_options'] options array
    $defaults = array( 
        'speed' => 500,
        'start' => 1,
        'autoPlay' => 'false',
        'autoPlayInterval' => 5,
        'cycle' => 'false',
        'keys' => 'true',
        'scroll' => 'true',
        'activeCorner' => 'true',
        'hideSpines' => 'false',
        'transition' => 'swing'
    );
    
    return ( isset( $slidedeck['slidedeck_options'][$option] ) ? $slidedeck['slidedeck_options'][$option] : $defaults[$option] );
}


/**
 * Get Dynamic SlideDeck options for a SlideDeck. Used to make adding new SlideDeck JavaScript library
 * features easier (database values do not need to be pre-defined).
 * 
 * @param object $slidedeck SlideDeck object
 * @param string $option Option to return
 * 
 * @return $slidedeck['dynamic_options'] value or default value
 */
function slidedeck_get_dynamic_option( $slidedeck, $option ) {
    // Defaults for the $slidedeck['slidedeck_options'] options array
    $defaults = array( 
        'type' => 'recent',
        'post_type' => 'post',
        'filter_by_category' => 0,
        'filter_categories' => array(),
        'navigation_type' => 'simple-dots',
        'total' => 5,
        'cache_minutes' => 30,
        'image_source' => 'content',
        'excerpt_length_with_image' => SLIDEDECK_EXCERPT_LENGTH_WITH_IMAGE,
        'excerpt_length_without_image' => SLIDEDECK_EXCERPT_LENGTH_WITHOUT_IMAGE,
        'title_length_with_image' => SLIDEDECK_TITLE_LENGTH_WITH_IMAGE,
        'title_length_without_image' => SLIDEDECK_TITLE_LENGTH_WITHOUT_IMAGE,
        'feed_url' => "",
        'validate_images' => 0
    );
    
    return ( isset( $slidedeck['dynamic_options'][$option] ) ? $slidedeck['dynamic_options'][$option] : $defaults[$option] );
}


/**
 * Show a message on a page based off of the $_GET['message'] ID passed
 * 
 * @param int $_GET['message'] The ID of the message to show
 */
function slidedeck_show_message() {
    if( !isset( $_GET['message'] ) ) {
        return false;
    }
    
    $messages = array(
        '<strong>SlideDeck updated!</strong> Now <a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/edit.php">insert your SlideDeck into a post</a> or use the <a href="#get-slidedeck-template-snippet"><em>Theme Code Snippet</em></a> to place it in your theme.',
        '<strong>New SlideDeck created!</strong> Now <a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/edit.php">insert your SlideDeck into a post</a> or use the <a href="#get-slidedeck-template-snippet"><em>Theme Code Snippet</em></a> to place it in your theme.',
        '<strong>New Smart SlideDeck created!</strong> Now use the <a href="#get-slidedeck-template-snippet"><em>Theme Code Snippet</em></a> to place it in your templates.',
        'SlideDeck deleted!'
    );
    
    $message_id = ( intval( $_GET['message'] ) - 1 );
    
    echo '<div id="message" class="updated fade below-h2" style="background-color: rgb(255,251,204);"><p>' . $messages[$message_id] . '</p></div>';
}


/**
 * The processor for the "Add New" menu item
 * 
 * @uses slidedeck_get_skins()
 * @uses slidedeck_dir() 
 */ 
function slidedeck_add_new() {
    $form_action = "create";    // Set the form action ( referenced when saving the SlideDeck and for interface appearance )
    $default_slide_amount = 3;    // Set the default amount of slides to start with
    
    $skins = slidedeck_get_skins();
    
    // Set the default SlideDeck settings
    $slidedeck = array( 
        'title' => "My SlideDeck",
        'slidedeck_options' => array(),
        'gallery_id' => time(),
        "skin" => SLIDEDECK_DEFAULT_SKIN
     );

    // Populate the default slide values
    $slides = array();
    $slide_names = array();
    for ( $i = 1; $i <= $default_slide_amount; $i++ ) {
        $slides[] = array(
            'title' => "",
            'content' => "",
            'slide_order' => $i,
            'gallery_id' => $slidedeck['gallery_id']
         );
    }
    
    // Defaults for the $slidedeck
    $slidedeck_params = array(
        'slidedeck-create_wpnonce' => wp_create_nonce( 'slidedeck-for-wordpress' ),
        'dynamic' => 0,
        'action' => 'create',
        'slidedeck_options' => array(
            'autoPlayInterval' => slidedeck_get_option( $slidedeck, 'autoPlayInterval' ),
            'speed' => slidedeck_get_option( $slidedeck, 'speed' ),
            'start' => slidedeck_get_option( $slidedeck, 'start' ),
            'autoPlay' => slidedeck_get_option( $slidedeck, 'autoPlay' ),
            'cycle' => slidedeck_get_option( $slidedeck, 'cycle' ),
            'activeCorner' => slidedeck_get_option( $slidedeck, 'activeCorner' ),
            'keys' => slidedeck_get_option( $slidedeck, 'keys' ),
            'scroll' => slidedeck_get_option( $slidedeck, 'scroll' ),
            'continueScrolling' => slidedeck_get_option( $slidedeck, 'continueScrolling' ),
            'hideSpines' => slidedeck_get_option( $slidedeck, 'hideSpines' )
        ),
        'skin' => $slidedeck['skin'],
        'title' => $slidedeck['title']
    );
    
    $slidedeck = slidedeck_save( $slidedeck_params, 'auto-draft' );
    for( $i = 0; $i < count( $slides ); $i++ ) {
        $slides[$i]['gallery_id'] = $slidedeck['id'];
    }
    
    // Render the editor form
    include( slidedeck_dir( '/views/edit-form.php' ) );
}


/**
 * The processor for the "Add New Dynamic" menu item
 * 
 * @uses slidedeck_get_skins()
 * @uses get_categories()
 * @uses slidedeck_load()
 * @uses slidedeck_dir()
 */
function slidedeck_dynamic() {
    $form_action = "create";
    if ( isset( $_GET['action'] ) && !empty( $_GET['action'] ) ) {
        $form_action = $_GET['action'];
    }
    
    $default_slide_amount = 5;
    $skins = slidedeck_get_skins( 'dynamic' );
    $categories = get_categories( array(
        'type' => 'post',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false
    ) );
    
    $slidedeck = array( 
        'title' => "",
        'slidedeck_options' => array(
            'speed' => 500,
            'start' => 1,
            'autoPlay' => 'true',
            'autoPlayInterval' => 5000,
            'cycle' => 'true',
            'hideSpines' => 'true'
        ),
        'dynamic_options' => array(
            'type' => 'recent',
            'filter_by_category' => 0,
            'filter_categories' => array(),
            'navigation_type' => 'simple-dots',
            'total' => 5,
            'cache_minutes' => 30,
            'image_source' => 'content',
            'excerpt_length_with_image' => SLIDEDECK_EXCERPT_LENGTH_WITH_IMAGE,
            'excerpt_length_without_image' => SLIDEDECK_EXCERPT_LENGTH_WITHOUT_IMAGE,
            'title_length_with_image' => SLIDEDECK_TITLE_LENGTH_WITH_IMAGE,
            'title_length_without_image' => SLIDEDECK_TITLE_LENGTH_WITHOUT_IMAGE
        ),
        'skin' => 'dark',
        'gallery_id' => time(),
        'dynamic' => 1
    );
    if ( isset( $_GET['id'] ) && !empty( $_GET['id'] ) ) {
        $slidedeck_id = intval( $_GET['id'] );
        
        $slidedeck = slidedeck_load( $slidedeck_id );
    }
    
    include( slidedeck_dir( '/views/add-edit-dynamic.php' ) );
}

/**
 * Outputs an <ul> for the SlideDeck Blog on the "Overview" page
 * 
 * @uses fetch_feed() 
 */
function slidedeck_blog_feeds(){
    if( !function_exists( 'fetch_feed' ) ) {
        die('false');
    }
    $rss = fetch_feed('http://feeds.feedburner.com/Slidedeck');
    if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly
        $output = ""; 
        // Figure out how many total items there are, but limit it to 5. 
        $maxitems = $rss->get_item_quantity( 2 ); 
    
        // Build an array of all the items, starting with element 0 (first element).
        $rss_items = $rss->get_items(0, $maxitems); 
        $output = '<ul class="postList">' . "\n";
        foreach( $rss_items as $key => $value ){
            $output .= '<li><a href="' . $value->get_permalink() . '" target="_blank">' . $value->get_title() . '</a></li>' . "\n";
        } 
        $output .= '</ul>' . "\n";
        
        $output .= '<a class="more" href="http://www.slidedeck.com/blog" target="_blank">See More</a>' . "\n";
        
        die( $output );
    endif;
    
    die('false');
}

/**
 * The processor for the "Edit" and "Overview" menu items
 * 
 * @uses slidedeck_get_skins()
 * @uses slidedeck_load()
 * @uses slidedeck_load_slides()
 * @uses slidedeck_dir()
 * @uses wp_delete_post()
 */
function slidedeck_edit() {
    $action = "overview";    // Set the default action to overview to display a list of SlideDecks
    // Override the default action if one has been specified
    if ( isset( $_REQUEST['action'] ) && !empty( $_REQUEST['action'] ) ) {
        $action = $_REQUEST['action'];
    }
    
    $skins = slidedeck_get_skins();
    
    // Change processing based off of action specified
    switch ( $action ) {
        case "edit":
            global $post_ID;
            
            $form_action = "edit";
            $slidedeck_id = $post_ID = $_GET['id'];
            
            // Get the SlideDeck
            $slidedeck = slidedeck_load( $slidedeck_id );

            // Get the SlideDeck's slides
            $slides = slidedeck_load_slides( $slidedeck_id );
            
            // Accommodate for entries without a gallery_id
            if ( $slidedeck['gallery_id'] == '0' ) {
                $slidedeck['gallery_id'] = time();
            }

            // Assign the SlideDeck's gallery_id to each slide
            for ( $i=0; $i<count( $slides ); $i++ ) {
                $slides[$i]['gallery_id'] = $slidedeck['gallery_id'];
            }
            
            // Render the editor form
            include( slidedeck_dir( '/views/edit-form.php' ) );
        break;
        
        case "delete":
            if( check_admin_referer( 'slidedeck-delete' ) ) {
                $slidedeck_id = $_GET['id'];
                
                // Delete the SlideDeck entry
                wp_delete_post( $slidedeck_id, true );
                
                // Delete the SlideDeck slides associated with the SlideDeck entry
                $slides = slidedeck_load_slides( $slidedeck_id );
                foreach( $slides as $slide ) {
                    wp_delete_post( $slide['id'], true );
                }

                wp_remote_fopen( "http://trk.kissmetrics.com/e?_k=" . KMAPI_KEY . "&_n=SlideDeck+Deleted&_p=" . SLIDEDECK_USER_HASH . '&variation=' . ( $params['dynamic'] == '1' ? 'dynamic' : 'manual' ) . '&version=Lite' );
            } else {
                return false;
            }

        default:
            global $slidedeck_global_options, $slidedeck_global_options_defaults;
            
            // Accommodate for ordering parameters passed in the URL
            $orderby = 'title';
            if( isset( $_GET['orderby'] ) && !empty( $_GET['orderby'] ) ) {
                $orderby = $_GET['orderby'];
            }
            $order = 'ASC';
            if( isset( $_GET['order'] ) && !empty( $_GET['order'] ) ) {
                $order = $_GET['order'];
            }
            
            // Get a list of all SlideDecks in the system
            $slidedecks = slidedeck_load( null, $orderby, $order );

            $form_action = 'update_global_options';
            if( isset( $_POST ) && !empty( $_POST ) ) {
                $form_action = slidedeck_sanitize( $_POST['action'] );
                if( !wp_verify_nonce( $_POST['slidedeck-' . $form_action . '_wpnonce'], 'slidedeck-for-wordpress' ) ) {
                    return false;
                }
                
                $options = array( 
                    'disable_wpautop' => isset( $_POST['disable_wpautop'] ) ? true : false,
                    'enable_ssl_check' => isset( $_POST['enable_ssl_check'] ) ? true : false,
                    'dont_enqueue_scrollwheel_library' => isset( $_POST['dont_enqueue_scrollwheel_library'] ) ? true : false
                );

                update_option( 'slidedeck_global_options', $options );
                
                $slidedeck_global_options = get_option( 'slidedeck_global_options', $slidedeck_global_options_defaults );
                $slidedeck_global_options = array_merge( $slidedeck_global_options_defaults, $slidedeck_global_options );
            }
            
            // Render the overview list
            include( slidedeck_dir( '/views/overview.php' ) );
        break;
    }
}



/**
 * Processor to save SlideDecks - shared by create and edit form submissions
 * 
 * @param object $post_params The SlideDeck options to save, if none are passed, returns false
 * 
 * @uses wp_verify_nonce()
 * @uses slidedeck_sanitize()
 * @uses wp_insert_post()
 * @uses slidedeck_load()
 * @uses slidedeck_load_slides()
 * @uses update_post_meta()
 * 
 * @return object $slidedeck Updated SlideDeck object
 */
function slidedeck_save( $post_params = null, $post_status = 'publish' ) {
    if( !isset( $post_params ) ) {
        return false;
    }
    
    $action = $post_params['action'];

    if( !wp_verify_nonce( $post_params['slidedeck-' . $action . '_wpnonce'], 'slidedeck-for-wordpress' ) ) {
        return false;
    }
    
    $params = array();
    foreach ( (array) $post_params as $key => $val ) {
        if ( is_string( $val ) ) {
            $params[$key] = slidedeck_sanitize( $val );
        } elseif ( is_array( $val ) ) {
            foreach ( (array) $val as $key1 => $val1 ) {
                if ( is_array( $val1 ) ) {
                    $sub_arr = array();
                    foreach ( (array) $val1 as $key2 => $val2 ) {
                        $sanitized = $val2;
                        switch ( $key2 ) {
                            case "title":
                            case "content":
                                $sanitized = html_entity_decode( slidedeck_sanitize( $val2 ), ENT_QUOTES, 'UTF-8' );
                            break;
                        }
                        $sub_arr[$key2] = $sanitized;
                    }
                    $params[$key][$key1] = $sub_arr;
                } else {
                    $params[$key][$key1] = slidedeck_sanitize( $val1 );
                }
            }
        }
    }
    
    // Begin building formatted array of data to be saved
    $slidedeck_params = array( 
        'title' => $params['title'],
        'dynamic' => $params['dynamic'],
        'gallery_id' => $params['gallery_id']
    );
    
    if ( $params['dynamic'] == '1' ) {
        if ( !isset( $params['dynamic_options']['filter_by_category'] ) || !isset( $params['dynamic_options']['filter_categories'] ) ) {
            $params['dynamic_options']['filter_by_category'] = '0';
            $params['dynamic_options']['filter_categories'] = array();
        }
        $slidedeck_params['dynamic_options'] = serialize( $params['dynamic_options'] );
    }

    $slidedeck_params['skin'] = $params['skin'];

    if ( !isset( $params['slidedeck_options']['autoPlay'] ) ) {
        $params['slidedeck_options']['autoPlay'] = '0';
    }
    if ( !isset( $params['slidedeck_options']['cycle'] ) ) {
        $params['slidedeck_options']['cycle'] = 'false';
    }
    if ( !isset( $params['slidedeck_options']['activeCorner'] ) ) {
        $params['slidedeck_options']['activeCorner'] = 'false';
    }
    if ( !isset( $params['slidedeck_options']['keys'] ) ) {
        $params['slidedeck_options']['keys'] = 'false';
    }
    if ( !isset( $params['slidedeck_options']['scroll'] ) ) {
        $params['slidedeck_options']['scroll'] = 'false';
    }
    if ( !isset( $params['slidedeck_options']['continueScrolling'] ) ) {
        $params['slidedeck_options']['continueScrolling'] = 'false';
    }
    if ( !isset( $params['slidedeck_options']['hideSpines'] ) ) {
        $params['slidedeck_options']['hideSpines'] = 'false';
    }
    $params['slidedeck_options']['autoPlayInterval'] = $params['slidedeck_options']['autoPlayInterval'] * 1000;
    $slidedeck_params['slidedeck_options'] = serialize( $params['slidedeck_options'] );
    
    // Process based off of action - is this new or updating an existing entry
    switch ( $action ) {
        case "create":
            if( isset( $params['id'] ) ) {
                $slidedeck_id = wp_update_post( array(
                    'ID' => $params['id'],
                    'post_status' => "publish",
                    'post_content' => "",
                    'post_title' => $slidedeck_params['title']
                ) );
            } else {
                // Insert a new SlideDeck in the database
                $slidedeck_id = wp_insert_post( array(
                    'post_content' => "",
                    'post_title' => $slidedeck_params['title'],
                    'post_status' => $post_status,
                    'comment_status' => "closed",
                    'ping_status' => "closed",
                    'post_type' => SLIDEDECK_POST_TYPE
                ) );
            }
                
            if( $post_status == "publish" ) {
                wp_remote_fopen( "http://trk.kissmetrics.com/e?_k=" . KMAPI_KEY . "&_n=SlideDeck+Created&_p=" . SLIDEDECK_USER_HASH . '&variation=' . ( $params['dynamic'] == '1' ? 'dynamic' : 'manual' ) . '&version=Lite' );
            }
            
            // Retrieve the last SlideDeck created to get the ID of the new database entry
            $slidedeck = slidedeck_load( $slidedeck_id );
            
            // Insert new slide entries linked to the newly created SlideDeck if this is not a dynamic SlideDeck
            if ( $params['dynamic'] != '1' ) {
                foreach ( (array) $params['slide'] as $slide ) {
                    $slide_id = wp_insert_post( array(
                        'post_content' => $slide['content'],
                        'post_title' => $slide['title'],
                        'post_status' => "publish",
                        'comment_status' => "closed",
                        'ping_status' => "closed",
                        'post_parent' => $slidedeck_id,
                        'menu_order' => $slide['slide_order'],
                        'post_type' => SLIDEDECK_SLIDE_POST_TYPE
                    ) );
                }
            }
        break;
        
        case "edit":
             $slidedeck_id = wp_update_post( array(
                'ID' => $params['id'],
                'post_content' => "",
                'post_title' => $slidedeck_params['title']
             ) );
             
            if ( $params['dynamic'] != '1' ) {
                // Get the existing slides associated with this SlideDeck
                $slides = slidedeck_load_slides( $slidedeck_id );
                
                $existing_slides = array();
                $new_slides = array();

                // Create an array of IDs of the current slides associated with this SlideDeck prior to submission
                foreach ( (array) $slides as $slide ) {
                    if( isset( $slide['id'] ) && !empty( $slide['id'] ) ) {
                        $existing_slides[] = $slide['id'];
                    }
                }
                
                foreach ( (array) $params['slide'] as $slide ) {
                    // Compare each submitted slide to see if it exists in the database already
                    if ( isset( $slide['id'] ) && !empty( $slide['id'] ) ) {
                         $slide_id = wp_update_post( array(
                            'ID' => $slide['id'],
                            'post_content' => $slide['content'],
                            'post_title' => $slide['title'],
                            'menu_order' => $slide['slide_order']
                         ) );

                        // Add the submitted slide ID to an array to later compare against the previously existing slides
                        $new_slides[] = $slide_id;
                    } else {
                        // If slide does not exist yet, add it to the database
                        $slide_id = wp_insert_post( array(
                            'post_content' => $slide['content'],
                            'post_title' => $slide['title'],
                            'post_status' => "publish",
                            'comment_status' => "closed",
                            'ping_status' => "closed",
                            'post_parent' => $slidedeck_id,
                            'menu_order' => $slide['slide_order'],
                            'post_type' => SLIDEDECK_SLIDE_POST_TYPE
                        ) );
                        
                        // Add the ID of the new slide to the comparison array
                        $new_slides[] = $slide_id;
                    }
                }
                
                // Compare the array of slides that existed prior to submission to the slides that were submitted
                foreach ( (array) $existing_slides as $slide_id ) {
                    // If the previously existing slide is not found in the submitted slides array, user has deleted it - remove the record from the database
                    if ( !in_array( $slide_id, $new_slides ) ) {
                        wp_delete_post( $slide_id, true );
                    }
                }
            }
        break;
    }
    
    // Update SlideDeck in the database
    update_post_meta( $slidedeck_id, '_slidedeck_slidedeck_options', $slidedeck_params['slidedeck_options'] );
    update_post_meta( $slidedeck_id, '_slidedeck_is_dynamic', $slidedeck_params['dynamic'] );
    update_post_meta( $slidedeck_id, '_slidedeck_skin', $slidedeck_params['skin'] );
    if( isset( $slidedeck_params['dynamic_options'] ) ) {
        update_post_meta( $slidedeck_id, '_slidedeck_dynamic_options', $slidedeck_params['dynamic_options'] );
    }
    /**
     * Flag the saved SlideDeck to use the new stored data format
     * @since 1.4.5
     */
    update_post_meta( $slidedeck_id, '_slidedeck_new_format', true );
    
    // Get the record for the submitted SlideDeck to return the newly saved values
    $slidedeck = slidedeck_load( $slidedeck_id, null, null, $post_status );
    
    return $slidedeck;
}


/**
 * Setup TinyMCE button
 * 
 * @uses wp_register_style()
 * @uses current_user_can()
 * @uses get_user_option()
 * @uses wp_enqueue_script()
 * @uses wp_enqueue_style()
 */
function slidedeck_addbuttons() {
    // Setup the stylesheet to use for the modal window interaction
    wp_register_style( 'slidedeck-ui-styles', slidedeck_url( '/lib/tinymce3/slidedeck-jquery-ui.css' ) );

    // Return false if the user does not have WYSIWYG editing privileges
    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
        return false;
    }
    
    // Add buttons to TinyMCE editor if user can edit with WYSIWYG editor
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
        add_filter( 'mce_external_plugins', 'slidedeck_add_tinymce_plugin' );
        add_filter( 'mce_buttons', 'slidedeck_register_button' );
    }

    // Only load the necessary scripts if the user is on the post/page editing admin pages
    if ( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post-new.php', 'page-new.php', 'post.php', 'page.php' ) ) ) {
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'slidedeck-sidebar', slidedeck_url( '/lib/slidedeck-sidebar.js' ), array('jquery-ui-dialog'), SLIDEDECK_VERSION, true );
        wp_enqueue_style( 'slidedeck-ui-styles' );
    }
}


/**
 * Add the SlideDeck button to the TinyMCE interface
 * 
 * @param object $buttons An array of buttons for the TinyMCE interface
 * 
 * @return object $buttons The modified array of TinyMCE buttons
 */
function slidedeck_register_button( $buttons ) {
    array_push( $buttons, "separator", "slidedeck" );
    return $buttons;
}


/**
 * Add the SlideDeck TinyMCE plugin to the TinyMCE plugins list
 * 
 * @param object $plugin_array The TinyMCE options array
 * 
 * @uses slidedeck_is_plugin()
 * @uses slidedeck_url()
 * 
 * @return object $plugin_array The modified TinyMCE options array
 */
function slidedeck_add_tinymce_plugin( $plugin_array ) {
    if( !slidedeck_is_plugin() ) {
        $plugin_array['slidedeck'] = slidedeck_url( '/lib/tinymce3/editor-plugin.js' );
    }

    return $plugin_array;
}


/**
 * Create the modal window dialog box for the TinyMCE plugin
 * 
 * @uses slidedeck_load()
 * @uses slidedeck_dir()
 */
function slidedeck_tinymce_plugin_dialog() {
    // Only load the necessary scripts and render the modal window dialog box if the user is on the post/page editing admin pages
    if ( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post-new.php', 'page-new.php', 'post.php', 'page.php' ) ) ) {
        $slidedecks = slidedeck_load();
        
        include( slidedeck_dir( '/views/_tinymce-plugin-dialog.php' ) );
    }
}



/**
 * Determine which SlideDecks are being loaded on this page
 * 
 * @uses slidedeck_load()
 */
function slidedeck_wp_hook() {
    global $posts, $slidedeck_skin_included;
    
    $slidedeck_skin_included['default'] = true;
    
    if( isset( $posts ) && !empty( $posts ) ) {
        $slidedeck_ids = array();
    
        // Process through $posts for the existence of SlideDecks
        foreach( (array) $posts as $post ) {
            $matches = array();
            preg_match_all( '/\[SlideDeck( ([a-zA-Z0-9]+)\=\'([a-zA-Z0-9\%\-_\.]+)\')*\]/', $post->post_content, $matches );
            if( !empty( $matches[0] ) ) {
                foreach( $matches[0] as $match ) {
                    $str = $match;
                    $str_pieces = explode( " ", $str );
                    foreach( $str_pieces as $piece ) {
                        $attrs = explode( "=", $piece );
                        if( $attrs[0] == "id" ) {
                            // Add the ID of this SlideDeck to the ID array for loading
                            $slidedeck_ids[] = intval( str_replace( "'", '', $attrs[1] ) );
                        }
                    }
                }
            }
        }
        
        $legacy_ids = array();
        foreach( $slidedeck_ids as $slidedeck_id ) {
            $legacy_ids[] = slidedeck_legacy_id( $slidedeck_id );
        }
        $slidedeck_ids = $legacy_ids;
        
        if( !empty( $slidedeck_ids ) ) {
            // Load SlideDecks used on this URL passing the array of IDs
            $slidedecks = slidedeck_load( $slidedeck_ids );
    
            // Loop through SlideDecks used on this page and add their skins to the $slidedeck_skin_included array for later use
            foreach( (array) $slidedecks as $slidedeck ) {
                $skin_slug = isset( $slidedeck['skin'] ) && !empty( $slidedeck['skin'] ) ? $slidedeck['skin'] : 'default';
                $slidedeck_skin_included[$skin_slug] = true;
            }
        }
    }
}


/**
 * Load the SlideDeck library JavaScript and support files in the public views to render SlideDecks
 * 
 * @uses wp_register_script()
 * @uses slidedeck_url()
 * @uses wp_enqueue_script()
 * @uses slidedeck_get_skin()
 */
function slidedeck_print_scripts() {
    global $slidedeck_skin_included;
    global $slidedeck_global_options;
    
    wp_register_script( 'slidedeck-library-js', slidedeck_url( '/lib/slidedeck.jquery.lite.pack.js' ), array( 'jquery' ), SLIDEDECK_VERSION );
    wp_register_script( 'scrolling-js', slidedeck_url( '/lib/jquery-mousewheel/jquery.mousewheel.min.js' ), array( 'jquery' ), SLIDEDECK_VERSION );

    wp_enqueue_script( 'jquery' );
    
    if( $slidedeck_global_options['dont_enqueue_scrollwheel_library'] != true ) {
        wp_enqueue_script( 'scrolling-js' );
    }
    
    wp_enqueue_script( 'slidedeck-library-js' );
    
    // Make accommodations for the editing view to only load the skin files for the SlideDeck being edited
    if( slidedeck_is_plugin() ){
        if( isset( $_GET['id'] ) ) {
            $slidedeck = slidedeck_load( $_GET['id'] );
            $skin = $slidedeck['skin'];
        } else {
            $skin = SLIDEDECK_DEFAULT_SKIN;
        }
        
        $slidedeck_skin_included = array( $skin => 1 );
        echo '<script type="text/javascript">var SLIDEDECK_USE_OLD_TINYMCE_EDITOR = ' . var_export( SLIDEDECK_USE_OLD_TINYMCE_EDITOR, true ) . ';</script>';
    }
    
    foreach( (array) $slidedeck_skin_included as $skin_slug => $val ) {
        $skin = slidedeck_get_skin( $skin_slug );
        if( isset( $skin['script_url'] ) ) {
            wp_register_script( 'slidedeck-skin-js-' . $skin_slug, $skin['script_url'], array( 'jquery', 'slidedeck-library-js' ), SLIDEDECK_VERSION );
            wp_enqueue_script( 'slidedeck-skin-js-' . $skin_slug );
        }
    }
}


/**
 * Load SlideDeck support CSS files for skins used by SlideDecks on a page
 * 
 * @uses slidedeck_get_skin()
 * @uses slidedeck_get_skin_css()
 */
function slidedeck_print_styles() {
    global $slidedeck_skin_included;
    
    // Make accommodations for the editing view to only load the skin files for the SlideDeck being edited
    if( slidedeck_is_plugin() ){
        if( isset( $_GET['id'] ) ) {
            $slidedeck = slidedeck_load( $_GET['id'] );
            $skin = $slidedeck['skin'];
        } else {
            $skin = SLIDEDECK_DEFAULT_SKIN;
        }
        
        $slidedeck_skin_included = array( $skin => 1 );
    }
    
    foreach( (array) $slidedeck_skin_included as $skin_slug => $val ) {
        $skin = slidedeck_get_skin( $skin_slug );
        echo slidedeck_get_skin_css( $skin );
    }
} 


/**
 * Process the SlideDeck shortcode
 * 
 * @param object $atts Attributes of the shortcode
 * 
 * @uses shortcode_atts()
 * @uses slidedeck_process_template()
 * 
 * @return object The processed shortcode
 */
function slidedeck_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'id' => false,
        'width' => '100%',
        'height' => '300px',
        'include_skin_files' => true
    ), $atts ) );
    
    $id = slidedeck_legacy_id( $id );
    
    if ( $id !== false ) {
        return slidedeck_process_template( $id, array( 'width' => $width, 'height' => $height ), $include_skin_files );
    } else {
        return "";
    }
}

/**
 * SlideDeck markup creator
 * 
 * @param object $slidedeck_id The ID of the SlideDeck to render
 * @param array $styles [optional] The styles to apply to the main SlideDeck tag ( usually just width and height )
 * @param boolean $include_skin_files Include the files for this SlideDeck's skin?
 * 
 * @uses slidedeck_load()
 * @uses slidedeck_load_slides()
 * @uses slidedeck_dir()
 * @uses slidedeck_dynamic_template_content()
 * @uses slidedeck_get_skin()
 * @uses WP_Query
 * @uses get_the_title()
 * @uses get_the_time()
 * @uses human_time_diff()
 * @uses wp_trim_excerpt()
 * @uses get_permalink()
 * @uses get_post_custom_values()
 * @uses get_post_meta()
 * @uses slidedeck_get_dynamic_option()
 * @uses slidedeck_parse_html_for_images()
 * @uses slidedeck_get_option()
 * @uses slidedeck_prepare_excerpt()
 * @uses slidedeck_output()
 * 
 * @return Rendered SlideDeck markup and JavaScript tag to initialize SlideDeck render
 */
function slidedeck_process_template( $slidedeck_id, $styles = array( 'width' => '100%', 'height' => '300px' ), $include_skin_files = true ) {
    global $slidedeck_skin_included, $slidedeck_footer_scripts;
    
    // Lookup the SlideDeck requested
    $slidedeck = slidedeck_load( $slidedeck_id );
    
    if ( isset( $slidedeck ) && !empty( $slidedeck ) ) {
        $is_dynamic = (boolean) $slidedeck['dynamic'];
        $image_skin = false;
        
        $skin = slidedeck_get_skin( ( isset( $slidedeck['skin'] ) && !empty( $slidedeck['skin'] ) ) ? $slidedeck['skin'] : 'default' );
        if( isset( $skin['meta']['Skin Type'] ) && $skin['meta']['Skin Type'] == "fixed" ) {
            $styles['height'] = $skin['meta']['Skin Height'] . "px";
        }
        if( isset( $skin['meta']['Skin Slide Type'] ) && $skin['meta']['Skin Slide Type'] == "image" ) {
            $image_skin = true;
        }
        $skin_image_width = isset( $skin['meta']['Skin Image Width'] ) ? $skin['meta']['Skin Image Width'] : '270px';
        $skin_image_height = isset( $skin['meta']['Skin Image Height'] ) ? $skin['meta']['Skin Image Height'] : '250px';
        
        // Setup styles array as inline style string
        $styles_str = "";
        $sep = "";
        foreach ( (array) $styles as $style => $def ) {
            $styles_str.= $sep . $style . ":" . $def;
            $sep = ";";
        }
    
        srand(); // Seed the random number generator
        // Create unique SlideDeck ID for this SlideDeck
        $slidedeck_uid = "SlideDeck_" . rand( 100, 999 ) . "_" . $slidedeck['id'];
        
        if ( $is_dynamic === true ) {
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $slidedeck['dynamic_options']['total']
            );
            if ( $slidedeck['dynamic_options']['filter_by_category'] == '1' ) {
                $args['cat'] = implode( ',', $slidedeck['dynamic_options']['filter_categories'] );
            }
            switch ( $slidedeck['dynamic_options']['type'] ) {
                case "recent":
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                break;
                
                case "featured":
                    $args['meta_key'] = '_slidedeck_post_featured';
                    $args['meta_value'] = '1';
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                break;
                
                case "popular":
                    $args['orderby'] = '_slidedeck_popularity';
                    $args['order'] = 'DESC';
                break;
            }
            
            $excerpt_length_with_image = SLIDEDECK_EXCERPT_LENGTH_WITH_IMAGE;
            $excerpt_length_without_image = SLIDEDECK_EXCERPT_LENGTH_WITHOUT_IMAGE;
            $title_length_with_image = SLIDEDECK_TITLE_LENGTH_WITH_IMAGE;
            $title_length_without_image = SLIDEDECK_TITLE_LENGTH_WITHOUT_IMAGE;

            // Post Based Dynamic SlideDeck.
            $dynamic_posts = new WP_Query( $args );
            
            $slides = array();
            foreach( $dynamic_posts->posts as $post ) {
                $post_id = $post->ID;
                
                $slide = array();
                
                $slide_nodes = array(
                    'type' => $slidedeck['dynamic_options']['type'],
                    'title' => html_entity_decode( get_the_title( $post_id ), ENT_QUOTES, 'UTF-8' ),
                    'timestamp' => get_the_time( 'U', $post_id ),
                    'timesince' => "Posted " . human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) ) . " ago",
                    'permalink' => get_permalink( $post_id )
                );
                
                $post_content = $post->post_content;
                $post_excerpt = false;
                if( !empty( $post->post_excerpt ) ){
                    $post_excerpt = $post->post_excerpt;
                }
                
                switch( slidedeck_get_dynamic_option( $slidedeck, 'image_source' ) ) {
                    case 'none':
                        $slide_nodes['image'] = null;
                    break;
                    default:
                    case 'content':
                        $slide_nodes['image'] = slidedeck_parse_html_for_images( $post_content, slidedeck_get_dynamic_option( $slidedeck, 'validate_images' ) );
                    break;
                }
                
                
                // Unfortunately we cannot use WP's built in excerpt shortener since
                // we cannot easily access the existing set excerpt length and shouldn't
                // modify the user's perferences here
                $title_chracter_limit = !empty( $slide_nodes['image'] ) ? $title_length_with_image : $title_length_without_image;
                $the_excerpt_limit = isset( $slide_nodes['image'] ) ? $excerpt_length_with_image : $excerpt_length_without_image;
                
                if( $post_excerpt === false ){
                    $the_excerpt = strip_shortcodes( $post_content );
                }else{
                    $the_excerpt = $post_excerpt;                        
                }
                
                $slide_nodes['excerpt'] = slidedeck_prepare_excerpt( $the_excerpt, $the_excerpt_limit );
                $slide_nodes['title'] = slidedeck_prepare_title( $slide_nodes['title'], $title_chracter_limit );
                
                // Process content nodes through template to create slide content
                ob_start();
    
                    foreach ( $slide_nodes as $node => $val ) {
                        $$node = $val;
                    }
                        if( empty( $slide_nodes['image'] ) && $image_skin ){
                            // Keep looking if we don't find a post with an image
                            continue;
                        }else{
                            // Until a full template selection system is built, just get the first one in the folder
                            $template = reset( $skin['templates'] );
                            include( $template['file'] );
                            $slide['content'] = ob_get_contents();
                        }
    
                    foreach ( $slide_nodes as $node => $val ) {
                        $$node = null;
                    }
    
                ob_end_clean();
                
                // Process slide title with post title or specified slide title
                $spine_title = get_post_meta( $post_id, '_slidedeck_slide_title', true );
                $slide['title'] = !empty( $spine_title ) ? $spine_title :  get_the_title( $post_id );
                
                $slide['timestamp'] = $slide_nodes['timestamp'];
                
                $slides[] = $slide;
            }
        } else {
            // Get the Slides for the SlideDeck requested
            $slides = slidedeck_load_slides( $slidedeck_id );
        }
        
        // Generate markup for the template string.
        $template_str = slidedeck_output( $slidedeck, $slidedeck_uid, $slides, $styles_str );
        
        // Build the JavaScript statement.
        $slidedeck_options_json = "{ ";
        $sep = "";
        foreach ( (array) $slidedeck['slidedeck_options'] as $key => $val ) {
            $slidedeck_options_json.= $sep . $key . ": ";
            
            if ( $val == 'true' || $val == 'false' ) { $slidedeck_options_json.= $val; }
            elseif ( is_numeric( $val ) ) { $slidedeck_options_json.= $val; }
            else { $slidedeck_options_json.= (string) "'{$val}'"; }
            
            $sep = ", ";
        }
        $slidedeck_options_json.= " }";
        
        if ( $is_dynamic === true ) {
            $template_str.= slidedeck_dynamic_template_content( $slidedeck, $slides );
        }
        
        $template_str.= '</div>';

        $slidedeck_footer_scripts .= '<script type="text/javascript">jQuery( \'#' . $slidedeck_uid . '\' ).slidedeck( ' . $slidedeck_options_json . ' );</script>';
        
        if( !isset( $slidedeck_skin_included[$skin['slug']] ) && $include_skin_files === true ) {
            
            $slidedeck_skin_included[$skin['slug']] = true;

            $skin_css_tags = slidedeck_get_skin_css( $skin );
            $template_str = $skin_css_tags . $template_str;

            if ( isset( $skin['script_url'] ) && !empty( $skin['script_url'] ) ) {
                $slidedeck_footer_scripts .= '<script type="text/javascript" src="' . $skin['script_url'] .'"></script>';
            }
        }
        
    } else {
        $template_str = "";
    }
    
    return $template_str;
}

/**
 * Used for printing out the JavaScript commands to load SlideDecks and appropriately
 * read the DOM for positioning, sizing, dimensions, etc.
 * 
 * @return Echo out the JavaScript tags generated by slidedeck_process_template;
 */
function slidedeck_print_footer_scripts() {
    global $slidedeck_footer_scripts;
    
    echo $slidedeck_footer_scripts;
}



/**
 * Used for trimming HTML down to a certain length
 * 
 * @param str Input HTML
 * @param int Length of words to output
 * 
 * @return str Trimmed input HTML
 */
function slidedeck_prepare_excerpt( $input, $limit ) {
    global $slidedeck_global_options;
    if( $limit > 0 ) {
        $the_excerpt = trim( strip_tags( $input ) );
        $the_excerpt_pieces = explode( " ", $the_excerpt );
        
        // Remove empty values from the array.
        foreach ( $the_excerpt_pieces as $key => $value ) {
          if ( empty( $value ) ) {
            unset( $the_excerpt_pieces[$key] );
          }
        }
        
        // Only return something if there's something to return.
        if( count( $the_excerpt_pieces ) > 0 ) {
            $slidedeck_trimmed_excerpt = implode( " ", array_slice( $the_excerpt_pieces, 0, $limit ) );
            if ( count( $the_excerpt_pieces ) > $limit ) {
                $slidedeck_trimmed_excerpt.="&hellip;";
            }
            if( $slidedeck_global_options['disable_wpautop'] == true ) {
                return html_entity_decode( $slidedeck_trimmed_excerpt, ENT_QUOTES, 'UTF-8' );
            }else{
                return wpautop( html_entity_decode( $slidedeck_trimmed_excerpt, ENT_QUOTES, 'UTF-8' ), false );
            }
        }
    }
}


/**
 * Truncate the title string
 * 
 * Truncate a title string for better visual display in Smart SlideDecks.This
 * function is multibyte aware so it should handle UTF-8 strings correctly.
 * 
 * @param $text str The text to truncate
 * @param $length int (100) The length in characters to truncate to
 * @param $ending str The ending to tack onto the end of the truncated title (if the title was truncated)
 */
function slidedeck_prepare_title( $text, $length = 100, $ending = '&hellip;' ) {
    $truncated = mb_substr( strip_tags( $text ), 0, $length, 'UTF-8' );
    
    if( function_exists( 'mb_strlen' ) ) {
        $original_length = mb_strlen( $text, 'UTF-8' );
    } else {
        $original_length = strlen( $text );
    }
    if( $original_length > $length ) {
        $truncated.= $ending;
    }
    
    return $truncated;
}


/**
 * Parses raw HTML and returns an array of images 
 * 
 * @param str Raw HTML to be processed
 * @param boolean (false) Validate the feed content images for valid images
 * 
 * @return arr Array of images found in the passed in HTML
 */
function slidedeck_parse_html_for_images( $html_string, $validate = false ) {
    $html_string = str_replace( array( "\n", "\r" ), array( " ", " " ), $html_string);
    $image_raw = substr( $html_string, strpos( $html_string, '<img ' ) );
    $image_raw = substr( $image_raw, 0, strpos( $image_raw, '>' ) );
    
    $image_strs = array();
    preg_match_all( '/<img(\s*([a-zA-Z]+)\=\"([a-zA-Z0-9\/\#\&\=\|\-_\+\%\!\?\:\;\.\(\)\~\s\,]*)\")+\s*\/?>/', $html_string, $image_strs );
    
    $images = array();
    if( isset( $image_strs[0] ) && !empty( $image_strs[0] ) ) {
        foreach( (array) $image_strs[0] as $image_str ) {
            $image_attr = array();
            $image_substr = preg_match_all( '/([a-zA-Z]+)\=\"([a-zA-Z0-9\/\#\&\=\|\-_\+\%\!\?\:\;\.\(\)\~\s\,]*)\"/', $image_str, $image_attr );
            
            if( in_array( 'src', $image_attr[1] ) ) {
                $images[] = array_combine( $image_attr[1], $image_attr[2] );
            }
        }
    }
    
    $output = false;
    $output_set = false;
    $threshold = 2;
    if( !empty( $images ) ) {
        // If validation for this SlideDeck is turned on, the filtering threshold will be upped to 120 pixels wide.
        // This helps to filter out most advertisements. 1 pixel images will always be removed (unless a width is not defined).
        if( (boolean) $validate === true ) {
            $threshold = 120;
        }
        foreach( $images as $image ) {
            $valid = true;
            
            if( $output_set === false ) {
                // Validate against width if it is present
                if( isset( $image['width'] ) && !empty( $image['width'] ) ) {
                    if( intval( $image['width'] ) < $threshold ) {
                        $valid = false;
                    }
                }
                if( (boolean) $validate === true ) {
                    // Look for common ad network keywords
                    if( preg_match( '/(tweetmeme|stats|advertisement|commindo|valueclickmedia|imediaconnection|adify|traffiq|premiumnetwork|advertisingz|gayadnetwork|vantageous|networkadvertising|advertising|digitalpoint|viraladnetwork|decknetwork|burstmedia|doubleclick).|feeds\.[a-zA-Z0-9\-_]+\.com\/~ff|wp\-digg\-this|feeds\.wordpress\.com|\/media\/post_label_source/i', $image['src'] ) ) {
                        $valid = false;
                    }
                }
                
                if( $valid === true ) {
                    $output = $image;
                    $output_set = true;
                }
            }
        }
    }
    
    return $output;
}

/**
 * Process extra code needed for dynamic SlideDecks
 * 
 * @param object $slidedeck SlidDeck Object with meta information as returned by slidedeck_load()
 * @param object $slides Slides Object with meta information describing each slide
 * 
 * @return str HTML markup for extra navigational elements for dynamic SlideDecks
 */
function slidedeck_dynamic_template_content( $slidedeck, $slides ) {
    $template_str = '<a href="#previous" class="sd-node sd-node-nav-link sd-node-previous">Previous</a><a href="#next" class="sd-node sd-node-nav-link sd-node-next">Next</a>';
    $template_str.= '<ul class="sd-node sd-node-nav sd-node-nav-primary sd-node-navigation-type-' . $slidedeck['dynamic_options']['navigation_type'] . ' sd-node-total-' . count( $slides ) . '">';
    
    $i = 1;
    foreach ( (array) $slides as $slide ) {
        $buffer = 0;
        $title_cap = ( 37 + ( ( 5 - $slidedeck['dynamic_options']['total'] ) * 10 ) );
        $title_substr = mb_substr( $slide['title'], 0, $title_cap ) . ( mb_strlen( $slide['title'] ) < $title_cap ? '' : '&hellip;' );
        
        $template_str.= '<li><a href="#' . $i . '" class="sd-node-nav-link"><span class="sd-node-nav-link-label-date">' . date_i18n( 'M j', $slide['timestamp'] ) . '</span><span class="sd-node-nav-link-label-title">' . $title_substr . '</span></a></li>';
        $i++;
    }
    
    $template_str.= '</ul>';
    
    return $template_str;
}

/**
 * SlideDeck markup creator public function. This function can be called from a template or theme
 * to embed a SlideDeck in your layout.
 * 
 * @param object $slidedeck_id The ID of the SlideDeck to render
 * @param array $styles [optional] The styles to apply to the main SlideDeck tag ( usually just width and height )
 * @param boolean $include_skin_files Include the skin files used by this SlideDeck
 * 
 * @uses slidedeck_process_template()
 * 
 * @return Rendered SlideDeck markup and JavaScript tag to initialize SlideDeck render
 */
function slidedeck( $slidedeck_id, $styles=array( 'width' => '100%', 'height' => '370px' ), $include_skin_files = true ) {
    
    $slidedeck_id = slidedeck_legacy_id( $slidedeck_id );
    
    // Send the SlideDeck parameters through the output template processor
    $template_str = slidedeck_process_template( $slidedeck_id, $styles, $include_skin_files );

    echo $template_str;
}


/**
 * Tests whether or not the passed in SlideDeck has vertical slides.
 * 
 * @param array $slidedeck A SlideDeck retrieved from the database
 * 
 * @return SlideDeck HTML markup
 */
function slidedeck_has_vertical_slides ( $slidedeck ) {
    return (boolean) ( isset( $slidedeck['slidedeck_options']['vertical_slides'] ) && !empty( $slidedeck['slidedeck_options']['vertical_slides'] ) );
}


/**
 * Create the markup for a standard SlideDeck
 * 
 * @param array $slidedeck The SlideDeck
 * @param object $slidedeck_uid A Unique identifier for the outputted SlideDeck
 * @param array $slides The array of slides to be rendered
 * 
 * @uses do_shortcode()
 * 
 * @return SlideDeck HTML markup
 */
function slidedeck_output( $slidedeck, $slidedeck_uid, $slides, $styles_str ) {
    $inc = 1;
    $template_str = '<div class="slidedeck_frame skin-' . ( ( isset( $slidedeck['skin'] ) && !empty( $slidedeck['skin'] ) ) ? $slidedeck['skin'] : 'default' ) . '"><dl id="' . $slidedeck_uid . '" class="slidedeck slidedeck_' . $slidedeck['id'] . '" style="' . $styles_str . '">';
    foreach ( (array) $slides as $slide ) {
        $template_str.= '<dt>';
        
        $slide_content_decoded = html_entity_decode( stripslashes( $slide['title'] ), ENT_QUOTES, 'UTF-8' );
        if( function_exists( 'esc_html' ) ) {
            $template_str.= esc_html( $slide_content_decoded );
        } else {
            $template_str.= wp_specialchars( $slide_content_decoded );
        }
        
        $template_str.= '</dt>';            // Slide Title Bar
        
        $template_str.= '<dd>' . slidedeck_process_slide_content( $slide['content'] ) . '</dd>';        // Slide Content
        
        $inc++;
    }
    $template_str .= '</dl>';
    return $template_str;
}


/**
 * Add a SlideDeck sidebar block to the post and page views
 * 
 * @uses add_meta_box()
 * @uses slidedeck_custom_box()
 */
function slidedeck_add_custom_box() {
    if ( function_exists( 'add_meta_box' ) ) {
        add_meta_box( 'slidedeck-sidebar', 'SlideDeck', 'slidedeck_cusom_box', 'post', 'side', 'high' );
        add_meta_box( 'slidedeck-sidebar', 'SlideDeck', 'slidedeck_cusom_box', 'page', 'side', 'high' );
        wp_register_style( 'slidedeck-sidebar-css', slidedeck_url( '/slidedeck-sidebar.css' ), array(), SLIDEDECK_VERSION, "screen" );
        wp_enqueue_style( 'slidedeck-sidebar-css' );
    }
}


/**
 * Populate content for SlideDeck custom meta box on post and page views
 * 
 * @uses get_the_title()
 * @uses slidedeck_dir()
 */
function slidedeck_cusom_box() {
    global $post;
    
    $slidedeck_post_meta = array(
        '_slidedeck_slide_title' => get_the_title(),
        '_slidedeck_post_featured' => 0
    );
    
    foreach ( $slidedeck_post_meta as $meta_key => $meta_val ) {
        
        $post_meta_value = get_post_meta( $post->ID, $meta_key, true );
        if( !empty( $post_meta_value ) ) {
            $slidedeck_post_meta[$meta_key] = $post_meta_value;
        }
    }
    
    include( slidedeck_dir( '/views/sidebar.php' ) );
}


/**
 * Process saving of SlideDeck custom meta information for posts and pages
 * 
 * @uses wp_verify_nonce()
 * @uses update_post_meta()
 * @uses delete_post_meta()
 */
function slidedeck_save_dynamic_meta() {
    if( isset( $_POST['slidedeck-for-wordpress-dynamic-meta_wpnonce'] ) && !empty( $_POST['slidedeck-for-wordpress-dynamic-meta_wpnonce'] ) ) {
        if( !wp_verify_nonce( $_POST['slidedeck-for-wordpress-dynamic-meta_wpnonce'], 'slidedeck-for-wordpress' ) ) {
            return false;
        }
    
        $slidedeck_post_meta = array( '_slidedeck_slide_title', '_slidedeck_post_featured' );
        
        foreach( $slidedeck_post_meta as $meta_key ) {
            if( isset( $_POST[$meta_key] ) && !empty( $_POST[$meta_key] ) ) {
                update_post_meta( $_POST['ID'], $meta_key, $_POST[$meta_key] );
            } else {
                delete_post_meta( $_POST['ID'], $meta_key );
            }
        }
    }
}


/**
 * Adds additional ordering parameters when requesting posts in order
 * of popularity
 * 
 * @param string $arg The SQL arguments being run in a query object
 * 
 * @return string $arg The modified arguments
 */
function slidedeck_dynamic_orderby_popular( $arg ) {
    global $wpdb, $slidedeck_orderby_popular_flag;
    
    if( $slidedeck_orderby_popular_flag === true ) {
        $arg = "$wpdb->posts.comment_count DESC, $wpdb->posts.post_date DESC";
    }
    
    return $arg;
}


/**
 * Sets the flag to append additional query arguments when 
 * requesting posts in order of popularity
 * 
 * @param object $query The SQL query object being run
 */
function slidedeck_set_orderby_popular_flag( $query ) {
    global $slidedeck_orderby_popular_flag;
    
    $slidedeck_orderby_popular_flag = false;
    
    if( isset( $query->query_vars['orderby'] ) ) {
        if( $query->query_vars['orderby'] == '_slidedeck_popularity' ) {
            $slidedeck_orderby_popular_flag = true;
        }
    }
}


/**
 * AJAX function for previewing a SlideDeck
 * 
 * @param int $_GET['slidedeck_id'] The ID of the SlideDeck to load 
 * @param int $_GET['width'] The width of the preview window 
 * @param int $_GET['height'] The height of the preview window
 * @param int $_GET['preview_w'] The width of the SlideDeck in the preview window
 * @param int $_GET['preview_h'] The height of the SlideDeck in the preview window
 * 
 * @return the preview window as templated in views/preview.php
 */
function slidedeck_preview() {
    $slidedeck_id = $_GET['slidedeck_id'];
    $width = $_GET['width'];
    $height = $_GET['height'];
    $preview_w = $_GET['preview_w'];
    $preview_h = $_GET['preview_h'];

    $slidedeck = slidedeck_load( $slidedeck_id );
    
    $skin = slidedeck_get_skin( $slidedeck['skin'] );
    
    $dynamic = (boolean) $slidedeck['dynamic'];
    
    $first_preview = false;
    if( isset( $_GET['first_preview'] ) ) {
        $first_preview = true;
    }
    
    include( slidedeck_dir( '/views/preview.php' ) );
    
    slidedeck_print_footer_scripts();
    
    die();
}


/**
 * AJAX function for adding a new slide
 * 
 * @param int $_GET['count'] The count of the current displayed slides
 * @param int $_GET['gallery_id'] The ID of the SlideDeck
 * 
 * @return the formatted slide editing area as templated in views/_edit-slide.php 
 */
function slidedeck_add_slide() {
    global $post_ID;
    
    $count = $_GET['count'] + 1;
    $slide = array(
        'title' => "Slide " . $count,
        'slide_order' => $count,
        'content' => "",
        'gallery_id' => $_GET['gallery_id']
    );
    
    $post_ID = $slidedeck_id = $_GET['gallery_id'];
    
    include( slidedeck_dir( '/views/_edit-slide.php' ) );
    exit;
}


/**
 * Register post types used by SlideDeck 
 * 
 * @uses register_post_type
 */
function slidedeck_register_post_types() {
    if( function_exists( 'register_post_type' ) ) {
        register_post_type( 'slidedeck',
            array(
                'labels' => array(
                    'name' => 'slidedeck',
                    'singular_name' => __( 'SlideDeck' )
                ),
                'public' => false
            )
        );
        register_post_type( 'slidedeck_slide',
            array(
                'labels' => array(
                    'name' => 'slidedeck_slide',
                    'singular_name' => __( 'SlideDeck Slide' )
                ),
                'public' => false
            )
        );
    }
}


/**
 * SlideDeckWidget BETA
 * 
 * Creates a multi-instance widget for users of WordPress 2.8+ to deploy SlideDeck instances
 * in widget areas on a user's WordPress website.
 */
global $wp_version;
if( version_compare( $wp_version, '2.8', '>=' ) ) {
    class SlideDeckWidget extends WP_Widget {
        
        /**
         * Constructor function for Class
         * 
         * @uses WP_Widget()
         */
        function SlideDeckWidget() {
            $widget_options = array(
                'classname' => 'slidedeck_widget',
                'description' => 'Add SlideDecks to your widget areas'
            );
            $this->WP_Widget( 'slidedeck_widget', 'SlideDeck Widget BETA', $widget_options );
        }
        
        /**
         * Initialization function to register widget
         * 
         * @uses register_widget()
         */
        function init() {
            register_widget( "SlideDeckWidget" );
        }
        
        /**
         * Form function for the widget control panel
         * 
         * @param object $instance Option data for this widget instance
         * 
         * @uses slidedeck_load()
         * @uses slidedeck_dir()
         */
        function form( $instance ) {
            $instance = wp_parse_args( (array) $instance, array(
                'slidedeck_id' => "",
                'width' => "100%",
                'height' => "300px"
            ) );
            
            $width = strip_tags( $instance['width'] );
            $height = strip_tags( $instance['height'] );
            $slidedeck_id = strip_tags( $instance['slidedeck_id'] );
            
            $slidedecks = slidedeck_load();
            
            include( slidedeck_dir( '/views/_widget-form.php' ) );
        }
        
        /**
         * Update processing function for saving widget instance settings
         * 
         * @param object $new_instance Option data submitted for this widget instance
         * @param object $old_instance Existing option data for this widget instance
         * 
         * @return object $instance Updated option data
         */
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
    
            $instance['width'] = strip_tags( $new_instance['width'] );
            $instance['height'] = strip_tags( $new_instance['height'] );
            $instance['slidedeck_id'] = $new_instance['slidedeck_id'];
            
            return $instance;
        }
        
        /**
         * Widget output function
         * 
         * Loads a SlideDeck instance based off the widget settings specified by the user
         * 
         * @param object $args Extra arguments provided for this widget output see documentation at
         *                     http://codex.wordpress.org/Function_Reference/the_widget
         * @param object $instance Option data for this widget instance
         * 
         * @uses slidedeck()
         */
        function widget( $args, $instance ) {
            extract( $args, EXTR_SKIP );
            
            echo $before_widget;
            
            slidedeck( $instance['slidedeck_id'], array( 'width' => $instance['width'], 'height' => $instance['height'] ) );
            
            echo $after_widget;
        }
    }
    add_action( 'widgets_init', array( 'SlideDeckWidget', 'init' ) );
}


/**
 * Hook into admin_print_footer_scripts action
 * 
 * Run the wp_tiny_mce_preload_dialogs function for versions of WordPress
 * that have this function and need it to make the TinyMCE editor dialogs
 * in the SlideDeck editing interface work.
 * 
 * Note that the wp_tiny_mce_preload_dialogs() function was eliminated in
 * WordPress 3.2 as it was no longer necessary to make the TinyMCE dialog
 * work properly.
 */
function slidedeck_wp_tiny_mce_preload_dialogs() {
    if( function_exists( 'wp_tiny_mce_preload_dialogs' ) && SLIDEDECK_USE_OLD_TINYMCE_EDITOR === true ) {
        wp_tiny_mce_preload_dialogs();
    }
}


if ( function_exists( 'add_action' ) ) {
    // Add custom post type
    add_action( 'init', 'slidedeck_register_post_types' );
    
    // Add navigation elements to add SlideDecks to Post/Page editing views
    add_action( 'admin_menu', 'slidedeck_add_custom_box' );
    add_action( 'admin_menu', 'slidedeck_menu' );
    add_action( 'save_post', 'slidedeck_save_dynamic_meta' );
    
    // Add SlideDeck button to TinyMCE navigation
    add_action( 'admin_init', 'slidedeck_addbuttons' );
    add_action( 'admin_footer', 'slidedeck_tinymce_plugin_dialog' );
    
    // Add admin menu items to main control panel navigation
    add_action( 'admin_init', 'slidedeck_admin_init' );

    // Add JavaScript and Stylesheets for admin interface on appropriate pages
    add_action( 'admin_print_scripts-slidedeck_page_slidedeck/slidedeck_add_new', 'slidedeck_admin_head' );
    add_action( 'admin_print_scripts-slidedeck_page_slidedeck/slidedeck_dynamic', 'slidedeck_admin_head' );
    add_action( 'admin_print_scripts-toplevel_page_slidedeck', 'slidedeck_admin_head' );

    // Add required JavaScript and Stylesheets for displaying SlideDecks in public view    
    add_action( 'wp_print_scripts', 'slidedeck_print_scripts' );
    if( !is_admin() ) {
        add_action( 'wp_print_styles', 'slidedeck_print_styles' );
    }

    // Add shortcode to replace SlideDeck shortcodes in content with SlideDeck contents
    add_shortcode( 'SlideDeck', 'slidedeck_shortcode' );
    
    // Add AJAX actions
    add_action( 'wp_ajax_slidedeck_preview', 'slidedeck_preview' );
    add_action( 'wp_ajax_slidedeck_add_slide', 'slidedeck_add_slide' );
    add_action( 'wp_ajax_slidedeck_blog_feed', 'slidedeck_blog_feeds' );
    
    // Add actions to sort by popularity (WordPress 2.9+)
    add_action( 'posts_orderby', 'slidedeck_dynamic_orderby_popular' );
    add_action( 'parse_query', 'slidedeck_set_orderby_popular_flag' );

    // Append necessary skin and initialization script commands to the bottom of the DOM for proper loading
    if( function_exists( 'wp_print_footer_scripts' ) ) {
        add_action( 'wp_print_footer_scripts', 'slidedeck_print_footer_scripts' );
    } else {
        add_action( 'wp_footer', 'slidedeck_print_footer_scripts' );
    }
    
    // Remove SlideDeck from its own WYSIWYG editors
    add_filter( 'tiny_mce_before_init', 'slidedeck_change_mce_options' );
    
    // Pre-loading for skins used by SlideDeck(s) in post(s) on a page
    if( !is_admin() ) {
        add_action( 'wp', 'slidedeck_wp_hook' );
    }
    
    // Run tinyMCE Preload Dialogs
    add_action( 'admin_print_footer_scripts', 'slidedeck_wp_tiny_mce_preload_dialogs', 30 );
}

function slidedeck_debug( $var, $verbose = false ) {
    echo "<pre>";

    if( $verbose == true ) {
        var_dump( $var );
    } else {
        if( is_array( $var ) || is_object( $var ) ) {
            print_r( $var );
        } elseif( is_bool( $var ) ) {
            echo var_export( $var, true );
        } else {
            echo $var;
        }
    }
    echo "</pre>";
}