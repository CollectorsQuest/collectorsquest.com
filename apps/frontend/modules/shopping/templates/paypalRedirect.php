<!DOCTYPE html>
<html>
<head>
  <title>Redirecting...</title>
  <script src="//www.paypalobjects.com/js/external/dg.js"></script>
  <script>
    var _top = top;

    if (top.opener && (_top = top.opener.top) && (_dgFlow = top.opener.top.dgFlow))
    {
      _dgFlow.closeFlow();
      top.close();
    }

    (_top || top).location.replace('<?= url_for($url); ?>');
  </script>
</head>
<body style="background: #fff;">
<div>
  <div style="width: 80%; margin: auto;">
    <h1>If this page does not redirect <a href="<?= url_for($url); ?>" target="_top">click here</a></h1>
  </div>
</div>
</body>
</html>
