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
<?php include_partial('global/head'); ?>
<body id="<?= 'body-'. $sf_params->get('module') .'-'. $sf_params->get('action'); ?>" data-controller="<?= $sf_params->get('module'); ?>" data-action="<?= $sf_params->get('action'); ?>">

  <style type="text/css">
    body {
      margin: 20px 0 0 0;
      padding: 20px;
    }

    .container {
      padding: 30px 40px;
      border: 1px solid #ddd;
      background-color: #fff;
      text-align: left;
      -moz-border-radius: 10px;
      -webkit-border-radius: 10px;
      border-radius: 10px;
      min-width: 770px;
      max-width: 770px
    }
  </style>

  <div class="container">
    <?php echo $sf_content ?>
  </div>

  <?php
    $sf_cache_key  = (int) $sf_user->getId() .'_';
    $sf_cache_key .= $sf_user->isAuthenticated() ? 'authenticated' : 'not_authenticated';

    // Include the global javascripts
    include_partial('global/javascripts', array('sf_cache_key' => $sf_cache_key));
  ?>

  <?php
    cqStats::timing(
      'collectorsquest.modules.'. $sf_context->getModuleName() .'.'. $sf_context->getActionName(),
      cqTimer::getInstance()->getElapsedTime()
    );
  ?>

  <!-- Page generated in <?= cqTimer::getInstance()->getElapsedTime(); ?> seconds //-->
</body>
</html>
