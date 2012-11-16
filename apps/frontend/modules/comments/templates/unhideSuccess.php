<?php
  /* @var $comment Comment */

  $link = link_to_model_object('Back to comment thread >>', $comment);
  cq_page_title('Unhide comment on "'.$comment->getModelObject().'"', $link, array());
?>

<div class="row-fluid">
  <h2> Are you sure you want to unhide this comment? </h2>

  <fieldset class="form-horizontal">
    <div class="user-comments">
      <?php
        include_partial('comments/single_comment', array(
            'comment' => $comment,
            'with_controls' => false,
            'force_show' => true,
        ));
      ?>
    </div>
    <div class="form-actions">
      <form method="post" action="<?= url_for('comments_unhide', $comment); ?>" >
        <button type="submit" class="btn btn-warning">Unhide Comment</button>
        <a href="<?= url_for_model_object($comment); ?>" class="btn">Cancel</a>
      </form>
    </div>
  </fieldset>
</div>