<!DOCTYPE html>
<html>
<head>
  <title>Redirecting...</title>
  <script>
    top.location.replace('<?= url_for($url); ?>');
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
