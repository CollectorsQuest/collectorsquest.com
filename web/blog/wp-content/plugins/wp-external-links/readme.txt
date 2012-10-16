=== WP External Links ===
Contributors: freelancephp
Tags: links, external, icon, target, _blank, _new, _none, rel, nofollow, new window, new tab, javascript, xhtml, seo
Requires at least: 3.0.0
Tested up to: 3.3.2
Stable tag: 1.30

Open external links in a new window or tab, add "nofollow", choose icon, SEO friendly options...

== Description ==

Configure external links the way you want, with options like:

= Features =

* Open external links in new window or tab
* Add "nofollow" and "external" to rel-attribute
* Choose out of 20 icons
* Add additional classes for custom styling
* Set no-icon class
* Set title attribute
* XHTML strict and SEO friendly

This latest version requires PHP 5.2+ and WP 3.0+.

== Installation ==

1. Go to `Plugins` in the Admin menu
1. Click on the button `Add new`
1. Search for `WP External Links` and click 'Install Now' OR click on the `upload` link to upload `wp-external-links.zip`
1. Click on `Activate plugin`

== Frequently Asked Questions ==

[Do you have a question? Please ask me](http://www.freelancephp.net/contact/)

== Screenshots ==

1. Link Icon on the Site
1. Admin Settings Page

== Other notes ==

= Credits =
* [jQuery Tipsy Plugin](http://plugins.jquery.com/project/tipsy) made by [Jason Frame](http://onehackoranother.com/)
* [phpQuery](http://code.google.com/p/phpquery/) made by [Tobiasz Cudnik](http://tobiasz123.wordpress.com)
* [Icon](http://findicons.com/icon/164579/link_go?id=427009) made by [FatCow Web Hosting](http://www.fatcow.com/)

== Changelog ==

= 1.30 =
* Re-arranged options in metaboxes
* Added option for no icons on images

= 1.21 =
* Fixed phpQuery bugs (class already exists and loading stylesheet)
* Solved php notices

= 1.20 =
* Added option to ignore certain links or domains
* Solved tweet button problem by adding link to new ignore option
* Made JavaScript method consistent to not using JS
* Solved PHP warnings
* Solved bug adding own class
* Changed bloginfo "url" to "wpurl"

= 1.10 =
* Resolved old parsing method (same as version 0.35)
* Option to use phpQuery for parsing (for those who didn't experience problems with version 1.03)

= 1.03 =
* Workaround for echo DOCTYPE bug (caused by attributes in the head-tag)

= 1.02 =
* Solved the not working activation hook

= 1.01 =
* Solved bug after live testing

= 1.00 =
* Added option for setting title-attribute
* Added option for excluding filtering certain external links
* Added Admin help tooltips using jQuery Tipsy Plugin
* Reorginized files and refactored code to PHP5 (no support for PHP4)
* Added WP built-in meta box functionallity (using the `WP_Meta_Box_Page` Class)
* Reorganized saving options and added Ajax save method (using the `WP_Option_Forms` Class)
* Removed Regexp and using phpQuery
* Choose menu position for this plugin (see "Screen Options")
* Removed possibility to convert all `<a>` tags to xhtml clean code (so only external links will be converted)
* Removed "Solve problem" options

= 0.35 =
* Widget Logic options bug

= 0.34 =
* Added option only converting external `<a>` tags to XHTML valid code
* Changed script attribute `language` to `type`
* Added support for widget_content filter of the Logic Widget plugin

= 0.33 =
* Added option to fix js problem
* Fixed PHP / WP notices

= 0.32 =
* For jQuery uses live() function so also opens dynamicly created links in given target
* Fixed bug of changing `<abbr>` tag
* Small cosmetical adjustments

= 0.31 =
* Small cosmetical adjustments

= 0.30 =
* Improved Admin Options, f.e. target option looks more like the Blogroll target option
* Added option for choosing which content should be filtered

= 0.21 =
* Solved bug removing icon stylesheet

= 0.20 =
* Put icon styles in external stylesheet
* Can use "ext-icon-..." to show a specific icon on a link
* Added option to set your own No-Icon class
* Made "Class" optional, so it's not used for showing icons anymore
* Added 3 more icons

= 0.12 =
* Options are organized more logical
* Added some more icons

= 0.11 =
* JavaScript uses window.open() (tested in FireFox Opera, Safari, Chrome and IE6+)
* Also possible to open all external links in the same new window
* Some layout changes on the Admin Options Page

= 0.10 =
* Features: opening in a new window, set link icon, set "external", set "nofollow", set css-class
* Replaces external links by clean XHTML <a> tags
* Internalization implemented (no language files yet)

== Upgrade Notice ==

= 1.30 =
* Re-arranged options in metaboxes
* Added option for no icons on images

= 1.21 =
* Fixed phpQuery bugs (class already exists and loading stylesheet)
* Solved php notices

= 1.20 =
Main updates:
* Added option to ignore certain links or domains
* Solved tweet button problem
* Solved PHP warnings and other fixes

= 1.10 =
* Resolved old parsing method (same as version 0.35)
* Option to use phpQuery for parsing (same as version 1.03)
* This version requires PHP5.2+ and WP3.0+
