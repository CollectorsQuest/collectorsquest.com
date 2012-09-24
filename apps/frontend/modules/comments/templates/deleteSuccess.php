<?php
  /* @var $comment  Comment */
  /* @var $form     CommentDeleteConfirmationForm */

  $link = link_to_model_object('Back to comment thread >>', $comment);
  cq_page_title('Remove comment on "'.$comment->getModelObject().'"', $link, array());
?>

<div class="row-fluid">
  <h2> Are you sure you want to delete this comment? </h2>

  <fieldset class="form-horizontal">
    <div class="user-comments">
      <?php include_partial('comments/single_comment', array('comment' => $comment)); ?>
    </div>

    <?= form_tag($sf_request->getUri()); ?>
      <?= $form->renderHiddenFields(); ?>
      <?= $form['_confirm']->renderRow(); ?>

      <div class="form-actions">
        <button type="submit" class="btn btn-danger">Delete Comment</button>
        <a href="<?= url_for_model_object($comment); ?>" class="btn">Cancel</a>
      </div>
    </form>
  </fieldset>
</div>