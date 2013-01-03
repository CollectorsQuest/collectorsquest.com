<form  action="<?= url_for('ajax_mycq', array('section' => 'collectible', 'page' => 'Wizard')); ?>"
       method="post" class="form-horizontal" id="wz-step2">
  <?= $form; ?>
  <input type="hidden" name="step" value="2" />
</form>