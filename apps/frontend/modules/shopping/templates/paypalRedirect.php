<!DOCTYPE html>
<html>
<head>
  <title>Redirecting...</title>
  <script>
    top.location.replace('<?= url_for($url); ?>');
  </script>
</head>
<body>
  <h1>If this page does not redirect <a href="<?= url_for($url); ?>" target="_top">Click Here</a></h1>
</body>
</html>
