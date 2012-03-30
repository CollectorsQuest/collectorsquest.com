<?php

function cq_page_title($h1, $small = null, $options = array())
{
  $small = (is_string($small) && !empty($small)) ? content_tag('small', (string) $small) : null;
  $h1 = content_tag('h1', (string) $h1 . ($small ? '&nbsp;' . $small : ''));
  $options = array_merge(array('class' => 'page-header'), $options);

  echo content_tag('div', $h1, $options);
}

function cq_section_title($h2, $link = null, $options = array())
{
  $content = sprintf(<<<EAT
  <div class="span9">
    <h2 style="color: #CC0000;">%s</h2>
  </div>
  <div class="span3" style="text-align: right; padding-top: 15px;">
    %s
  </div>
EAT
, $h2, $link);

  $options = array_merge(array('class' => 'row-fluid section-header'), $options);

  echo content_tag('div', $content, $options);
}
