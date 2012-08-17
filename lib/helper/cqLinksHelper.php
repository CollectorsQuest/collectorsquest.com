<?php

/** @var cqApplicationConfiguration $configuration */
$configuration = sfProjectConfiguration::getActive();
$configuration->loadHelpers(array('Asset', 'Text', 'Url', 'cqImages'));

/**
 * @param array $options
 */
function _cq_add_requires_login_class_to_options(&$options)
{
  $options['class'] = isset($options['class']) ? $options['class'] . ' requires-login' : 'requires-login';
}

/**
 * Wrapper around link_to that will add a "requires-login" class
 * to the returned link if the target route is secure
 *
 * @see link_to()
 * @return mixed
 */
function cq_link_to()
{
  // for BC with 1.1
  $arguments = func_get_args();

  if ($arguments[0] instanceof BaseObject)
  {
    return call_user_func_array('link_to_'. get_class($arguments[0]), $arguments);
  }
  else if (
    empty($arguments[1]) || is_array($arguments[1]) ||
    '@' == substr($arguments[1], 0, 1) || false !== strpos($arguments[1], '/')
  )
  {
    if (!array_key_exists(2, $arguments))
    {
      $arguments[2] = array();
    }

    if (cqLinkUtils::getInstance()->isSecureRoute($arguments[1]))
    {
      _cq_add_requires_login_class_to_options($arguments[2]);
    }

    if (!isset($arguments[2]['absolute']))
    {
      $arguments[2]['absolute'] = true;
    }

    return call_user_func_array('link_to1', $arguments);
  }
  else
  {
    if (!array_key_exists(2, $arguments))
    {
      $arguments[2] = array();
    }

    if (cqLinkUtils::getInstance()->isSecureRoute($arguments[1]))
    {
      _cq_add_requires_login_class_to_options($arguments[3]);
    }
    if (!isset($arguments[3]['absolute']))
    {
      $arguments[3]['absolute'] = true;
    }

    return call_user_func_array('link_to2', $arguments);
  }
}

/**
 * @see url_for()
 */
function cq_url_for()
{
  // for BC with 1.1
  $arguments = func_get_args();

  if ($arguments[0] instanceof BaseObject)
  {
    if (
      ($function = 'url_for_'. sfInflector::underscore(get_class($arguments[0]))) &&
      function_exists($function)
    )
    {
      return call_user_func_array($function, $arguments);
    }
    else
    {
      return url_for_model_object($arguments[0]);
    }
  }
  else
  {
    return call_user_func_array('url_for', $arguments);
  }
}

function link_to_collector($object, $type = 'text', $options = array(), $image_options = array())
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
  $alt = isset($image_options['alt']) ?
    $image_options['alt'] :
    (isset($options['alt']) ? $options['alt'] : $display_name);

  $options = array_merge(array(
    'absolute'=> true,
    'title'   => $display_name
  ), $options);

  $image_options = array_merge(array(
    'alt'     => $alt,
    'absolute'=> true,
  ), $image_options);

  unset($options['alt'], $image_options['title']);

  if (array_key_exists('truncate', $options) && strlen($display_name) > $options['truncate'])
  {
    $display_name = truncate_text($display_name, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  $url = route_for_collector($collector);
  switch ($type)
  {
    case 'image':
      $link = link_to(image_tag_collector($collector, '100x100', $image_options), $url, $options);
      break;
    case 'text':
    default:
      $link = link_to($display_name, $url, $options);
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
      '@collector_by_slug?id=' . $collector->getId() . '&slug=' . $collector->getSlug() :
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

  $title   = trim($collection->getName());
  $options = array_merge(
    array(
      'width'  => 150,
      'height' => 150,
      'alt'    => $title,
      'title'  => $title
    ),
    $options
  );

  if (array_key_exists('truncate', $options))
  {
    $title = truncate_text($title, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  $route = route_for_collection($collection);
  switch ($type)
  {
    case 'image':
      $which = (isset($options['width']) && isset($options['height'])) ?
        $options['width'] . 'x' . $options['height'] :
        '150x150';

      $image_tag = image_tag_collection($collection, $which, $options);

      $link = link_to($image_tag, $route);
      break;
    case 'text':
    default:
      $link = link_to($title, $route, $options);
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

/**
 * @see url_for_collection()
 */
function url_for_collector_collection()
{
  return call_user_func_array('url_for_collection', func_get_args());
}

function route_for_collection(Collection $collection = null)
{
  $route = null;

  if ($collection instanceof CollectionDropbox && ($collector = $collection->getCollector()))
  {
    $route = '@dropbox_by_slug?collector_id=' . $collector->getId() . '&collector_slug=' . $collector->getSlug();
  }
  else if ($collection instanceof Collection)
  {
    $route = '@collection_by_slug?id=' . $collection->getId() . '&slug=' . $collection->getSlug();
  }

  return $route;
}

/**
 * @param  Collectible|CollectionCollectible  $collectible
 * @param  string  $type
 * @param  array  $options
 *
 * @return string
 */
function link_to_collectible($collectible, $type = 'text', $options = array('link_to' => array(), 'image_tag' => array()))
{
  //used for second merge function - how should we avoid merging without this?
  $options_copy = $options;
  if (!empty($options['link_to']))
  {
    $options['link_to'] = array_merge(
      array(
        'title'  => $collectible->getName()
      ),
      $options['link_to']
    );
  }
  else
  {
    $options['link_to'] = array_merge(
      array(
        'title'  => $collectible->getName()
      ),
      $options
    );
  }

  if (!empty($options['image_tag']))
  {
    $options['image_tag'] = array_merge(
      array(
        'width'  => 150,
        'height' => 150,
        'alt'    => $collectible->getName(),
        'title'  => $collectible->getName()
      ),
      $options['image_tag']
    );
  }
  else
  {
    $options['image_tag'] = array_merge(
      array(
        'width'  => 150,
        'height' => 150,
        'alt'    => $collectible->getName(),
        'title'  => $collectible->getName()
      ),
      $options_copy
    );
  }

  // unset empty parameters
  if (empty($options['link_to']['width']) || empty($options['link_to']['height']))
  {
    unset($options['link_to']['width']);
    unset($options['link_to']['height']);
  }
  if (empty($options['link_to']['alt']))
  {
    unset($options['link_to']['alt']);
  }
  if (empty($options['image_tag']['width']) || empty($options['image_tag']['height']))
  {
    unset($options['image_tag']['width']);
    unset($options['image_tag']['height']);
  }
  foreach ($options['image_tag'] as $option)
  {
    if (empty($option))
    {
      unset($option);
    }
  }
  foreach ($options['link_to'] as $option)
  {
    if (empty($option))
    {
      unset($option);
    }
  }

  $title = $collectible->getName();
  if (array_key_exists('truncate', $options['link_to']) && strlen($title) > $options['link_to']['truncate'])
  {
    $title = truncate_text($title, $options['link_to']['truncate'], '...', true);
    unset($options['link_to']['truncate']);
  }

  $route = route_for_collectible($collectible);
  switch ($type)
  {
    case 'image':
      $which = (isset($options['image_tag']['width']) && isset($options['image_tag']['height'])) ?
        $options['image_tag']['width'] . 'x' . $options['image_tag']['height'] :
        '150x150';

      $link = link_to(image_tag_collectible($collectible, $which, $options['image_tag']), $route, $options['link_to']);
      break;
    case 'text':
    default:
      $link = link_to($title, $route, $options['link_to']);
      break;
  }

  return $link;
}

/**
 * @param  null|Collectible|CollectionCollectible  $collectible
 * @param  boolean  $absolute
 *
 * @return null|string
 */
function url_for_collectible($collectible = null, $absolute = true)
{
  return ($collectible) ?
      url_for(route_for_collectible($collectible), $absolute) :
      null;
}

/**
 * @see url_for_collectible()
 */
function url_for_collection_collectible()
{
  return call_user_func_array('url_for_collectible', func_get_args());
}

/**
 * @param  Collectible|CollectionCollectible  $collectible
 * @return null|string
 */
function route_for_collectible($collectible = null)
{
  if ($collectible instanceof CollectionCollectible)
  {
    $collection_id = $collectible->getCollectionId();

    $id   = $collectible->getCollectibleId();
    $slug = preg_replace('/[\d\-]+$/', '', $collectible->getSlug()) . '-c' . $collection_id;
  }
  else if ($collectible instanceof Collectible)
  {
    $id   = $collectible->getId();
    $slug = $collectible->getSlug();
  }
  else
  {
    return null;
  }

  return '@collectible_by_slug?id=' . $id . '&slug=' . $slug;
}

function link_to_video(Video $video, $type = 'text', $options = array())
{
  $title   = $video->getTitle();
  $options = array_merge($options, array('title' => $title));

  if (array_key_exists('truncate', $options) && strlen($title) > $options['truncate'])
  {
    $title = truncate_text($title, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  switch ($type)
  {
    case 'image':
      return link_to(image_tag($video->getThumbLargeSrc(), $options), url_for_video($video), $options);
      break;
    case 'text':
    default:
      return link_to($title, url_for_video($video), $options);
      break;
  }
}

function url_for_video(Video $video)
{
  return url_for('@video_by_id?id=' . $video->getId() . '&slug=' . $video->getSlug());
}

function link_to_featured_week(Featured $featured_week, $type = 'text', $options = array())
{
  $title   = $featured_week->title;
  $options = array_merge($options, array('title' => $title));

  if (array_key_exists('truncate', $options) && strlen($title) > $options['truncate'])
  {
    $title = truncate_text($title, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  switch ($type)
  {
    case 'text':
    default:
      return link_to($title, url_for_featured_week($featured_week), $options);
      break;
  }
}

function url_for_featured_week(Featured $featured_week)
{
  return url_for('@featured_week?id=' . $featured_week->getId() . '&slug=' . Utf8::slugify($featured_week->title));
}

function link_to_blog_post(wpPost $post, $type = 'text', $options = array())
{
  $title = $post->getPostTitle();
  if (array_key_exists('truncate', $options) && strlen($title) > $options['truncate'])
  {
    $title = truncate_text($title, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  return link_to($title, $post->getPostUrl(), $options);
}

function link_to_blog_author(wpUser $author, $type = 'text', $options = array())
{
  switch ($type)
  {
    case 'image':
      if (!$avatar_url = $author->getAvatarUrl('40'))
      {
        $avatar_url = 'blog/avatar-' . str_replace(' ', '-', strtolower($author->getDisplayName()));
      }

      return link_to(
        cq_image_tag($avatar_url, $options),
        '/blog/author/' . urlencode($author->getUserNicename()) . '/'
      );
      break;
    case 'text':
    default:
      return link_to(
        $author->getDisplayName(),
        '/blog/author/' . urlencode($author->getUserLogin()) . '/'
      );
      break;
  }
}

function link_to_collection_category(CollectionCategory $category, $type = 'text', $options = array())
{
  $options = array_merge(
    array(
      'alt'   => $category->getName(),
      'title' => $category->getName()
    ),
    $options
  );

  if (empty($options['width']) || empty($options['height']))
  {
    unset($options['width']);
    unset($options['height']);
  }

  $route = url_for('collections_by_category', $category);
  switch ($type)
  {
    case 'image':
      $link = null;
      break;
    case 'text':
    default:
      $link = link_to($category->getName(), $route, $options);
      break;
  }

  return $link;
}

function link_to_content_category(ContentCategory $category, $type = 'text', $options = array())
{
  $options = array_merge(
    array(
      'alt'   => $category->getName(),
      'title' => $category->getName()
    ),
    $options
  );

  if (empty($options['width']) || empty($options['height']))
  {
    unset($options['width']);
    unset($options['height']);
  }

  $route = url_for('content_category', $category);
  switch ($type)
  {
    case 'image':
      $link = null;
      break;
    case 'text':
    default:
      $link = link_to($category->getName(), $route, $options);
      break;
  }

  return $link;
}

/**
 * Try to provide an url for a model object's cononical url
 *
 * @param     BaseObject $model_object
 * @param     boolean $absolute
 * @return    string|boolean
 *
 * @see       cqFrontWebController::genUrlForModelObject()
 */
function url_for_model_object(BaseObject $model_object, $absolute = false)
{
  /** @var $controller cqFrontWebController */
  $controller = cqContext::getInstance()->getController();

  return $controller->genUrlForModelObject($model_object, $absolute);
}

/**
 * Try to provide a link to a model object's canonical url
 *
 * @param     mixed $name
 * @param     BaseObject $model_object
 * @param     array $options
 * @return    string
 *
 * @see       cqFrontWebController::genUrlForModelObject()
 */
function link_to_model_object($name, BaseObject $model_object, $options = array())
{
  $html_options = _parse_attributes($options);

  $html_options = _convert_options_to_javascript($html_options);

  $absolute = false;
  if (isset($html_options['absolute_url']))
  {
    $html_options['absolute'] = $html_options['absolute_url'];
    unset($html_options['absolute_url']);
  }
  if (isset($html_options['absolute']))
  {
    $absolute = (boolean) $html_options['absolute'];
    unset($html_options['absolute']);
  }

  $uri = url_for_model_object($model_object, $absolute);
  $uri = false == $uri ? '#' : $uri;

  return link_to1($name, $uri, $options);
}

function cq_canonical_url()
{
  /** @var $response cqWebResponse */
  $response = cqContext::getInstance()->getResponse();

  if (!$canonical_url = $response->getCanonicalUrl())
  {
    /** @var $route cqPropelRoute */
    $route = cqContext::getInstance()->getRequest()->getAttribute('sf_route');

    if ($route instanceof cqPropelRoute)
    {
      /** @var $object BaseObject */
      $object = $route->getObject();

      // CollectionCollectibles need to be normalized to Collectibles
      if ($object instanceof CollectionCollectible)
      {
        /** @var $object CollectionCollectible */
        $object = $object->getCollectible();
      }

      /** If we cannot generate a */
      if (!$canonical_url = cq_url_for($object, false))
      {
        /** @var $routing cqPatternRouting */
        $routing = cqContext::getInstance()->getRouting();

        $canonical_url = url_for($routing->getCurrentRouteName(), $object, false);
      }
    }
  }

  if (!empty($canonical_url))
  {
    $canonical_url = (substr($canonical_url, 0, 1) == '@') ? url_for($canonical_url) : $canonical_url;
    $options = array('rel' => 'canonical', 'href' => 'http://' . sfConfig::get('app_www_domain') . $canonical_url);

    echo tag('link', $options, true);
  }
}
