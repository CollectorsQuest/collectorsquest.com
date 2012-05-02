<?php

require __DIR__ .'/../config/bootstrap.php';

// Set the location of the shared/ directory
$shared = (SF_ENV === 'dev') ?
  '/www/vhosts/collectorsquest.dev/shared' :
  '/www/vhosts/collectorsquest.com/shared';

// Set the location of teh web/ directory
$web = $_SERVER['DOCUMENT_ROOT'];

@list(, $type, $size, $filename) = explode('/', $_SERVER['REQUEST_URI']);
if (in_array($type, array('image', 'video')))
{
  // Include sfYaml from Symfony
  include_once __DIR__ .'/../lib/vendor/symfony/lib/yaml/sfYaml.php';

  $databases = sfYaml::load(__DIR__ .'/../config/databases.yml');

  if (empty($databases['prod']))
  {
    $databases['prod'] = $databases['all'];
  }
  if (empty($databases['dev']))
  {
    $databases['dev']  = $databases['all'];
  }

  $dbh = new PDO(
    $databases[SF_ENV]['propel']['param']['dsn'],
    $databases[SF_ENV]['propel']['param']['username'],
    $databases[SF_ENV]['propel']['param']['password']
  );

  preg_match('/-(\d+)\.(jpg|flv)/i', $filename, $m);

  if (isset($m[1]) && ctype_digit($m[1]))
  {
    $stmt = $dbh->prepare("SELECT * FROM `multimedia` WHERE `id` = ? AND `type` = ? LIMIT 1");

    if ($stmt->execute(array($m[1], $type)))
    {
      $row = $stmt->fetch(PDO::FETCH_NAMED);

      $path  = '/uploads/'. $row['model'] .'/'. date_format(new DateTime($row['created_at']), 'Y/m/d');

      $extension = @array_shift(explode('?', end(explode('.', $filename))));
      $original = implode('.', array($path .'/'. $row['md5'], 'original', $extension));
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

      // Does the multimedia exist? (we want to avoid extra stat() calls)
      $is_readable = is_readable($shared . $path);

      if ($type === 'image' && !$is_readable && is_readable($shared . $original))
      {
        $thumb = iceModelMultimediaPeer::makeThumb($shared . $original, $size, 'top', false);
        $thumb && $thumb->saveAs($shared . $path, 'image/jpeg') && ($is_readable = true);
      }

      if ($is_readable)
      {
        // Send Content-Type and the X-SendFile header
        header("Content-Type: ". $content_type);
        header("X-SendFile: ". $shared . $path);

        exit;
      }
      else
      {
        $path  = '/images/'. SF_APP .'/multimedia/'. $row['model'] .'/'. $size .'.png';

        // Send Content-Type and the X-SendFile header
        header("Content-Type: image/png");
        header("X-SendFile: ". $web . $path);

        exit;
      }
    }
  }
}

header("HTTP/1.0 404 Not Found");
