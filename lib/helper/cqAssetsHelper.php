<?php

/**
 * @see include_http_metas()
 */
function cq_include_http_metas()
{
  /** @var $response sfWebResponse */
  $response = sfContext::getInstance()->getResponse();

  foreach ((array) $response->getHttpMetas() as $httpequiv => $value)
  {
    echo tag('meta', array('http-equiv' => $httpequiv, 'content' => $value))."\n";
  }
}

/**
 * @see include_metas()
 */
function cq_include_metas()
{
  /** @var $context sfContext */
  $context = sfContext::getInstance();

  /** @var $response sfWebResponse */
  $response = $context->getResponse();

  /** @var $i18n sfI18N */
  $i18n = sfConfig::get('sf_i18n') ? $context->getI18N() : null;

  $i = 0;
  foreach ($response->getMetas() as $name => $content)
  {
    // Do not display empty meta tags
    if ($content === '' || $content === null) continue;

    echo ($i++ > 0) ? '  ' : '';
    echo tag('meta', array('name' => $name, 'content' => null === $i18n ? $content : $i18n->__($content)))."\n";
  }
}

/**
 * @see include_title()
 */
function cq_include_title()
{
  /** @var $response sfWebResponse */
  $response = sfContext::getInstance()->getResponse();

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
  return image_tag(cq_image_src($source), $options);
}

function cq_image_src($image)
{
  return '//'. sfConfig::get('app_static_domain') .'/images/'. $image;
}

function cq_stylesheet_src($stylesheet)
{
  return '//'. sfConfig::get('app_static_domain') .'/css/'. $stylesheet .'?rev='. (defined('SVN_REVISION') ? SVN_REVISION : 0);
}

function cq_javascript_src($javascript)
{
  return '//'. sfConfig::get('app_static_domain') .'/js/'. $javascript .'?rev='. (defined('SVN_REVISION') ? SVN_REVISION : 0);
}

function cq_include_stylesheets()
{
  // Do not combine or do anything special if not in Production
  if (SF_ENV === 'prod')
  {
    include_stylesheets();

    return;
  }

  /** @var $response sfWebResponse */
  $response = sfContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.stylesheets_included', true);

  if ($response->getStylesheets(sfWebResponse::FIRST))
  {
    $stylesheets = array();
    foreach (array_keys($response->getStylesheets(sfWebResponse::FIRST)) as $stylesheet)
    {
      if ($stylesheet[0] != '/' || substr($stylesheet, 0, 5) == '/css/')
      {
        $stylesheets[] = $stylesheet;
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
      }
    }

    cq_combine_stylesheets($stylesheets);
  }
}

function cq_include_javascripts()
{
  // Do not combine or do anything special if not in Production
  if (SF_ENV === 'prod')
  {
    include_javascripts();

    return;
  }

  /** @var $response sfWebResponse */
  $response = sfContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.javascripts_included', true);

  if ($response->getJavascripts(sfWebResponse::FIRST))
  {
    $javascripts = array();
    foreach (array_keys($response->getJavascripts(sfWebResponse::FIRST)) as $javascript)
    {
      if ($javascript[0] != '/' || substr($javascript, 0, 3) == '/js/')
      {
        $javascripts[] = $javascript;
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
      }
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
      'http://%s/combine.php?type=css&files=%s',
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

    echo '<link rel="stylesheet" type="text/css" href="', $url, '"/>';
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
      'http://%s/combine.php?type=javascript&files=%s',
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
