<?php
  /** @var $form FrontendCommentForm */ $form;
?>

<div class="add-comment">
  <div class="input-append post-comment">
    <form method="post" action="<?= url_for('@comments_add', true) ?>">
      <?= $form->renderHiddenFields(); ?>

      <div class="extra-fields row-fluid not-authenticated">
        <div class="span4">
          <?= $form['author_name']->render(array('class' => 'span12')); ?>
          <?= $form['author_name']->renderLabel('<small>Name</small>'); ?>
        </div>
        <div class="span4">
          <?= $form['author_email']->render(array('class' => 'span12')); ?>
          <?= $form['author_email']->renderLabel('<small>Email (will not be published)</small>'); ?>
        </div>
      </div>

      <?= $form['body']->render(array('class' => 'shrink', 'placeholder' => ' What do you think?')); ?>
      <button type="button" class="btn btn-large fake">Comment</button>
      <button type="submit" class="btn btn-large hidden" id="submit-comment-add">Comment</button>

      <div class="extra-fields clearfix spacer-top non-optional">
        <label class="checkbox" for="comment_is_notify">
          <?= $form['is_notify']; ?> Notify me of follow-up comments by email.
        </label>
      </div>

      <?= cqStatic::getAyahClient()->getPublisherHTML(array('buttonid' => 'submit-comment-add')); ?>
    </form>
  </div>
</div>
