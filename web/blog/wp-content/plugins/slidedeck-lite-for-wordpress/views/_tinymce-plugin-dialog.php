<?php
/**
 * TinyMCE plugin dialog markup
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
 * @uses slidedeck_action()
 * @uses slidedeck_url()
 */
?>
<div id="slidedeck_tinymce_dialog">
	<p>Choose a SlideDeck from the list below to embed in your post:</p>
	<?php if( isset( $slidedecks ) && !empty( $slidedecks ) ): ?>
		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th class="manage-column column-title" scope="col">Title</th>
					<th width="90" class="manage-column column-date" scope="col">Date</th>
				</tr>
			</thead>
			<tbody>
				<?php $alternate = 0; ?>
				<?php foreach( (array) $slidedecks as $slidedeck ): ?>
					<tr id="slidedeck_id_<?php echo $slidedeck['id']; ?>" class="author-self status-publish iedit<?php echo ( $alternate & 1 ) ? ' alternate' : ''; ?><?php echo ( $slidedeck['dynamic'] == '1' ) ? ' dynamic' : ''; ?>" valign="top">
						<td class="post-title column-title">
							<?php if( $slidedeck['dynamic'] == '1' ): ?>
								<img src="<?php echo slidedeck_url( '/images/icon_dynamic.png' ); ?>" alt="Dynamic SlideDeck" />
							<?php endif; ?>
							<?php echo $slidedeck['title']; ?>
						</td>
						<td clsss="date column-date"><?php echo date( "Y/m/d", strtotime( $slidedeck['updated_at'] ) ); ?></td>
					</tr>
					<?php $alternate++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="dialog-other-options">
			Dimensions: <input type="text" size="5" value="100%" id="slidedeck_tinymce_dimension_w" /> x <input type="text" size="5" value="300px" id="slidedeck_tinymce_dimension_h" />
		</div>
	<?php else: ?>
	<div class="message">
		<p>No SlideDecks found! <a href="<?php echo slidedeck_action( '/slidedeck_add_new' ); ?>">Create a New SlideDeck</a></p>
	</div>
	<?php endif; ?>
</div>
