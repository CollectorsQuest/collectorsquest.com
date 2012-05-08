<?php

function cq_page_title($h1, $link = null, $options = array())
{
  $default = null !== $link ? array('left' => 8, 'right' => 4) : array();
  $options = array_merge($default, $options);

  if (null === $link)
  {
    $content = sprintf(<<<EAT
  <div class="span12">
    <h1 class="Chivo webfont">%s</h1>
  </div>
EAT
, $h1, $link);
  }
  else
  {
    $content = sprintf(<<<EAT
  <div class="span%d">
    <h1 class="Chivo webfont">%s</h1>
  </div>
  <div class="span%d text-right">%s</div>
EAT
, (int) $options['left'], $h1, (int) $options['right'], $link);
  }

  unset($options['left'], $options['right']);
  $options = array_merge(array('class' => 'row-fluid header-bar'), $options);

  echo content_tag('div', $content, $options);
}

function cq_section_title($h2, $link = null, $options = array())
{
  $default = null !== $link ? array('left' => 8, 'right' => 4) : array();
  $options = array_merge($default, $options);

  if (null === $link)
  {
$content = sprintf(<<<EAT
  <div class="span12">
    <h2 class="Chivo webfont">%s</h2>
  </div>
EAT
, $h2, $link);
  }
  else
  {
$content = sprintf(<<<EAT
  <div class="span%d">
    <h2 class="Chivo webfont">%s</h2>
  </div>
  <div class="span%d text-right">%s</div>
EAT
, (int) $options['left'], $h2, $options['right'], $link);
  }

  unset($options['left'], $options['right']);
  $options = array_merge(array('class' => 'row-fluid section-title'), $options);

  echo content_tag('div', $content, $options);
}

function cq_sidebar_title($h3, $link = null, $options = array())
{
  $default = null !== $link ? array('left' => 9, 'right' => 3) : array();
  $options = array_merge($default, $options);

  if (null === $link)
  {
$content = sprintf(<<<EAT
  <div class="span12">
    <h3 class="Chivo webfont">%s</h3>
  </div>
EAT
, $h3, $link);
  }
  else
  {
$content = sprintf(<<<EAT
  <div class="span%d">
    <h3 class="Chivo webfont">%s</h3>
  </div>
  <div class="span%d text-right">%s&nbsp;</div>
EAT
, (int) $options['left'], $h3, $options['right'], $link);
  }

  unset($options['left'], $options['right']);
  $options = array_merge(array('class' => 'row-fluid sidebar-title'), $options);

  echo content_tag('div', $content, $options);
}
