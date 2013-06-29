<?php
/**
 * Edit/Create Dynamic SlideDeck form
 * 
 * SlideDeck for WordPress 1.4.8 - 2011-12-14
 * Copyright (c) 2011 digital-telepathy (http://www.dtelepathy.com)
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
 * @uses slidedeck_dir()
 * @uses slidedeck_show_message()
 * @uses slidedeck_get_dynamic_option()
 */
?>
<div class="wrap" id="dynamic_slidedeck_form">
    
    <?php include('_notification_bar.php'); ?>
    
	<div id="icon-edit" class="icon32"></div><h2><?php echo "create" == $form_action ? "Add Smart SlideDeck" : "Edit Smart SlideDeck"; ?></h2>
    
    <?php echo slidedeck_show_message(); ?>
    
	<form action="" method="post" id="dynamic_slidedeck_form">
	    <?php function_exists( 'wp_nonce_field' ) ? wp_nonce_field( 'slidedeck-for-wordpress', 'slidedeck-' . $form_action . '_wpnonce' ) : ''; ?>
		<input type="hidden" name="action" value="<?php echo $form_action; ?>" id="form_action" />
		<input type="hidden" name="gallery_id" value="<?php echo $slidedeck['gallery_id']; ?>" id="slidedeck_gallery_id" />
		<input type="hidden" name="dynamic" value="1" />
        <input type="hidden" name="skin" value="default" />
		<input type="hidden" name="slidedeck_options[hideSpines]" value="<?php echo $slidedeck['slidedeck_options']['hideSpines']; ?>" />
		<input type="hidden" name="slidedeck_options[cycle]" value="<?php echo $slidedeck['slidedeck_options']['cycle']; ?>" />
		<?php if( isset( $slidedeck['id'] ) && !empty( $slidedeck['id'] ) ): ?>
			<input type="hidden" name="id" value="<?php echo $slidedeck['id']; ?>" id="slidedeck_id" />
		<?php endif; ?>
		<div class="metabox-holder has-right-sidebar">
			<div class="inner-sidebar">
				<div id="slidedeck-options" class="postbox">
					<h3 class="hndle">SlideDeck Options</h3>
					<div class="inside">
						<p>Make sure to save your SlideDeck before you preview it.</p>
						<?php if( $form_action != "create" ): ?>
							<div id="slidedeck-preview"><div class="ajax-masker"></div><a href="<?php echo admin_url('admin-ajax.php'); ?>?action=slidedeck_preview&preview_w=900px&preview_h=370px&slidedeck_id=<?php echo $slidedeck['id']; ?>&width=920&height=570&first_preview" class="thickbox button" onclick="closePreviewWatcher();" title="Preview SlideDeck">Preview SlideDeck</a></div>
						<?php else: ?>
							<div id="slidedeck-preview"><a href="javascript:void(null);" title="You must save first to preview" class="button disabled">Preview SlideDeck</a></div>
						<?php endif; ?>
					</div>
					<div id="major-publishing-actions" class="submitbox">
						<?php if( $form_action != "create" ): ?>
							<div id="delete-action">
								<a href="<?php echo wp_nonce_url( slidedeck_action() . '&action=delete&id=' . $slidedeck['id'], 'slidedeck-delete' ); ?>" class="submitdelete deletion">Delete SlideDeck</a>
							</div>
						<?php endif; ?>
						
						<div id="publishing-action">
							<input type="submit" class="button-primary" value="<?php echo 'create' == $form_action ? 'Save SlideDeck' : 'Update'; ?>" style="float:right;" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
				
                <?php if( isset( $slidedeck['id'] ) && !empty( $slidedeck['id'] ) ): ?>
                    <div id="get-slidedeck-template-snippet" class="postbox">
                        <h3 class="hndle">Theme Code Snippet</h3>
                        <div class="inside">
                            <p>Want to place this SlideDeck in your WordPress theme template? Define the dimensions you want and copy-and-paste this in the appropriate theme file.</p>
                            <textarea cols="20" rows="5" id="slidedeck-template-snippet" readonly="readonly">&lt;?php slidedeck( <?php echo $slidedeck['id']; ?>, array( 'width' => '100%', 'height' => '370px' ) ); ?></textarea>
                            <div class="misc-pub-section misc-pub-section-last">
                                <label>Dimensions:</label> 
                                <input type="text" name="width" value="100%" id="template_snippet_w" /> 
                                <input type="text" name="height" value="370px" id="template_snippet_h" />
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
				
                <div class="editPageUpgradeCallout">
                    <h4>SlideDeck 2</h4>
                    <div class="inner">
                        <p class="align-center">We've gone next level on the content slider. <strong><em>It's all new</em></strong> from the ground up.</p>
                        <a href="<?php echo slidedeck_action( '/upgrade' ); ?>&variation=SlideDeck+Edit+CTA" target="_blank" class="upgrade">Upgrade</a>
                    </div>
                </div>
                
                <div class="follow-twitter callout-button"><p>For tips, tricks &amp; discounts</p><a href="http://twitter.com/slidedeck" target="_blank" class="button"><span class="inner"><img src="<?php echo slidedeck_url( '/images/twitter.png' ); ?>" /> Follow Us on Twitter</span></a></div>
                <div class="learn-more callout-button"><p>Remove the branding</p><a href="<?php echo slidedeck_action( '/upgrade' ); ?>&variation=Smart+SlideDeck+Edit+CTA" target="_blank" class="button"><span class="inner"><img src="<?php echo slidedeck_url( '/images/icon.png' ); ?>" /> Learn more about SD PRO</span></a></div>
                <div class="view-screencasts callout-button"><p>For how-to's and troubleshooting</p><a href="http://www.slidedeck.com/screencasts/" target="_blank" class="button"><span class="inner"><img src="<?php echo slidedeck_url( '/images/youtube.png' ); ?>" /> View Our Screencasts</span></a></div>
                <div class="bug-report callout-button"><p>Help us squash the bugs</p><a href="http://www.getsatisfaction.com/slidedeck/topics" target="_blank" class="button"><span class="inner"><img src="<?php echo slidedeck_url( '/images/bug.png' ); ?>" /> Report a bug for SlideDeck</span></a></div>
			</div>
			
			<div class="editor-wrapper">
				<div class="editor-body">
					<div id="titlediv">
						<div id="titlewrap">
							<label for="name">Name this SlideDeck</label>
							<input type="text" name="title" size="40" maxlength="255" value="<?php echo !empty( $slidedeck['title'] ) ? $slidedeck['title'] : 'Recent Posts'; ?>" id="title" />
						</div>
					</div>
				
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="skin">Choose Skin</label>
								</th>
								<td>
									<input type="hidden" name="skin" value="<?php echo $slidedeck['skin']; ?>" id="slidedeck_skin" />
									<div class="skins-choices">
									<?php foreach( (array) $skins as $skin ): ?>
										<a href="#<?php echo $skin['slug']; ?>" class="skin-thumbnail<?php echo $skin['slug'] == $slidedeck['skin'] ? ' active' : ''; ?>">
											<img src="<?php echo $skin['thumbnail']; ?>" alt="<?php echo $skin['meta']['Skin Name']; ?>" />
											<span class="skin-name"><?php echo $skin['meta']['Skin Name']; ?></span>
										</a>
									<?php endforeach; ?>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">Total Slides to Display</th>
								<td>
									<select name="dynamic_options[total]" id="slidedeck_total_slides">
										<?php for( $i = 3; $i <= 10; $i++ ): ?>
											<option value="<?php echo $i; ?>"<?php echo $slidedeck['dynamic_options']['total'] == $i ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
										<?php endfor; ?>
									</select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">Playback Options</th>
								<td>
									<p><label><input type="checkbox" name="slidedeck_options[autoPlay]" value="true"<?php echo $slidedeck['slidedeck_options']['autoPlay'] == 'true' ? ' checked="checked"' : ''; ?>> Autoplay</label>
									<label><input type="text" name="slidedeck_options[autoPlayInterval]" value="<?php echo intval($slidedeck['slidedeck_options']['autoPlayInterval']) / 1000; ?>" size="1" /> seconds per slide</label></p>
                                    <p><label>Animation Speed: 
                                        <select name="slidedeck_options[speed]">
                                            <?php $speeds = array( 250, 500, 1000, 1500, 2000 ); ?>
                                            <?php foreach( (array) $speeds as $speed ): ?>
                                                <option value="<?php echo $speed; ?>"<?php echo slidedeck_get_option( $slidedeck, 'speed' ) == $speed ? ' selected="selected"' : '' ;?>><?php echo $speed; ?></option>
                                            <?php endforeach; ?>
                                        </select> ms
                                    </label></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Interaction Options</th>
                                <td>
                                    <p><label><input type="checkbox" name="slidedeck_options[keys]" value="true"<?php echo slidedeck_get_option( $slidedeck, 'keys' ) == 'true' ? ' checked="checked"' : ''; ?> /> Allow Keyboard Navigation</label></p>
                                    <p><label><input type="checkbox" name="slidedeck_options[scroll]" value="true"<?php echo slidedeck_get_option( $slidedeck, 'scroll' ) == 'true' ? ' checked="checked"' : ''; ?> /> Allow Scroll Wheel Navigation</label></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">Type of Content</th>
								<td>
									<fieldset id="smart_slidedeck_type_of_content">
									    
										<legend class="screen-reader-text">Type of Content</legend>
										<label><input type="radio" name="dynamic_options[type]" value="recent"<?php echo $slidedeck['dynamic_options']['type'] == 'recent' ? ' checked="checked"' : ''; ?> /> Recent Posts</label><br />
										<label><input type="radio" name="dynamic_options[type]" value="featured"<?php echo $slidedeck['dynamic_options']['type'] == 'featured' ? ' checked="checked"' : ''; ?> /> Featured Posts</label><br />
                                        <?php if( floatval( get_bloginfo( 'version' ) ) < 2.9 ): ?>
											<label class="disabled"><input type="radio" name="dynamic_options[type]" disabled="disabled" value="popular"<?php echo $slidedeck['dynamic_options']['type'] == 'popular' ? ' checked="checked"' : ''; ?> /> Popular Posts <em>Requires WordPress 2.9+</em></label><br />
										<?php else: ?>
											<label><input type="radio" name="dynamic_options[type]" value="popular"<?php echo $slidedeck['dynamic_options']['type'] == 'popular' ? ' checked="checked"' : ''; ?> /> Popular Posts</label><br />
										<?php endif; ?>
										<div id="filter_posts_by_category" class="category-filter">
											<p><label><input type="checkbox" value="1" name="dynamic_options[filter_by_category]" id="slidedeck_filter_by_category"<?php echo $slidedeck['dynamic_options']['filter_by_category'] == '1' ? ' checked="checked"' : ''; ?> /> Filter these posts by category</label></p>
											<div id="category_filter_categories"<?php echo $slidedeck['dynamic_options']['filter_by_category'] != 1 ? ' style="display:none;"' : ''; ?>>
												<?php foreach( (array) $categories as $category ): ?>
													<label><input type="checkbox" name="dynamic_options[filter_categories][]" value="<?php echo $category->cat_ID; ?>"<?php echo in_array( $category->cat_ID, (array) $slidedeck['dynamic_options']['filter_categories'] ) ? ' checked="checked"' : ''; ?> /> <?php echo $category->name; ?></label>
												<?php endforeach; ?>
											</div>
										</div>
										<label class="disabled"><input disabled="disabled" type="radio" name="dynamic_options[type]" value="rss"<?php echo slidedeck_get_dynamic_option( $slidedeck, 'type' ) == 'rss' ? ' checked="checked"' : ''; ?> /> RSS/Atom Feed <span style="font-style:italic;color:#000;">Requires: <a href="<?php echo slidedeck_action( '/upgrade' ); ?>&variation=Dynamic+SlideDeck+Inline+CTAs" class="link-pro">SlideDeck Pro for WordPress</a></span></label><br />
                                        <label class="feed-validate">
                                            <input type="checkbox" value="1"<?php echo (boolean) slidedeck_get_dynamic_option( $slidedeck, 'validate_images' ) === true ? ' checked="checked"' : ''; ?> name="dynamic_options[validate_images]" id="slidedeck_validate_images" />
                                            Validate Images (helps with websites that include advertisement pixel images in their posts)
                                        </label>
                                        
									</fieldset>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
								    Content Settings
                                    <p style="font-size:11px;"><em>Requires:<br /> <a href="<?php echo slidedeck_action( '/upgrade' ); ?>&variation=Dynamic+SlideDeck+Inline+CTAs" class="link-pro">SlideDeck Pro for WordPress</a></em></p>
                                </th>
								<td>
									<fieldset>
										<legend class="screen-reader-text">Image Options</legend>
										<label for="slidedeck_image_source" class="disabled">Image Options</label>
										<select name="dynamic_options[image_source]" id="slidedeck_image_source" disabled="disabled">
											<option id="image_option_none" value="none"<?php echo slidedeck_get_dynamic_option( $slidedeck, 'image_source' ) == 'none' ? ' selected="selected"' : ''; ?>>No Image</option>
											<option id="image_option_content" value="content"<?php echo slidedeck_get_dynamic_option( $slidedeck, 'image_source' ) == 'content' ? ' selected="selected"' : ''; ?>>Display First Image in Content</option>
											<option id="image_option_gallery" value="gallery"<?php echo slidedeck_get_dynamic_option( $slidedeck, 'image_source' ) == 'gallery' ? ' selected="selected"' : ''; ?>>Display First Image in Gallery</option>
											<?php if ( function_exists('current_theme_supports')){ ?>
												<?php if ( current_theme_supports('post-thumbnails') ){ ?>
													<option id="image_option_thumbnail" value="thumbnail"<?php echo $slidedeck['dynamic_options']['image_source'] == 'thumbnail' ? ' selected="selected"' : ''; ?>>Display Post Thumbnail</option>
												<?php } ?>
											<?php } ?>
										</select>
									</fieldset>
                                    <fieldset>
                                        <legend class="screen-reader-text">Title Length</legend>
                                        <label class="disabled" for="slidedeck_title_length_with_image">Title length in words (with image) <input disabled="disabled" type="text" size="4" value="<?php echo slidedeck_get_dynamic_option( $slidedeck, 'title_length_with_image' ); ?>" name="dynamic_options[title_length_with_image]" id="slidedeck_title_length_with_image" /></label><br />
                                        <label class="disabled" for="slidedeck_title_length_without_image">Title length in words (without image) <input disabled="disabled" type="text" size="4" value="<?php echo slidedeck_get_dynamic_option( $slidedeck, 'title_length_without_image' ); ?>" name="dynamic_options[title_length_without_image]" id="slidedeck_title_length_without_image" /></label>
                                    </fieldset>
                                    <fieldset>
                                        <legend class="screen-reader-text">Excerpt Length</legend>
                                        <label class="disabled" for="slidedeck_excerpt_length_with_image">Excerpt length in words (with image) <input disabled="disabled" type="text" size="4" value="<?php echo slidedeck_get_dynamic_option( $slidedeck, 'excerpt_length_with_image' ); ?>" name="dynamic_options[excerpt_length_with_image]" id="slidedeck_excerpt_length_with_image" /></label><br />
                                        <label class="disabled" for="slidedeck_excerpt_length_without_image">Excerpt length in words (without image) <input disabled="disabled" type="text" size="4" value="<?php echo slidedeck_get_dynamic_option( $slidedeck, 'excerpt_length_without_image' ); ?>" name="dynamic_options[excerpt_length_without_image]" id="slidedeck_excerpt_length_without_image" /></label>
                                    </fieldset>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">Navigation Type</th>
								<td>
									<fieldset>
										<legend class="screen-reader-text">Navigation Type</legend>
										<input type="hidden" name="dynamic_options[navigation_type]" value="<?php echo $slidedeck['dynamic_options']['navigation_type']; ?>" id="slidedeck_navigation_type" />
										
										<a href="#simple-dots" id="navigation_simple-dots" class="navigation-type<?php echo $slidedeck['dynamic_options']['navigation_type'] == 'simple-dots' ? ' active' : ''; ?>">
											<img src="<?php echo slidedeck_url( '/images/navigation_simple-dots.png' ); ?>" alt="Simple Dots" /> Simple Dots
										</a>
										<a href="#dates" id="navigation_dates" class="navigation-type<?php echo $slidedeck['dynamic_options']['navigation_type'] == 'dates' ? ' active' : ''; ?>">
											<img src="<?php echo slidedeck_url( '/images/navigation_dates.png' ); ?>" alt="Dates" /> Dates
										</a>
										<a href="#post-titles" id="navigation_post-titles" class="navigation-type<?php echo $slidedeck['dynamic_options']['navigation_type'] == 'post-titles' ? ' active' : ''; ?>">
											<img src="<?php echo slidedeck_url( '/images/navigation_post-titles.png' ); ?>" alt="Post Titles" /> Post Titles
										</a>
									</fieldset>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</form>
</div>