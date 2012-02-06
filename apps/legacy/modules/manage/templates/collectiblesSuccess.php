<?php

/**
 * @var Collectible  $collectible
 * @var Collection  $collection
 * @var ManageCollectiblesForm  $form
 */
?>

<br clear="all"/>
<form action="<?php echo url_for('manage_collectibles_by_slug', $collection).'?page='. $sf_params->get('page', 1).'&batch='. $sf_params->get('batch'); ?>" method="post" enctype="multipart/form-data">
  <?php echo $form->renderHiddenFields() ?>
  <?php foreach ($form->getEmbeddedForms() as $index => $subform): ?>
    <?php
      $data = $form->getDefault($index);
      $collectible = CollectiblePeer::retrieveByPK($data['id']);
    ?>
    <?php include_partial('manage/collectible_form', array('form' => $form[$index], 'collectible' => $collectible))  ?>
    <hr/><br clear="all">
  <?php endforeach; ?>

  <div class="span-18">
    <?php cq_button_submit(__('Save Changes and edit next >'), null, 'float: right;'); ?>
  </div>
</form>

<script src="/js/jquery/tags.js" type="text/javascript"></script>
<script type="text/javascript">
  jQuery(document).ready(function()
  {
    $('.tags').fcbkcomplete(
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

    jQuery('textarea').tinymce(
    {
      script_url: '/js/tiny_mce/tiny_mce.js',
      content_css : "/css/legacy/tinyMCE.css",

      theme: "advanced",
      theme_advanced_buttons1: "formatselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
      theme_advanced_buttons2: "",
      theme_advanced_buttons3: "",
      theme_advanced_toolbar_location: "external",
      theme_advanced_toolbar_align: "left",
      theme_advanced_resizing: true
    });
  })
</script>
