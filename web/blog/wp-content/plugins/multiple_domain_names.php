<?php
/**
 * Plugin Name:    Server Name Support
 * Description:    Allows the blog to be accessed with multiple server names
 * Author:         Michal Wojciechowski
 * Version:        0.0.1
 * Author URI:     http://odyniec.net/
 */
$sns_home = preg_replace(
  '!://[a-z0-9.-]*!', '://' . $_SERVER['SERVER_NAME'],
  get_option('home')
);

function sns_home()
{
  global $sns_home;

  return $sns_home;
}

function sns_replace_host($url, $path = '')
{
  return preg_replace(
    '!://[a-z0-9.-]*!', '://' . $_SERVER['SERVER_NAME'],
    $url
  );
}

add_filter('pre_option_home', 'sns_home');
add_filter('pre_option_siteurl', 'sns_home');
add_filter('pre_option_url', 'sns_home');
add_filter('stylesheet_uri', 'sns_replace_host');

add_filter('admin_url', 'sns_replace_host');
