<?php
/**
 * Multimedia Upload/Insert buttons for slide editors
 * 
 * SlideDeck for WordPress 1.4.8 - 2011-12-14
 * Copyright 2011 digital-telepathy  (email : support@digital-telepathy.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * @package SlideDeck
 * @subpackage SlideDeck for WordPress
 * 
 * @author digital-telepathy
 * @version 1.4.8
 * 
 * @uses apply_filters()
 * @uses get_bloginfo()
 */
?>
<div class="editor-nav">
    <?php if( 'true' == get_user_option( 'rich_editing' ) ){ ?>
	<a href="#html" class="editor-html mode">HTML</a>
	<a href="#visual" class="editor-visual mode active">Visual</a>
    <?php } ?>

	<div class="media-buttons hide-if-no-js">
		<?php
			$uploading_iframe_ID = $slide['gallery_id'];
			$context = apply_filters( 'media_buttons_context', __( 'Upload/Insert  %s' ) );
			$media_upload_iframe_src = get_bloginfo( 'wpurl' ) . '/wp-admin/media-upload.php?post_id=' . $uploading_iframe_ID . '&amp;editor=' . $editor_id;
			$media_title = __( 'Add Media' );
			$image_upload_iframe_src = apply_filters( 'image_upload_iframe_src', $media_upload_iframe_src . "&amp;type=image" );
			$image_title = __( 'Add an Image' );
			$video_upload_iframe_src = apply_filters( 'video_upload_iframe_src', $media_upload_iframe_src . "&amp;type=video" );
			$video_title = __( 'Add Video' );
			$audio_upload_iframe_src = apply_filters( 'audio_upload_iframe_src', $media_upload_iframe_src . "&amp;type=audio" );
			$audio_title = __( 'Add Audio' );
			
			$out = '<a href="' . $image_upload_iframe_src . '&amp;TB_iframe=true" class="thickbox" title="' . $image_title . '" onclick="return false;"><img src="' . get_bloginfo('wpurl') . '/wp-admin/images/media-button-image.gif" alt="' . $image_title . '" /></a>';
			$out.= '<a href="' . $video_upload_iframe_src . '&amp;TB_iframe=true" class="thickbox" title="' . $video_title . '" onclick="return false;"><img src="' . get_bloginfo('wpurl') . '/wp-admin/images/media-button-video.gif" alt="' . $video_title . '" /></a>';
			$out.= '<a href="' . $audio_upload_iframe_src . '&amp;TB_iframe=true" class="thickbox" title="' . $audio_title . '" onclick="return false;"><img src="' . get_bloginfo('wpurl') . '/wp-admin/images/media-button-music.gif" alt="' . $audio_title . '" /></a>';
			$out.= '<a href="' . $media_upload_iframe_src . '&amp;TB_iframe=true" class="thickbox" title="' . $media_title . '" onclick="return false;"><img src="' . get_bloginfo('wpurl') . '/wp-admin/images/media-button-other.gif" alt="' . $media_title . '" /></a>';
			
			printf($context, $out);
		?>
	</div>

</div>