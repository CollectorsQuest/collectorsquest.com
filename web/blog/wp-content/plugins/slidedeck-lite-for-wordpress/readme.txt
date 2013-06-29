=== SlideDeck 1 Lite Content Slider ===
Contributors: dtelepathy, kynatro, jamie3d, dtrenkner, oriontimbers
Donate link: http://www.slidedeck.com/download
Tags: Slider, dynamic, slide show, slideshow, widget, Search Engine Optimized, seo, jquery, plugin, pictures, slide, skinnable, skin, posts, video, photo, media, image gallery, iPad, iphone, vertical slides, touch support, theme
Requires at least: 2.8.6
Tested up to: 3.5
Stable tag: trunk

Create SlideDecks on your WordPress blogging platform. Manage SlideDeck content and insert them into templates and posts.

== Description ==

= Deprecation Notice =
**SlideDeck 1 Lite for WordPress is deprecated.** As of 3/13/2013 we will not longer be updating this plugin, but we suggest that you consider trying [SlideDeck 2 Lite](http://wordpress.org/extend/plugins/slidedeck2/) for WordPress instead.
[SlideDeck 2 Lite](http://wordpress.org/extend/plugins/slidedeck2/) has replaced SlideDeck 1 and is now our primary SlideDeck focus.

Thanks for your support! - The SlideDeck Team


The SlideDeck WordPress slider plugin allows you to easily create a content slider widget or slideshow on your WordPress blog without having to write any code. Just create a new slider with the SlideDeck control panel tool and insert the widget into your post via the WYSIWYG editor with the TinyMCE plugin SlideDeck picker. 

You can also create a dynamic slider by using the Smart Slider function. Just choose your blog post criteria (recent, popular, featured), select a theme, set your options and viola, you have a dynamically updated slider in seconds! Users can now visually experience your blog posts.

**NEW!:** Vertical slides (PRO), RSS Feed Smart SlideDecks (PRO), Skin Support, Compatible with WordPress 3.0+, Now uses custom post types!

**Requirements:** PHP5+, WordPress 2.8.6+

**Important Links:**

* [Demo](http://www.slidedeck.com/wordpress)
* [Community Examples](http://www.slidedeck.com/examples)
* [More Details](http://www.slidedeck.com/wordpress)
* [Full Feature List](http://www.slidedeck.com/features)
* [Documentation](http://www.slidedeck.com/usage-documentation)
* [Support](http://getsatisfaction.com/slidedeck)

**Features:**

* No coding required!
* Smart SlideDecks. Build dynamic SlideDeck slideshows from blog content
* Add any media (image, video, mp3...etc) to a slide with the WordPress editor
* Add, remove or reorder slides with a slick drag and drop interface
* Search Engine Optimized (SEO) - all content of each SlideDeck (copy, alt tags...etc) are completely indexable by search engines
* Update SlideDeck content at anytime without even editing your posts or template code
* Specify any slide as the start slide as well as the animation speed
* Specify unique spine title text
* Use all the tools in the WordPress Kitchen Sink editor to make your SlideDeck look perfect
* Customize the code and add any content directly into the slide with the WordPress HTML editor
* Preview your SlideDeck in a modal box or on your post as you create it
* Set custom dimensions for each SlideDeck
* Copy and paste a code snippet to place your SlideDeck anywhere on your WordPress blog or site
 * Touchscreen support for iPad, iPhone and other devices (PRO).
 * Ability to create vertical slides (PRO).
 * Smart SlideDecks from RSS feeds (PRO).
 * Ability to apply free skin/themes.
 
**Use Cases:**

* Dynamic Content
 * Feature Slider for WordPress Blog Posts  
 * Visualize Any RSS Feed 
 * Automate News Articles and Updates
* Tours & Demos
 * Product Tour 
 * Features Demo 
 * Process Guide 
 * Step-by-Step Instruction
* Media Galleries
 * Photo Gallery 
 * Video Gallery 
 * Artwork Gallery 
 * Music Gallery (Artist, Album Song) 
 * Movie/Television Guide/Gallery
* Multi-Dimensional Web Content (User Input or Vertical + Horizontal Slides)
 * "Choose Your Own Content" Based on User Input 
 * Lead Generation Based on User Input 
 * Decision Tree Process 
 * User Based Tutorials (Skip steps based on user level)
 * Surveys


== Installation ==

1. Upload the `slidedeck` folder and all its contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a new SlideDeck from the new menu in the control panel sidebar
1. Insert a SlideDeck in your post or page by clicking on the `Embed a SlideDeck` button in the rich text editor or the button in the sidebar in the post/page view. 

You can also place a SlideDeck in your template or theme via the PHP command slidedeck(); Just pass the ID of the SlideDeck you want to render and an associative array of the styles you want to apply to the SlideDeck itself. For example:

    `<?php slidedeck(12,array('width'=>'100%','height'=>'300px')); ?>`
    
Where 12 is the SlideDeck's ID. You can also see this code snippet in the sidebar of the SlideDeck editing interface.

== Frequently Asked Questions ==

The best place for getting your questions answered is via our [Get Satisfaction support thread](http://www.getsatisfaction.com/slidedeck). 

= I can't add a slide, it just shows me a wierd looking page =

Make sure that you are running up-to-date plugins on your website and that they are all compatible with the version of WordPress you are running. We find that the most common cause of any problem with getting SlideDeck working on a website has to do with a plugin that isn't written for the version of WordPress you are running and it causes a conflict or a JavaScript failure (which prevents our JavaScript from loading).

= I just purchased the Pro version, but I don't see a feature that the SlideDeck JavaScript library has =

We try and move the features we develop for the JavaScript library over to the WordPress plugin as soon as possible, but it takes some time to integrate and create an interface to use the feature. Keep an eye on your Inbox for updates; we'll let you know when the plugin is updated with the feature. 

= I just put a SlideDeck on my site and I'm getting tons of "Warning: cannot yet handle MBCS in html_entity_decode()!" errors, whats going on? =

This error appears if you are running PHP 4 on your server. This is a bug in PHP 4 itself that is causing this error to occur. Please contact your web hosting company and ask them how to upgrade your web server to PHP 5; this is usally a quick switch flip in your web host's control panel. 

= My WYSIWYG editors are not loading =

Make sure you are running up-to-date plugins. Some older versions of common plugins that add buttons to the WYSIWYG editor (such as Vipers video plugin) may cause the WYSIWYG editor to error out when it is initializing, preventing much of the JavaScript on the page from working.

= My SlideDeck isn't loading =

Make sure that your theme is running both the `wp_head();` command and the `wp_footer();` command, otherwise SlideDeck will not work properly. If you are manually loading jQuery in your template or theme after the `<?php wp_head(); ?>` command, you will overwrite the SlideDeck plugin extension. Make sure that `<?php wp_head(); ?>` is the last thing loading in your `<head>` tag. If you need to load JavaScript for your WordPress theme, make sure you are using the `wp_enqueue_script();` command in your theme's `functions.php` file. See http://codex.wordpress.org/Function_Reference/wp_enqueue_script for more information on how to implement this.

= I can't get SlideDeck working with Buddypress =

We've made some serious improvements to the way we are implementing interface elements, doing previews, etc. in version 1.2 that may resolve some of these issues. Please try the latest version of SlideDeck and let us know how it is working for you.

= Pieces of the SlideDeck look wierd on my website =

Sometimes you might see extra spaces between links or "closed" slides, this is usually due to a conflict with the WordPress theme you are running on your website and the SlideDeck CSS. We are constantly working to improve the stability of the CSS of SlideDeck, but sometimes there are some themes that do some CSS definitions we cannot accommodate for. We recommend looking into getting [Firebug](http://www.getfirebug.com) for [Firefox](http://www.firefox.com) and investigate the elements that look strange and try and correct your theme's conflicting CSS.

= I want to put video in my SlideDeck, how do I do that? =

We updated the WYSIWYG editor implementation method in version 1.2 to be more compatible with other plugins and to process shortcodes. Please be advised that it is up to the plugin author to choose to display their plugin buttons in WYSIWYG editors used outside of the Posts and Pages section of WordPress. So, you still may not see your shortcode buttons, but we do still process any shortcodes entered manually. Any sort of media though can still be embedded in a SlideDeck. Just click on the HTML edit tab for the slide and copy-and-paste the embed code for a video into a SlideDeck's content area.

= I've placed a video in my SlideDeck, but it shows through the SlideDeck, even on closed slides =

Flash doesn't play nicely all the time with fancy interactions like SlideDeck and the like, luckily there's an easy fix. Just add the the `wmode="opaque"` parameter to your embed code. For example, with a YouTube video embed code, this will change the code from this:

`<object width="480" height="385"><param name="movie" value="http://www.youtube.com/v/au3-hk-pXsM&hl=en_US&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/au3-hk-pXsM&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>`

to this:

`<object width="480" height="385"><param name="movie" value="http://www.youtube.com/v/au3-hk-pXsM&hl=en_US&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><param name="wmode" value="opaque"></param><embed src="http://www.youtube.com/v/au3-hk-pXsM&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385" wmode="opaque"></embed></object>`

Take note of the addition of the `<param name="wmode" value="opaque"></param>` tag inside the `<object>` tag and the `wmode="opaque"` attribute on the `<embed>` tag.

= I want to customize the way my SlideDeck looks, how do I do that? = 

We provide a few skins with SlideDeck, all of which you can edit. You will find all the skins located in the "skins" folder in the SlideDeck WordPress Plugin folder. Edit the CSS files in the skins folders to make changes. Just be sure and backup your changes before updating your plugin, otherwise you'll loose them!

== Screenshots ==

1. The SlideDeck editing view. Create a SlideDeck, add slides, re-order slides and change SlideDeck settings with the sidebar modules.
2. Preview your SlideDeck before you insert it into a post or into your theme.
3. SlideDeck integrates seamlessly in your post and page editing views. Just click on the `Embed a SlideDeck` button in the WYSIWYG editor and choose a SlideDeck you have created from the list in the dialog box. You can also specify dimensions for how the SlideDeck appears in your post.
4. Insert SlideDecks directly into your posts or pages.
5. Smart SlideDecks - create a SlideDeck out of your pre-existing posts! Choose the most recent posts on your blog, featured posts, or popular posts (requires at least WordPress 2.9+) and even filter by category.
6. Use `Gallery Style` Smart SlideDecks to create interactive elements in your theme or posts.
7. A preview of the Smart SlideDeck. Three different navigation layouts available - Post Titles, Post Dates, and Dots.

== Changelog ==
= 1.4.8 =
* Compatibility fixes for WordPress 3.5.x

= 1.4.7 =
* Updated skin asset pathing to work better in some non-standard server configurations
* Updated skin processing of goTo() indexes to pass an integer typed value to work properly with newer versions of jQuery
* Fixed default option retrieval to be more compatible with older installations of SlideDeck 

= 1.4.6 =
* Updated SlideDeck JavaScript core to 1.3.3
* Fixed skins that were not working with improved Preview modal
* Improved Preview modal to not require a second reload upon initial viewing
* Improved IE CSS override implementation so IE9 can be accommodated for - just define the styles in skin.ie.css
* Improved multibyte support for Smart SlideDeck navigation tab titles

= 1.4.5 =
* Added support for WordPress 3.3
* Re-factored much of the TinyMCE implementation to accommodate better for cross-browser and WordPress version support
* Improved SlideDeck editor interface to not load all available skin files, only loading the skin that the SlideDeck was last saved with
* Improved SlideDeck preview window to size properly and accommodate better for skins that have padding
* Improved SlideDeck skin support interaction to automatically set and lock-out required SlideDeck settings
* Added Touch Screen configuration options
* Consolidated playback and interaction options for Smart SlideDecks with the rest of the options in the interface instead of being in the sidebar
* Re-factored commands for adding slides to a regular SlideDeck to use an AJAX based routine for both vertical and horizontal slide addition. This was done to accommodate for WordPress 3.3's new TinyMCE implementation (wp_editor()).
* Fixed issues with TinyMCE and Internet Explorer that was preventing the editing interface from working properly
* Improved slide data storage method with WordPress 3.3 that allows for better HTML markup support in visual editor and HTML editor
* Updated SlideDeck JavaScript core to 1.3.2

= 1.4.2 =
* Modified method of loading template files for dynamic skins to accommodate for differently named template files
* Abstracted SlideDeck Options skin response to allow for easier per-skin option accommodation
* Updated JavaScript Library Core to 1.3.0
* Removed <noscript> tags from SlideDeck output

= 1.4.1 =
* Fixed Fullscreen Mode conflict with WordPress version 3.2.
* Fixed Smart SlideDeck content type selection conflict with WordPress 3.2. Posts, Pages, and other public custom post types allowed.

= 1.4 =
* Vertical Stacked Skin support added for Regular and Smart SlideDecks (PRO feature only).
* New Vertical Slide Structure (PRO feature only).
* New Vertical SlideDeck Skins for Regular and Smart SlideDecks (PRO feature only).
* Vertical SlideDeck titles (PRO feature only).
* UI Improvements for Smart SlideDecks.
* New Advanced Option to limit Edit/Create access to Admins Only (PRO feature only).
* Limited Advanced Options to users with Manage Options privileges (PRO feature only).
* Add/Edit TinyMCE Bug Fix - button was not working, fixed.
* Optimization of skin assets - some skin assets were being output even if no SlideDeck was present on the page.
* External skin folder bug fix - some users skins were not being detected.
* Skin pathing bug fix - some users were having pathing issues due to server permission settings.

= 1.3.72 =
* IE9/Chromeframe compatibility added.
* Added permissions setting to the directory creation of the SlideDeck Skins directory to prevent errors.
* Type-casted the $custom_skin_files variable in the foreach() as array for getting custom skins.

= 1.3.71 =
* Modified function being used for title length truncating in PRO version to be simpler and more reliable for multibyte character handling.
* Plugin will now read any skins in the folder /wp-content/plugins/slidedeck-skins in addition to those already located in the /skins sub-folder. Skins in the /wp-content/plugins/slidedeck-skins folder will override those in the /skins sub-folder if their folder name is the same. So, if you want to customize one of the built-in distributed skins, just copy it to the new /wp-content/plugins/slidedeck-skin folder and edit away; your modified skin will be used instead of the default one. Be sure to place your custom skins in this folder as well so they are not accidentally erased upon updating the plugin.
* Modified RSS feed of blog posts on overview page to pull in posts via AJAX.

= 1.3.7 =
* Added title length option for Smart SlideDecks (PRO feature only).
* Added option to disable wpautop().
* Added RSS feed of blog posts to overview page.

= 1.3.6 =
* Changed user levels to roles/capabilities.
* Added an option for being able to scroll through vertical slides and to the previous or next horizontal slide.
* Added an option to control the new continueScrolling JavaScript library option.
* Updated the SlideDeck JavaScript library to version 1.2.1 The changes are below:
* Fixed an issue where SlideDeck was too greedy when selecting slide elements. This caused problems with nested SlideDecks.
* Improved mouse wheel scroll navigation when used in conjunction with the cycle option.

= 1.3.5 =
* Fixed issue where the SlideDeck Preview would incorrectly process width values.
* Standardized width & height adjustment boxes for the PHP Snippet in the sidebar on both regular and Smart SlideDeck editing pages.
* Insert SlideDeck modal now allows selection and adjustment without inserting the deck. An insert button has been added.
* Added the ability to adjust the height of a Smart SlideDeck.
* Added 3 new Smart SlideDeck skins that focus on imagery/image gallery style. For image based (gallery) skins, posts with no images are excluded.
* Added the ability to request a preferred image size (for Smart SlideDecks) via an option in a Skin file's CSS.
* HTML/Visual tabs are now remembered and restored on page load when editing a deck.
* Disabling the visual editor (in user profile settings) now works as expected, instead of reporting errors and breaking interaction.
* Voyager & Ribbons skins have been updated to better support slide background images in Internet Explorer.
* Fixed an issue where entering custom formatted HTML into the HTML Editor view could result in escaped (&lt;p&gt;) contents being loaded when the deck was next edited.
* Adjusted default height for Standard & Smart SlideDecks for future compatibility.
* Fixed an issue where the default autoPlay interval would be 5000 seconds.
* Added jquery.mousewheel.js library as part of the distribution.
* Added options for hiding the active corner, disabling keyboard navigation, disabling scroll navigation.
* Added advanced global option for SSL & jquery.mousewheel.js exclusion.

= 1.3.3 =
* BETA Feature: Widget deployment option for users running WordPress 2.8+

= 1.3.2 =
* Modified the way that we were processing dates to be more globally compatibile with different time zones.
* Bug fix for WordPress 2.7 to make accommodations for the lack of the esc_html() function.
* Bug fix to properly handle getting the first image from a post's gallery. We were not accommodating for a keyed array return and it was preventing access to the first element in the returned array.
* Bug fix for WordPress 2.9.2 that was preventing the "Upload/Set" button for slide backgrounds from opening the media upload dialog.
* Bug fix for adding media to a new, un-saved SlideDeck. Implemented new method for creating SlideDecks that will create a new SlideDeck entry in the database with the "auto-draft" post_status value to have a legitimate post entry to associate media attachments to - method modeled after the way WordPress handles regular post creation.

= 1.3.2beta1 =
* Linked images in Smart SlideDecks to their article's permalink
* Made IE stylesheet conditionals for skins more specific to prevent IE8 specific styles from accidentally overriding IE7 specific styles
* Improved RSS Smart SlideDeck XML parsing for access to the RSS feed's content area
* Changed RSS XML loading to use WordPress' built in wp_remote_fopen() function for better compatibility with servers that do not have allow_url_fopen set to "On"
* Added additional exclusions for RSS image filtering to further filter out unwanted imagery from getting picked up as a post's summary image
* Made an exclusion for BuddyPress to get around the missing easing in certain versions of the ScrollTo library that comes with BuddyPress
* Improved SlideDeck slide content processing to prevent plugins that append content to posts from doing so on SlideDeck slide content
* Bug fix for TinyMCE editors that was causing shortcodes to come back as rendered markup instead of the shortcode
* Bug fix for the way that content was being loaded for SlideDecks and SlideDeck slides that caused a conflict in comment open/close status
* Bug fix for GLOB_BRACE issue; GLOB_BRACE was unnecessary for the command so it was removed

= 1.3.1 =
* Bug fix for TinyMCE editors to properly process HTML tags and prevent paragraphs from being accidentally removed
* Fixed a bug that was causing comments to appear in posts that had comments turned off and had a Smart SlideDeck embeded

= 1.3.0 =
* Major change to the way SlideDeck stores data in the database, we are no longer using any custom tables, now we use custom post types to store SlideDecks!
* Now compatible with WordPress 3.0!
* Improved image scrubbing for Smart SlideDecks
* Improved in-line documentation
* Hooked up slide backgrounds to the media library (PRO feature only)

= 1.2.2 =
* Pathing fixes for "preview" and "add another slide" buttons
* Added button overlay to prevent AJAX button utilization before JavaScript has had a chance to map events

= 1.2.1 =
* Fixed image parsing for dynamic SlideDecks to be more reliable
* Added "validate images" option for dynamic SlideDecks to help eliminate possible advertisement images
* Fixed stripslashes issue
* Updated plugin URL and directory referencing plugins to help improve reliability for deployments outside of top level domain
* Fixed issue with new skin loading method that wasn't accommodating for multiple SlideDecks in a single post
* Added new BETA feature to add backgrounds to slides for regular SlideDecks (PRO feature only)!

= 1.2.0 =
* NEW! Vertical Slides (PRO feature only)
* NEW! RSS feed Smart SlideDecks (PRO feature only)
* NEW! Skin support for regular SlideDecks
* Upgraded JavaScript core plugin to 1.1.6 to improve cross-browser compatibility
* New and improved preview method that is less hacky and more cross-browser and cross-platform compatible
* New and improved skin loading methods for better cross-browser compatibility and greater plugin stability
* New and improved WYSIWYG implementations for better compatibility
* Added shortcode processing support for SlideDecks
* Improved WordPress 3.0 compatibility. NOTE: Unfortunately the new WordPress 3.0 core will not show the "Insert into post" button in the Upload/Insert media dialog. See the [Get Satisfaction support thread](http://www.getsatisfaction.com/slidedeck/topics/media_insert_buttons_and_wordpress_3_0) for more details. 
* Tons of little internal code optimizations, improvements and bug fixes

= 1.1.4 =
* Bug fix for IE display compatibility

= 1.1.1 =
* Bug fixes to dark and light skin JavaScript when displaying more than one Dynamic SlideDeck on the page.
* Bug fixes for pathing to fix TinyMCE issues some users were experiencing.
* Added SlideDeck Lite JavaScript core options to static SlideDeck interface: Auto Play, Hide Slide Title Bars, Loop slides

= 1.1.0 =
* Added Smart SlideDecks - create SlideDecks automatically based off of content from your posts! Select recent, featured and popular posts (requires at least WordPress 2.9) to display in your Smart SlideDeck. Place your Smart SlideDeck in your theme as an automatically updating feature.
* Added post sidebar options to feature a post in Smart SlideDecks and customize a post's Smart SlideDeck title
* Added core changes for skin and template handling in preparation for the skin library coming soon!
* Updated SlideDeck Lite library to new GPL licensed 1.1.5
* IE Interface fixes and preview improvements
* Implemented wp_nonce security measures where appropriate
* Made function prefixing more consistent
* Implemented numerous SQL protection implementations
* Modification to database table structure for better option storage - remember to BACKUP your database before installing or upgrading any plugin!

= 1.0.35 =
* Modified preview to use UTF-8 character set.
* Specified removal of background image from preview.
* Added UTF-8 character set decoding to template processing for proper UTF-8 output.
* Specified text-align:left in preview to override odd text-align:center default.
* Changed JavaScript and Stylesheet inclusion methods to be more reliable.

= 1.0.31 =
* Bug fix for HTML/WYSIWYG editor syncing.

= 1.0.3 =
* Fixed minor bug that prevented HTML editing view from syncing with WYSIWYG editors.

= 1.0.2 =
* Updated database schema to use UTF-8 collation and character sets for title and content fields on SlideDecks and SlideDeck slides for better international language support.

= 1.0.1 =
* Update of JavaScript library to version 1.1.3.
* Changed method of creating SlideDeck instances to use direct Class instancing instead of extended jQuery method.

= 1.0 =
* Addition of media uploads to SlideDeck slides and per SlideDeck gallery associations.
* Addition of SlideDeck preview interaction.
* Addition of theme PHP snippet sidebar module.
* Addition of embed button in rich editor view sidebar for better visibility.
* Lots of bug fixes for data storage, character escaping and encoding.
* Lots of bug fixes for proper use of plugin in WordPress 2.7.x environments - removed jQuery `.live()` references and adapted jQuery UI skinning for older version of jQuery UI that comes with WordPress 2.7.x. 

= 0.5 =
* Initial beta release with basic SlideDeck creation, management, and placement.

== Upgrade Notice ==

= 1.4.8 =
Final Update. Bug fixes for compatibility with WordPress 3.5.

= 1.4.2 =
Update to SlideDeck JavaScript Core 1.3.0 to accommodate for new skin bundle.

= 1.4.1 =
Minor Update. Bux fixes for compatibility with WordPress 3.2.

= 1.4 =
Minor Update. Various bug fixes, core library JavaScript update. Stacked vertical slide support for PRO users.

= 1.3.72 =
Minor Update. Skin directory bug fixes, core library JavaScript update, IE9 support, enhanced ChromeFrame support.

= 1.3.71 =
Minor update. Alternative skin folder, truncate titles (PRO only).

= 1.3.3 =
New custom post types for Smart SlideDecks, vertical slide autoplay support, BETA widget deployment.

= 1.3.2 =
Bug fix patch. Made some BuddyPress exceptions, improved RSS feed reading.

= 1.3.2beta1 =
Private beta release primarily for bug fixes

= 1.3.1 =
Update to the TinyMCE visual/html editor bug that caused paragraphs to be removed from the content area

= 1.3.0 =
Major database storage change - using custom post types! WordPress 3.0 compatibility!

= 1.2.2 =
Hotfix again! Sorry guys, last fix introduced some pathing problems. I've cleared these up in this release, so please update.

= 1.2.1 =
Hotfix! Couple of bug fixes from the latest 1.2.0 release. Update now!

= 1.2.0 =
Major update! NEW features: RSS Smart SlideDecks (PRO), Vertical Slides (PRO), Skin Support! Lots of compatibility bug fixes!

= 1.1.3 =
Bug fix for IE display.

= 1.1.1 =
Bug fixes and addition of new SlideDeck Lite features to static SlideDecks.

= 1.1.0 =
Smart SlideDecks and skin system core added! SlideDeck Lite JavaScript updated to new GPL licensed 1.1.5 version. 

= 1.0.35 =
Preview changes, UTF-8 decoding on output, and changed JavaScript and Stylesheet inclusion methods to be more reliable. 

= 1.0.31 =
Bug fix for WYSIWYG editors when saving.

= 1.0.3 =
Bug fix for slide editors. Fixed problem where updating/saving a SlideDeck when in the HTML editing mode erased the HTML content.

= 1.0.2 =
Updated the way we store titles and content for better international character support.

= 1.0.1 =
Updated plugin to use SlideDeck 1.1.3 which adds a more reliable implementation method.

= 1.0 =
Gold release, please upgrade your beta plugin now.

= 0.5 =
Initial beta test release.
