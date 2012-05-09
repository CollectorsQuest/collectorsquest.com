<?php

class wpfaqMetaboxHelper extends wpFaqPlugin {

	var $name = 'Metabox';

	function wpfaqMetaboxHelper() {
	
		return true;
	}
	
	function settings_submit() {
		$this -> render('metaboxes' . DS . 'settings-submit', false, 'admin', true);
		return true;
	}
	
	function settings_sections() {
		$this -> render('metaboxes' . DS . 'settings-sections', false, 'admin', true);
		return true;
	}
	
	function settings_otheractions() {
		$this -> render('metaboxes' . DS . 'settings-otheractions', false, 'admin', true);
		return true;
	}
	
	function settings_wprelated() {
		$this -> render('metaboxes' . DS . 'settings-wprelated', false, 'admin', true);
		return;
	}
	
	function settings_general() {
		$this -> render('metaboxes' . DS . 'settings-general', false, 'admin', true);		
		return true;
	}
	
	function settings_ask() {
		$this -> render('metaboxes' . DS . 'settings-ask', false, 'admin', true);
		return true;
	}
	
	function settings_questions() {
		$this -> render('metaboxes' . DS . 'settings-questions', false, 'admin', true);
		return true;
	}
	
	function settings_accordion() {
		$this -> render('metaboxes' . DS . 'settings-accordion', false, 'admin', true);
		return true;
	}
	
	function settings_customcss() {
		$this -> render('metaboxes' . DS . 'settings-customcss', false, 'admin', true);
		return true;
	}
	
	function questions_save_pp() {
		$this -> render('metaboxes' . DS . 'question-pp', false, 'admin', true);	
	}
}

?>