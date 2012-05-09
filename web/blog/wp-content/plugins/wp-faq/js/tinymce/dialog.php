<?php

//turn off display_errors
error_reporting(E_ALL);
@ini_set('display_errors', 0);

$root = __FILE__;
for ($i = 0; $i < 6; $i++) $root = dirname($root);

if (!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }
require_once($root . DS . 'wp-config.php');
require_once(ABSPATH . 'wp-admin' . DS . 'admin-functions.php');

//global variables
global $wpfaqDb, $wpfaqGroup, $wpfaqQuestion;

$wpfaqDb -> model = $wpfaqGroup -> model;
$groups = $wpfaqDb -> find_all();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php _e('Insert FAQs', "wp-faq"); ?></title>
		<script language="javascript" type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
		<script language="javascript" type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
		<script language="javascript" type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
		<script language="javascript" type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
		<script language="javascript" type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/jquery/jquery.js"></script>
		<script language="javascript" type="text/javascript">
		
		var _self = tinyMCEPopup;
		function init () {
			tinyMCEPopup.resizeToInnerSize();
			//changeQuestionGroup();
		}
		
		function insertTag() {	
			var tagtext = "";
		
			var universal = document.getElementById('universal_panel');
			var group = document.getElementById('group_panel');
			var question = document.getElementById('question_panel');
			
			if (universal.className.indexOf('current') != -1) {			
				if (uni = jQuery("input[name='uni']:checked").val()) {				
					if (uni == "groups") {
						tagtext = "[wpfaqgroups]";
					} else if (uni == "questions") {
						var questionsorder = jQuery('#questionsorder').val();
						var questionsorderby = jQuery('#questionsorderby').val();
						tagtext = '[wpfaqs order="' + questionsorder + '" orderby="' + questionsorderby + '"]';
					} else if (uni == "search") {
						tagtext = "[wpfaqsearch";
						
						if (jQuery("#search_menu:checked").val() != null) {
							tagtext += " menu=1";
						} else {
							tagtext += " menu=0";	
						}
						
						if (group_id = jQuery("#search_group").val()) {
							if (group_id != 0) {
								tagtext += ' group_id="' + group_id + '"';
							}
						}
						
						tagtext += "]";
					} else if (uni == "ask") {
						if (group_id = jQuery("#ask_group").val()) {
							tagtext = '[wpfaqask';
							
							if (group_id != 0) {
								tagtext += ' group_id="' + group_id + '"';
							}
							
							tagtext += ']';
						}
					}
				}
			}
			
			if (group.className.indexOf('current') != -1) {
				if (group_id = jQuery("#groupsmenu").val()) {
					if (group_id != 0) {
						tagtext = '[wpfaqgroup id="' + group_id + '"]';
					}
				}
			}
		
			if (question.className.indexOf('current') != -1) {			
				if (question_id = jQuery("#singlequestionmenu").val()) {
					if (question_id != 0) {
						tagtext = '[wpfaqquestion id="' + question_id + '"]';
					}
				}
			}	
			
			if (window.tinyMCE && tagtext != "") {
				//TODO: For QTranslate we should use here 'qtrans_textarea_content' instead 'content'
				window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
				//Peforms a clean up of the current editor HTML. 
				//tinyMCEPopup.editor.execCommand('mceCleanup');
				//Repaints the editor. Sometimes the browser has graphic glitches. 
				tinyMCEPopup.editor.execCommand('mceRepaint');
				tinyMCEPopup.close();
			}
			return;
		}
		
		function closePopup() {
			tinyMCEPopup.close();
		}
	
		function changeQuestionGroup() {
			var questiongroupmenu = jQuery('#questiongroupmenu');
			var singlequestionmenu = jQuery('#singlequestionmenu');
			jQuery('#questiongroupmenuloading').show();	
			
			jQuery.post("<?php echo WP_PLUGIN_URL; ?>/wp-faq/wp-faq-ajax.php?cmd=questions_by_group&group_id=" + questiongroupmenu.val(), { group:questiongroupmenu.val() }, function(response) {
				singlequestionmenu.html(response);
				jQuery('#questiongroupmenuloading').hide();
			});
		}
			
		</script>	
	</head>

	<body id="link" onload="init(); document.body.style.display = '';">
		<form onsubmit="insertTag(); return false;" action="#">
			<div class="tabs">
				<ul>
					<li id="universal_tab" class="current"><span><a href="javascript:mcTabs.displayTab('universal_tab','universal_panel');" onmousedown="return false;"><?php _e('Universal', "wp-faq"); ?></a></span></li>
					<li id="group_tab"><span><a href="javascript:mcTabs.displayTab('group_tab','group_panel');" onmousedown="return false;"><?php _e('Group', "wp-faq"); ?></a></span></li>
					<li id="question_tab"><span><a href="javascript:mcTabs.displayTab('question_tab','question_panel');" onmousedown="return false;"><?php _e('Question', "wp-faq"); ?></a></span></li>
				</ul>
			</div>
		
			<div class="panel_wrapper" style="height:175px !important;">
				<div id="universal_panel" class="panel current">
					<br/>
					
					<table border="0" cellpadding="4" cellspacing="0">
						<tbody>
							<tr>
								<td nowrap="nowrap" valign="top"><label for="uni_groups"><?php _e('Insert', "wp-faq"); ?></label></td>
								<td>
									<label><input checked="checked" onclick="change_uni(this.value);" type="radio" name="uni" value="groups" id="uni_groups" /> <?php _e('Groups List', "wp-faq"); ?></label><br/>
                                    <label><input onclick="change_uni(this.value);" type="radio" name="uni" value="questions" id="uni_questions" /> <?php _e('All Questions', "wp-faq"); ?></label><br />
									<label><input onclick="change_uni(this.value);" type="radio" name="uni" value="search" id="uni_search" /> <?php _e('Search Box', "wp-faq"); ?></label><br/>
									<label><input onclick="change_uni(this.value);" type="radio" name="uni" value="ask" id="uni_ask" /> <?php _e('Ask Box', "wp-faq"); ?></label>
								</td>
							</tr>
						</tbody>
					</table>
                    
                    <script type="text/javascript">
					function change_uni(unival) {							
						jQuery('div[id^="uni_"]').hide();
						jQuery('div#uni_' + unival + '_div').show();	
					}
					</script>
                    
                    <div id="uni_questions_div" style="display:none;">
                    	<table border="0" cellspacing="0" cellpadding="4">
                        	<tbody>
                            	<tr>
                                	<td nowrap="nowrap"><label for="questionsorder"><?php _e('Sort', "wp-faq"); ?></label></td>
                                    <td>
                                    	<select name="questionsorder" id="questionsorder">
                                        	<option value="ASC"><?php _e('Ascending', "wp-faq"); ?></option>
                                            <option value="DESC"><?php _e('Descending', "wp-faq"); ?></option>
                                        </select>
                                        <?php _e('BY', "wp-faq"); ?>
                                        <select name="questionsorderby" id="questionsorderby">
                                        	<option value="id"><?php _e('ID', "wp-faq"); ?></option>
                                            <option value="question"><?php _e('Question', "wp-faq"); ?></option>
                                            <option value="group_id"><?php _e('Group ID', "wp-faq"); ?></option>
                                            <option value="created"><?php _e('Created Date', "wp-faq"); ?></option>
                                            <option value="modified"><?php _e('Modified Date', "wp-faq"); ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
					
					<div id="uni_search_div" style="display:none;">
						<table border="0" cellspacing="0" cellpadding="4">
							<tbody>
								<tr>
									<td nowrap="nowrap"><label for="search_menu"><?php _e('Groups Menu', "wp-faq"); ?></label></td>
									<td>
										<label><input type="checkbox" name="search_menu" value="1" id="search_menu" /> <?php _e('Include a groups dropdown menu', "wp-faq"); ?></label>
									</td>
								</tr>
								<tr>
									<td nowrap="nowrap"><label for="search_group"><?php _e('Group', "wp-faq"); ?></label></td>
									<td>
										<select id="search_group" name="search_group">
											<option value="">- <?php _e('Select a Group', "wp-faq"); ?> -</option>
											<?php foreach ($groups as $group) : ?>
												<option value="<?php echo $group -> id; ?>"><?php echo $group -> name; ?></option>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div id="uni_ask_div" style="display:none;">
						<table border="0" cellpadding="4" cellspacing="0">
							<tbody>
								<tr>
									<td nowrap="nowrap"><label for="ask_group"><?php _e('Group', "wp-faq"); ?></td>
									<td>
										<select id="ask_group" name="ask_group">
											<option value="">- <?php _e('Select a Group', "wp-faq"); ?> -</option>
											<?php foreach ($groups as $group) : ?>
												<option value="<?php echo $group -> id; ?>"><?php echo $group -> name; ?></option>
											<?php endforeach; ?>
										</select>	
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
				<div id="group_panel" class="panel">
					<br/>
					<table border="0" cellpadding="4" cellspacing="0">
						<tbody>
							<tr>
								<td nowrap="nowrap"><label for="groupsmenu"><?php _e('Group', "wp-faq"); ?></label></td>
								<td>
									<select name="groupsmenu" id="groupsmenu">
										<option value="">- <?php _e('Select a Group', "wp-faq"); ?> -</option>
										<?php foreach ($groups as $group) : ?>
											<option value="<?php echo $group -> id; ?>"><?php echo $group -> name; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div id="question_panel" class="panel">
					<br/>					
					<table border="0" cellpadding="4" cellspacing="0">
						<tbody>
							<tr>
								<td nowrap="nowrap"><label for="questiongroupmenu"><?php _e('Group', "wp-faq"); ?></label></td>
								<td>
									<select onchange="changeQuestionGroup(); return false;" id="questiongroupmenu" name="questiongroupmenu">
										<option value=""><?php _e('- Select a Group -', "wp-faq"); ?></option>
										<?php foreach ($groups as $group) : ?>
											<option value="<?php echo $group -> id; ?>"><?php echo $group -> name; ?></option>
										<?php endforeach; ?>
									</select>
                                    <span id="questiongroupmenuloading" style="display:none;"><?php _e('loading...', "wp-faq"); ?></span>
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap" valign="top"><label for="singlequestionmenu"><?php _e('Question', "wp-faq"); ?></label></td>
								<td>
									<select id="singlequestionmenu" style="max-width:260px;" name="questionmenu" size="7">
										<option value=""><?php _e('- Select a Group first -', "wp-faq"); ?></option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="mceActionPanel">
				<div style="float:left;">
					<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="closePopup();"/>
				</div>
		
				<div style="float:right;">
					<input type="button" id="insert" name="insert" value="{#insert}" onclick="insertTag();" />
				</div>
			</div>
		</form>
	</body>
</html>