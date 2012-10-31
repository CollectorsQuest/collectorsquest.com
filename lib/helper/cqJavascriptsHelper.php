<?php

/** @var cqApplicationConfiguration $configuration */
$configuration = sfProjectConfiguration::getActive();
$configuration->loadHelpers(array('JavascriptBase'));

/**
 * Render a cq ad slot
 *
 * @param  string   $image     the path to image being displayed, it should be inside /web/images/ folder
 * @param  string   $link_to   where does the image lead to
 *
 * @return string
 */
function cq_ad_slot($image, $link_to)
{
  // set the routing name as the ref parameter
  $ref = sfContext::getInstance()->getRouting()->getCurrentInternalUri(true);
  $ref = str_replace('@', '', $ref);

  // add the ref parameter to the link
  $link_to .= '?ref=' . $ref;

  echo link_to(image_tag($image), $link_to);


  /*if (SF_ENV != 'prod')
  {
    $image = 'iab/'. $width .'x'. $height .'.gif';
    if (is_file(sfConfig::get('sf_web_dir').'/images/' . $image))
    {
      echo '<center>', cq_image_tag($image), '</center>';
    }
    else
    {
      echo sprintf(
        '<div style="margin: auto; width: %dpx; height: %dpx; background: #59CF76; border: 1px solid #00AC52; position: relative;">',
        $width, $height
      );
      echo sprintf(
        '<div style="position: absolute; bottom: 5px; right: 5px; color: #638606; font-size: 14px; font-weight: bold;">%dx%d</div>',
        $width, $height
      );
      echo '</div>';
    }

    return;
  }

  if ($delayed == true)
  {
    $request = cqContext::getInstance()->getRequest();
    $slots = $request->getAttribute('slots', array(), 'cq/view/ads');

    echo sprintf(
      '<div id="ad_slot_%s" style="width: %dpx; height: %dpx; line-height: %dpx; margin: auto;">&nbsp;</div>',
      $slot, $width, $height, $height
    );

    $slots[] = $slot;
    $request->setAttribute('slots', $slots, 'cq/view/ads');
  }
  else
  {
    echo sprintf(
      '<div align="center"><iframe src="/ad_slot.php?slot=%1$s&rand=%2$s" id="ad_slot_%1$s" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" style="border: 0; width: %3$dpx; height: %4$dpx; padding: 0; margin: 0;" width="%3$d" height="%4$d"></iframe></div>',
      $slot, uniqid('slot_', true), $width, $height
    );
  }*/
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

  include_partial(
    'global/js/dart_slot',
    array(
      'src' => $src, 'href' => $href,
      'width' => (int) $width, 'height' => (int) $height
    )
  );
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
