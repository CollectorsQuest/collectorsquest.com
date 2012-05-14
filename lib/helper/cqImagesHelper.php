<?php

/** @var cqApplicationConfiguration $configuration */
$configuration = sfProjectConfiguration::getActive();
$configuration->loadHelpers(array('Asset', 'Tag'));

/**
 * Returns an HTML image tag for a Collector object
 *
 * @see image_tag_multimedia()
 *
 * @param  Collector  $collector
 * @param  string     $which
 * @param  array      $options
 *
 * @return string
 */
function image_tag_collector($collector, $which = '100x100', $options = array())
{
  if (!($collector instanceof Collector))
  {
    return image_tag(sfConfig::get('sf_app') .'/multimedia/Collector/'. $which .'.png', $options);
  }

  $options = array_merge(
    array('alt_title' => $collector->getDisplayName(), 'slug' => $collector->getSlug()),
    is_array($options) ? $options : array()
  );

  $multimedia = $collector->getPhoto();
  $image_tag = image_tag_multimedia($multimedia, $which, $options);

  if (empty($image_tag))
  {
    $image_tag = image_tag(sfConfig::get('sf_app') .'/multimedia/Collector/'. $which .'.png', $options);
  }

  return $image_tag;
}

/**
 * @param Collector $collector
 * @param string $which
 * @param array $options
 *
 * @return null|string
 */
function src_tag_collector($collector, $which = '100x100', $options = array())
{
  $default = '/images/'. sfConfig::get('sf_app') .'/multimedia/Collector/'. $which .'.png';

  if (!($collector instanceof Collector))
  {
    if (isset($options['absolute']) && true === $options['absolute'])
    {
      $default = sfConfig::get('app_cq_multimedia_domain') . $default;
    }

    return $default;
  }

  $multimedia = $collector->getPhoto();
  $src_tag    = src_tag_multimedia($multimedia, $which, $options);

  if (empty($src_tag))
  {
    $src_tag = $default;
  }

  return $src_tag;
}

/**
 * Returns an HTML image tag for a Collection object
 *
 * @see image_tag_multimedia()
 *
 * @param  Collection  $collection
 * @param  string      $which
 * @param  array       $options
 *
 * @return string
 */
function image_tag_collection($collection, $which = '150x150', $options = array())
{
  if (is_null($collection) || !($collection instanceof Collection))
  {
    $class = is_object($collection) ? get_class($collection) : 'Collection';
    return image_tag(sfConfig::get('sf_app') .'/multimedia/'. $class .'/'. $which .'.png');
  }

  $options = array_merge(
    array('alt_title' => $collection->getName(), 'slug' => $collection->getSlug()),
    $options
  );

  $multimedia = $collection->getThumbnail();
  $image_tag = image_tag_multimedia($multimedia, $which, $options);

  if (empty($image_tag))
  {
    $image_tag = image_tag(sfConfig::get('sf_app') .'/multimedia/'. get_class($collection) .'/'. $which .'.png', $options);
  }

  return $image_tag;
}

/**
 * Returns an HTML image tag for a Collectible object
 *
 * @see image_tag_multimedia()
 *
 * @param  Collectible|CollectionCollectible  $collectible
 * @param  string       $which
 * @param  array        $options
 *
 * @return string
 */
function image_tag_collectible($collectible, $which = null, $options = array())
{
  if ($which === null)
  {
    switch (sfConfig::get('sf_app'))
    {
      case 'frontend':
        $which = '190x150';
        break;

      case 'legacy':
      default:
        $which = '150x150';
        break;
    }
  }

  $default = sfConfig::get('sf_app') . '/multimedia/Collectible/'. $which .'.png';

  if ($collectible instanceof CollectionCollectible)
  {
    $collectible = $collectible->getCollectible();
  }

  if (!$collectible instanceof Collectible)
  {
    return image_tag($default, $options);
  }

  $options = array_merge(
    array('alt_title' => $collectible->getName(), 'slug' => $collectible->getSlug()),
    $options
  );

  $multimedia = $collectible->getPrimaryImage();
  $image_tag = image_tag_multimedia($multimedia, $which, $options);

  if (empty($image_tag))
  {
    $image_tag = image_tag($default, $options);
  }

  return $image_tag;
}

/**
 * @param  Collectible  $collectible
 * @param  string       $which
 *
 * @return null|string
 */
function src_tag_collectible($collectible, $which = '150x150')
{
  $multimedia = $collectible->getPrimaryImage();
  $src_tag = src_tag_multimedia($multimedia, $which);

  if (empty($src_tag))
  {
    $src_tag = '/images/'. sfConfig::get('sf_app') .'/multimedia/Collectible/'. $which .'.png';
  }

  return $src_tag;
}

/**
 * @param  wpPost  $wp_post
 * @param  string  $which
 *
 * @return null|string
 */
function image_tag_wp_post($wp_post, $which = '150x150')
{
  list($width, $height) = explode('x', $which);

  if (!$src = $wp_post->getPostThumbnail())
  {
    $src = '/images/'. sfConfig::get('sf_app') .'/multimedia/wpPost/'. $which .'.png';
  }

  return image_tag($src, array('width' => $width, 'height' => $height));
}


/**
 * Returns an HTML image tag of the multimedia object
 *
 * @param  iceModelMultimedia  $multimedia  The multimedia object
 * @param  string      $which       ['thumbnail', 'original', 'WIDTHxHEIGHT']
 * @param  array       $options     Options for the <img> HTML element
 *
 * @see image_tag()
 *
 * @return string
 */
function image_tag_multimedia($multimedia, $which, $options = array())
{
  if (is_null($multimedia) || !($multimedia instanceof iceModelMultimedia))
  {
    return null;
  }

  $image_info = $multimedia->getImageInfo($which);
  $options = array_merge(
    array('alt_title' => '', 'width' => $image_info['width'], 'height' => $image_info['height']),
    $options
  );

  if (isset($options['max_width']) || isset($options['max_height']))
  {
    if(list($w, $h) = @getimagesize($multimedia->getAbsolutePath($which)))
    {
      $mw = @$options['max_width'];
      $mh = @$options['max_height'];
      foreach(array('w','h') as $v)
      {
        $m = "m{$v}";

        if(${$v} > ${$m} && ${$m}) { $o = ($v == 'w') ? 'h' : 'w';
        $r = ${$m} / ${$v}; ${$v} = ${$m}; ${$o} = ceil(${$o} * $r); }
      }
    }

    $options['width']  = $w;
    $options['height'] = $h;
  }

  $options = array_filter($options);
  $src = src_tag_multimedia($multimedia, $which, $options);

  // Unsetting all options which should not make it to the html <img/> tag
  unset($options['max_width'], $options['max_height'], $options['slug']);

  return image_tag($src, $options);
}

/**
 * @param  iceModelMultimedia  $multimedia
 * @param  string      $which
 * @param  array       $options
 *
 * @return null|string
 */
function src_tag_multimedia($multimedia, $which = 'thumb', $options = array())
{
  if (!$multimedia instanceof iceModelMultimedia)
  {
    return null;
  }

  $src = sprintf(
    '%s/%s/%s/%s-%d.%s?%d',
    sfConfig::get('app_cq_multimedia_domain'),
    $multimedia->getType(), $which,
    (!empty($options['slug'])) ? $options['slug'] : strtolower($multimedia->getModel()),
    $multimedia->getId(), $multimedia->getFileExtension(), $multimedia->getCreatedAt('U')
  );

  return $src;
}
