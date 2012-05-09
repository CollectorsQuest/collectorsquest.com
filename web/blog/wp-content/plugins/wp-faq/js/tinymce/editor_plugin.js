/**
 * Checkout TinyMCE Plugin
 * @author Tribulant Software
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack("FAQs");

	tinymce.create('tinymce.plugins.FAQs', {
		init: function(ed, url) {
			ed.addCommand('mceFAQs', function() {			
				ed.windowManager.open({
					file : url + '/dialog.php',
					width : 360,
					height : 260,
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('FAQs', {
				title : 'FAQs.desc',
				cmd : 'mceFAQs',
				image : url + '/faqs.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			/*ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('Checkout', n.nodeName == 'IMG');
			});*/
			
			
		},		
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'FAQs TinyMCE Plugin',
				author : 'Tribulant Software',
				authorurl : 'http://tribulant.com',
				infourl : 'http://tribulant.com',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('FAQs', tinymce.plugins.FAQs);
})();