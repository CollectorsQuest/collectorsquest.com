<?php
  /* @var $comment  Comment */
  /* @var $form     CommentReportSpamConfirmationForm */

  $link = link_to_model_object('Back to comment thread >>', $comment);
  cq_page_title('Report comment on "'.$comment->getModelObject().'"', $link, array());
?>

<div class="row-fluid">
  <h2> Are you sure you want to report this comment as spam? </h2>

  <fieldset class="form-horizontal">
    <div class="user-comments">
      <?php include_partial('comments/single_comment', array('comment' => $comment, 'force_show' => true)); ?>
    </div>

    <?= form_tag($sf_request->getUri()); ?>
      <?= $form->renderHiddenFields(); ?>
      <?= $form['_confirm']->renderRow(); ?>

      <div class="form-actions">
        <button type="submit" class="btn btn-danger">Report Spam</button>
        <a href="<?= url_for_model_object($comment); ?>" class="btn">Cancel</a>
      </div>
    </form>
  </fieldset>
</div>