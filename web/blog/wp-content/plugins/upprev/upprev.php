<?php
/*
Plugin Name: upPrev
Plugin URI: http://iworks.pl/upprev/
Description: When scrolling post down upPrev will display a flyout box with a link to the previous post from the same category. Based on upPrev Previous Post Animated Notification by Jason Pelker, Grzegorz Krzyminski
Version: 3.3.12
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*

Copyright 2011-2012 Marcin Pietrzak (marcin@iworks.pl)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

/**
 * static options
 */
define( 'IWORKS_UPPREV_VERSION', '3.3.12' );
define( 'IWORKS_UPPREV_PREFIX',  'iworks_upprev_' );

/**
 * i18n
 */
load_plugin_textdomain( 'upprev', false, dirname( plugin_basename( __FILE__) ).'/languages' );

require_once dirname(__FILE__).'/includes/common.php';

/**
 * install & uninstall
 */
register_activation_hook( __FILE__, 'iworks_upprev_activate' );
register_deactivation_hook( __FILE__, 'iworks_upprev_deactivate' );

/**
 * init
 */
add_action( 'init', 'iworks_upprev_init' );

function iworks_upprev_init()
{
    add_action( 'admin_enqueue_scripts',      'iworks_upprev_admin_enqueue_scripts' );
    add_action( 'admin_init',                 'iworks_upprev_options_init' );
    add_action( 'admin_menu',                 'iworks_upprev_add_pages' );
    add_action( 'wp_before_admin_bar_render', 'iworks_upprev_add_to_admin_bar' );
    add_action( 'wp_enqueue_scripts',         'iworks_upprev_enqueue_scripts' );
    add_action( 'wp_footer',                  'iworks_upprev_box', PHP_INT_MAX, 0 );
    add_action( 'wp_print_scripts',           'iworks_upprev_print_scripts' );
    add_action( 'wp_print_styles',            'iworks_upprev_print_styles' );
}

function iworks_upprev_check()
{
    if ( !is_single() && !is_page() ) {
        return true;
    }
    /**
     * check mobile devices
     */
    if ( iworks_upprev_check_mobile_device() ) {
        return true;
    }
    /**
     * global option object
     */
    global $iworks_upprev_options;
    /**
     * check post types
     */
    $post_types = $iworks_upprev_options->get_option( 'post_type' );
    if ( is_page() && $iworks_upprev_options->get_option( 'match_post_type' ) ) {
        return !array_key_exists( 'page', $post_types );
    } else if ( $iworks_upprev_options->get_option( 'match_post_type' ) && is_array( $post_types ) ) {
        global $post;
        return !array_key_exists( get_post_type( $post ), $post_types );
    }
    return false;
}

function iworks_upprev_enqueue_scripts()
{
    if ( iworks_upprev_check() ) {
        return;
    }
    wp_enqueue_script( 'iworks_upprev-js', plugins_url('/scripts/upprev.js', __FILE__), array('jquery'), IWORKS_UPPREV_VERSION );
    $plugin_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
    wp_enqueue_style("upprev-css",$plugin_path.'styles/upprev.css');
}

function iworks_upprev_print_scripts()
{
    if ( iworks_upprev_check() ) {
        return;
    }
    global $iworks_upprev_options;
    $use_cache = $iworks_upprev_options->get_option( 'use_cache' );
    if ( $use_cache ) {
        $cache_key = IWORKS_UPPREV_PREFIX.'scripts_'.get_the_ID().'_'.$iworks_upprev_options->get_option('cache_stamp');
        if ( true === ( $content = get_site_transient( $cache_key ) ) ) {
            print $content;
            return;
        }
    }
    $data = '';
    foreach ( array( 'animation', 'position', 'offset_percent', 'offset_element', 'css_width', 'css_side', 'compare', 'url_new_window', 'ga_track_views', 'ga_track_clicks', 'ga_opt_noninteraction' ) as $key ) {
        $value   = $iworks_upprev_options->get_option( $key );
        $data .= sprintf(
            '%s: %s, ',
            $key,
            is_numeric($value)? $value:(sprintf("'%s'", $value))
        );
    }
    if ( empty($data) ) {
        return;
    }
    $content  = '<script type="text/javascript">'."\n";
    $content .= 'var iworks_upprev = { ';
    $content .= $data;
    $content .= 'title: \''.esc_attr( get_the_title() ).'\'';
    $content .= ' };'."\n";
    /**
     * Google Analitics tracking code
     */
    $ga_account = $iworks_upprev_options->get_option( 'ga_account' );
    if ( $ga_account && $iworks_upprev_options->get_option( 'ga_status' )) {
        $content.= 'var _gaq = _gaq || [];'."\n";
        $content.= '_gaq.push([\'_setAccount\', \''.$ga_account.'\']);'."\n";
        $content.= '(function() {'."\n";
        $content.= '    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;'."\n";
        $content.= '    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';'."\n";
        $content.= '    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);'."\n";
        $content.= '})();'."\n";
    }
    $content .= '</script>'."\n";
    if ( $use_cache ) {
        set_site_transient( $cache_key, $content, $iworks_upprev_options->get_option( 'cache_lifetime' ) );
    }
    echo $content;
}

function iworks_upprev_print_styles()
{
    if ( iworks_upprev_check() ) {
        return;
    }
    global $iworks_upprev_options;
    $use_cache = $iworks_upprev_options->get_option( 'use_cache' );
    if ( $use_cache ) {
        $cache_key = IWORKS_UPPREV_PREFIX.'style_'.get_the_ID().'_'.$iworks_upprev_options->get_option( 'cache_stamp' );
        if ( true === ( $content = get_site_transient( $cache_key ) ) ) {
            print $content;
            return;
        }
    }
    $content = '<style type="text/css">'."\n";
    $content .= '#upprev_box{';
    $values = array();
    foreach ( array( 'position', 'animation' ) as $key ) {
        $values[$key] = $iworks_upprev_options->get_option( $key );
    }
    foreach ( array( 'bottom', 'width', 'side' ) as $key ) {
        $values[$key] = $iworks_upprev_options->get_option( 'css_'.$key );
        switch ( $key ) {
        case 'position':
            break;
        case 'side':
            $content .= sprintf( '%s:%dpx;', $values['position'], $values[ $key ] ) ;
            break;
        default:
            $content .= sprintf( '%s:%dpx;', $key, $values[ $key ] ) ;
        }
    }
    $content .= sprintf ( 'display:%s;', $values['animation'] == 'flyout'? 'block':'none' );
    if ( $values['animation'] == 'flyout' ) {
        $content .= sprintf( '%s:-%dpx;', $values['position'], $values['width'] + $values['side'] + 50 );
    }
    $content .= sprintf ( 'display:%s;', $values['animation'] == 'flyout'? 'block':'none' );
    $content .= '}'."\n";
    $content .= preg_replace( '/\s\s+/s', ' ', preg_replace( '/#[^\{]+ \{ \}/', '', preg_replace( '@/\*[^\*]+\*/@', '', $iworks_upprev_options->get_option( 'css' ) ) ) );
    $content .= '</style>'."\n";
    if ( $use_cache ) {
        set_site_transient( $cache_key, $content, $iworks_upprev_options->get_option( 'cache_lifetime' ) );
    }
    echo $content;
}

/**
 * Add page to theme menu
 */
function iworks_upprev_add_pages()
{
    $dir = explode('/', dirname(__FILE__));
    $dir = $dir[ count( $dir ) - 1 ];
    if (current_user_can( 'manage_options' ) && function_exists('add_theme_page') ) {
        add_theme_page(
            __('upPrev', 'upprev'),
            __('upPrev', 'upprev'),
            'manage_options',
            $dir.'/admin/index.php'
        );
    }
}

function iworks_upprev_admin_enqueue_scripts()
{
    $dir = explode('/', dirname(__FILE__));
    $dir = $dir[ count( $dir ) - 1 ];
    global $current_screen;
    if ( isset( $current_screen->id ) && $current_screen->id == $dir.'/admin/index' ) {
        wp_enqueue_script( 'upprev', plugins_url('/scripts/upprev-admin.js', __FILE__), array('jquery-ui-tabs'), IWORKS_UPPREV_VERSION );
        wp_enqueue_style( 'upprev', plugins_url('/styles/upprev-admin.css', __FILE__), null, IWORKS_UPPREV_VERSION );
    }
}

function iworks_upprev_box()
{
    if ( iworks_upprev_check() ) {
        return;
    }
    global $post, $iworks_upprev_options;
    $use_cache = $iworks_upprev_options->get_option( 'use_cache' );
    if ( $use_cache ) {
        $cache_key = IWORKS_UPPREV_PREFIX.'box_'.get_the_ID().'_'.$iworks_upprev_options->get_option( 'cache_stamp' );
        if ( true === ( $value = get_site_transient( $cache_key ) ) ) {
            print $value;
            return;
        }
    }

    /**
     * get current post title and convert special characters to HTML entities
     */
    $current_post_title = esc_attr( get_the_title() );

    /**
     * get used params
     */
    foreach( array(
        'compare',
        'excerpt_length',
        'excerpt_show',
        'ignore_sticky_posts',
        'number_of_posts',
        'show_thumb',
        'taxonomy_limit',
        'url_prefix',
        'url_sufix'
    ) as $key ) {
        $$key = $iworks_upprev_options->get_option( $key );
    }

    $show_taxonomy   = true;
    $siblings        = array();

    $args = array(
        'ignore_sticky_posts' => $ignore_sticky_posts,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'post__not_in'        => array( $post->ID ),
        'posts_per_page'      => $number_of_posts,
        'post_type'           => array()
    );
    $post_type = $iworks_upprev_options->get_option( 'post_type' );
    if ( !empty( $post_type ) ) {
        if ( array_key_exists( 'any', $post_type ) ) {
            $args['post_type'] = 'any';
        } else {
            foreach( $post_type as $type ) {
                $args['post_type'][] = $type;
            }
        }
    }
    if ( $compare == 'yarpp' ) {
        if ( defined( 'YARPP_VERSION' ) && version_compare( YARPP_VERSION, '3.3' ) > -1 ) {
            if ( version_compare( YARPP_VERSION, '4.0' ) > -1 ) {
                $yarpp_posts = yarpp_related( array( 'limit' => $number_of_posts ), $post->ID, false );
            } else {
                $a = array();
                if ( array_key_exists( 'post', $post_type ) && array_key_exists( 'page', $post_type ) ) {
                    $yarpp_posts = related_entries( $a, $post->ID, false );
                } else if ( array_key_exists( 'post', $post_type ) ) {
                    $yarpp_posts = related_posts( $a, $post->ID, false );
                } else if ( array_key_exists( 'page', $post_type ) ) {
                    $yarpp_posts = related_pages( $a, $post->ID, false );
                }
            }
            if ( !$yarpp_posts ) {
                return;
            }
        } else {
            $compare = 'simple';
        }
    }
    switch ( $compare ) {
        case 'category':
            $categories = get_the_category();
            if ( count( $categories ) < 1 ) {
                break;
            }
            $max = count( $categories );
            if ( $taxonomy_limit > 0 && $taxonomy_limit > $max ) {
                $max = $taxonomy_limit;
            }
            $ids = array();
            for ( $i = 0; $i < $max; $i++ ) {
                $siblings[ get_category_link( $categories[$i]->term_id ) ] = $categories[$i]->name;
                $ids[] = $categories[$i]->cat_ID;
            }
            $args['cat'] = implode(',',$ids);
            break;
        case 'tag':
            $count_args = array ( 'taxonomy' => 'post_tag' );
            $tags = get_the_tags();
            if ( is_array( $tags ) && count( $tags ) ) {
                $max = count( $tags );
                if ( $max < 1 ) {
                    break;
                }
                if ( $taxonomy_limit > 0 && $taxonomy_limit > $max ) {
                    $max = $taxonomy_limit;
                }
                $ids = array();
                $i = 1;
                foreach( $tags as $tag ) {
                    if ( ++$i > $max ) {
                        continue;
                    }
                    $siblings[ get_tag_link( $tag->term_id ) ] = $tag->name;
                    $ids[] = $tag->term_id;
                }
                $args['tag__in'] = $ids;
            }
            break;
        case 'random':
            $args['orderby'] = 'rand';
            unset($args['order']);
        default:
            $show_taxonomy   = false;
    }
    $value = '<div id="upprev_box">';
    if ( $compare != 'yarpp' ) {
        if ( $compare != 'random' ) {
            add_filter( 'posts_where',  'iworks_upprev_filter_where',   72, 1 );
        }
        add_filter( 'excerpt_more', 'iworks_upprev_excerpt_more',   72, 1 );

        if ( $excerpt_length > 0 ) {
            add_filter( 'excerpt_length', 'iworks_upprev_excerpt_length', 72, 1 );
        }
        $upprev_query = new WP_Query( $args );
        if (!$upprev_query->have_posts()) {
            return;
        }
        /**
         * remove any filter if needed
         */
        if ( $iworks_upprev_options->get_option( 'remove_all_filters' ) ) {
            remove_all_filters( 'the_content' );
        }
        /**
         * catch elements
         */
        ob_start();
        do_action( 'iworks_upprev_box_before' );
        $value .= ob_get_flush();
        if ( $iworks_upprev_options->get_option( 'header_show' ) ) {
            $value .= '<h6>';
            if ( count( $siblings ) ) {
                $value .= sprintf ( '%s ', __('More in', 'upprev' ) );
                $a = array();
                foreach ( $siblings as $url => $name ) {
                    $a[] = sprintf( '<a href="%s" rel="%s">%s</a>', $url, $current_post_title, $name );
                }
                $value .= implode( ', ', $a);
            } else if ( $compare == 'random' ) {
                $value .= __('Read more:', 'upprev' );
            } else {
                $value .= __('Read previous post:', 'upprev' );
            }
            $value .= '</h6>';
        }
        $i = 1;
        $ga_click_track = '';
        while ( $upprev_query->have_posts() ) {
            $item = '';
            $upprev_query->the_post();
            $item_class = 'upprev_excerpt';
            if ( $i > $number_of_posts ) {
                break;
            }
            if ( $i++ < $number_of_posts ) {
                $item_class .= ' upprev_space';
            }
            $image = '';
            $permalink = sprintf(
                '%s%s%s',
                $url_prefix,
                get_permalink(),
                $url_sufix
            );
            if ( current_theme_supports('post-thumbnails') && $show_thumb && has_post_thumbnail( get_the_ID() ) ) {
                $item_class .= ' upprev_thumbnail';
                $image = sprintf(
                    '<a href="%s" title="%s" class="upprev_thumbnail"%s rel="%s">%s</a>',
                    $permalink,
                    wptexturize(get_the_title()),
                    $ga_click_track,
                    $current_post_title,
                    apply_filters(
                        'iworks_upprev_get_the_post_thumbnail', get_the_post_thumbnail(
                            get_the_ID(),
                            array(
                                $iworks_upprev_options->get_option( 'thumb_width' ),
                                9999
                            ),
                            array(
                                'title'=>get_the_title(),
                                'class'=>'iworks_upprev_thumb'
                            )
                        )
                    )
                );
            } else {
                ob_start();
                do_action( 'iworks_upprev_image' );
                $image = ob_get_flush();
            }
            $item .= sprintf( '<div class="%s">%s', $item_class, $image );
            $item .= sprintf(
                '<h5><a href="%s"%s rel="%s">%s</a></h5>',
                $permalink,
                $ga_click_track,
                $current_post_title,
                get_the_title()
            );
            if ( $excerpt_show != 0 && $excerpt_length > 0 ) {
                $item .= sprintf( '<p>%s</p>', get_the_excerpt() );
            } else if ( $image ) {
                $item .= '<br />';
            }
            $item .= '</div>';
            $value .= apply_filters( 'iworks_upprev_box_item', $item );
        }
    } else {
        $value .= $yarpp_posts;
    }
    if ( $iworks_upprev_options->get_option( 'close_button_show' ) ) {
        $value .= sprintf( '<a id="upprev_close" href="#" rel="close">%s</a>', __('Close', 'upprev') );
    }
    if ( $iworks_upprev_options->get_option( 'promote' ) ) {
        $value .= '<p class="promote"><small>'.__('Previous posts box brought to you by <a href="http://iworks.pl/produkty/wordpress/wtyczki/upprev/en/">upPrev plugin</a>.', 'upprev').'</small></p>';
    }
    ob_start();
    do_action( 'iworks_upprev_box_after' );
    $value .= ob_get_flush();
    $value .= '</div>';
    if ( !$compare != 'yarpp' ) {
        wp_reset_postdata();
        remove_filter( 'excerpt_more',   'iworks_upprev_filter_where', 72, 1 );
        remove_filter( 'excerpt_more',   'iworks_upprev_excerpt_more', 72, 1 );
        if ( $excerpt_length > 0 ) {
            remove_filter( 'excerpt_length', 'iworks_upprev_excerpt_length', 72, 1 );
        }
    }
    if ( $use_cache && $compare != 'random' ) {
        set_site_transient( $cache_key, $value, $iworks_upprev_options->get_option( 'cache_lifetime' ) );
    }
    echo apply_filters( 'iworks_upprev_box', $value );
}

function iworks_upprev_excerpt_more($more)
{
    return '...';
}

function iworks_upprev_excerpt_length($length)
{
    global $iworks_upprev_options;
    return $iworks_upprev_options->get_option( 'excerpt_length' );
}

function iworks_upprev_filter_where( $where = '' )
{
    global $post;
    if ($post->post_date) {
        $where .= " AND post_date < '" . $post->post_date . "'";
    }
    return $where;
}

function iworks_upprev_plugin_links ( $links, $file )
{
    if ( $file == plugin_basename(__FILE__) ) {
        if ( !is_multisite() ) {
            $dir = explode('/', dirname(__FILE__));
            $dir = $dir[ count( $dir ) - 1 ];
            $links[] = '<a href="themes.php?page='.$dir.'/admin/index.php">' . __('Settings') . '</a>';
        }
        $links[] = '<a href="http://iworks.pl/donate/upprev.php">' . __('Donate') . '</a>';
    }
    return $links;
}

function iworks_upprev_add_to_admin_bar()
{
    if ( !current_user_can( 'manage_options' ) ) {
        return;
    }
    global $wp_admin_bar;
    $dir = explode('/', dirname(__FILE__));
    $dir = $dir[ count( $dir ) - 1 ];
    $wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'id' => 'upprev', 'title' => __('upPrev', 'upprev'), 'href' => admin_url('themes.php?page='.$dir.'/admin/index.php') ) );
}

