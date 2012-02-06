<?php

if (defined('DB_NAME') && defined('DB_USER') && defined('DB_PASSWORD')) {
  return;
}

if (!defined('SF_ENV') && $_SERVER['HTTP_HOST'] == 'www.collectorsquest.dev')
{
  define('SF_ENV', 'dev');
}
else if (!defined('SF_ENV') && $_SERVER['HTTP_HOST'] == 'www.collectorsquest.stg')
{
  define('SF_ENV', 'staging');
}
else if (!defined('SF_ENV'))
{
  define('SF_ENV', 'prod');
}

if (SF_ENV === 'dev')
{
  define('DB_NAME', 'collectorsquest_dev');
  define('DB_USER', 'root');
  define('DB_PASSWORD', '');
  define('DB_HOST', 'localhost');

  define('AUTH_KEY',         'kY?,$GZNiOk$= ?#OW|,-1PuP6DXI=#Lff-R,}9I90<l<YqK5Q904<UoxF}gs6s=');
  define('SECURE_AUTH_KEY',  '<GdO)poK3Asj/|+fMW-);1N2{k?PbP|K#gDUZya~9`/<YPG:4G35KW&F[8WF%S@r');
  define('LOGGED_IN_KEY',    'Ee7[c-Z_awlOYCx%;L$/c}z(}ib&W|9Ix/-K{0ai&h$m gjzS0{xeTJhFX%[Pxm*');
  define('NONCE_KEY',        'AcX+&FqiXqw| mlj09szMU_sxTTy~nSNVr|ZV+h@N&qwg,}+9 jFa-y*fm^?J[sQ');
  define('AUTH_SALT',        '#v^nF=dB{rXc # Ck%@=f/UQ=m~C_W7*^8hw-~fXJeH>Afc`e[DZ4=,09Klu =d^');
  define('SECURE_AUTH_SALT', 'M<!D]=R5X8 RFiS}|-W<Fu0R/;NBx_|Vv#B]&1r,q `ptZ,F.LWEhM4y-Hsq$y{1');
  define('LOGGED_IN_SALT',   '^m,t-XP+c,yawM=nS-M <FmoZ,X%W>%PWJ)+p4q]&gtj .%fluz!JkS(g#Gi}d1l');
  define('NONCE_SALT',       'VB x)2b`VY([8n^sQ]`u9s]^uf `ssNRY<V/pge^ox-8%2zfAzkhH7.%{%e~!*<O');
}
else if (is_readable(__DIR__ .'/secure/wp-config.php'))
{
  include_once __DIR__ .'/secure/wp-config.php';
}
