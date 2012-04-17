<?php

function cq_page_title($h1, $small = null, $options = array())
{
  $small = (is_string($small) && !empty($small)) ? content_tag('small', (string) $small) : null;
  $h1 = content_tag('h1', (string) $h1 . ($small ? '&nbsp;' . $small : ''), array('class' => 'Chivo webfont'));
  $options = array_merge(array('class' => 'header-bar'), $options);

  echo content_tag('div', $h1, $options);
}

function cq_section_title($h2, $link = null, $options = array())
{
  $content = sprintf(<<<EAT
  <div class="span9">
    <h2 class="Chivo webfont">%s</h2>
  </div>
  <div class="span3">%s</div>
EAT
, $h2, $link);

  $options = array_merge(array('class' => 'row-fluid section-title'), $options);

  echo content_tag('div', $content, $options);
}

function cq_sidebar_title($h3, $link = null, $options = array())
{
  $options = array_merge(array('left' => 9, 'right' => 3), $options);

  $content = sprintf(<<<EAT
<div class="span%d">
  <h3 class="Chivo webfont">%s</h3>
</div>
<div class="span%d text-right">%s&nbsp;</div>
EAT
, (int) $options['left'], $h3, $options['right'], $link);

  $options = array_merge(array('class' => 'row-fluid sidebar-title'), $options);
  echo content_tag('div', $content, $options);
}
