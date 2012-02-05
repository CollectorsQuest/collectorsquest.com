<?php slot('sidebar'); ?>
  <h2 style="margin-top: 10px;">Quick Tips</h2>
  <div style="margin: 0 10px;">
    <p>How to search:</p>
    <ol style="margin: 0; padding-left: 25px;">
      <li style="margin-bottom: 10px;"></li>
    </ol>
  </div>
<?php end_slot(); ?>

<br clear="all">
<form action="<?= url_for('@search'); ?>" method="post">

  <div class="span-4" style="text-align: right;">
    <?= cq_label_for($form, 'q', __('Search For:')); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?= cq_input_tag($form, 'q', array('width' => 400)); ?>
    <?= $form['q']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?= cq_label_for($form, 'types', __('Search In:')); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?= $form['types']; ?>
    <?= $form['types']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?= cq_label_for($form, 'category', __('Category:')); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?= cq_textarea_tag($form, 'category', array('width' => 400, 'height' => 300)); ?>
    <?= $form['category']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-16" style="text-align: right;">
    <?php cq_button_submit(__('Search'), null, 'float: right;'); ?>
  </div>
</form>
