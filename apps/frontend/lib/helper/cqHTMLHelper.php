<?php

function cq_page_title($h1, $small = null, $options = array())
{
  $small = (is_string($small) && !empty($small)) ? content_tag('small', (string) $small) : null;
  $h1 = content_tag('h1', (string) $h1 . ($small ? '&nbsp;' . $small : ''));
  $options = array_merge(array('class' => 'page-header'), $options);

  echo content_tag('div', $h1, $options);
}
