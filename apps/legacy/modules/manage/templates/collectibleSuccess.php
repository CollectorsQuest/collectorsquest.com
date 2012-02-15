<?php
/**
 * @var  $collectible  Collectible
 * @var  $form  CollectibleEditForm
 */
?>
<br class="clear" />

<form action="<?php echo url_for('@manage_collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug()); ?>" method="post" enctype="multipart/form-data">

  <?php include_partial('manage/collectible_form', array('form' => $form, 'collectible' => $collectible))  ?>

  <div class="clear append-bottom">&nbsp;</div>
  <div class="span-18" style="text-align: right;">
    <?php cq_button_submit(__('Save Changes'), null, 'float: right;'); ?>
  </div>

  <?php echo $form['_csrf_token']; ?>
</form>

<script src="/js/jquery/tags.js" type="text/javascript"></script>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  $(function()
  {
    $('#collectible_description').tinymce(
    {
      script_url: '/js/tiny_mce/tiny_mce.js',
      content_css : "/css/legacy/tinymce.css",

      theme: "advanced",
      theme_advanced_buttons1: "formatselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
      theme_advanced_buttons2: "",
      theme_advanced_buttons3: "",
      theme_advanced_toolbar_location: "external",
      theme_advanced_toolbar_align: "left",
      theme_advanced_resizing: true
    });

    $('#collectible_tags').fcbkcomplete(
    {
      json_url: '<?php echo url_for('@ajax_autocomplete?section=tags'); ?>',
      maxshownitems: 10,
      cache: true,
      filter_case: true,
      filter_hide: true,
      firstselected: true,
      filter_selected: true,
      addoncomma: true,
      input_min_size: 2,
      width: '388px',
      newel: true
    });
  });
</script>
<?php cq_end_javascript_tag(); ?>
