<?php
/**
 * @var $sf_user cqFrontendUser
 */
?>

<?php if (has_slot('flash_error')): ?>
<div class="alert alert-error alert-block in" data-alert="alert">
  <a class="close" data-dismiss="alert">×</a>
  <h4 class="alert-heading"><?= __('Oh snap! You got an error!', array(), 'flash'); ?></h4>
  <?= get_slot('flash_error'); ?>
</div>
<?php elseif ($sf_user->hasFlash('error')): ?>
<div class="alert alert-error in" data-alert="alert">
  <a class="close" data-dismiss="alert">×</a>
  <?= implode('<br><br>', array_filter((array) $sf_user->getFlashAndDelete('error'))); ?>
</div>
<?php elseif ($sf_user->hasFlash('success')): ?>
<div class="alert alert-success in" data-alert="alert">
  <a class="close" data-dismiss="alert">×</a>
  <?= $sf_user->getFlashAndDelete('success'); ?>
</div>
<?php elseif ($sf_user->hasFlash('success', 'ajax')): ?>
<div class="alert alert-success in" data-alert="alert">
  <?= $sf_user->getFlashAndDelete('success', '', 'ajax'); ?>
</div>
<?php elseif ($sf_user->hasFlash('info')): ?>
<div class="alert alert-info in" data-alert="alert">
  <a class="close" data-dismiss="alert">×</a>
  <?= $sf_user->getFlashAndDelete('info'); ?>
</div>
<?php elseif ($sf_user->hasFlash('highlight')): ?>
<div class="alert">
  <?= has_slot('flash_highlight') ? get_slot('flash_highlight') : $sf_user->getFlashAndDelete('highlight'); ?>
</div>
<?php endif; ?>
