<form  action="<?= url_for('ajax_mycq', array('section' => 'collectible', 'page' => 'Wizard')); ?>"
       method="post" class="form-horizontal" id="wz-step3">
  <?= $form; ?>
  <input type="hidden" name="step" value="3" />
</form>