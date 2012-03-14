<?php

function cq_tags_for_collector(Collector $collector)
{
  $tags = array();

  foreach ($collector->getTags() as $tag => $weight)
  {
    $tags[] = link_to($tag, '@tag?tag='. $tag, array('title' => sprintf('Explore incredible and unique %s collectors!', $tag)));
  }

  return implode(', ', $tags);
}

function cq_tags_for_collectible(Collectible $collectible)
{
  $tags = array();

  foreach ($collectible->getTags() as $tag)
  {
    $tags[] = link_to($tag, '@tag?tag='. $tag, array('title' => sprintf('Explore incredible and unique %s collectibles!', $tag)));
  }

  return implode(', ', $tags);
}

function cq_tags_for_collection(Collection $collection)
{
  $tags = array();

  foreach ($collection->getTags() as $tag)
  {
    $tags[] = link_to($tag, '@tag?tag='. $tag, array('title' => sprintf('Explore incredible and unique %s collections!', $tag)));
  }

  return implode(', ', $tags);
}

function cq_escape_tag($tag)
{
  $text = strtolower($tag);

  // strip all non word chars
  $text = preg_replace('/\W/', ' ', $text);
  // replace all white space sections with a dash
  $text = preg_replace('/\ +/', '-', $text);
  // replace / with a dash
  $text = str_replace('/', '-', $text);
  // trim dashes
  $text = trim($text, '-');

  return $text;
}

function cq_unescape_tag($tag)
{
  return str_replace('-', ' ', $tag);
}
