
  <meta charset="utf-8" />
  <?php cq_include_http_metas() ?>
  <?php cq_include_metas() ?>
  <?php cq_include_title() ?>

  <?php
    echo stylesheet_tag('frontend/stylesheets.bundle.' . GIT_REVISION . '.css');
    // echo stylesheet_tag('frontend/responsive.css');

    // Include the application specific stylesheets
    cq_include_stylesheets();
  ?>

  <?php include_partial('global/head_ie'); ?>

  <link href='//fonts.googleapis.com/css?family=Chivo:400,400italic,900,900italic' rel='stylesheet' type='text/css'>
  <link rel="shortcut icon" href="<?php echo cq_image_src('frontend/favicon.ico', true); ?>"/>
  <link rel="icon" type="image/png" href="<?php echo cq_image_src('frontend/favicon.png', true); ?>"/>

  <script>
    window.cq = {
      ENV: '<?= sfConfig::get('sf_environment') ?>',
      authenticated: <?= $sf_user->isAuthenticated() ? 'true' : 'false'; ?>,
      page_load_start: new Date(),
      server_load_time: 0,
      username_cookie: '<?= sfConfig::get('app_collector_username_cookie_name', 'cqUsername'); ?>',
      settings: {}
    };

    // http://stackoverflow.com/a/8567229
    var docready=[],$=function(){return{ready:function(fn){docready.push(fn)}}};
  </script>
  <script src="<?= cq_javascript_src('frontend/head.js'); ?>"></script>

  <!-- A&E Networks javascript code (tracking, analytics, etc) //-->
  <script src="//nexus.ensighten.com/aetn/Bootstrap.js"></script>
