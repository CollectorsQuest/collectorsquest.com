<!doctype html>
<!--[if lt IE 7 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js"><!--<![endif]-->
<?php include_partial('global/head'); ?>
<body data-controller="<?php echo $sf_params->get('module'); ?>" data-action="<?php echo $sf_params->get('action'); ?>">
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

    if (has_component_slot('sidebar_120'))
    {
      $sidebar = 'sidebar_120';
      echo '<div id="content" class="container-fluid fixed-right-120">';
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
    else if (has_component_slot('sidebar_grid_120'))
    {
      $sidebar = 'sidebar_grid_120';
      echo '<div id="content" class="container fixed-right-120">';
    }
    else if (has_component_slot('sidebar_grid_180'))
    {
      $sidebar = 'sidebar_grid_180';
      echo '<div id="content" class="container fixed-right-180">';
    }
    else if (has_component_slot('sidebar_grid_300'))
    {
      $sidebar = 'sidebar_grid_300';
      echo '<div id="content" class="container fixed-right-300">';
    }
    else
    {
      $sidebar = null;
      echo '<div id="content" class="container-fluid without-column">';
    }
    /** @var $sf_content string */
  ?>

  <div id="main">
    <?= $sf_content;?>
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


<footer>
<? include_component_slot('footer'); ?>
</footer>


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
