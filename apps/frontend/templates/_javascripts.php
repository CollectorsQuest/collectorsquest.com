<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script>
  window.jQuery    || document.write('<script src="<?= cq_javascript_src('frontend/jquery.js'); ?>"><\/script>');
  window.jQuery.ui || document.write('<script src="<?= cq_javascript_src('frontend/jquery.ui.js'); ?>"><\/script>');
</script>

<?php if ($sf_params->get('gcf')): ?>
<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<script> CFInstall.check({ mode: "overlay" }); </script>
<?php endif; ?>

<?php
  if (SF_ENV === 'prod')
  {
    // Include the cqcdns.com javascript files
    ice_include_javascripts();
  }

  // Include the cqcdns.com javascript files
  cq_include_javascripts();
?>

<script>
  Modernizr.load([{
    test: Modernizr.isauthenticated,
    yep:  '<?= javascript_path('frontend/scripts.authenticated.bundle.' . GIT_REVISION . '.js'); ?>',
    both: '<?= javascript_path('frontend/scripts.common.bundle.' . GIT_REVISION . '.js'); ?>',
    complete: function ()
    {
      // http://stackoverflow.com/a/8567229
      (function ($, window, document)
      {
        for (func in window.docready) {
          $(document).ready(window.docready[func]);
        }
      }(jQuery, this, this.document));

      // Execute the controller in scripts.common.bundle.js
      $(document).ready(CONTROLLER.init());
    }
  }]);
</script>

<script>
(function(d, t)
{
  var addthis = d.createElement(t);
  var s = d.getElementsByTagName(t)[0];

  addthis.async = true;
  addthis.src = '//s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fa2c6240b775d05&async=true';
  s.parentNode.insertBefore(addthis, s);

})(document, 'script');

$(window).load(function() {
  addthis.init()
})
</script>

<?php if ($sf_request->isSecure()): ?>
  <script src="https://www.startssl.com/seal.js"></script>
<?php endif; ?>
