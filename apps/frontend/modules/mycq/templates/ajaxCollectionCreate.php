<?php
/**
 * @var $form CollectionCreateForm
 * @var $collectible Collectible
 * @var $categories ContentCategory[]
 * @var $image iceModelMultimedia
 */
?>

<form action="<?= url_for('ajax_mycq', array('section' => 'collection', 'page' => 'create', 'collectible_id' => $collectible->getId())); ?>"
      method="post" class="ajax form-horizontal form-modal">

  <h1>Describe Your Collection - Step 2</h1>
  <?= $form->renderAllErrors(); ?>

  <div style="position: relative;">
    <?= image_tag_multimedia($image, '75x75', array('style'=> 'position: absolute; top: -10px; right: 10px;')); ?>

    <?= $form['name']->renderRow(); ?>
    <?= $form['description']->renderRow(); ?>
    <?= $form['tags']->renderRow(); ?>

    <div class="control-group" style="margin-bottom: 0px;">
      <?= $form['content_category_id']->renderLabel('Category') ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?php cq_content_categories_to_ul($categories, array('id' => 'categories', 'tabindex'=>3)); ?>
        </div>
        <p class="help-block">
          Choose a category from the list above which best fits your collection.
        </p>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Finish
    </button>
    <button type="reset" class="btn" onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>
  <?= $form->renderHiddenFields(); ?>
</form>

<script>
$(document).ready(function()
{
  $('#collection_description')
    .css({'height':'53px', 'min-height':'53px'})
    .wysihtml5({
    "font-styles": false, "image": false, "link": false,
    events:
    {
      "load": function() {
        $('#collection_description')
          .removeClass('js-hide')
          .removeClass('js-invisible')
          .removeAttr('required')
          .css({'height':'53px', 'min-height':'53px'});
      }
    }
  });

  var categories_tabindex = $('#categories').attr('tabIndex') || 0;
  $("#categories").attr('tabIndex', 0).columnview({
    multi: false, preview: false,
    onchange: function(element) {
      if (0 < $(element).data('object-id')) {
        $("#collection_content_category_id").val($(element).data('object-id'));
      }
      $('#categories').scrollLeft(500);
      $('.feature', '#categories').hide();
    }
  });

  $('.top', '#categories').attr('role', 'listbox').attr('tabIndex', categories_tabindex);

  // making categories area shorter
  $('.columnview').css({'height':'107px', 'max-height':'107px'});
});
</script>
