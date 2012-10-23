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
    $stmt = $dbh->prepare('SELECT * FROM `multimedia` WHERE `id` = ? AND `type` = ? LIMIT 1');

    if ($stmt->execute(array($m[1], $type)))
    {
      $row = $stmt->fetch(PDO::FETCH_NAMED);
      $created_at = new DateTime($row['created_at']);

      $path  = '/uploads/'. $row['model'] .'/'. date_format($created_at, 'Y/m/d');

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

      /**
       * We want to (re)generate the requested thumbnail if:
       *   1. The requested multimedia is image
       *   2. There is original image file available
       *   3. And one of the below condictions:
       *      3.1 The requested thumbnail does not exist already
       *      3.2 For some reason the original image was updated after the thumbnail was generated
       *      3.3 We have a $_GET['crop'] parameter and we need to adjust the crop method
       */
      if (
        $type === 'image' &&
        is_readable($shared . $original) &&
        (
          !$is_readable ||
          filemtime($shared . $original) > filemtime($shared . $path) ||
          !empty($_GET['crop'])
        )
      )
      {
        @list($width, $height) = getimagesize($shared . $original);
        @list($_width, $_height) = explode('x', $size);

        if ($width && ($_width == 0 || $width <= $_width) && $height && ($height <= $_height || $_height == 0))
        {
          copy($shared . $original, $shared . $path);
          $is_readable = true;
        }
        else
        {
          require __DIR__ .'/../config/ProjectConfiguration.class.php';

          /** @var cqApplicationConfiguration $configuration */
          $configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP, SF_ENV, SF_DEBUG);

          $thumb = iceModelMultimediaPeer::makeThumb($shared . $original, $size, @$_GET['crop'] ?: 'top', false);
          $thumb && $thumb->saveAs($shared . $path, 'image/jpeg') && ($is_readable = true);
        }
      }

      $modified_at = (string) @array_shift(array_keys($_GET));
      $modified_at = (ctype_digit($modified_at) && strlen($modified_at) >= 10) ?
        (int) $modified_at :
        $created_at->getTimestamp();

      // Set the Last-Modified header based on the created_at date
      header('Last-Modified: '. gmdate('D, d M Y H:i:s', $modified_at) .' GMT');

      // Expires 30 days after this request
      header('Expires: '. gmdate('D, d M Y H:i:s', time() + 2592000) .' GMT');

      // Set the Etag based on the md5 hash and created_at date
      $etag = $row['md5'] .'-'. $modified_at;
      header('Etag: '. $etag);

      if (
        isset($_SERVER['HTTP_IF_NONE_MATCH']) &&
        stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"'. $etag .'"'
      )
      {
        // Return visit and no modifications, so do not send anything
        header ('HTTP/1.0 304 Not Modified');
        header ('Content-Length: 0');

        exit;
      }
      else if ($is_readable)
      {
        // Send Content-Type and the X-SendFile header
        header('Content-Type: '. $content_type);

        if (php_sapi_name() === 'fpm-fcgi')
        {
          header('HTTP/1.0 200 OK');
          header('Access-Control-Allow-Origin: *');
          header('Access-Control-Allow-Methods: GET');
          header('X-Accel-Redirect: '. $path);
        }
        else
        {
          header('X-SendFile: '. $shared . $path);
        }

        exit;
      }
      else
      {
        $path  = '/images/'. SF_APP .'/multimedia/'. $row['model'] .'/'. $size .'.png';

        // Send Content-Type and the X-SendFile header
        header('Content-Type: image/png');

        if (php_sapi_name() === 'fpm-fcgi')
        {
          header('HTTP/1.0 200 OK');
          header('Access-Control-Allow-Origin: *');
          header('Access-Control-Allow-Methods: GET');
          header('X-Accel-Redirect: '. $path);
        }
        else
        {
          header('X-SendFile: '. $web . $path);
        }

        exit;
      }
    }
  }
}

header('HTTP/1.0 404 Not Found');
