<?php

function cq_image_src($image, $secure = false)
{
  return 'http://'. sfConfig::get('app_static_domain') .'/images/'. $image;
}

function cq_stylesheet_src($stylesheet, $secure = false)
{
  return 'http://'. sfConfig::get('app_static_domain') .'/css/'. $stylesheet .'?rev='. (defined('SVN_REVISION') ? SVN_REVISION : 0);
}

function cq_javascript_src($javascript, $secure = false)
{
  return 'http://'. sfConfig::get('app_static_domain') .'/js/'. $javascript .'?rev='. (defined('SVN_REVISION') ? SVN_REVISION : 0);
}

function cq_include_stylesheets()
{
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
    echo sprintf(
      '<link rel="stylesheet" type="text/css" href="http://%s/combine.php?type=css&files=%s&revision=%d"/>',
      sfConfig::get('app_static_domain'), implode(',', $stylesheets), defined('SVN_REVISION') ? SVN_REVISION : null
    );
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
    echo sprintf(
      '<script type="text/javascript" src="http://%s/combine.php?type=javascript&files=%s&revision=%d"></script>',
      sfConfig::get('app_static_domain'), implode(',', $javascripts), defined('SVN_REVISION') ? SVN_REVISION : null
    );
  }
}
