
  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
  <script>
    window.jQuery    || document.write('<script src="<?= cq_javascript_src('frontend/jquery.js'); ?>"><\/script>');
    window.jQuery.ui || document.write('<script src="<?= cq_javascript_src('frontend/jquery.ui.js'); ?>"><\/script>');

    // Store a reference to the original remove method.
    window.cq._ready = jQuery.fn.ready;

    // Define overriding method. (http://stackoverflow.com/a/8567229)
    jQuery.fn.ready = window.cq.ready;
  </script>
