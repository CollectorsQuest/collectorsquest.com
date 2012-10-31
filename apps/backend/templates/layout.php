<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include_metas() ?>
    <?php include_title() ?>

	<link rel="shortcut icon" href="/images/favicon.ico">

    <?php include_partial('iceBackendModule/head'); ?>

    <?php cq_include_stylesheets(); ?>
  </head>
  <body id="<?= 'body-'. $sf_context->getModuleName() .'-'. $sf_context->getActionName(); ?>"
        class="<?= 'body-'. $sf_context->getModuleName(); ?>"
        data-controller="<?= $sf_context->getModuleName(); ?>"
        data-action="<?= $sf_context->getActionName(); ?>">
    <?php include_component('iceBackendModule', 'body', array('content' => $sf_content)); ?>

    <?php
      // Include all external javascript files
      cq_include_javascripts();

      // Echo all the javascript for the page
      cq_echo_javascripts();
    ?>
  </body>
</html>
