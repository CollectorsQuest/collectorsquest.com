<form class="object_rating_form" method="POST"
      action="<?= url_for(
        '@ajax_object_rating?dimension='.$form->getObject()->getDimension()
          .'&class='.$class.'&id='.$id.'&user_id='.$form->getObject()->getSfGuardUserId(), true
      ); ?>">
  <?= $form->renderGlobalErrors(); ?>
  <?= $form['rating']->renderError(); ?>
  <?= $form['rating']; ?>
  <?= $form->renderHiddenFields(); ?>
</form>
