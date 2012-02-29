<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>

    <link rel="shortcut icon" href="/images/favicon.ico" />
    <?php include_partial('iceBackendModule/head'); ?>

    <?php cq_include_stylesheets(); ?>
  </head>
  <body>
    <?php include_component('iceBackendModule', 'body', array('content' => $sf_content)); ?>

    <?php
      // Include all external javascript files
      cq_include_javascripts();

      // Echo all the javascript for the page
      cq_echo_javascripts();
    ?>
  </body>
</html>
