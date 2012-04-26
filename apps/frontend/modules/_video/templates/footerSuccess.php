<?php
/**
 * @var  $sf_user     cqFrontendUser
 * @var  $sf_params   sfParameterHolder
 * @var  $sf_context  sfContext
 */
?>
<!doctype html>
<!--[if lt IE 7 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8 ]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="en" class="no-js"><!--<![endif]-->
<head>
  <?php include_partial('global/head'); ?>
  <base target="_parent" />
</head>
<body id="<?= 'body-'. $sf_params->get('module') .'-'. $sf_params->get('action'); ?>" data-controller="<?= $sf_params->get('module'); ?>" data-action="<?= $sf_params->get('action'); ?>">
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
