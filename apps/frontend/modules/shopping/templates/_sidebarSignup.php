<?php if (!$sf_user->isAuthenticated()): ?>

  <?php cq_sidebar_title('Signup for an Account', null, array('style' => 'margin-top: 0;')); ?>

  <div class="signup-form-sidebar">
    <form action="<?= url_for('@collector_signup', true); ?>" method="post"
          class="form-horizontal">
      <?= $form->renderUsing('BootstrapWithRowFluid'); ?>
      <div class="row-fluid spacer-7">
        <div class="span9 spacer-inner-top">
          <?php
            include_partial(
              'global/footer_signup_external_buttons',
              array('action' => 'signup')
            );
          ?>
        </div>
        <div class="span3">
          <button type="submit" class="btn btn-primary pull-right">
            Submit
          </button>
        </div>
      </div>
    </form>
  </div>

<?php endif; ?>
