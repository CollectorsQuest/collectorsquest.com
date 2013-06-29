<?php
/**
 * SlideDeck Widget control form
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
 */
?>
<p>Display a SlideDeck in a widget area. <em><strong>NOTE:</strong> since most widget areas are narrow sidebars, your SlideDeck may not appear correctly. We only recommend placing SlideDecks in wider widget areas like headers and footers.</em></p>
<p><label><strong>Choose a SlideDeck:</strong><br />
<select name="<?php echo $this->get_field_name( 'slidedeck_id' ); ?>" id="<?php echo $this->get_field_id( 'slidedeck_id' ); ?>" class="widefat">
    <?php foreach( (array) $slidedecks as $slidedeck ): ?>
    <option value="<?php echo $slidedeck['id']; ?>"<?php echo $slidedeck_id == $slidedeck['id'] ? ' selected="selected"' : ''; ?>><?php echo $slidedeck['title']; ?></option>
    <?php endforeach; ?>
</select>
</label></p>
<p>Dimensions: <input type="text" name="<?php echo $this->get_field_name( 'width' ); ?>" size="4" value="<?php echo $width; ?>" /> x <input type="text" name="<?php echo $this->get_field_name( 'height' ); ?>" size="4" value="<?php echo $height; ?>" /></p>