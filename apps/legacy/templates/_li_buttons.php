<?php

if (!empty($buttons))
{
  foreach ($buttons as $i => $button)
  {
    $route = (substr($button['route'], 0, 1) == '@') ? $button['route'] : $button['route'];

    echo sprintf('<li class="%s">', ($i == 0) ? 'first' : ($i == count($buttons) ? 'last' : ''));
    if (isset($button['icon']))
    {
      $button['text'] = sprintf('<span class="ui-icon ui-icon-%s"></span>', $button['icon']) . $button['text'];
    }
    if (isset($button['active']) && $button['active'] == true)
    {
      echo image_tag(
        'legacy/sidebar-button-arrow.png',
        array('style' => 'float: left; margin-left: -26px; margin-top: -4px;')
      );
      echo (substr($button['route'], 0, 1) == '@') ?
              link_to($button['text'], $route, array('class' => 'active')) :
              link_to_function($button['text'], $route, array('class' => 'active'));
    }
    else
    {
      echo (substr($button['route'], 0, 1) == '@') ?
              link_to($button['text'], $route) :
              link_to_function($button['text'], $route);
    }
    echo '</li>';
  }
}
