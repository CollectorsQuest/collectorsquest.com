<?php
/**
 * @var  $sf_user     cqFrontendUser
 * @var  $sf_params   sfParameterHolder
 * @var  $sf_context  sfContext
 */
?>
<!doctype html>
<!--[if IE 8 ]>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" class="no-js lt-ie10 lt-ie9">
<![endif]-->
<!--[if IE 9]>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" class="no-js lt-ie10">
<![endif]-->
<!--[if gt IE 9]>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" class="no-js">
<!--<![endif]-->
<head>
  <?php include_partial('global/head'); ?>
  <base target="_top" />
</head>
<body id="body-_video-footer" data-controller="_video" data-action="footer">
  <?php
    $sf_cache_key  = (int) $sf_user->getId() .'_';
    $sf_cache_key .= $sf_user->isAuthenticated() ? 'authenticated' : 'not_authenticated';

    include_component_slot('footer', array('sf_cache_key' => $sf_cache_key));
    include_partial('global/footer_links');

    // Include the global javascripts
    include_partial('global/javascripts', array('sf_cache_key' => $sf_cache_key));
  ?>
</body>
</html>
