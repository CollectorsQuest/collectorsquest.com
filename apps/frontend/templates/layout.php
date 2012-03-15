<!doctype html>
<!--[if lt IE 7 ]>     <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7 ]>        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9 lt-ie8">        <![endif]-->
<!--[if IE 8 ]>        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9">               <![endif]-->
<!--[if gt IE 8]><!--> <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js">                  <!--<![endif]-->
<?php include_partial('global/head'); ?>
<body>
  <div id="fb-root"></div>
  <script type="text/javascript">
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

    if (has_component_slot('sidebar_120'))
    {
      $sidebar = 'sidebar_120';
      echo '<div class="container-fluid fixed-right-column-120">';
    }
    else if (has_component_slot('sidebar_180'))
    {
      $sidebar = 'sidebar_180';
      echo '<div class="container-fluid fixed-right-column-180">';
    }
    else if (has_component_slot('sidebar_300'))
    {
      $sidebar = 'sidebar_300';
      echo '<div class="container-fluid fixed-right-column">';
    }
    else
    {
      $sidebar = null;
      echo '<div class="container-fluid">';
    }
      /** @var $sf_content string */
    echo $sf_content;

    if (null !== $sidebar)
    {
      include_component_slot($sidebar);
    }
    echo '</div>';

    include_component_slot('footer');
  ?>

  <?php
    /** @var $sf_user cqFrontendUser */
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

  <?php
    /** @var $sf_context sfContext */

    cqStats::timing(
      'collectorsquest.modules.'. $sf_context->getModuleName() .'.'. $sf_context->getActionName(),
      cqTimer::getInstance()->getElapsedTime()
    );
  ?>
  <!-- Page generated in <?= cqTimer::getInstance()->getElapsedTime(); ?> seconds //-->
</body>
</html>
