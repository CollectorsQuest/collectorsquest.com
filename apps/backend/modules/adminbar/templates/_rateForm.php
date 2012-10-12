<form class="object_rate_form" method="POST"
      action="<?= url_for(
        '@ajax_object_rate?dimension='.$form->getObject()->getDimension()
          .'&class='.$class.'&id='.$id.'&bc='.$form->getObject()->getSfGuardUserId(), true
      ); ?>">
  <?= $form->renderGlobalErrors(); ?>
  <?= $form['rate']->renderError(); ?>
  <?= $form['rate']; ?>
  <?= $form->renderHiddenFields(); ?>
</form>
