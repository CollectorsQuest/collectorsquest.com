<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>JSON Response</title>
</head>
<body>
  json:<br>
  <?php echo json_encode($sf_data->getRaw('data')); ?>
  <br>
  data structure:<br>
  <pre>
  <?php print_r($sf_data->getRaw('data')); ?>
  </pre>
</body>
</html>
