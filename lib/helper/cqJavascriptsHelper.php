<?php

/** @var cqApplicationConfiguration $configuration */
$configuration = sfProjectConfiguration::getActive();
$configuration->loadHelpers(array('JavascriptBase'));

/**
 * Render a cq ad slot
 *
 * @param  string   $image
 * @param  string   $link_to
 *
 * @return string
 */
function cq_ad_slot($image, $link_to)
{
  // set the routing name as the ref parameter
  $ref = sfContext::getInstance()->getRouting()->getCurrentInternalUri(true);
  $ref = str_replace('@', '', $ref);

  if ($query = parse_url($link_to, PHP_URL_QUERY))
  {
    $params = array();
    parse_str($query, $params);

    if (!isset($params['ref']))
    {
      // add the ref parameter to the link
      $link_to .= '&ref=' . $ref;
    }
  }
  else
  {
    // add the ref parameter to the link
    $link_to .= '?ref=' . $ref;
  }

  echo link_to($image, $link_to);
}

function cq_dart_slot($size, $zone1 = 'other', $zone2 = null, $pos = null)
{
  list($width, $height) = explode('x', $size);

  $test = isset($_GET['test']) && $_GET['test'] === 'on' ? 'on' : null;
  $src = sprintf(
    'http://ad.doubleclick.net/adj/aetn.hist.cq/cq%s;s1=cq%s;s2=%s;kw=;test=%s;aetn=ad;pos=%s;dcopt=%s;sz=%s',
    $zone1, $zone1, $zone2, $test, $pos, ($pos === 'top') ? 'ist' : null, $size
  );

  $href = sprintf(
    'http://ad.doubleclick.net/jump/aetn.hist.cq/%s;s1=%s;s2=%s;kw=;test=%s;aetn=ad;pos=%s;sz=%s',
    $zone1, $zone1, $zone2, $test, $pos, $size
  );

  // fix for add banners to be center aligned when responsive design
  echo '<div class="mobile-optimized-300 center">';
  include_partial(
    'global/js/dart_slot',
    array(
      'src' => $src, 'href' => $href,
      'width' => (int) $width, 'height' => (int) $height
    )
  );
  echo '</div>';
}

function cq_javascript_tag()
{
  /** @var $request sfWebRequest */
  $request = cqContext::getInstance()->getRequest();

  if (SF_ENV != 'prod' || $request->isXmlHttpRequest())
  {
    return;
  }

  ob_start();
  ob_implicit_flush(0);
}

function cq_end_javascript_tag()
{
  /** @var $request sfWebRequest */
  $request = cqContext::getInstance()->getRequest();

  if (SF_ENV != 'prod' || $request->isXmlHttpRequest())
  {
    return;
  }

  $request = cqContext::getInstance()->getRequest();
  $contents = (array) @unserialize($request->getAttribute('contents', '', 'symfony/view/cqJavascripts'));

  $script = ob_get_clean();
  $script = str_replace(
    array('<script type="text/javascript">', '<script>', '</script>'), '', $script
  );

  $contents[] = $script ."\n";
  $request->setAttribute('contents', serialize($contents), 'symfony/view/cqJavascripts');
}

function cq_echo_javascripts()
{
  $request = cqContext::getInstance()->getRequest();
  $contents = (array) @unserialize($request->getAttribute('contents', '', 'symfony/view/cqJavascripts'));
  $contents = implode("\n", array_unique($contents));

  if (!empty($contents))
  {
    if (function_exists('jsmin'))
    {
      $contents = (sfConfig::get('sf_environment') == 'prod') ? jsmin($contents) : $contents;
    }
    else
    {
      include_once __DIR__ . '/../../plugins/iceLibsPlugin/lib/vendor/JavaScriptMinify.class.php';

      try
      {
        $contents = (sfConfig::get('sf_environment') == 'prod') ? JavaScriptMinify::minify($contents) : $contents;
      }
      catch (Exception $e)
      {
        ;
      }
    }

    echo content_tag('script', javascript_cdata_section(trim($contents)), array('type' => 'text/javascript'));
  }
}
