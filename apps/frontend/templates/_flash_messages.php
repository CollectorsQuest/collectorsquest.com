<?php if (has_slot('flash_error')): ?>
<div class="alert alert-error alert-block fade in" data-alert="alert">
  <a class="close" data-dismiss="alert">×</a>
  <h4 class="alert-heading"><?= __('Oh snap! You got an error!', array(), 'flash'); ?></h4>
  <?= get_slot('flash_error'); ?>
</div>
<?php elseif ($sf_user->hasFlash('error')): ?>
<div class="alert alert-error fade in" data-alert="alert">
  <a class="close" data-dismiss="alert">×</a>
  <?= implode('<br><br>', array_filter((array) $sf_user->getFlash('error', null, true))); ?>
</div>
<?php elseif ($sf_user->hasFlash('success')): ?>
<div class="alert alert-success fade in" data-alert="alert">
  <a class="close" data-dismiss="alert">×</a>
  <?= $sf_user->getFlash('success', null, true); ?>
</div>
<?php elseif ($sf_user->hasFlash('success_ajax')): ?>
<div class="alert alert-success fade in" data-alert="alert">
  <?= $sf_user->getFlash('success_ajax', null, true); ?>
</div>
<?php elseif ($sf_user->hasFlash('info')): ?>
<div class="alert alert-info fade in" data-alert="alert">
  <a class="close" data-dismiss="alert">×</a>
  <?= $sf_user->getFlash('info', null, true); ?>
</div>
<?php elseif ($sf_user->hasFlash('hightlight')): ?>
<div class="alert">
  <?= has_slot('flash_highlight') ? get_slot('flash_highlight') : $sf_user->getFlash('highlight', null, true); ?>
</div>
<?php endif; ?>
