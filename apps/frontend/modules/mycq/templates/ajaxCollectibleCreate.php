<?php
/**
 * @var $form               CollectibleCreateForm
 * @var $collectible        Collectible
 * @var $collectible_public Collectible
 * @var $image              iceModelMultimedia
 */
?>

<?php
  if (isset($collectible_public) && !$collectible_public->isNew())
  {
    include_partial(
      'global/loading',
      array('url' => url_for(
        'mycq_collectible_by_slug', array('sf_subject' => $collectible_public, 'return_to' => 'collection')
      ))
    );

    return;
  }
?>

<form action="<?= url_for('@ajax_mycq?section=collectible&page=create&collectible_id=' . $collectible->getId()); ?>"
      method="post" id="form-create-collectible" class="ajax form-horizontal form-modal">

  <h1>Describe Your Item - Step 2</h1>
  <?= $form->renderAllErrors(); ?>

  <div style="position: relative;">
    <?= image_tag_multimedia($image, '75x75', array('style'=> 'position: absolute; top: 0px; right: 10px;')); ?>

    <?= $form['name']->renderRow(); ?>
    <?= $form['description']->renderRow(); ?>
    <?= $form['tags']->renderRow(); ?>
  </div>

  <?php
  /**
   * we append this "help-block" to description filed using js
   * if there are form errors we hide it
   */
  ?>
  <p id="description_help" class="help-block" >
    Add more details about your item.
  </p>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Finish
    </button>
    <button type="reset" class="btn" onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

  <?= $form->renderHiddenFields() ?>
</form>

<script>
  $(document).ready(function()
  {
    $('input.tag', '#form-create-collectible').tagedit({
      autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
      autocompleteOptions: { minLength: 3 },
      // return, comma, semicolon
      breakKeyCodes: [ 13, 44, 59 ]
    });

    $('#collectible_description').wysihtml5({
      "font-styles": false, "image": false, "link": false,
      events:
      {
        "load": function() {
          $('#collectible_description')
            .removeClass('js-hide')
            .removeClass('js-invisible')
            .removeAttr('required');
        },
        "focus": function() {
          $(editor.composer.iframe).autoResize();
        }
      }
    });

    <?php if ($form->hasErrors()): ?>
      $('#description_help').hide();
    <?php else: ?>
      $('textarea#collectible_description').parent().parent().append($('#description_help'));
    <?php endif; ?>

    <?php
      if (isset($collectible_public) && !$collectible_public->isNew())
      {
        echo '$("#form-create-collectible").showLoading();';
      }
    ?>
  });
</script>
