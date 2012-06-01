<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
  window.jQuery || document.write('<script src="<?= cq_javascript_src('frontend/jquery.js'); ?>"><\/script>');
</script>

<!--[if lt IE 7]>
<script>
  var IE6UPDATE_OPTIONS = {
    icons_path: "/images/legacy/ie6update/",
    message: "<?= __('Internet Explorer is missing updates required to view this site. Click here to update...'); ?>",
    url: "http://www.google.com/chromeframe"
  }
</script>
<script src="/js/ie6update.js"></script>
<![endif]-->

<?php
  // Include the cqcdns.com javascript files
  // ice_include_javascripts();

  // Include the cqcdns.com javascript files
  cq_include_javascripts();
?>
<script>
  // http://stackoverflow.com/a/8567229
  (function ($, window, document){
    for (func in window.docready) {
      $(document).ready(window.docready[func]);
    }
  }(jQuery, this, this.document));
</script>


<script>
  // Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
  Modernizr.load([{
    test : Modernizr.isauthenticated,
    yep  : '<?= javascript_path('frontend/scripts.authenticated.bundle.' . GIT_REVISION . '.js'); ?>',
    both : '<?= javascript_path('frontend/scripts.common.bundle.' . GIT_REVISION . '.js'); ?>'
  }]);
</script>

<script>
  Modernizr.load('//s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fa2c6240b775d05');
</script>

<?php
  // Include analytics code only in production
  if (sfConfig::get('sf_environment') === 'prod')
  {
    include_partial('global/js/analytics');
  }
?>
