<?php

/** @var cqApplicationConfiguration $configuration */
$configuration = sfProjectConfiguration::getActive();
$configuration->loadHelpers(array('JavascriptBase'));

/**
 * Render a Google AdManager ad slot
 *
 * @param  string   $slot     Which ad slot to load
 * @param  integer  $width    The width of the ad slot
 * @param  integer  $height   The height of the ad slot
 * @param  boolean  $delayed  Whether to delay the loading of the ad until page load.
 *                            Delayed loading does not work with ajax calls so set to false in those cases.
 *
 * @return void
 */
function cq_ad_slot($slot, $width, $height, $delayed = false)
{
  if (SF_ENV != 'prod')
  {
    $image = 'iab/'. $width .'x'. $height .'.gif';
    if (is_file(sfConfig::get('sf_web_dir').'/images/' . $image))
    {
      echo '<center>', image_tag($image), '</center>';
    }
    else
    {
      echo sprintf(
        '<div style="margin: auto; width: %dpx; height: %dpx; background: #59CF76; border: 1px solid #00AC52; position: relative;">',
        $width, $height
      );
      echo sprintf('<div style="position: absolute; bottom: 5px; right: 5px; color: #638606; font-size: 14px; font-weight: bold;">%dx%d</div>', $width, $height);
      echo '</div>';
    }

    return;
  }

  if ($delayed == true)
  {
    $request = sfContext::getInstance()->getRequest();
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
  }
}

function cq_javascript_tag()
{
  /** @var $request sfWebRequest */
  $request = sfContext::getInstance()->getRequest();

  if (SF_ENV != 'prod' || $request->isXmlHttpRequest()) return;

  ob_start();
  ob_implicit_flush(0);
}

function cq_end_javascript_tag()
{
  /** @var $request sfWebRequest */
  $request = sfContext::getInstance()->getRequest();

  if (SF_ENV != 'prod' || $request->isXmlHttpRequest()) return;

  $request = sfContext::getInstance()->getRequest();
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
  $request = sfContext::getInstance()->getRequest();
  $contents = (array) @unserialize($request->getAttribute('contents', '', 'symfony/view/cqJavascripts'));
  $contents = implode("\n", array_unique($contents));

  if (!empty($contents))
  {
    include_once __DIR__.'/../vendor/JavaScriptMinify.class.php';

    try
    {
      $contents = JavaScriptMinify::minify($contents);
    }
    catch (Exception $e) { ; }

    echo content_tag('script', javascript_cdata_section(trim($contents)), array('type' => 'text/javascript'));
  }
}
