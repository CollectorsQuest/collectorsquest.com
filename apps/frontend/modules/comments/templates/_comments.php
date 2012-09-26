<?php
  /* @var $for_object BaseObject */
  /* @var $with_controls boolean */
?>

<div id="comments">
  <?php include_component('comments', 'addComment', array('for_object' => $for_object)); ?>
  <?php
    if (isset($height) && property_exists($height, 'value'))
    {
      $height->value += 121;
    }
  ?>

  <?php if ($sf_user->hasFlash('success', 'comment')): ?>
    <div class="alert alert-success">
      <a class="close" data-dismiss="alert" href="javascript:void(0)">×</a>
      <?= $sf_user->getFlash('success', '', 'comment'); ?>
    </div>
  <?php endif; ?>

  <?php if ($sf_user->hasFlash('error', 'comment')): ?>
      <?= $sf_user->getFlash('error', '', 'comment'); ?>
  <?php endif; ?>

  <?php
    include_component('comments', 'showComments', array(
        'for_object' => $for_object,
        'height' => &$height,
        'with_controls' => $with_controls,
    )); ?>
</div>
