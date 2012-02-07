<?php

if (!empty($buttons))
{
  foreach ($buttons as $i => $button)
  {
    $options = array();

    if (!empty($button['confirm']))
    {
      $options['onclick'] = 'return confirm("'. addslashes($button['confirm']) .'");';
    }

    $route = (substr($button['route'], 0, 1) == '@') ? $button['route'] : $button['route'];

    echo sprintf('<li class="%s">', ($i == 0) ? 'first' : ($i == count($buttons) ? 'last' : ''));
    if (isset($button['icon']))
    {
      $button['text'] = sprintf('<span class="ui-icon ui-icon-%s"></span>', $button['icon']) . $button['text'];
    }
    if (isset($button['active']) && $button['active'] == true)
    {
      $options['class'] = 'active';

      echo image_tag(
        'legacy/sidebar-button-arrow.png',
        array('style' => 'float: left; margin-left: -26px; margin-top: -4px;')
      );
      echo (substr($button['route'], 0, 1) == '@') ?
             link_to($button['text'], $route, $options) :
             link_to_function($button['text'], $route, $options);
    }
    else
    {
      echo (substr($button['route'], 0, 1) == '@') ?
             link_to($button['text'], $route, $options) :
             link_to_function($button['text'], $route, $options);
    }
    echo '</li>';
  }
}
