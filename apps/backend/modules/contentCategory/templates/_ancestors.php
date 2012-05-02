<?php

$ancestors = $ContentCategory->getAncestors();

$ancestor_links = array_map(function($content_category) {
  return link_to($content_category, array(
      'sf_route' => 'content_category_edit',
      'sf_subject' => $content_category,
  ));
}, $ancestors ? $ancestors->getArrayCopy() : array());

echo implode(' / ', $ancestor_links);