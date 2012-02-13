<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'about_me', __('Tell us about yourself')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?php echo cq_textarea_tag($form, 'about_me', array('width' => 450, 'height' => 150, 'rich' => false)); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'about_collections', __("Tell us about your collections")); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?php echo cq_textarea_tag($form, 'about_collections', array('width' => 450, 'height' => 100, 'rich' => false)); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'about_interests', __("Tell us about your interests")); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?php echo cq_textarea_tag($form, 'about_interests', array('width' => 450, 'height' => 100, 'rich' => false)); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'about_what_you_collect', __('What do you collect?')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'about_what_you_collect', array('width' => 450)); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'about_most_expensive_item', __('What is the most you&apos;ve spent on an item?')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'about_most_expensive_item', array('width' => 100)); ?>
  <?= $form['about_most_expensive_item']->renderError(); ?>
</div>
<br clear="all"/><br>

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'about_annually_spend', __('How much do you spend annually? (in USD)')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'about_annually_spend', array('width' => 100)); ?>
  <?= $form['about_annually_spend']->renderError(); ?>
</div>
