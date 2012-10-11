<?php include_component_slot('jquery_footer'); ?>

<?php if ($sf_params->get('gcf')): ?>
<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<script> CFInstall.check({ mode: "overlay" }); </script>
<?php endif; ?>

<?php
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
        for (func in window._docready) {
          $(document).ready(window._docready[func]);
        }
      }(jQuery, this, this.document));

      // execute the main controller after template level JS to allow
      // window.cq.settings modifications :)
      $(document).ready(CONTROLLER.init);

      // Let is "free" :)
      $.holdReady(false);
    }
  }]);
</script>

<script>
if (document.getElementById('social-sharing') !== null)
{
  (function(d, t)
  {
    var addthis = d.createElement(t);
    var s = d.getElementsByTagName(t)[0];

    addthis.async = true;
    addthis.src = '//s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fa2c6240b775d05&async=true';
    s.parentNode.insertBefore(addthis, s);

  })(document, 'script');

  $(window).load(function() {
    addthis.toolbox('#social-sharing');
  });
}
</script>

<?php if ($sf_request->isSecure()): ?>
  <script src="https://www.startssl.com/seal.js"></script>
<?php endif; ?>
