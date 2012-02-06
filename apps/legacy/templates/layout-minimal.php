<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>

  <link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen, projection">
  <link rel="stylesheet" href="/css/print.css" type="text/css" media="print">
  <!--[if lt IE 8]><link rel="stylesheet" href="/css/ie.css" type="text/css" media="screen, projection"><![endif]-->
  <link href="/css/legacy/jquery.css" rel="stylesheet" type="text/css" media="screen, projection"/>

  <?php
    sfConfig::set('symfony.asset.stylesheets_included', true);
    $css = @implode(',', array_keys($sf_response->getStylesheets()));

    if (!empty($css))
    {
      echo '<link rel="stylesheet" type="text/css" media="screen" href="/combine.php?type=css&files='. $css .'&revision='. SVN_REVISION .'" />';
    }
  ?>

  <style type="text/css">
    body { margin: 0; padding: 20px; margin-top: 20px; background: #eee; }
    body, td, th { font: 11px Verdana, Arial, sans-serif; color: #333 }
    a { color: #333 }
    h1 { margin: 0 0 0 10px; padding: 10px 0 10px 0; font-weight: bold; font-size: 120% }
    h2 { margin: 0; padding: 5px 0; font-size: 110% }
    ul { padding-left: 20px; list-style: decimal }
    ul li { padding-bottom: 5px; margin: 0 }
    ol { font-family: monospace; white-space: pre; list-style-position: inside; margin: 0; padding: 10px 0 }
    ol li { margin: -5px; padding: 0 }
    ol .selected { font-weight: bold; background-color: #ddd; padding: 2px 0 }
    table.vars { padding: 0; margin: 0; border: 1px solid #999; background-color: #fff; }
    table.vars th { padding: 2px; background-color: #ddd; font-weight: bold }
    table.vars td  { padding: 2px; font-family: monospace; white-space: pre }
    p.error { padding: 10px; background-color: #f00; font-weight: bold; text-align: center; -moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; }
    p.error a { color: #fff }
    .container { padding: 30px 40px; border: 1px solid #ddd; background-color: #fff; text-align:left; -moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; min-width: 770px; max-width: 770px }
    #message { padding: 10px; margin-bottom: 10px; background-color: #eee; -moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; }
    a.file_link { text-decoration: none; }
    a.file_link:hover { text-decoration: underline; }
    .code, #sf_settings, #sf_request, #sf_response, #sf_user, #sf_globals { overflow: auto; }
  </style>

  <?php
    // TODO: We are stuck at jquery version 1.5 because of jquery.beutytips.js
    if ('dev' == SF_ENV)
    {
      echo '<script type="text/javascript" src="/js/jquery.js"></script>';
    }
    else
    {
      echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>';
    }
  ?>
</head>
<body>
  <div class="container">
    <?php echo $sf_content ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>
</body>
</html>
