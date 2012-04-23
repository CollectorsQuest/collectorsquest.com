<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
  window.jQuery || document.write('<script src="<?= cq_javascript_src('frontend/jquery.js'); ?>"><\/script>');

  // http://stackoverflow.com/a/8567229
  (function ($, window, document){
    for (func in window.docready) {
      $(document).ready(window.docready[func]);
    }
  }(jQuery, this, this.document));
</script>

  <!-- WP Footer //-->

<?php
  // Include the cqcdns.com javascript files
  ice_include_javascripts();

  // Include the cqcdns.com javascript files
  cq_include_javascripts();
?>

<script>
  // Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
  Modernizr.load([{
    test : Modernizr.isauthenticated,
    yep  : '<?= javascript_path('frontend/scripts.authenticated.bundle.' . GIT_REVISION . '.js'); ?>',
    both : '<?= javascript_path('frontend/scripts.common.bundle.' . GIT_REVISION . '.js'); ?>'
  }]);
</script>

<script>var switchTo5x=true;</script>
<script src="http://w.sharethis.com/button/buttons.js"></script>
<script>stLight.options({publisher:'b69d7375-23f2-424c-9d59-f6bf47e9a722'});</script>

<?php
  // Include analytics code only in production
  if (sfConfig::get('sf_environment') === 'prod')
  {
    include_partial('global/js/analytics');
  }
?>
