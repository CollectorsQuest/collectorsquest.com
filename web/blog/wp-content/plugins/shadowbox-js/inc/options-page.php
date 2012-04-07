<?php
/**
 * Intermingled PHP and HTML for the options page
 *
 * This file contains all PHP and HTML required for the Shadowbox Options Page in the admin of WordPress
 *
 * @package shadowbox-js
 * @subpackage options-page
 * @since 3.0.0.4
 */
?>
		<div class="wrap shadowbox">
			<div id="icon-options-general" class="icon32"><br /></div>
			<h2><?php _e( 'Shadowbox JS' , 'shadowbox-js' ); ?></h2>
			<?php if ( has_filter ( 'shadowbox-js' ) ) : ?>
			<div id="shadowbox-override" class="notice">
				<p>
					<strong>
						<?php _e( 'The URL for shadowbox.js has been overridden. Numerous options on this page will not display when the URL for shadowbox.js has been overriden as they will not have any effect.' ); ?>
					</strong>
				</p>
			</div>
			<?php endif; ?>
			<?php if ( ! empty ( $this->options ) && ! has_filter( 'shadowbox-js' ) ) : ?>
			<div class="metabox-holder">
				<?php do_meta_boxes ( 'shadowbox-js' , 'normal' , '' ); ?>
			</div>
			<?php endif; ?>
			<form action="options.php" method="post">
				<?php settings_fields ( 'shadowbox' ); ?>
				<?php if ( ! empty ( $this->options ) ) : // Start option check. Don't show most of the form if there are no options in the db ?>
				<input type="hidden" name="shadowbox[version]" value="<?php echo $this->esc ( $this->dbversion , 'attr' ); ?>" />
				<h3><?php _e( 'General' , 'shadowbox-js' ); ?></h3>
				<p><?php _e( 'These are general options for the Shadowbox Javascript that tell Shadowbox how to run, how to look and what language to use.' , 'shadowbox-js' ); ?></p>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Javascript Library' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[library]">
								<option value="base"<?php selected ( 'base' , $this->get_option ( 'library' ) ); ?>><?php _e( 'None' , 'shadowbox-js' ); ?></option>
								<option value="yui"<?php selected ( 'yui' , $this->get_option ( 'library' ) ); ?>>YUI</option>
								<option value="prototype"<?php selected ( 'prototype' , $this->get_option ( 'library' ) ); ?>>Prototype</option>
								<option value="jquery"<?php selected ( 'jquery' , $this->get_option ( 'library' ) ); ?>>jQuery</option>
								<option value="mootools"<?php selected ( 'mootools' , $this->get_option ( 'library' ) ); ?>>Mootools</option>
							</select>
							<br />
							<?php _e( 'Default is None.' , 'shadowbox-js' ); ?>
							<?php if ( has_filter ( 'shadowbox-js' ) ) : ?>
							<div id="shadowbox-override-library" class="notice">
								<p>
									<strong>
										<?php _e( 'The URL for shadowbox.js has been overridden. The above setting must match the library/adapter that was chosen when building shadowbox.js or Shadowbox will not function.' ); ?>
									</strong>
								</p>
							</div>
							<?php endif; ?>
						</td>
					</tr>
					<?php if ( ! has_filter ( 'shadowbox-js' ) ) : ?>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Language' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[language]">
		<?php foreach ( $this->languages () as $language ) : ?>
								<option value="<?php echo $language; ?>"<?php selected ( $language , $this->get_option ( 'language' ) ); ?>><?php echo $language; ?></option>
		<?php endforeach; ?>
		<?php unset ( $language ); ?>
							</select>
							<br />
							<?php _e( 'Default is en.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Players' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<p><input type="checkbox" name="shadowbox[players][]" value="html"<?php checked ( true , in_array ( 'html' , (array) $this->get_option ( 'players' ) ) ); ?>/>HTML</p>
							<p><input type="checkbox" name="shadowbox[players][]" value="iframe"<?php checked ( true , in_array ( 'iframe' , (array) $this->get_option ( 'players' ) ) ); ?>/>IFRAME</p>
							<p><input type="checkbox" name="shadowbox[players][]" value="img"<?php checked ( true , in_array ( 'img' , (array) $this->get_option ( 'players' ) ) ); ?>/>IMG</p>
							<p><input type="checkbox" name="shadowbox[players][]" value="qt"<?php checked ( true , in_array ( 'qt' , (array) $this->get_option ( 'players' ) ) ); ?>/>QT</p>
							<p><input type="checkbox" name="shadowbox[players][]" value="swf"<?php checked ( true , in_array ( 'swf' , (array) $this->get_option ( 'players' ) ) ); ?>/> SWF</p>
							<p><input type="checkbox" name="shadowbox[players][]" value="wmp"<?php checked ( true , in_array ( 'wmp' , (array) $this->get_option ( 'players' ) ) ); ?>/> WMP</p>
							<?php if ( $this->get_option ( 'enableFlv' ) != 'true' ) $class = ' class="hidden"'; else $class = ''; ?>
							<p id="flvplayer"<?php echo $class; ?>><input type="checkbox" name="shadowbox[players][]" value="flv"<?php checked ( true , in_array ( 'flv' , (array) $this->get_option ( 'players' ) ) ); ?>/>FLV</p>
							<?php _e( 'The list of enabled or disabled players. Default is HTML, IFRAME, IMG, QT, SWF and WMP. The FLV option will not be available until you <a href="#enableFlv">enable FLV support</a> as outlined below.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Enable FLV Support' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select id="enableFlv" name="shadowbox[enableFlv]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'enableFlv' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'enableFlv' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'By enabling FLV support you are agreeing to the the <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">noncommercial license</a> used by JW FLV Media Player. The JW FLV Media Player is licensed under the terms of the <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported License</a>. After enabling FLV support you will need to select FLV from the list of players above, and optionally enable automation for FLV links under "Shadowbox Automation". Default is false.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Enable Smart Loading' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[smartLoad]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'smartLoad' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'smartLoad' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Enabling this will only load Shadowbox and its dependencies when needed based on the content of your posts.	Please note that when enabling this Shadowbox will not be loaded if rel="shadowbox" is not found in the content of your post(s).  If you experience problems after enabling this, try disabling. Default is false.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Use Cached shadowbox.js' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[useCache]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'useCache' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'useCache' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'This will allow this plugin to create a cached copy of shadowbox.js in wp-content/uploads/shadowbox-js.  With this disabled the shadowbox.js file will be built on the fly during each page load.  If you experience problems with this plugin not working with this enabled, try disabling. Default is true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
				</table>
				<h3 id="sbadvancedtitle"><?php _e( 'Advanced Configuration' , 'shadowbox-js' ); ?></h3>
				<p><input id="sbadvancedbtn" type="button" class="button" value="<?php _e( 'Show Advanced Configuration' , 'shadowbox-js' ); ?>" style="display:none; font-weight: bold; width: 216px;"/></p>
				<table id="sbadvanced" class="form-table">
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Animate' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[animate]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'animate' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'animate' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this false to disable all fancy animations (except fades). This can improve the overall effect on computers with poor performance. Defaults to true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Fade Animations' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[animateFade]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'animateFade' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'animateFade' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this false to disable all fading animations. Defaults to true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Animation Sequence' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[animSequence]">
								<option value="wh"<?php selected ( 'wh' , $this->get_option ( 'animSequence' ) ); ?>>wh</option>
								<option value="hw"<?php selected ( 'hw' , $this->get_option ( 'animSequence' ) ); ?>>hw</option>
								<option value="sync"<?php selected ( 'sync' , $this->get_option ( 'animSequence' ) ); ?>>sync</option>
							</select>
							<br />
							<?php _e( 'The animation sequence to use when resizing Shadowbox. May be either "wh" (width first, then height), "hw" (height first, then width), or "sync" (both simultaneously). Defaults to "sync".' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Modal' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[modal]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'modal' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'modal' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this true to disable listening for mouse clicks on the overlay that will close Shadowbox. Defaults to false.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Show Overlay' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[showOverlay]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'showOverlay' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'showOverlay' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this false to disable showing the overlay. Defaults to true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Overlay Color' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[overlayColor]" value="<?php echo $this->esc ( $this->get_option ( 'overlayColor' ) , 'attr' ); ?>" size="7" />
							<br />
							<?php _e( 'The color to use for the modal overlay (in hex). Defaults to "#000".' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Overlay Opacity' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[overlayOpacity]" value="<?php echo $this->esc ( $this->get_option ( 'overlayOpacity' ) , 'attr' ); ?>" size="4" />
							<br />
							<?php _e( 'The opacity to use for the modal overlay. Defaults to 0.8.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Flash Background Color' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[flashBgColor]" value="<?php echo $this->esc ( $this->get_option ( 'flashBgColor' ) , 'attr' ); ?>" size="7" />
							<br />
							<?php _e( 'The default background color to use for Flash movies. Defaults to "#000000".' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Auto-Play Movies' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[autoplayMovies]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'autoplayMovies' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'autoplayMovies' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this false to disable automatically playing movies when they are loaded. Defaults to true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Show Movie Controls' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[showMovieControls]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'showMovieControls' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'showMovieControls' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this false to disable displaying QuickTime and Windows Media player movie control bars. Defaults to true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Slideshow Delay' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[slideshowDelay]" value="<?php echo $this->esc ( $this->get_option ( 'slideshowDelay' ) , 'attr' ); ?>" size="2" style="width: 1.5em;" />
							<br />
							<?php _e( 'A delay (in seconds) to use for slideshows. If set to anything other than 0, this value determines an interval at which Shadowbox will automatically proceed to the next piece in the gallery. Defaults to 0.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Resize Duration' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[resizeDuration]" value="<?php echo $this->esc ( $this->get_option ( 'resizeDuration' ) , 'attr' ); ?>" size="4" />
							<br />
							<?php _e( 'The duration (in seconds) of the resizing animations. Defaults to 0.55.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Fade Duration' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[fadeDuration]" value="<?php echo $this->esc ( $this->get_option ( 'fadeDuration' ) , 'attr' ); ?>" size="4" />
							<br />
							<?php _e( 'The duration (in seconds) of the fade animations. Defaults to 0.35.' , 'shadowbox-js' ); ?>
						</td>
					</tr>				
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Display Navigation' , 'shadowbox-js' ); ?>
						</th> 
						<td>
							<select name="shadowbox[displayNav]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'displayNav' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'displayNav' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this false to hide the gallery navigation controls. Defaults to true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Continuous' , 'shadowbox-js' ); ?>
						</th> 
						<td>
							<select name="shadowbox[continuous]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'continuous' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'continuous' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this true to enable "continuous" galleries. By default, the galleries will not let a user go before the first image or after the last. Enabling this feature will let the user go directly to the first image in a gallery from the last one by selecting "Next". Defaults to false.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Display Counter' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[displayCounter]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'displayCounter' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'displayCounter' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this false to hide the gallery counter. Counters are never displayed on elements that are not part of a gallery. Defaults to true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Counter Type' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[counterType]">
								<option value="default"<?php selected ( 'default' , $this->get_option ( 'counterType' ) ); ?>><?php _e( 'default' , 'shadowbox-js' ); ?></option>
								<option value="skip"<?php selected ( 'skip' , $this->get_option ( 'counterType' ) ); ?>><?php _e( 'skip' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'The mode to use for the gallery counter. May be either "default" or "skip". The default counter is a simple "1 of 5" message. The skip counter displays a separate link to each piece in the gallery, enabling quick navigation in large galleries. Defaults to "default".' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Counter Limit' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[counterLimit]" value="<?php echo $this->esc ( $this->get_option ( 'counterLimit' ) , 'attr' ); ?>" size="3" />
							<br />
							<?php _e( 'Limits the number of counter links that will be displayed in a "skip" style counter. If the actual number of gallery elements is greater than this value, the counter will be restrained to the elements immediately preceding and following the current element. Defaults to 10.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Viewport Padding' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[viewportPadding]" value="<?php echo $this->esc ( $this->get_option ( 'viewportPadding' ) , 'attr' ); ?>" size="3" />
							<br />
							<?php _e( 'The amount of padding (in pixels) to maintain around the edge of the browser window. Defaults to 20.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Handle Oversize' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[handleOversize]">
								<option value="none"<?php selected ( 'none' , $this->get_option ( 'handleOversize' ) ); ?>><?php _e( 'none' , 'shadowbox-js' ); ?></option>
								<option value="resize"<?php selected ( 'resize' , $this->get_option ( 'handleOversize' ) ); ?>><?php _e( 'resize' , 'shadowbox-js' ); ?></option>
								<option value="drag"<?php selected ( 'drag' , $this->get_option ( 'handleOversize' ) ); ?>><?php _e( 'drag' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'The mode to use for handling content that is too large for the viewport. May be one of "none", "resize", or "drag" (for images). The "none" setting will not alter the image dimensions, though clipping may occur. Setting this to "resize" enables on-the-fly resizing of large content. In this mode, the height and width of large, resizable content will be adjusted so that it may still be viewed in its entirety while maintaining its original aspect ratio. The "drag" mode will display an oversized image at its original resolution, but will allow the user to drag it within the view to see portions that may be clipped. Defaults to "resize".' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Handle Unsupported' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[handleUnsupported]">
								<option value="link"<?php selected ( 'link' , $this->get_option ( 'handleUnsupported' ) ); ?>><?php _e( 'link' , 'shadowbox-js' ); ?></option>
								<option value="remove"<?php selected ( 'remove' , $this->get_option ( 'handleUnsupported' ) ); ?>><?php _e( 'remove' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'The mode to use for handling unsupported media. May be either "link" or "remove". Media are unsupported when the browser plugin required to display the media properly is not installed. The link option will display a user-friendly error message with a link to a page where the needed plugin can be downloaded. The remove option will simply remove any unsupported gallery elements from the gallery before displaying it. With this option, if the element is not part of a gallery, the link will simply be followed. Defaults to "link".' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Auto Dimensions' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[autoDimensions]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'autoDimensions' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'autoDimensions' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this true to automatically set the initialHeight and initialWidth automatically from the configured object\'s height and width. Defaults to false.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Initial Height' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[initialHeight]" value="<?php echo $this->esc ( $this->get_option ( 'initialHeight' ) , 'attr' ); ?>" size="3" />
							<br />
							<?php _e( 'The height of Shadowbox (in pixels) when it first appears on the screen. Defaults to 160.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Initial Width' , 'shadowbox-js' ); ?>
						</th> 
						<td>
							<input type="text" name="shadowbox[initialWidth]" value="<?php echo $this->esc ( $this->get_option ( 'initialWidth' ) , 'attr' ); ?>" size="3" />
							<br />
							<?php _e( 'The width of Shadowbox (in pixels) when it first appears on the screen. Defaults to 320.' , 'shadowbox-js' ); ?>
						</td>
					</tr>				
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Enable Keys' , 'shadowbox-js' ); ?>
						</th> 
						<td>
							<select name="shadowbox[enableKeys]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'enableKeys' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'enableKeys' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this false to disable keyboard navigation of galleries. Defaults to true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Skip Setup' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[skipSetup]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'skipSetup' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'skipSetup' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this true to skip Shadowbox.setup() during Shadowbox.init(). For purposes of this plugin you will have to manually add Shadowbox.setup() to the footer of your theme. Defaults to false.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Use Sizzle' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[useSizzle]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'useSizzle' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'useSizzle' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Set this true to enable loading the <a href="http://sizzlejs.com/">Sizzle.js</a> CSS selector library. Note that if you choose not to use Sizzle you may not use CSS selectors to set up your links. In order to use Sizzle.js you must set Skip Setup to true. Defaults to false.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Flash Params' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<textarea name="shadowbox[flashParams]" rows="10" cols="50"><?php echo $this->esc ( $this->get_option ( 'flashParams' ) , 'htmledit' ); ?></textarea>
							<br />
							<?php _e( 'A list of parameters (in a JavaScript object) that will be passed to a flash &lt;object&gt;. For a partial list of available parameters, see <a href="http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_12701">this page</a>. Only one parameter is specified by default: bgcolor. Defaults to {bgcolor:"#000000", allowFullScreen:true}.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Flash Vars' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<textarea name="shadowbox[flashVars]" rows="10" cols="50"><?php echo $this->esc ( $this->get_option ( 'flashVars' ), 'htmledit' ); ?></textarea>
							<br />
							<?php _e( 'A list of variables (in a JavaScript object) that will be passed to a flash movie as <a href="http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_16417">FlashVars</a>. Defaults to {}.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Flash Version' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[flashVersion]" value="<?php echo $this->esc ( $this->get_option ( 'flashVersion' ) , 'attr' ); ?>" size="5" />
							<br />
							<?php _e( 'The minimum Flash version required to play a flash movie (as a string). Defaults to "9.0.0".' , 'shadowbox-js' ); ?>
						</td>
					</tr>
				</table>
				<h3><?php _e( 'Shadowbox Automation' , 'shadowbox-js' ); ?></h3>
				<p><?php _e( 'These options will give you the capability to have Shadowbox automatically used for all of a certain file type.' , 'shadowbox-js' ); ?></p>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<acronym title="bmp, gif, png, jpg, and jpeg"><?php _e( 'Image Links' , 'shadowbox-js' ); ?></acronym>
						</th>
						<td>
							<select name="shadowbox[autoimg]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'autoimg' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'autoimg' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Default is true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<acronym title="flv, f4v, and mp4"><?php _e( 'FLV Links' , 'shadowbox-js' ); ?></acronym>
						</th>
						<td>
							<select id="autoflv" name="shadowbox[autoflv]"<?php if ( $this->get_option ( 'enableFlv' ) == 'false' && ! has_filter ( 'shadowbox-js' ) ) { echo ' disabled="disabled"'; } ?>>
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'autoflv' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'autoflv' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php if ( ! has_filter ( 'shadowbox-js' ) )
								 _e( 'Default is false.  To enable this option you must first <a href="#enableFlv">enable FLV support</a>.' , 'shadowbox-js' );
							else
								_e( 'Default is false.' , 'shadowbox-js' );
							?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<acronym title="swf, flv, f4v, dv, mov, moov, movie, mp4, asf, wm, wmv, avi, mpg and mpeg"><?php _e( 'Movie Links' , 'shadowbox-js' ); ?></acronym>
						</th>
						<td>
							<select name="shadowbox[automov]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'automov' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'automov' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Default is true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<acronym title="mp3, aac"><?php _e( 'Music Links' , 'shadowbox-js' ); ?></acronym>
						</th>
						<td>
							<select name="shadowbox[autoaud]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'autoaud' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'autoaud' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Default is true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'YouTube and Google Video Links' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[autotube]">
								<option value="true"<?php selected ( 'true' , $this->get_option ( 'autotube' ) ); ?>><?php _e( 'true' , 'shadowbox-js' ); ?></option>
								<option value="false"<?php selected ( 'false' , $this->get_option ( 'autotube' ) ); ?>><?php _e( 'false' , 'shadowbox-js' ); ?></option>
							</select>
							<br />
							<?php _e( 'Default is true.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
				</table>
				<h3><?php _e( 'Sizes' , 'shadowbox-js' ); ?></h3>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Generic Video Width' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[genericVideoWidth]" value="<?php echo $this->esc ( $this->get_option ( 'genericVideoWidth' ) , 'attr' ); ?>" size="3" />
							<br />
							<?php _e( 'The width of Shadowbox (in pixels) when displaying videos. Defaults to 640.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Generic Video Height' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<input type="text" name="shadowbox[genericVideoHeight]" value="<?php echo $this->esc ( $this->get_option ( 'genericVideoHeight' ) , 'attr' ); ?>" size="3" />
							<br />
							<?php _e( 'The height of Shadowbox (in pixels) when when displaying videos. Defaults to 385.' , 'shadowbox-js' ); ?>
						</td>
					</tr>
				</table>
				<?php else : // Else option check. Display this if there are no options in the DB ?>
					<div id="error" class="error"><p><strong><?php _e( 'The settings for this plugin have been deleted. The plugin can now be' , 'shadowbox-js' ); ?> <a href="<?php echo wp_nonce_url ( 'plugins.php?action=deactivate&amp;plugin=' . $this->plugin_basename , 'deactivate-plugin_' . $this->plugin_basename ); ?>" title="<?php _e( 'Deactivate Shadowbox JS' , 'shadowbox-js' ); ?>" style="border-bottom: none;"><?php _e( 'deactivated' , 'shadowbox-js' ); ?></a>. <?php _e( 'If you want to create the settings with their defaults so this plugin can be used again, set "Reset to Defaults" to "true" and click "Save Changes"' , 'shadowbox-js' ); ?>.</strong></p></div>
				<?php endif; // End Option Check ?>
				<h3><?php _e( 'Resets' , 'shadowbox-js' ); ?></h3>
				<p><?php _e( 'These options will allow you to revert the options back to their defaults or to remove the options from the database for a clean uninstall.' , 'shadowbox-js' ); ?></p>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Reset to Defaults' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[default]">
								<option value="false" selected="selected"><?php _e( 'false' , 'shadowbox-js' ); ?></option>
								<option value="true"><?php _e( 'true' , 'shadowbox-js' ); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Delete Options for a Clean Uninstall' , 'shadowbox-js' ); ?>
						</th>
						<td>
							<select name="shadowbox[delete]">
								<option value="false" selected="selected"><?php _e( 'false' , 'shadowbox-js' ); ?></option>
								<option value="true"><?php _e( 'true' , 'shadowbox-js' ); ?></option>
							</select>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' , 'shadowbox-js' ) ?>" />
				</p>
			</form>
		</div>
