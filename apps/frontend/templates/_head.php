<?php
/**
 * @var $sf_user cqFrontendUser
 */
?>

  <meta charset="utf-8">

  <link rel="dns-prefetch" href="//d2y8496azcwpd6.cloudfront.net">
  <link rel="dns-prefetch" href="//d2qss72tiioiku.cloudfront.net">
  <link rel="dns-prefetch" href="//fonts.googleapis.com">
  <link rel="dns-prefetch" href="//ajax.googleapis.com">
  <link rel="dns-prefetch" href="//nexus.ensighten.com">
  <link rel="dns-prefetch" href="//video.collectorsquest.com">
  <link rel="dns-prefetch" href="//collectorsquest.rpxnow.com">
  <link rel="dns-prefetch" href="//s7.addthis.com">

  <meta property="fb:admins" content="<?= cqConfig::getCredentials('facebook', 'admins') ?>">
  <meta property="fb:app_id" content="<?= cqConfig::getCredentials('facebook', 'application_id') ?>">
  <meta property="og:site_name" content="CollectorsQuest.com">

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!--[if ie]><meta http-equiv="X-Ua-Compatible" content="IE=Edge,chrome=1"><![endif]-->

  <?php cq_include_http_metas() ?>
  <?php cq_include_metas() ?>
  <?php cq_include_title() ?>

  <?php
    $bundle = sprintf(
      '//%s/%s',
      sfConfig::get('app_static_domain'),
      sfConfig::get('sf_environment') !== 'dev' ?
        ltrim(stylesheet_path('frontend/stylesheets.bundle.' . GIT_REVISION . '.min.css', false), '/') :
        ltrim(stylesheet_path('frontend/stylesheets.bundle.' . GIT_REVISION . '.css', false), '/')
    );
    echo '<link rel="stylesheet" type="text/css" media="screen" href="'. $bundle .'">'."\n";
    unset($bundle);

    // Include the application specific stylesheets
    cq_include_stylesheets();
  ?>
  <?php include_partial('global/head_ie'); ?>

  <link href='//fonts.googleapis.com/css?family=Chivo:400,400italic,900,900italic' rel='stylesheet' type='text/css'>
  <link rel="shortcut icon" href="<?php echo cq_image_src('frontend/favicon.ico', true); ?>">
  <link rel="icon" type="image/png" href="<?php echo cq_image_src('frontend/favicon.png', true); ?>">
  <?php cq_canonical_tag(); ?>
  <?php echo "\n" . get_slot('prev_next') ?>

  <script>
    window.cq = {
      ENV: '<?= sfConfig::get('sf_environment') ?>',
      authenticated: <?= $sf_user->isAuthenticated() ? 'true' : 'false'; ?>,
      is_mobile: <?= $sf_request->isMobileLayout() ? 'true' : 'false'; ?> ,
      page_load_start: new Date(),
      server_load_time: 0,
      cookies: {
        username_: '<?= sfConfig::get('app_collector_username_cookie_name', 'cq_username'); ?>',
        mycq_dropbox_state: '<?= cqFrontendUser::DROPBOX_OPEN_STATE_COOKIE_NAME; ?>'
      },
      settings: {
        aviary: {
          apiKey: '<?= cqConfig::getCredentials('aviary', 'api_key'); ?>',
          postUrl: '<?= url_for('@mycq_aviary_update_image', true); ?>'
        }
      }
    };

    // http://stackoverflow.com/a/8567229
    var _docready=[],$=function(){return{ready:function(fn){_docready.push(fn)}}};
  </script>
  <script src="<?= cq_javascript_src('frontend/head.js'); ?>"></script>
  <?php include_component_slot('jquery_header'); ?>
