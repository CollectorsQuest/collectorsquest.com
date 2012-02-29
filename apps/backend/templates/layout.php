<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include_metas() ?>
    <?php include_title() ?>
    
	<link rel="shortcut icon" href="/images/favicon.ico">
	
    <?php include_partial('iceBackendModule/head'); ?>

    <?php
      sfConfig::set('symfony.asset.stylesheets_included', true);
      $css = @implode(',', array_keys($sf_response->getStylesheets()));

      if (!empty($css))
      {
        echo '<link rel="stylesheet" type="text/css" media="screen" href="/combine.php?type=css&files='. $css .'&revision='. SVN_REVISION .'" />';
      }
    ?>
  </head>
  <body>
    <?php include_component('iceBackendModule', 'body', array('content' => $sf_content)); ?>

    <?php
      sfConfig::set('symfony.asset.javascripts_included', true);
      $js = @implode(',', array_keys($sf_response->getJavascripts()));

      if (!empty($js))
      {
        echo '<script type="text/javascript" src="/combine.php?type=javascript&files='. $js .'&revision='. SVN_REVISION .'"></script>';
      }

      // Echo all the javascript for the page
      cq_echo_javascripts();
    ?>
  </body>
</html>
