<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'about', __('Tell us about %username%', array('%username%' => $collector->getDisplayName()))); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?php echo cq_textarea_tag($form, 'about', array('width' => 450, 'height' => 150, 'rich' => false)); ?>
</div>
<br clear="all"/><br>


<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'collections', __("Tell us about %username%'s collections", array('%username%'=>$collector->getDisplayName()))); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?php echo cq_textarea_tag($form, 'collections', array('width' => 450, 'height' => 100, 'rich' => false)); ?>
</div>
<br clear="all"/><br>




<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'interests', __("Tell us about %username%'s interests", array('%username%'=>$collector->getDisplayName()))); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?php echo cq_textarea_tag($form, 'interests', array('width' => 450, 'height' => 100, 'rich' => false)); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'collecting', __('What do you collect?')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'collecting', array('width' => 450)); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'most_spent', __('What is the most you&apos;ve spent on an item?')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'most_spent', array('width' => 100)); ?>
  <?= $form['most_spent']->renderError(); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'anually_spent', __('How much do you spend annually? (in USD)')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'anually_spent', array('width' => 100)); ?>
  <?= $form['anually_spent']->renderError(); ?>
</div>
