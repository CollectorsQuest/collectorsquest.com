<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */
?>

<form action="<?= url_for('mycq_collection_by_slug', $collection); ?>" method="post"
      enctype="multipart/form-data" class="form-horizontal spacer-bottom-reset">
<div class="row-fluid">
  <div class="span3">
    <div class="drop-zone-large">
      <a class="plus-icon-holder h-center" href="#">
        <i class="icon-plus icon-white"></i>
      </a>
      <a class="blue-link" href="#">
        Click to Add Main<br>Thumbnail or Drag and Drop<br>from Collection
      </a>
    </div>
  </div>
  <div class="span9">
    <?php
      $link = link_to(
        'View collection page &raquo;',
        'collection_by_slug', $collection,
        array('class' => 'text-v-middle link-align')
      );

      cq_sidebar_title(
        $collection->getName(), $link,
        array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
      );
    ?>

    <fieldset>
        <?= $form; ?>
    </fieldset>

  </div>
  <div class="row-fluid">
    <div class="span12">
      <div class="form-actions text-center">
        <button class="btn btn-primary blue-button" type="submit">Save changes</button>
        <button class="btn gray-button spacer-left">Cancel</button>
      </div>
    </div>
  </div>
</div>
</form>

<div id="mycq-tabs">
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
    <?php
      include_component(
        'mycq', 'dropbox',
        array('instructions' => array(
          'position' => 'top',
          'text' => 'Drag and drop collectibles into your collections.')
        )
      );
    ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    $('input.tag').tagedit({
      autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
      // return, comma, semicolon
      breakKeyCodes: [ 13, 44, 59 ]
    });

    $('#collection_description').wysihtml5({
      "font-styles": false, "image": false, "link": false
    });
  });
</script>
