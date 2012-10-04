
  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script>
    window.jQuery || document.write('<script src="<?= cq_javascript_src('frontend/jquery.js'); ?>"><\/script>');
  </script>

  <!-- Grab Google CDN's jQuery UI, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
  <script>
    window.jQuery.ui || document.write('<script src="<?= cq_javascript_src('frontend/jquery.ui.js'); ?>"><\/script>');
  </script>

  <script>
    // We want to delay the $(document).ready()
    // until Modernizr has loaded all dependencies
    $.holdReady(true);
  </script>
