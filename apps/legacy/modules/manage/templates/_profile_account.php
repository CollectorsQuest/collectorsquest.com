<div class="span-5" style="text-align: right;">
  <label><?= __('Your profile photo:'); ?></label>
</div>
<div class="prepend-1 span-12 last">
  <div style="float: right;"><?php echo image_tag_collector($collector); ?></div>
  <?= $form['photo']; ?>
  <?= $form['photo']->renderError(); ?>
  <br><br>
  <span style="color: grey; margin-right: 10px;">
    All popular image formats are supported but the file should be less than 1MB in size!
  </span>
</div>

<br clear="all"/><br>
<hr style="border: 1px dotted grey; border-bottom: 0;">
<br clear="all"/>

<div class="span-5" style="text-align: right;">
  <label><?= __('Username:'); ?></label>
</div>
<div class="prepend-1 span-12 last">
  <b><?= $collector->getUsername(); ?></b>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'display_name', __('Display Name:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'display_name', array('width' => 400)); ?>
  <?= $form['display_name']->renderError(); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'email', __('E-mail:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
</div>
<div class="prepend-1 span-6 last">
  <?= cq_input_tag($form, 'email', array('width' => 400)); ?>
  <?= $form['email']->renderError(); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'password', __('Change Password:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'password', array('width' => 250)); ?>
  <?= $form['password']->renderError(); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'password_again', __('Confirm Password:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'password_again', array('width' => 250)); ?>
  <?= $form['password_again']->renderError(); ?>
</div>