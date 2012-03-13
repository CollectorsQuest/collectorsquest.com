<!DOCTYPE html>
<!--[if IE]><![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
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

  <?php include_component_slot('header'); ?>
  <div class="container-fluid r-column-padding">
    <?php
      /** @var $sf_content string */
      echo $sf_content;

      if (has_component_slot('sidebar_120'))
        {
        include_component_slot('sidebar_120');
      }
      else if (has_component_slot('sidebar_340'))
      {
        include_component_slot('sidebar_340');
      }
    ?>
  </div>
  <?php include_component_slot('footer'); ?>

  <?php include_partial('global/javascripts'); ?>
  <?php include_partial('global/ad_slots'); ?>

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
