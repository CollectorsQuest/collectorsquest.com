<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?= cq_javascript_src('frontend/jquery.js'); ?>"><\/script>')</script>

<?php
  // Include the cqcdns.com javascript files
  ice_include_javascripts();

  // include application JS files
  cq_include_javascripts();

  /** @var $sf_user cqFrontendUser */
  if ($sf_user->isAuthenticated())
  {
    use_javascript('tiny_mce/jquery.tinymce.js');
    use_javascript('jquery/autogrow.js');
    use_javascript('jquery/editable.js');

    include_partial('global/js/authenticated');
  }
?>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  function cq_not_implemented_yet()
  {
    alert('<?php echo __("We are sorry but this feature is not yet available. We are working on the final set of missing functonality.\\n\\nThank you for the understanding. (the CollectorsQuest.com team)"); ?>');

    return true;
  }
</script>
<?php cq_end_javascript_tag(); ?>

<?php
  // Echo all the javascript for the page
  cq_echo_javascripts();

  // Include analytics code only in production
  if (sfConfig::get('sf_environment') === 'prod')
  {
    include_partial('global/js/analytics');
  }
?>
