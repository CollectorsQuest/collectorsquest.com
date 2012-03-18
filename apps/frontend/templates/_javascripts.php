<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
  window.jQuery || document.write('<script src="<?= cq_javascript_src('frontend/jquery.js'); ?>"><\/script>')
  $ = jQuery; for(n in docready) $(document).ready(docready[n]);
</script>

<?php
  // Include the cqcdns.com javascript files
  ice_include_javascripts();

  // Include the cqcdns.com javascript files
  cq_include_javascripts();
?>

<script>
  // Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
  Modernizr.load([{
    test : Modernizr.isAuthenticated,
    yep  : '<?= javascript_path('frontend/scripts.authenticated.bundle.' . GIT_REVISION . '.js'); ?>',
    both : '<?= javascript_path('frontend/scripts.common.bundle.' . GIT_REVISION . '.js'); ?>'
  }]);
</script>

<?php
  // Include analytics code only in production
  if (sfConfig::get('sf_environment') === 'prod')
  {
    include_partial('global/js/analytics');
  }
?>
