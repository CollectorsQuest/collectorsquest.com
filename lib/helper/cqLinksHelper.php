<?php

/** @var cqApplicationConfiguration $configuration */
$configuration = sfProjectConfiguration::getActive();
$configuration->loadHelpers(array('Asset', 'Text', 'Url', 'cqGeneral', 'cqImages'));

function link_to_collector($object, $type = 'text', $options = array())
{
  if ($object instanceof Collectible)
  {
    /** @var Collectible $object */
    $collector = $object->getCollector();
  }
  else if ($object instanceof Collection)
  {
    /** @var Collection $object */
    $collector = $object->getCollector();
  }
  else if ($object instanceof Collector)
  {
    $collector = clone $object;
  }
  else
  {
    return null;
  }

  /** @var Collector $collector */

  $display_name = $collector->getDisplayName();
  $options = array_merge($options, array('alt' => $display_name, 'title' => $display_name));

  if (array_key_exists('truncate', $options) && strlen($display_name) > $options['truncate'])
  {
    $display_name = truncate_text($display_name, $options['truncate'], "...", true);
    unset($options['truncate']);
  }

  $url = route_for_collector($collector);
  switch ($type)
  {
    case "collection_image":
      $c = new Criteria();
      $c->add(CollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
      $c->addAscendingOrderByColumn('RAND()');

      if (array_key_exists('collection_category', $options))
      {
        $c->add(CollectionPeer::COLLECTION_CATEGORY_ID, (is_object($options['collection_category'])) ? $options['collection_category']->getId() : $options['collection_category']);
      }

      $collections = $collector->getCollections($c);
      if (is_array($collections))
      {
        /** @var Collection $collection */
        $collection = array_shift($collections);
        if ($collection instanceof Collection)
        {
          $url = route_for_collection($collection);
          $link = link_to_if(!$collector->isFacebookOnly(), image_tag_collection($collection, '100x100', array_merge(array('width' => 100, 'height' => 100), $options)), $url, $options);
        }
      }
      break;
    case 'stack':
      $options = array_merge($options, array('width' => 64, 'height' => 64));
      $link = sprintf(
        '<div style="width: 80px; height: 80px; background: transparent url(/images/legacy/avatar-bgr.png) no-repeat; padding: 13px 0 0 13px;">%s</div>',
        link_to(image_tag_collector($collector, '100x100', $options), $url, $options)
      );
      break;
    case "image":
      $link = link_to_if(!$collector->isFacebookOnly(), image_tag_collector($collector, '100x100', $options), $url, $options);
      break;
    case "text":
    default:
      $link = link_to_if(!$collector->isFacebookOnly(), $display_name, $url, $options);
      break;
  }

  return isset($link) ? $link : null;
}

function url_for_collector(Collector $collector = null, $absolute = false)
{
  return ($collector) ? url_for(route_for_collector($collector), $absolute) : null;
}

function route_for_collector(Collector $collector = null)
{
  return ($collector) ?
      '@collector_by_id?id='. $collector->getId() .'&slug='. $collector->getSlug() :
      null;
}

function link_to_collection($object, $type = 'text', $options = array())
{
  if ($object instanceof Collectible)
  {
    /** @var $object Collectible */
    $collection = $object->getCollection();
  }
  else if ($object instanceof Collection)
  {
    /** @var $object Collection */
    $collection = clone $object;
  }
  else
  {
    return null;
  }

  $title = trim($collection->getName());
  $options = array_merge(
    array(
      'route' => route_for_collection($collection),
      'width' => 150, 'height' => 150, 'alt' => $title, 'title' => $title
    ),
    $options
  );

  if (array_key_exists('truncate', $options))
  {
    $title = truncate_text($title, $options['truncate'], "...", true);
    unset($options['truncate']);
  }

  switch ($type)
  {
    case 'image':
      $which = (isset($options['width']) && isset($options['height'])) ? $options['width'].'x'.$options['height'] : '150x150';
      $image_tag = image_tag_collection($collection, $which, $options);

      $link = link_to($image_tag, $options['route']);
      break;
    case 'text':
    default:
      $link = link_to($title, $options['route'], $options);
      break;
  }

  return $link;
}

function url_for_collection(Collection $collection = null, $absolute = false)
{
  return ($collection) ?
      url_for(route_for_collection($collection), $absolute) :
      null;
}

function route_for_collection(Collection $collection = null)
{
  $route = null;

  if ($collection instanceof CollectionDropbox && ($collector = $collection->getCollector()))
  {
    $route = '@dropbox_by_slug?collector_id='. $collector->getId() .'&collector_slug='. $collector->getSlug();
  }
  else if ($collection instanceof Collection)
  {
    $route = '@collection_by_slug?id='. $collection->getId() .'&slug='. $collection->getSlug();
  }

  return $route;
}

function link_to_collectible(Collectible $collectible, $type = 'text', $options = array())
{
  $options = array_merge(
    array('width' => 150, 'height' => 150, 'alt' => $collectible->getName(), 'title' => $collectible->getName(), 'class' => 'thumbnail'),
    $options
  );

  if (empty($options['width']) || empty($options['height']))
  {
    unset($options['width']);
    unset($options['height']);
  }

  $route = route_for_collectible($collectible);
  switch ($type)
  {
    case 'image':
      $which = (isset($options['width']) && isset($options['height'])) ? $options['width'].'x'.$options['height'] : '150x150';
      $link = link_to(image_tag_collectible($collectible, $which, $options), $route, $options);
      break;
    case 'text':
    default:
      $link = link_to($collectible->getName(), $route, $options);
      break;
  }

  return $link;
}

function url_for_collectible(Collectible $collectible = null, $absolute = false)
{
  return ($collectible) ?
      url_for(route_for_collectible($collectible), $absolute) :
      null;
}

function route_for_collectible(Collectible $collectible = null)
{
  return ($collectible) ?
      '@collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug() :
      null;
}

function link_to_video(Video $video, $type = 'text', $options = array())
{
  $title = $video->getTitle();
  $options = array_merge($options, array('title' => $title));

  if (array_key_exists('truncate', $options) && strlen($title) > $options['truncate'])
  {
    $title = truncate_text($title, $options['truncate'], "...", true);
    unset($options['truncate']);
  }

  switch ($type)
  {
    case "image":
      return link_to(image_tag($video->getThumbLargeSrc(), $options), url_for_video($video), $options);
      break;
    case "text":
    default:
      return link_to($title, url_for_video($video), $options);
      break;
  }
}

function url_for_video(Video $video)
{
  return url_for('@video_by_id?id='.$video->getId().'&slug='.$video->getSlug());
}

function link_to_featured_week(Featured $featured_week, $type = 'text', $options = array())
{
  $title = $featured_week->title;
  $options = array_merge($options, array('title' => $title));

  if (array_key_exists('truncate', $options) && strlen($title) > $options['truncate'])
  {
    $title = truncate_text($title, $options['truncate'], "...", true);
    unset($options['truncate']);
  }

  switch ($type)
  {
    case "text":
    default:
      return link_to($title, url_for_featured_week($featured_week), $options);
      break;
  }
}

function url_for_featured_week(Featured $featured_week)
{
  return url_for('@featured_week?id='. $featured_week->getId() .'&slug='. Utf8::slugify($featured_week->title));
}

function link_to_blog_post(wpPost $post)
{
  return link_to($post->getPostTitle(), $post->getPostUrl());
}
