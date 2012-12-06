<?php
/* @var  $sf_user     cqFrontendUser */
/* @var  $sf_params   sfParameterHolder */
/* @var  $sf_context  sfContext */
?>
<!doctype html>
<!--[if IE 8 ]>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" xmlns:og="http://opengraph.org/schema/"
      lang="en" class="no-js lt-ie10 lt-ie9 ie8">
<![endif]-->
<!--[if IE 9]>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" xmlns:og="http://opengraph.org/schema/"
      lang="en" class="no-js lt-ie10 ie9">
<![endif]-->
<!--[if gt IE 9]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" xmlns:og="http://opengraph.org/schema/"
      lang="en" class="no-js">
<!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# collectorsquest: http://ogp.me/ns/fb/collectorsquest#">
  <?php include_partial('global/head'); ?>

  <!-- Blog Head //-->

  <!-- A&E Networks javascript code (tracking, analytics, etc) //-->
  <script src="//nexus.ensighten.com/aetn/Bootstrap.js"></script>
</head>
<body id="<?= 'body-'. $sf_context->getModuleName() .'-'. $sf_context->getActionName(); ?>"
      class="<?= 'body-'. $sf_context->getModuleName(); ?>"
      data-controller="<?= $sf_context->getModuleName(); ?>"
      data-action="<?= $sf_context->getActionName(); ?>">
  <?php include_component('global', 'adminBar'); ?>
  <a name="top"></a>

  <?php /*
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function()
    {
      FB.init(
      {
        appId: '',
        channelUrl: '//<?= sfConfig::get('app_www_domain') ?>/fb_xdcomm.php',
        status: true, cookie: true, xfbml: true
      });
    };
    // Load the SDK Asynchronously
    (function(d)
    {
      var js, id = 'facebook-jssdk'; if (d.getElementById(id)) { return; }
      js = d.createElement('script'); js.id = id; js.async = true;
      js.src = "//connect.facebook.net/en_US/all.js";
      d.getElementsByTagName('head')[0].appendChild(js);
    }(document));
  </script>
 */ ?>

  <?php
    include_component_slot('header');
  ?>
  <div class="shadow">
  <?php
    if (has_component_slot('breadcrumbs'))
    {
      echo '<div id="breadcrumbs">';
        include_component_slot('breadcrumbs');
      echo '</div>';
    }

    if (has_component_slot('slot1'))
    {
      echo '<div class="slots-container"><div id="slot1">';
        include_component_slot('slot1');
      echo '</div></div>';
    }

    if (has_component_slot('sidebar_120'))
    {
      $sidebar = 'sidebar_120';
      echo '<div id="content" class="container-fluid fixed-right-120">';
    }
    else if (has_component_slot('sidebar_160'))
    {
      $sidebar = 'sidebar_160';
      echo '<div id="content" class="container-fluid fixed-right-160">';
    }
    else if (has_component_slot('sidebar_180'))
    {
      $sidebar = 'sidebar_180';
      echo '<div id="content" class="container-fluid fixed-right-180">';
    }
    else if (has_component_slot('sidebar_300'))
    {
      $sidebar = 'sidebar_300';
      echo '<div id="content" class="container-fluid fixed-right-300">';
    }
    else
    {
      $sidebar = null;
      echo '<div id="content" class="container-fluid without-column">';
    }
  ?>

  <?php include_partial('global/flash_messages'); ?>

  <div id="main">
    <?php
      /** @var $sf_content string */
      echo $sf_content;
    ?>
  </div><!--/#main-->

  <?php
    if (null !== $sidebar)
    {
      $height = $sf_user->getFlashAndDelete('height_main_div', null, 'internal');

      // Make sure we are backwords compatible to the old behavior
      if (empty($height) || !property_exists($height, 'value') || $height->value <= 0)
      {
        $height = new stdClass();
        $height->value = PHP_INT_MAX;
      }

      echo '<div id="sidebar">';
      include_component_slot($sidebar, array('height' => $height));
      echo '</div>';
    }
    echo '</div>';
  ?>

  <?php
    if (has_component_slot('slot2'))
    {
      echo '<div class="slots-container"><div id="slot2">';
      include_component_slot('slot2');
      echo '</div></div>';
    }
  ?>

  <?php
    include_component_slot('footer');
    include_partial('global/footer_links');

    if (!$sf_user->isAuthenticated())
    {
      include_component('_ajax', 'modalLogin');
    }

    // include the html for modal confirmation
    include_partial('global/modal_confirm');

    // include the html for modal alerts
    include_partial('global/modal_alert');

    // Include the global javascripts
    include_partial('global/javascripts');

    // Include analytics code only in production and exclude the NY office IP address
    if (sfConfig::get('sf_environment') === 'prod' && cqStatic::getUserIpAddress() != '207.237.37.24')
    {
      include_partial('global/js/analytics');
    }

    /** @var $sf_request cqWebRequest */
    if ($slots = $sf_request->getAttribute('slots', array(), 'cq/view/ads'))
    {
      include_partial('global/ad_slots', array('slots' => $slots));
    }
  ?>
  <!-- Blog Footer //-->

  </div>
  <!-- /.shadow -->

  <?php
    cqStats::timing(
      'collectorsquest.modules.'. $sf_context->getModuleName() .'.'. $sf_context->getActionName(),
      cqTimer::getInstance()->getElapsedTime()
    );
  ?>

  <!-- Page generated in <?= cqTimer::getInstance()->getElapsedTime(); ?> seconds by <?= gethostname(); ?> //-->
</body>
</html>
