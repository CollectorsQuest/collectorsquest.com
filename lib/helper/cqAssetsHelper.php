<?php

/**
 * @see include_http_metas()
 */
function cq_include_http_metas()
{
  /** @var $response sfWebResponse */
  $response = cqContext::getInstance()->getResponse();

  $i = 0;
  foreach ((array) $response->getHttpMetas() as $httpequiv => $value)
  {
    echo ($i++ > 0) ? '  ' : '';
    echo tag('meta', array('http-equiv' => $httpequiv, 'content' => $value), true)."\n";
  }
}

/**
 * @see include_metas()
 */
function cq_include_metas()
{
  /** @var $context sfContext */
  $context = cqContext::getInstance();

  /** @var $response sfWebResponse */
  $response = $context->getResponse();

  /** @var $i18n sfI18N */
  $i18n = sfConfig::get('sf_i18n') ? $context->getI18N() : null;

  $i = 0;
  foreach ($response->getMetas() as $name => $content)
  {
    if (substr($content, 0, 2) == 'a:' && ($u = unserialize($content)))
    {
      $content = $u;
    }
    $content = (array) $content;

    foreach ($content as $_content)
    {
      // Do not display empty meta tags
      if ($_content === '' || $_content === null)
      {
        continue;
      }

      // Get rid of new lines and extra spaces in the content of the meta
      $_content = preg_replace('/\s+/iu', ' ', $_content);

      echo ($i++ > 0) ? '  ' : '';
      $key = substr($name, 0, 3) === 'og:' ? 'property' : 'name';
      echo tag(
        'meta',
        array($key => $name, 'content' => null === $i18n ? $_content : $i18n->__($_content)),
        true
      ) . "\n";
    }
  }
}

/**
 * @see include_title()
 */
function cq_include_title()
{
  /** @var $response sfWebResponse */
  $response = cqContext::getInstance()->getResponse();

  /** @var $title string */
  $title = $response->getTitle();

  // Fixing double htmlspecialchars
  $title = str_replace('&amp;', '&', $title);

  echo $title ? content_tag('title', $title)."\n" : null;
}

function cq_htmLaw_tag($html, $config = array())
{
  require_once __DIR__ .'/../../plugins/iceLibsPlugin/lib/vendor/HtmLawed.php';

  return htmLawed($html, $config);
}

function cq_image_tag($source, $options = array())
{
  // Do we want an absolute path for the image?
  $absolute = isset($options['absolute']) ? $options['absolute'] : true;

  // Hardcoding for now to not use any versioning for images
  $options['rev'] = null;

  // Do we want to version it?
  $source = (!isset($options['rev']) || (isset($options['rev']) && $options['rev'] === null)) ?
    $source : $source .'?rev='. (defined('SVN_REVISION') ? SVN_REVISION : 0);

  // We do not need this option anymore
  unset($options['rev']);

  return image_tag(cq_image_src($source, $absolute), $options);
}

function cq_image_src($image, $absolute = true)
{
  return $absolute === true ?
    '//'. sfConfig::get('app_static_domain') .'/images/'. ltrim($image, '/') :
    '/images/'. ltrim($image, '/');
}

function cq_stylesheet_src($stylesheet)
{
  return '//'. sfConfig::get('app_static_domain') .'/css/'.
         $stylesheet .'?rev='. (defined('SVN_REVISION') ? SVN_REVISION : 0);
}

function cq_javascript_src($javascript)
{
  return '//'. sfConfig::get('app_static_domain') .'/js/'.
         $javascript .'?rev='. (defined('SVN_REVISION') ? SVN_REVISION : 0);
}

function cq_include_stylesheets()
{
  // Do not combine or do anything special if not in Production
  if (SF_ENV !== 'prod')
  {
    include_stylesheets();

    return;
  }

  /** @var $response sfWebResponse */
  $response = cqContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.stylesheets_included', true);

  // Get all the stylesheets
  $_stylesheets = $response->getStylesheets(sfWebResponse::ALL);

  if ($response->getStylesheets(sfWebResponse::FIRST))
  {
    $stylesheets = array();
    foreach (array_keys($response->getStylesheets(sfWebResponse::FIRST)) as $stylesheet)
    {
      if ($stylesheet[0] != '/' || substr($stylesheet, 0, 5) == '/css/')
      {
        $stylesheets[] = $stylesheet;
        unset($_stylesheets[$stylesheet[0]], $_stylesheets[$stylesheet]);
      }
    }

    cq_combine_stylesheets($stylesheets);
  }

  if ($response->getStylesheets(sfWebResponse::MIDDLE))
  {
    $stylesheets = array();
    foreach (array_keys($response->getStylesheets(sfWebResponse::MIDDLE)) as $stylesheet)
    {
      if ($stylesheet[0] != '/' || substr($stylesheet, 0, 5) == '/css/')
      {
        $stylesheets[] = $stylesheet;
        unset($_stylesheets[$stylesheet[0]], $_stylesheets[$stylesheet]);
      }
    }

    cq_combine_stylesheets($stylesheets);
  }

  if ($response->getStylesheets(sfWebResponse::LAST))
  {
    $stylesheets = array();
    foreach (array_keys($response->getStylesheets(sfWebResponse::LAST)) as $stylesheet)
    {
      if ($stylesheet[0] != '/' || substr($stylesheet, 0, 5) == '/css/')
      {
        $stylesheets[] = $stylesheet;
        unset($_stylesheets[$stylesheet[0]], $_stylesheets[$stylesheet]);
      }
    }

    cq_combine_stylesheets($stylesheets);
  }

  if (!empty($_stylesheets))
  {
    $stylesheets = array();
    foreach ($_stylesheets as $stylesheet => $options)
    {
      $stylesheets[] = $stylesheet;
    }

    cq_combine_stylesheets($stylesheets);
  }
}

function cq_include_javascripts()
{
  // Do not combine or do anything special if not in Production
  if (SF_ENV !== 'prod')
  {
    include_javascripts();

    return;
  }

  /** @var $response sfWebResponse */
  $response = cqContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.javascripts_included', true);

  // Get all the javascripts
  $_javascripts = $response->getJavascripts(sfWebResponse::ALL);

  if ($response->getJavascripts(sfWebResponse::FIRST))
  {
    $javascripts = array();
    foreach (array_keys($response->getJavascripts(sfWebResponse::FIRST)) as $javascript)
    {
      if ($javascript[0] != '/' || substr($javascript, 0, 3) == '/js/')
      {
        $javascripts[] = $javascript;
        unset($_javascripts[$javascript[0]], $_javascripts[$javascript]);
      }
    }

    cq_combine_javascripts($javascripts);
  }

  if ($response->getJavascripts(sfWebResponse::MIDDLE))
  {
    $javascripts = array();
    foreach (array_keys($response->getJavascripts(sfWebResponse::MIDDLE)) as $javascript)
    {
      if ($javascript[0] != '/' || substr($javascript, 0, 3) == '/js/')
      {
        $javascripts[] = $javascript;
        unset($_javascripts[$javascript[0]], $_javascripts[$javascript]);
      }
    }

    cq_combine_javascripts($javascripts);
  }

  if ($response->getJavascripts(sfWebResponse::LAST))
  {
    $javascripts = array();
    foreach (array_keys($response->getJavascripts(sfWebResponse::LAST)) as $javascript)
    {
      if ($javascript[0] != '/' || substr($javascript, 0, 3) == '/js/')
      {
        $javascripts[] = $javascript;
        unset($_javascripts[$javascript[0]], $_javascripts[$javascript]);
      }
    }

    cq_combine_javascripts($javascripts);
  }

  if (!empty($_javascripts))
  {
    $javascripts = array();
    foreach ($_javascripts as $javascript => $options)
    {
      $javascripts[] = $javascript;
    }

    cq_combine_javascripts($javascripts);
  }
}

/**
 * Generates a <link> tag
 *
 * @param <type> $stylesheets
 */
function cq_combine_stylesheets($stylesheets)
{
  if (!empty($stylesheets) && is_array($stylesheets))
  {
    $url = sprintf(
      '//%s/combine.php?type=css&files=%s',
      sfConfig::get('app_static_domain'), implode(',', $stylesheets)
    );

    if (defined('SVN_REVISION'))
    {
      $url .= '&revision='. intval(SVN_REVISION);
    }
    if (class_exists('sfConfig') && sfConfig::get('sf_environment') !== 'prod')
    {
      $url .= '&cache=0';
    }

    echo '<link rel="stylesheet" type="text/css" href="', $url, '">';
  }
}

/**
 * Generates a <script> tag
 *
 * @param <type> $javascripts
 */
function cq_combine_javascripts($javascripts)
{
  if (!empty($javascripts) && is_array($javascripts))
  {
    $url = sprintf(
      '//%s/combine.php?type=javascript&files=%s',
      sfConfig::get('app_static_domain'), implode(',', $javascripts)
    );

    if (defined('SVN_REVISION'))
    {
      $url .= '&revision='. intval(SVN_REVISION);
    }
    if (class_exists('sfConfig') && sfConfig::get('sf_environment') !== 'prod')
    {
      $url .= '&cache=0';
    }

    echo '<script type="text/javascript" src="', $url,'"></script>';
  }
}

function _cq_parse_options($options, $defaults = array())
{
  // used for second merge function - how should we avoid merging without this?
  $options_link_to = $options_image_tag = $options;

  unset($options_link_to['image_tag']);
  unset($options_image_tag['link_to']);

  $options['link_to'] = array_merge(
    isset($defaults['link_to']) ? (array) $defaults['link_to'] : array(),
    isset($options['link_to']) ? $options['link_to'] : $options_link_to
  );

  $options['image_tag'] = array_merge(
    isset($defaults['image_tag']) ? (array) $defaults['image_tag'] : array(),
    isset($options['image_tag']) ? $options['image_tag'] : $options_image_tag
  );

  $options['link_to'] = cqFunctions::array_filter_recursive($options['link_to']);
  $options['image_tag'] = cqFunctions::array_filter_recursive($options['image_tag']);

  // Cleaning some of the options we expect, definitely not a full list
  unset(
    $options['link_to']['width'], $options['link_to']['height'],
    $options['link_to']['max_width'], $options['link_to']['max_height'],
    $options['link_to']['alt'], $options['link_to']['truncate']
  );

  return $options;
}

/**
 * Returns array with only tag 'values', input array should be in format namespace:key=value
 *
 * @param $machine_tags  array   array of tags in format namespace:key=value
 * @param $namespace     string
 * @param $key           string
 */

function _cq_parse_machine_tags($machine_tags, $namespace, $key)
{
  $machine_tags_value_only = array();
  foreach ($machine_tags as $machine_tag)
  {
    if ($machine_tag['1'] == $namespace && ($machine_tag['2'] == $key || $machine_tag['2'] == 'all'))
    {
      $machine_tags_value_only[] = $machine_tag['3'];
    }
  }

  return $machine_tags_value_only;
}
