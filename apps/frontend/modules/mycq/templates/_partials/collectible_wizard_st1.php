
  <div class="row-fluid">
    <div class="span4">
      <?php
      include_partial(
        'mycq/partials/wizard_image_upload',
        array('form' => $upload_form, 'model' => 'Collectible', 'collectible' => $form->getObject())
      );
      ?>
    </div>
    <div class="span8">
      <form  action="<?= url_for('ajax_mycq', array('section' => 'collectible', 'page' => 'Wizard')); ?>"
             method="post" class="form-horizontal" id="wz-step1">
      <?= $form; ?>
      </form>
    </div>
  </div>

  <input type="hidden" name="step" value="1" />
