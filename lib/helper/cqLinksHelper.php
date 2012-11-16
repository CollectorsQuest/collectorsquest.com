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
    if (
      ($function = 'link_to_'. sfInflector::underscore(get_class($arguments[0]))) &&
      function_exists($function)
    )
    {
      return call_user_func_array($function, $arguments);
    }
    else
    {
      return call_user_func_array('link_to_model_object', $arguments);
    }
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

    return call_user_func_array('cq_link_to1', $arguments);
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

    return call_user_func_array('cq_link_to2', $arguments);
  }
}

/**
 * @ignore
 */
function cq_link_to2($name, $routeName, $params, $options = array())
{
  $params = array_merge(
    array('sf_route' => $routeName),
    is_object($params) ? array('sf_subject' => $params) : $params
  );

  return cq_link_to1($name, $params, $options);
}

/**
 * @ignore
 */
function cq_link_to1($name, $internal_uri, $options = array())
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

  $html_options['href'] = cq_url_for($internal_uri, $absolute);

  if (isset($html_options['query_string']))
  {
    $html_options['href'] .= '?'.$html_options['query_string'];
    unset($html_options['query_string']);
  }

  if (isset($html_options['anchor']))
  {
    $html_options['href'] .= '#'.$html_options['anchor'];
    unset($html_options['anchor']);
  }

  if (is_object($name))
  {
    if (method_exists($name, '__toString'))
    {
      $name = $name->__toString();
    }
    else
    {
      throw new sfException(sprintf(
        'Object of class "%s" cannot be converted to string (Please create a __toString() method).',
        get_class($name)
      ));
    }
  }

  if (!strlen($name))
  {
    $name = $html_options['href'];
  }

  return content_tag('a', $name, $html_options);
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
      return call_user_func_array('url_for_model_object', $arguments);
    }
  }
  else
  {
    if (is_array($arguments[0]) || '@' == substr($arguments[0], 0, 1) || false !== strpos($arguments[0], '/'))
    {
      // Let's try to add "ref=" to the link
      if (
        is_string($arguments[0]) && ($ref = cq_link_ref()) &&
        !stripos($arguments[0], '?ref=') && !stripos($arguments[0], '&ref=')
      )
      {
        $arguments[0] .= !stripos($arguments[0], '?') ? '?ref='. $ref : '&ref='. $ref;
      }

      return call_user_func_array('url_for1', $arguments);
    }
    else
    {
      // Let's try to add "ref=" to the link
      if (is_array($arguments[1]) && !array_key_exists('ref', $arguments[1]))
      {
        $arguments[1]['ref'] = cq_link_ref();
      }

      return call_user_func_array('url_for2', $arguments);
    }
  }
}

function cq_link_ref($ref = null)
{
  /** @var $sf_context cqContext */
  $sf_context = sfContext::getInstance();

  if ($sf_context->isHomePage())
  {
    $ref = 'hp' . (!empty($ref) ? '_' . $ref : '');
  }
  else if ($sf_context->isCollectionsPage())
  {
    $ref = 'cp' . (!empty($ref) ? '_' . $ref : '');
  }
  else if ($sf_context->isBlogPage())
  {
    $ref = 'bp' . (!empty($ref) ? '_' . $ref : '');
  }
  else if ($sf_context->isVideoPage())
  {
    $ref = 'vp' . (!empty($ref) ? '_' . $ref : '');
  }
  else if ($sf_context->isMarketPage())
  {
    $ref = 'mp' . (!empty($ref) ? '_' . $ref : '');
  }

  return $ref;
}

function link_to_collector($object, $type = 'text', $options = array('link_to' => array(), 'image_tag' => array()))
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

  $title = $collector->getDisplayName();
  $defaults = array(
    'link_to' => array(
      'title' => $title,
      'absolute'=> true
    ),
    'image_tag' => array(
      'alt'    => $title,
      'absolute'=> true
    )
  );
  $options = _cq_parse_options($options, $defaults);

  if (array_key_exists('truncate', $options) && strlen($title) > $options['truncate'])
  {
    $title = truncate_text($title, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  $route = route_for_collector($collector);
  switch ($type)
  {
    case 'image':
      $link = cq_link_to(image_tag_collector($collector, '100x100', $options['image_tag']), $route, $options['link_to']);
      break;
    case 'text':
    default:
      $link = cq_link_to($title, $route, $options['link_to']);
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
  $route = null;

  if ($collector)
  {
    $route = '@collector_by_slug?id=' . $collector->getId() . '&slug=' . $collector->getSlug();
    $route .= ($ref = cq_link_ref()) ? '&ref='. $ref : '';
  }

  return $route;
}

/**
 * @param  Collector  $collector
 * @param  string     $redirect_to  Where to redirect to after autologging the Collector
 * @param  boolean    $absolute
 *
 * @return string
 */
function url_for_collector_autologin(Collector $collector = null, $redirect_to = null, $absolute = true)
{
  $route = '@auto_login?hash=' . $collector->getAutoLoginHash() . '&r=' . $redirect_to;

  return ($collector) ? url_for($route, $absolute) : null;
}

function link_to_collection($object, $type = 'text', $options = array('link_to' => array(), 'image_tag' => array()))
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
  $defaults = array(
    'link_to' => array(
      'title' => $title
    ),
    'image_tag' => array(
      'width'  => 150,
      'height' => 150,
      'alt'    => $title,
      'title'  => $title
    )
  );
  $options = _cq_parse_options($options, $defaults);

  if (array_key_exists('truncate', $options))
  {
    $title = truncate_text($title, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  $route = route_for_collection($collection);
  switch ($type)
  {
    case 'image':
      $which = (isset($options['image_tag']['width']) && isset($options['image_tag']['height'])) ?
        $options['image_tag']['width'] . 'x' . $options['image_tag']['height'] :
        '150x150';

      // unset both width and height if any of them is not specified
      if (empty($options['image_tag']['width']) || empty($options['image_tag']['height']))
      {
        unset($options['image_tag']['width']);
        unset($options['image_tag']['height']);
      }

      $image_tag = image_tag_collection($collection, $which, $options['image_tag']);

      $link = cq_link_to($image_tag, $route, $options['link_to']);
      break;
    case 'text':
    default:
      $link = cq_link_to($title, $route, $options['link_to']);
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

  $route .= ($ref = cq_link_ref()) ? '&ref='. $ref : '';

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
  $defaults = array(
    'link_to' => array(
      'title' => $collectible->getName()
    ),
    'image_tag' => array(
      'width'  => 150,
      'height' => 150,
      'alt'    => $collectible->getName(),
      'title'  => $collectible->getName()
    )
  );
  $options = _cq_parse_options($options, $defaults);

  $title = $collectible->getName();
  if (array_key_exists('truncate', $options) && strlen($title) > $options['truncate'])
  {
    $title = truncate_text($title, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  $route = route_for_collectible($collectible);
  switch ($type)
  {
    case 'image':
      $which = (isset($options['image_tag']['width']) && isset($options['image_tag']['height'])) ?
        $options['image_tag']['width'] . 'x' . $options['image_tag']['height'] :
        '150x150';

      // unset both width and height if any of them is not specified
      if (empty($options['image_tag']['width']) || empty($options['image_tag']['height']))
      {
        unset($options['image_tag']['width']);
        unset($options['image_tag']['height']);
      }

      $link = cq_link_to(
        image_tag_collectible($collectible, $which, $options['image_tag']),
        $route, $options['link_to']
      );
      break;
    case 'text':
    default:
      $link = cq_link_to($title, $route, $options['link_to']);
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

  $route  = '@collectible_by_slug?id=' . $id . '&slug=' . $slug;
  $route .= ($ref = cq_link_ref()) ? '&ref='. $ref : '';

  return $route;
}

function link_to_blog_post(wpPost $post, $type = 'text', $options = array('link_to' => array(), 'image_tag' => array()))
{
  $title = $post->getPostTitle();
  $defaults = array(
    'link_to' => array(
      'title' => $title
    )
  );
  $options = _cq_parse_options($options, $defaults);

  if (array_key_exists('truncate', $options) && strlen($title) > $options['truncate'])
  {
    $title = truncate_text($title, $options['truncate'], '...', true);
    unset($options['truncate']);
  }

  switch ($type)
  {
    case 'image':
      $link = null;
      break;
    case 'text':
    default:
      $link = link_to($title, $post->getPostUrl(), $options['link_to']);
      break;
  }

  return $link;
}

function link_to_blog_author(wpUser $author, $type = 'text', $options = array('link_to' => array(), 'image_tag' => array()))
{
  $title = $author->getDisplayName();
  $defaults = array(
    'link_to' => array(
      'title' => $title
    ),
    'image_tag' => array(
      'width'  => 150,
      'height' => 150,
      'alt'    => $title,
      'title'  => $title
    )
  );
  $options = _cq_parse_options($options, $defaults);

  switch ($type)
  {
    case 'image':
      // unset both width and height if any of them is not specified
      if (empty($options['image_tag']['width']) || empty($options['image_tag']['height']))
      {
        unset($options['image_tag']['width']);
        unset($options['image_tag']['height']);
      }

      if (!$avatar_url = $author->getAvatarUrl('40'))
      {
        $avatar_url = 'blog/avatar-' . str_replace(' ', '-', strtolower($author->getDisplayName()));
      }

      $link = link_to(
        cq_image_tag($avatar_url, $options['image_tag']),
        '/blog/author/' . urlencode($author->getUserNicename()) . '/', $options['link_to']
      );
      break;
    case 'text':
    default:
      $link = link_to(
        $title, '/blog/author/' . urlencode($author->getUserLogin()) . '/', $options['link_to']
      );
      break;
  }

  return $link;
}

function link_to_content_category(ContentCategory $category, $type = 'text', $options = array('link_to' => array(), 'image_tag' => array()))
{
  $title = $category->getName();
  $defaults = array(
    'link_to' => array(
      'title' => $title
    )
  );
  $options = _cq_parse_options($options, $defaults);

  $route = url_for('content_category', $category);
  switch ($type)
  {
    case 'image':
      $link = null;
      break;
    case 'text':
    default:
      $link = cq_link_to($title, $route, $options['link_to']);
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

  return cq_link_to($name, $uri, $options);
}

function cq_canonical_url($absolute = true)
{
  /** @var $response cqWebResponse */
  $response = cqContext::getInstance()->getResponse();

  if (!$canonical_url = $response->getCanonicalUrl())
  {
    /** @var $route cqPropelRoute */
    $route = cqContext::getInstance()->getRequest()->getAttribute('sf_route');

    // We want to check if the route object actually exists because this code
    // will be triggered even for 404 pages
    if ($route instanceof cqPropelRoute && ($object = $route->getObject()))
    {
      /** @var $object BaseObject */

      // CollectionCollectibles need to be normalized to Collectibles
      if ($object instanceof CollectionCollectible)
      {
        /** @var $object CollectionCollectible */
        $object = $object->getCollectible();
      }

      if (!$canonical_url = cq_url_for($object, false))
      {
        /** @var $routing cqPatternRouting */
        $routing = cqContext::getInstance()->getRouting();

        $parameters = array(
          'ref' => null,
          'sf_subject' => $object
        );

        /** @var $canonical_url string */
        $canonical_url = url_for($routing->getCurrentRouteName(), $parameters, false);

        // Check if we need to return an absolute URL or not
        if ($canonical_url && $absolute === true)
        {
          $canonical_url = 'http://' . sfConfig::get('app_www_domain') . $canonical_url;
        }
      }
    }
  }

  return $canonical_url;
}

function cq_canonical_tag()
{
  if ($canonical_url = cq_canonical_url(false))
  {
    $canonical_url = (substr($canonical_url, 0, 1) == '@') ? url_for($canonical_url, false) : $canonical_url;
    $options = array('rel' => 'canonical', 'href' => 'http://' . sfConfig::get('app_www_domain') . $canonical_url);

    echo tag('link', $options, true);
  }
}
