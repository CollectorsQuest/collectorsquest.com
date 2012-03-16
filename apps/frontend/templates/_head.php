<head>
  <?php cq_include_http_metas() ?>
  <?php cq_include_metas() ?>
  <?php cq_include_title() ?>

  <?php
    // Include the cqcdns.com stylesheets
    ice_include_stylesheets();

    // Include the application specific stylesheets
    cq_include_stylesheets();
  ?>

  <?php include_partial('global/head_ie'); ?>

  <link rel="shortcut icon" href="<?php echo cq_image_src('frontend/favicon.ico', true); ?>"/>
  <link rel="icon" type="image/png" href="<?php echo cq_image_src('frontend/favicon.png', true); ?>"/>

  <script src="<?= cq_javascript_src('frontend/jquery.js'); ?>" type="text/javascript"></script>
  <script src="<?= cq_javascript_src('frontend/modernizr.js'); ?>" type="text/javascript"></script>

  <script type="text/javascript">
    window._ENV = '<?= sfConfig::get('sf_environment') ?>';
    window._page_load_start = new Date();
    window._server_load_time = 0;

    Modernizr.load([{ load: '<?= cq_javascript_src('frontend/head.js'); ?>' }]);
  </script>

  <!-- Blog Head //-->
</head>
