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

  <link rel="shortcut icon" href="<?php echo image_path('frontend/favicon.ico', true); ?>"/>
  <link rel="icon" type="image/png" href="<?php echo image_path('frontend/favicon.png', true); ?>"/>

  <script type="text/javascript">
    if (top.location != self.location)
    {
      top.location.replace(self.location.toString());
    }
  </script>

  <?php
    if (SF_ENV !== 'prod')
    {
      include_partial('global/head_analytics');
    }
  ?>

  <!-- Blog Head //-->
</head>
