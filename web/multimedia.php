<?php

date_default_timezone_set('America/New_York');

if (!empty($_SERVER['SF_APP']) && !empty($_SERVER['SF_ENV']))
{
  $app = $_SERVER['SF_APP'];
  $env = $_SERVER['SF_ENV'];
}
else
{
  $parts = explode('.collectorsquest.', $_SERVER['SERVER_NAME']);

  list($app, $env) = $parts;
  if ('new' == $env || 'com' == $env)
  {
    $env = 'prod';
  }
}

@list(, $type, $size, $filename) = explode('/', $_SERVER['REQUEST_URI']);
if (in_array($type, array('image', 'video')))
{
  // Include sfYaml from Symfony
  include_once __DIR__ .'/../lib/vendor/symfony/lib/yaml/sfYaml.php';

  $databases = sfYaml::load(__DIR__.'/../config/databases.yml');

  if (empty($databases['prod']))
  {
    $databases['prod'] = $databases['all'];
  }
  if (empty($databases['dev']))
  {
    $databases['dev']  = $databases['all'];
  }

  $dbh = new PDO(
    $databases[$env]['propel']['param']['dsn'],
    $databases[$env]['propel']['param']['username'],
    $databases[$env]['propel']['param']['password']
  );

  preg_match('/-(\d+)\.(jpg|flv)/i', $filename, $m);
  if (isset($m[1]) && ctype_digit($m[1]))
  {
    $stmt = $dbh->prepare("SELECT * FROM `multimedia` WHERE `id` = ? AND `type` = ? LIMIT 1");

    if ($stmt->execute(array($m[1], $type)))
    {
      $row = $stmt->fetch(PDO::FETCH_NAMED);

      $path  = '/uploads/'. $row['model'] .'/'. date_format(new DateTime($row['created_at']), 'Y/m/d');

      $extension = array_shift(explode('?', end(explode('.', $filename))));
      $path = implode('.', array($path .'/'. $row['md5'], $size, $extension));

      switch ($type)
      {
        case 'video':
          $content_type = 'video/x-flv';
          break;
        case 'image':
        default:
          $content_type = 'image/jpeg';
          break;
      }

      if (is_readable('/www/vhosts/collectorsquest.com/shared'. $path))
      {
        // Send Content-Type and the X-SendFile header
        header("Content-Type: ". $content_type);
        header("X-SendFile: /www/vhosts/collectorsquest.com/shared". $path);

        exit;
      }
      else
      {
        $path  = '/images/legacy/multimedia/'. $row['model'] .'/'. $size .'.png';

        // Send Content-Type and the X-SendFile header
        header("Content-Type: image/png");
        header("X-SendFile: ". __DIR__ . $path);

        exit;
      }
    }
  }
}

header("HTTP/1.0 404 Not Found");
