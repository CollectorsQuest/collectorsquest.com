<?php
/**
 * @var $form CollectibleWizardStep1Form
 */

?>

<form  action="<?= url_for('ajax_mycq', array('section' => 'collectible', 'page' => 'Wizard')); ?>"
       method="post" class="form-horizontal" id="wz-step1">
  <?= $form->renderGlobalErrors(); ?>
  <?= $form['collection_collectible_list']->renderRow(); ?>
  <?= $form['content_category']->renderRow(); ?>
  <?= $form->renderHiddenFields(); ?>

  <input type="hidden" name="step" value="1" />
  <input type="hidden" name="collectible_id" value="<?= $form->getObject()->getId() ?>" />
</form>

<script type="text/javascript">
  $(document).ready(function()
  {
    'use strict';

    $(".chzn-select").chosen();

  });
</script>
