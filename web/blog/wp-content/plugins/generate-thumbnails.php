<?php /*

**************************************************************************

Plugin Name:  Generate Thumbnails On The Fly
Description:  When a thumbnail image is requested of a specific width/height (rather than by name), generate it if it doesn't exist.
Version:      1.0.0
Author:       Alex Mills (Viper007Bond)
Author URI:   http://www.viper007bond.com/

**************************************************************************/

add_filter( 'image_downsize', 'viper007bond_generate_thumbnails_on_the_fly', 10, 3 );

function viper007bond_generate_thumbnails_on_the_fly( $existing_data, $attachment_id, $size ) {

  // Let WordPress handle named thumbnail sizes
  if ( ! is_array( $size ) )
    return $existing_data;

  // Safety
  $size = array_map( 'absint', $size );

  // Get the path to the fullsize image
  $fullsize_path = get_attached_file( $attachment_id );

  // Split it into parts
  $fullsize_info = pathinfo( $fullsize_path );

  // Create the thumbnail filename
  // Is there a helper function for all of this? I couldn't find one.
  $thumbnail_filename = str_replace( ".{$fullsize_info['extension']}", "-{$size[0]}x{$size[1]}.{$fullsize_info['extension']}", $fullsize_info['basename'] );

  $thumbnail_path = $fullsize_info['dirname'] . '/' . $thumbnail_filename;

  // If the thumbnail already exists
  if ( file_exists( $thumbnail_path ) ) {

    // Create the URL to the thumbnail by taking the fullsize image
    // and replacing it's filename with the thumbnail filename
    $thumbnail_url = str_replace( $fullsize_info['basename'], $thumbnail_filename, wp_get_attachment_url( $attachment_id ) );
  }

  // Okay, thumbnail doesn't exist. Make it!
  else {

    // Have to crop so that width/height is exact and findable in the future.
    $new_thumbnail_path = image_resize( $fullsize_path, $size[0], $size[1], true );

    if ( is_wp_error( $new_thumbnail_path ) )
      return $existing_data;

    // Get the thumbnail path parts, specifically the filename
    // Yeah, we created it above but let's be absolutely sure it's correct
    $new_thumbnail_info = pathinfo( $new_thumbnail_path );

    $thumbnail_url = str_replace( $fullsize_info['basename'], $new_thumbnail_info['basename'], wp_get_attachment_url( $attachment_id ) );
  }

  return array(
    $thumbnail_url, // URL
    $size[0],       // Width
    $size[1],       // Height
    true,           // is_intermediate, i.e. exact size or will it be resized via HTML?
  );
}
