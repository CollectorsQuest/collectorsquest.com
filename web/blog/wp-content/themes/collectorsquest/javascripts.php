<?php

$scripts = array(
  'jquery-ui-core' => '1.8.20',
  'jquery-effects-core' => '1.8.20',
  'jquery-effects-blind' => '1.8.20',
  'jquery-effects-bounce' => '1.8.20',
  'jquery-effects-clip' => '1.8.20',
  'jquery-effects-drop' => '1.8.20',
  'jquery-effects-explode' => '1.8.20',
  'jquery-effects-fade' => '1.8.20',
  'jquery-effects-fold' => '1.8.20',
  'jquery-effects-highlight' => '1.8.20',
  'jquery-effects-pulsate' => '1.8.20',
  'jquery-effects-scale' => '1.8.20',
  'jquery-effects-shake' => '1.8.20',
  'jquery-effects-slide' => '1.8.20',
  'jquery-effects-transfer' => '1.8.20',

  'jquery-ui-accordion' => '1.8.20',
  'jquery-ui-autocomplete' => '1.8.20',
  'jquery-ui-datepicker' => '1.8.20',
  'jquery-ui-dialog' => '1.8.20',
  'jquery-ui-draggable' => '1.8.20',
  'jquery-ui-droppable' => '1.8.20',
  'jquery-ui-mouse' => '1.8.20',
  'jquery-ui-position' => '1.8.20',
  'jquery-ui-progressbar' => '1.8.20',
  'jquery-ui-resizable' => '1.8.20',
  'jquery-ui-selectable' => '1.8.20',
  'jquery-ui-slider' => '1.8.20',
  'jquery-ui-sortable' => '1.8.20',
  'jquery-ui-tabs' => '1.8.20',
  'jquery-ui-widget' => '1.8.20'
);

foreach ($scripts as $name => $version)
{
  if ($wp_scripts->query($name, 'queue'))
  {
    wp_deregister_script($name);
    wp_register_script($name, '/wp-content/themes/collectorsquest/js/empty.js', array(), $version, 1);
    wp_enqueue_script($name);
  }
}

wp_enqueue_script(
  'cq-smartWizard', '/wp-content/themes/collectorsquest/js/cq-smartWizard.js',
  array('jquery'), '2.0', true
);
