<div id="comments">
  <?php include_component('comments', 'addComment', array('for_object' => $for_object)); ?>

  <?php if ($sf_user->hasFlash('comment_success')): ?>
    <div class="alert alert-success">
      <a class="close" data-dismiss="alert" href="#">×</a>
      <?= $sf_user->getFlash('comment_success'); ?>
    </div>
  <?php endif; ?>

  <?php if ($sf_user->hasFlash('comment_error')): ?>
    <div class="alert alert-error">
      <a class="close" data-dismiss="alert" href="#">×</a>
      <strong>Error adding comment:</strong>
      <br />
      <?= $sf_user->getFlash('comment_error'); ?>
    </div>
  <?php endif; ?>

  <?php include_component('comments', 'showComments', array('for_object' => $for_object)); ?>
</div>