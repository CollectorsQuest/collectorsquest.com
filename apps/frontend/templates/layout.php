<?php
/**
 * @var  $sf_user     cqFrontendUser
 * @var  $sf_params   sfParameterHolder
 * @var  $sf_context  sfContext
 */
?>
<!doctype html>
<!--[if lt IE 7 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js"><!--<![endif]-->
<head>
  <?php include_partial('global/head'); ?>
</head>
<body id="<?= 'body-'. $sf_params->get('module') .'-'. $sf_params->get('action'); ?>" data-controller="<?= $sf_params->get('module'); ?>" data-action="<?= $sf_params->get('action'); ?>">
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function()
    {
      FB.init(
      {
        appId: '',
        channelUrl: '//<?= sfConfig::get('app_www_domain') ?>/channel.php',
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

  <?php
    include_component_slot('header');

    echo '<div id="slot1">';
      include_component_slot('slot1');
    echo '</div>';

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

  <div id="main">
    <?php
      include_partial('global/flash_messages');

      /** @var $sf_content string */
      echo $sf_content;
    ?>
  </div><!--/#main-->

  <?php
    if (null !== $sidebar)
    {
      echo '<div id="sidebar">';
      include_component_slot($sidebar);
      echo '</div>';
    }
    echo '</div>';
  ?>

  <?php
    include_component_slot('footer');
    include_partial('global/footer_links');
  ?>

  <?php
    $sf_cache_key  = (int) $sf_user->getId() .'_';
    $sf_cache_key .= $sf_user->isAuthenticated() ? 'authenticated' : 'not_authenticated';

    // Include the global javascripts
    include_partial('global/javascripts', array('sf_cache_key' => $sf_cache_key));

    /** @var $sf_request cqWebRequest */
    if ($slots = $sf_request->getAttribute('slots', array(), 'cq/view/ads'))
    {
      include_partial('global/ad_slots', array('slots' => $slots));
    }
  ?>

  <!-- Blog Footer //-->

  <?php
    cqStats::timing(
      'collectorsquest.modules.'. $sf_context->getModuleName() .'.'. $sf_context->getActionName(),
      cqTimer::getInstance()->getElapsedTime()
    );
  ?>

  <!-- Page generated in <?= cqTimer::getInstance()->getElapsedTime(); ?> seconds //-->
</body>
</html>
