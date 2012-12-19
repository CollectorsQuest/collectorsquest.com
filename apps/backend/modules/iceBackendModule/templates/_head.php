<meta name="viewport" content="width=device-width">

<?php
  cq_combine_stylesheets(array(
    '/backend/css/h5bp.css', '/backend/css/bootstrap.css', '/backend/css/responsive.css',
    '/assets/css/jquery/bootstrap.css', '/assets/css/jquery/checkboxes.css',
    '/backend/css/default.css'
  ));

  cq_include_stylesheets();
?>

<?php
  cq_combine_javascripts(array(
    '/assets/js/jquery.js', '/assets/js/jquery/ui.js',
    '/assets/js/bootstrap/alert.js', '/assets/js/bootstrap/collapse.js', '/assets/js/bootstrap/dropdown.js',
    '/assets/js/bootstrap/tooltip.js', '/assets/js/bootstrap/popover.js', '/assets/js/bootstrap/clickover.js',
    '/assets/js/jquery/counter.js', '/assets/js/jquery/targets.js', '/assets/js/jquery/checkboxes.js',
    'backend/application.js'
  ));

  cq_include_javascripts();
?>

<!-- The HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
  <script type="text/javascript" src="/assets/js/html5.js"></script>
<![endif]-->
