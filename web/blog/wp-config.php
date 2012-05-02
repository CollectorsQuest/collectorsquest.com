<?php

require_once __DIR__ .'/../../config/wp-config.php';

// You can have multiple installations in one database if you give each a unique prefix
$table_prefix  = 'wp_';   // Only numbers, letters, and underscores please!

if (SF_ENV === 'prod')
{
  define('WPLANG', 'en');
  define('WP_CACHE', true);
}
else
{
  define('WPLANG', 'en');
  define('WP_CACHE', false);
}

/**
define('WP_ALLOW_MULTISITE', true);

define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
$base = '/blog/';
define( 'DOMAIN_CURRENT_SITE', 'www.collectorsquest.dev' );
define( 'PATH_CURRENT_SITE', '/blog/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
**/

/* That's all, stop editing! Happy blogging. */

@define('ABSPATH', __DIR__.'/');
require_once __DIR__ .'/wp-settings.php';
