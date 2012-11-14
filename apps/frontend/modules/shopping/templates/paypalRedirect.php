<!DOCTYPE html>
<html>
<head>
  <title>Redirecting...</title>
  <script src="//www.paypalobjects.com/js/external/dg.js"></script>
  <script>
    if (top.opener && (_top = top.opener.top) && (_dgFlow = top.opener.top.dgFlow))
    {
      _top.location.replace('<?= url_for($url); ?>');
      _dgFlow.closeFlow();
      top.close();
    }
    else if (parent.top && (_top = parent.top) && (_dgFlow = parent.top.dgFlow))
    {
      _top.location.replace('<?= url_for($url); ?>');
      _dgFlow.closeFlow();
    }
    else
    {
      window.location.replace('<?= url_for($url); ?>');
    }
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
