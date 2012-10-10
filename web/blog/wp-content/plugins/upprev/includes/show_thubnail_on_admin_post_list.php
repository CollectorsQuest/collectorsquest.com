<?php

function iworks_add_thumbnail( $cols )
{
    $cols['thumbnail'] = __( 'Thumbnail', 'upprev' );
    return $cols;
}

function iworks_admin_list_thumbnail( $column_name, $post_id )
{
    if ( 'thumbnail' == $column_name ) {
        $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
        if ( $thumbnail_id ) {
            $thumb = wp_get_attachment_image( $thumbnail_id, array( 32, 32 ), true );
        }
        if( isset( $thumb ) && $thumb ) {
            echo $thumb;
        } else {
            echo __( 'None', 'upprev' );
        }
    }
}

// for posts
add_filter( 'manage_posts_columns', 'iworks_add_thumbnail' );
add_action( 'manage_posts_custom_column', 'iworks_admin_list_thumbnail', 10, 2 );

// for pages
add_filter( 'manage_pages_columns', 'iworks_add_thumbnail' );
add_action( 'manage_pages_custom_column', 'iworks_admin_list_thumbnail', 10, 2 );
