<?php
/**
 * Overview list of SlideDecks
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
 * @uses slidedeck_show_message()
 * @uses wp_nonce_url()
 */
?>
<div class="slidedeck-wrapper">
    
    <?php include('_notification_bar.php'); ?>
    
    <div id="callout-sidebar">
        <div class="editPageUpgradeCallout overview">
            <h4>SlideDeck 2</h4>
            <div class="inner">
                <h5>New Everything!</h5>
                <p>We've taken the content slider to the next level with SlideDeck&nbsp;2. <strong><em>It's all new</em></strong> from the ground up, a WordPress slider like nothing else before it.</p>
                <a href="<?php echo slidedeck_action( '/upgrade' ); ?>&variation=SlideDeck+Overview+CTA">Upgrade to Pro</a>
            </div>
        </div>
        <div class="follow-twitter callout-button"><p>For tips, tricks &amp; discounts</p><a href="http://twitter.com/slidedeck" target="_blank" class="button"><span class="inner"><img src="<?php echo slidedeck_url( '/images/twitter.png' ); ?>" /> Follow Us on Twitter</span></a></div>
        <div class="view-screencasts callout-button"><p>For how-to's and troubleshooting</p><a href="http://www.slidedeck.com/screencasts/" target="_blank" class="button"><span class="inner"><img src="<?php echo slidedeck_url( '/images/youtube.png' ); ?>" /> View Our Screencasts</span></a></div>
        <div class="bug-report callout-button"><p>Help us squash the bugs</p><a href="http://www.getsatisfaction.com/slidedeck/topics" target="_blank" class="button"><span class="inner"><img src="<?php echo slidedeck_url( '/images/bug.png' ); ?>" /> Report a bug for SlideDeck</span></a></div>
    </div>
    <div class="wrap" id="slidedeck_overview">
    	<div id="icon-edit" class="icon32"></div>
        <h2>Edit SlideDecks 
            <a class="button add-new-h2" href="<?php echo slidedeck_action( '/slidedeck_add_new' ); ?>">Add New</a>
            <a class="button add-new-h2" href="<?php echo slidedeck_action( '/slidedeck_dynamic' ); ?>" style="margin-left:0;"><img src="<?php echo slidedeck_url( '/images/icon_dynamic.png' ); ?>" alt="Smart SlideDeck" /> Add Smart SlideDeck</a>
        </h2>
    	
        <?php echo slidedeck_show_message(); ?>
        
        <?php if( (boolean) SLIDEDECK_LEGACY_IMPORT_COMPLETE !== true ): ?>
            <div class="intro-text">
                <p>It doesn't look like you have run the plugin upgrade yet to import your legacy SlideDecks. Please go to the <a href="<?php echo clean_url( admin_url( 'plugins.php' ) ); ?>">plugins section</a> to deactivate and reactivate this plugin.</p>
            </div>
        <?php endif; ?>
        
    	<?php if( !empty( $slidedecks ) ): ?>
    		<table id="slidedecks" class="widefat post fixed" cellspacing="0">
    			<thead>
    				<tr>
    					<th class="manage-column column-title" scope="col"><a href="<?php echo slidedeck_orderby( 'title' ); ?>"<?php echo slidedeck_get_current_orderby( 'title' ) !== false ? ' class="order ' . slidedeck_get_current_orderby( 'title' ) . '"' : ''; ?>>Title</a></th>
    					<th width="150" class="manage-column" scope="col">Actions</th>
    					<th width="80" class="manage-column column-date" scope="col"><a href="<?php echo slidedeck_orderby( 'modified' ); ?>"<?php echo slidedeck_get_current_orderby( 'modified' ) !== false ? ' class="order ' . slidedeck_get_current_orderby( 'modified' ) . '"' : ''; ?>>Modified</a></th>
    				</tr>
    			</thead>
    			<tfoot>
    				<tr>
    					<th class="manage-column column-title" scope="col">Title</th>
    					<th class="manage-column" scope="col">Actions</th>
    					<th class="manage-column column-date" scope="col">Modified</th>
    				</tr>
    			</tfoot>
    			<tbody>
    				<?php $alternate = 0; ?>
    				<?php foreach( (array) $slidedecks as $slidedeck ): ?>
    					<tr class="author-self status-publish iedit<?php echo ( $alternate & 1 ) ? ' alternate' : ''; ?>" valign="top">
    						<td class="post-title column-title">
    							<a href="<?php echo slidedeck_action( $slidedeck['dynamic'] == '1' ? '/slidedeck_dynamic' : '' ); ?>&action=edit&id=<?php echo $slidedeck['id']; ?>">
        							<?php if( $slidedeck['dynamic'] == '1' ): ?>
        								<img src="<?php echo slidedeck_url( '/images/icon_dynamic.png' ); ?>" alt="Smart SlideDeck" />
        							<?php endif; ?>
                                    <?php echo $slidedeck['title']; ?>
                                </a> <span class="slidedeck-id">[<?php echo $slidedeck['id']; ?>]</span>
    						</td>
    						<td class="manage-column" scope="col">
    							<a href="<?php echo slidedeck_action( $slidedeck['dynamic'] == '1' ? '/slidedeck_dynamic' : '' ); ?>&action=edit&id=<?php echo $slidedeck['id']; ?>" class="slidedeck-action">Edit</a>
    							<a href="<?php echo wp_nonce_url( slidedeck_action() . '&action=delete&id=' . $slidedeck['id'], 'slidedeck-delete' ); ?>" class="slidedeck-action delete">Delete</a>
    						</td>
    						<td class="date column-date"><?php echo date( "Y/m/d", strtotime( $slidedeck['updated_at'] ) ); ?></td>
    					</tr>
    					<?php $alternate++; ?>
    				<?php endforeach; ?>
    			</tbody>
    		</table>
    	<?php else: ?>
    	<div id="message" class="updated">
    		<p>No SlideDecks found! <a href="<?php echo slidedeck_action( '/slidedeck_add_new' ); ?>">Create a New SlideDeck</a> or <a href="<?php echo slidedeck_action( '/slidedeck_dynamic' ); ?>">Create a New Smart SlideDeck</a></p>
    	</div>
    	<?php endif; ?>
    	<div class="overview-options">
			<div class="rss-feed">
			    <div id="slidedeck_blog_feed">
			        <h3>Product Blog <span>news, tips &amp; trends</span></h3>
			        <div id="slidedeck-blog-rss-feed">Fetch RSS Feed...</div>
			    </div>
			</div>
			
            <form action="" method="post" id="overview_options_form">
            <div>
                <h3>Advanced SlideDeck Options</h3>
                <p class="intro">These options are for situations where SlideDeck might not be working correctly. Only change them if you are having difficulty with your SlideDeck installation, or if you are certain of what they do.</p>
                <?php function_exists( 'wp_nonce_field' ) ? wp_nonce_field( 'slidedeck-for-wordpress', 'slidedeck-' . $form_action . '_wpnonce' ) : ''; ?>
                <input type="hidden" name="action" value="<?php echo $form_action; ?>" id="action" />
                    <ul>
                        <li>
                            <label><input type="checkbox" name="disable_wpautop" value="1"<?php echo $slidedeck_global_options['disable_wpautop'] == true ? ' checked="checked"' : ''; ?> /> Disable the <code>wpautop()</code> function?</label>
                        </li>
                        <li>
                            <label><input type="checkbox" name="enable_ssl_check" value="1"<?php echo $slidedeck_global_options['enable_ssl_check'] == true ? ' checked="checked"' : ''; ?> /> Enable SSL Support? (can be buggy on some servers)</label>
                        </li>
                        <li>
                            <label><input type="checkbox" name="dont_enqueue_scrollwheel_library" value="1"<?php echo $slidedeck_global_options['dont_enqueue_scrollwheel_library'] == true ? ' checked="checked"' : ''; ?> /> Don't enqueue the jquery.mousewheel.js library (if you have your own solution)</label>
                        </li>
                    </ul>
                <input type="submit" class="button-primary" value="Update Options" />
            </div>
            </form>
        </div>
    </div>
</div>