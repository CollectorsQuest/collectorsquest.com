<?php
  /**
   * @var  $sf_user       cqFrontendUser
   * @var  $sf_params     sfParameterHolder
   * @var  $sf_cache_key  string
   */

  $sf_cache_key  = (int) $sf_user->getId() .'_';
  $sf_cache_key .= $sf_user->isAuthenticated() ? 'authenticated' : 'not_authenticated';
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
<body id="body-_video-header" data-controller="_video" data-action="header">
  <?php
    include_component_slot('header', array('video' => true));
  ?>
  <div id="slot1" style="height: 50px; box-shadow: none; -webkit-box-shadow: none; -moz-box-shadow: none;">&nbsp;</div>

  <?php
    // Include the global javascripts
    include_partial('global/javascripts', array('sf_cache_key' => $sf_cache_key));
  ?>
</body>
</html>
