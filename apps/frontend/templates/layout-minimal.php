<?php
/**
 * @var  $sf_user     cqFrontendUser
 * @var  $sf_params   sfParameterHolder
 * @var  $sf_context  sfContext
 */
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
</head>
<body id="<?= 'body-'. $sf_params->get('module') .'-'. $sf_params->get('action'); ?>"
      data-controller="<?= $sf_params->get('module'); ?>" data-action="<?= $sf_params->get('action'); ?>">

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

    // Include analytics code only in production (and exclude the NY office IP address)
    if (sfConfig::get('sf_environment') === 'prod' && cqStatic::getUserIpAddress() != '207.237.37.24')
    {
      include_partial('global/js/analytics');
    }
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
