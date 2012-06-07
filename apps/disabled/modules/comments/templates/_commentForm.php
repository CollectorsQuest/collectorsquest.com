<?php use_helper('Date'); ?>

<a name="comment-form"></a>
<div class="clearfix append-bottom">&nbsp;</div>
<fieldset class="span-17" style="margin-left: 25px;">
  <legend><?= __('Have something to say?'); ?></legend>
  <form action="<?= url_for('comments/comment'); ?>" method="post">

    <?php if (!$sf_user->isAuthenticated()): ?>
      <div class="span-3" style="text-align: right;">
        <?= cq_label_for($form, 'name', __('Name:')); ?>
        <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-13 last">
        <?= cq_input_tag($form, 'name', array('class' => 'required', 'width' => 300)); ?>
        <?= $form['name']->renderError(); ?>
      </div>
      <div class="clear append-bottom">&nbsp;</div>

      <div class="span-3" style="text-align: right;">
        <?= cq_label_for($form, 'email', __('Email:')); ?>
        <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-13 last">
        <?= cq_input_tag($form, 'email', array('width' => 300)); ?>
        <?= $form['email']->renderError(); ?>
      </div>
      <div class="clear append-bottom">&nbsp;</div>

      <div class="span-3" style="text-align: right;">
        <?= cq_label_for($form, 'website', __('Website:')); ?>
        <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-13 last">
        <?= cq_input_tag($form, 'website', array('width' => 300)); ?>
        <?= $form['website']->renderError(); ?>
      </div>
      <div class="clear append-bottom">&nbsp;</div>
    <?php endif; ?>

    <div class="span-3" style="text-align: right;">
      <?= cq_label_for($form, 'body', __('Comment:')); ?>
      <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
    </div>
    <div class="prepend-1 span-13 last">
      <?= cq_textarea_tag($form, 'body', array('class' => 'required', 'width' => 450, 'height' => 200)); ?>
      <?= $form['body']->renderError(); ?>
    </div>
    <div class="clear append-bottom">&nbsp;</div>

    <?php echo $form['referer']->render(); ?>
    <?php echo $form['token']->render(); ?>
    <?php echo $form['_csrf_token']->render(); ?>

    <div class="prepend-12" style="text-align: right;">
      <?php echo cq_button_submit(__('Post Comment'), 'yellow'); ?>
    </div>
  </form>
</fieldset>
