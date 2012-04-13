<?php if (has_slot('flash_error')): ?>
<div class="alert alert-error alert-block fade in" data-alert="alert" style="margin-top: 15px;">
  <a class="close" data-dismiss="alert">×</a>
  <h4 class="alert-heading"><?= __('Oh snap! You got an error!', array(), 'flash'); ?></h4>
  <?= get_slot('flash_error'); ?>
</div>
<?php elseif ($sf_user->hasFlash('error')): ?>
<div class="alert alert-error fade in" data-alert="alert" style="margin-top: 15px;">
  <a class="close" data-dismiss="alert">×</a>
  <strong style="font-variant: small-caps;"><?= __('Error:', array(), 'flash'); ?></strong>&nbsp;
  <?= implode('<br/><br/>', array_filter((array) $sf_user->getFlash('error', null, true))); ?>
</div>
<?php elseif ($sf_user->hasFlash('success')): ?>
<div class="alert alert-success fade in" data-alert="alert" style="margin-top: 15px;">
  <a class="close" data-dismiss="alert">×</a>
  <strong style="font-variant: small-caps;"><?= __('Success:', array(), 'flash'); ?></strong>&nbsp;
  <?= $sf_user->getFlash('success', null, true); ?>
</div>
<?php elseif ($sf_user->hasFlash('info')): ?>
<div class="alert alert-info fade in" data-alert="alert" style="margin-top: 15px;">
  <a class="close" data-dismiss="alert">×</a>
  <strong style="font-variant: small-caps;"><?= __('Notice:', array(), 'flash'); ?></strong>&nbsp;
  <?= $sf_user->getFlash('info', null, true); ?>
</div>
<?php elseif ($sf_user->hasFlash('hightlight')): ?>
<div class="alert" style="margin-top: 15px;">
  <?= has_slot('flash_highlight') ? get_slot('flash_highlight') : $sf_user->getFlash('highlight', null, true); ?>
</div>
<?php endif; ?>
