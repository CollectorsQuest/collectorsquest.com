<?php

$webdir   = __DIR__;
$cssdir   = __DIR__ . '/css';
$lessdir  = __DIR__ . '/less';
$jsdir    = __DIR__ . '/js';

/**
 * Reuse symfony's cache directory
 * Added benefit: "php ./symfony cc" will clear assets cache also
 */
$cachedir = realpath(__DIR__ . '/../cache');

if (isset($_GET['revision']))
{
  $cache    = isset($_GET['cache']) ? (bool) $_GET['cache'] : true;
  $revision = intval($_GET['revision']);
}
else
{
  $cache    = false;
  $revision = rand(1, PHP_INT_MAX);
}

$type = isset($_GET['type']) ? $_GET['type'] : null;
$elements = isset($_GET['files']) ? explode(',', $_GET['files']) : array();

foreach ($elements as $k => $element)
{
  // Special case for iceBackendPlugin web/ resources
  if (substr($element, 0, 12) == '/backend/js/')
  {
    $elements[$k] = substr_replace($element, '/../plugins/iceBackendPlugin/web/js/', 0, 12);
  }
  else if (substr($element, 0, 13) == '/backend/css/')
  {
    $elements[$k] = substr_replace($element, '/../plugins/iceBackendPlugin/web/css/', 0, 13);
  }
  // Special case for iceAssetsPlugin web/ resources
  else if (substr($element, 0, 11) == '/assets/js/')
  {
    $elements[$k] = substr_replace($element, '/../plugins/iceAssetsPlugin/web/js/', 0, 11);
  }
  else if (substr($element, 0, 12) == '/assets/css/')
  {
    $elements[$k] = substr_replace($element, '/../plugins/iceAssetsPlugin/web/css/', 0, 12);
  }
}

// Determine the directory and type we should use
switch ($type)
{
  case 'css':
    $base = realpath($cssdir);
    break;
  case 'less':
    $base = realpath($lessdir);
    break;
  case 'javascript':
    $base = realpath($jsdir);
    break;
  default:
    header ('HTTP/1.0 503 Not Implemented');
    exit;
};

// Determine last modification date of the files
$lastmodified = 0;
while (list(,$element) = each($elements))
{
  if (empty($element))
  {
    continue;
  }

  if ($type == 'javascript' && substr($element, -3) != '.js')
  {
    $element .= '.js';
  }
  else if ($type == 'css' && substr($element, -4) != '.css' && substr($element, -5) != '.less')
  {
    $element .= '.css';
  }

  if ('/' == $element[0])
  {
    $path = realpath($webdir . $element);
  }
  else if ('less' == $type || ('css' == $type && substr($element, -5) == '.less'))
  {
    $path = realpath($lessdir .'/'. $element);
  }
  else
  {
    $path = realpath($base .'/'. $element);
  }

  if (!file_exists($path))
  {
    header ('HTTP/1.0 404 Not Found');
    exit;
  }

  $lastmodified = max($lastmodified, filemtime($path));
}

$lastmodified = $lastmodified + $revision;
$hash = $lastmodified .'-'. md5($_GET['files']) .'-'. $revision;
header ('Etag: "'. $hash .'"');

header('Vary: Accept-Encoding');
header('Last-Modified: '. gmdate('D, d M Y H:i:s', $lastmodified) .' GMT');
header('Expires: '. gmdate('D, d M Y H:i:s', $lastmodified + 2592000) .' GMT');

if (
  isset($_SERVER['HTTP_IF_NONE_MATCH']) &&
  stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"'. $hash .'"'
)
{
  // Return visit and no modifications, so do not send anything
  header ('HTTP/1.0 304 Not Modified');
  header ('Content-Length: 0');
}
else
{
  // First time visit or files were modified
  if ($cache)
  {
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
    {
      // Determine supported compression method
      $gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
      $deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');

      // Determine used compression method
      $encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');
    }
    else
    {
      $encoding = 'none';
    }

    // Check for buggy versions of Internet Explorer
    if (
      isset($_SERVER['HTTP_USER_AGENT']) &&
      !strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') &&
      preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)
    )
    {
      $version = floatval($matches[1]);

      if ($version < 6)
      {
        $encoding = 'none';
      }
      else if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1'))
      {
        $encoding = 'none';
      }
    }

    // Try the cache first to see if the combined files were already generated
    $cachefile = 'cache-' . $hash . '.' . $type . ($encoding != 'none' ? '.' . $encoding : '');

    if (file_exists($cachedir . '/' . $cachefile))
    {
      if ($fp = fopen($cachedir . '/' . $cachefile, 'rb'))
      {
        if ($encoding != 'none')
        {
          header ('Content-Encoding: ' . $encoding);
        }

        header ('Content-Type: text/' . $type);
        header ('Content-Length: ' . filesize($cachedir . '/' . $cachefile));

        fpassthru($fp);
        fclose($fp);

        exit;
      }
    }
  }

  // Get contents of the files
  $contents = '';
  reset($elements);
  while (list(,$element) = each($elements))
  {
    if (empty($element))
    {
      continue;
    }

    if ($type == 'javascript' && substr($element, -3) != '.js')
    {
      $element .= '.js';
    }
    if ($type == 'css' && substr($element, -4) != '.css' && substr($element, -5) != '.less')
    {
      $element .= '.css';
    }

    if ('/' == $element[0])
    {
      $path = realpath($webdir . $element);
    }
    else if ($type == 'css' && substr($element, -5) === '.less')
    {
      $path = realpath($lessdir .'/'. $element);
    }
    else
    {
      $path = realpath($base .'/'. $element);
    }

    if (substr($element, -5) === '.less')
    {
      require_once __DIR__ . '/../plugins/iceLibsPlugin/lib/vendor/Lessc.class.php';

      $less = new Lessc($path);
      $contents .= "\n\n". $less->parse();
    }
    else
    {
      $contents .= "\n\n" . file_get_contents($path);
    }
  }

  if ($type == 'css')
  {
    $pattern = '!/\*[^*]*\*+([^/][^*]*\*+)*/!';
    $contents = preg_replace($pattern, '', $contents);

    // remove new lines, tabs, spaces
    $contents = str_replace(
      array("\r\n", "\r", "\n", "\t", ' {', '} ', ';}'),
      array('', '', '', '', '{', '}', '}'),
      $contents
    );

    // drop more unecessary spaces
    $contents = preg_replace(
      array('!\s+!','!(\w+:)\s*([\w\s,#]+;?)!'), array(' ','$1$2'), $contents
    );
    $contents = trim($contents);
  }

  // Send Content-Type
  header ('Content-Type: text/' . $type);

  if (isset($encoding) && $encoding != 'none')
  {
    // Send compressed contents
    $contents = gzencode($contents, 9, isset($gzip) && $gzip ? FORCE_GZIP : FORCE_DEFLATE);
    header ('Content-Encoding: ' . $encoding);
    header ('Content-Length: ' . strlen($contents));

    echo $contents;
  }
  else
  {
    // Send regular contents
    header ('Content-Length: ' . strlen($contents));

    echo $contents;
  }

  // Store cache
  if (true === $cache && isset($cachefile))
  {
    file_put_contents($cachedir . '/' . $cachefile, $contents);
  }
}
