<?php

function cq_section_title($title)
{
  echo '<h2 class="section-title clear">', $title, '</h2>';
}

function cq_label_for($form, $field, $label = null)
{
  echo $form[$field]->renderLabel($label);
}

function cq_input_tag($form, $field, $options = array())
{
  $width = (isset($options['width'])) ? $options['width'] : 300;

  $options = array_merge(
    $options,
    array('style' => 'border: 1px solid #A7A7A7; font-size: 16px; height: 23px; width: '.($width-10).'px; padding: 4px; margin: 0;')
  );

  echo '<div style="background: #E9E9E9; vertical-align: middle; width: '.$width.'px; padding: 5px;">';
  echo $form[$field]->render($options);
  echo '</div>';
}

function cq_textarea_tag($form, $field, $options = array())
{
  $width = (isset($options['width'])) ? $options['width'] : 300;
  $height = (isset($options['height'])) ? $options['height'] : 100;

  if (isset($options['rich']) && $options['rich'] == true)
  {
    $w = $width;
    $h = $height - 2;

    unset($options['rich']);
  }
  else
  {
    $w = $width - 8;
    $h = $height - 5;
  }

  $options = array_merge(
    $options,
    array('style' => sprintf('border: 1px solid #A7A7A7; font-size: 14px; height: %dpx; min-height: %dpx; width: %dpx; margin: 0;', $h, $h, $w))
  );

  echo sprintf('<div style="background: #E9E9E9; min-height: %dpx; vertical-align: middle; width: %dpx; padding: 5px;">', $height, $width);
  echo $form[$field]->render($options);
  echo '</div>';
}

function cq_select_tag($form, $field, $options = array())
{
  $width = (isset($options['width'])) ? $width = $options['width'] : 300;

  echo '<div style="background: #E9E9E9; vertical-align: middle; width: '.$width.'px; padding: 5px;">';
  echo $form[$field]->render(array('choices' => array('test', 'me'), 'style' => 'border: 1px solid #A7A7A7; font-size: 14px; width: '.($width).'px; padding: 2px; margin: 0;'));
  echo '</div>';
}

function cq_button_submit($value, $class = null, $style = null)
{
  echo '<button class="submit ', $class,'" value="', $value,'" type="submit" style="', $style ,'">',
         '<span><span>', $value, '</span></span>',
       '</button>';
}

function cq_button($value, $route, $options = array())
{
  // Setting the default options
  $options = array_merge(array('class' => '', 'style' => ''), $options);

  // The route can be also a javascript function, not only a symfony route
  $onclick = (substr($route, 0, 1) == '@') ? "document.location.href = '". url_for($route) . "'" : $route;
  if (isset($options['confirm']))
  {
    $onclick = sprintf("if (confirm('%s')) ", addslashes($options['confirm'])) . $onclick;
  }

  echo '<button class="', $options['class'],'"
                value="', $value ,'"
                style="', $options['style'] ,'"
                onclick="', $onclick,';">',
         '<span><span>', $value, '</span></span>',
       '</button>';
}


function cq_button_set($buttons, $options = array())
{
  // Setting the default options
  $options = array_merge(array('class' => 'span-17 rounded prepend-1', 'style' => ''), $options);

  echo '<div class="button-set ', $options['class'] ,'" style="', $options['style'] ,'">';
  foreach ($buttons as $button)
  {
    cq_button($button['value'], $button['route'], @$button['options']);
  }
  echo '</div>', '<br class="clear">';
}
