<?php
/**
 * @var $login_form   sfForm
 * @var $rpxnow       string
 */
?>

<div id="modal-login-holder" class="modal hide">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <ul class="nav nav-pills">
      <li class="active"><a href="#modal-login-username-pane" class="btn" data-toggle="tab">Sign in with Collectors Quest</a></li>
      <li><a href="#modal-login-openid-pane" class="btn spacer-left" data-toggle="tab">Sign in with Social Networks</a></li>
    </ul>
  </div>

  <form action="<?= url_for('@login', true); ?>" class="form-horizontal" method="post" style="margin: 0">

    <div class="modal-body">
      <div class="tab-content">

        <div id="modal-login-username-pane" class="tab-pane active">
          <h3 class="text-center">Sign in to your account:</h3>
          <br />
          <?= $login_form['username']->renderRow(); ?>
          <?= $login_form['password']->renderRow(); ?>
          <div class="control-group ">
            <label class="control-label">&nbsp;</label>
            <div class="controls">
              <?= $login_form['remember']->render(array('style' => 'float: left; margin-top: 3px;')); ?>
              <?= $login_form->renderHiddenFields(); ?>
              <label for="login_remember">&nbsp; Remember me for two weeks</label>
            </div>
          </div>
        </div> <!-- #modal-login-username-pane .tab-pane.active -->

        <div id="modal-login-openid-pane" class="tab-pane">
          <?php include_partial('global/rpxnow_iframe') ?>
        </div> <!-- #modal-login-openid-pane .tab-pane -->
      </div> <!-- .tab-content -->
    </div> <!-- .modal-body -->

    <div class="modal-footer">
      <div class="tab-content">

        <div id="modal-login-username-footer" class="tab-pane active">
          <button type="submit" class="btn btn-primary">Sign&nbsp;In</button>
          <span class="spacer-left-15 modal-link text-left">
            - <?= link_to('Forgot your password?', '@recover_password'); ?> <br/>
            - <?= link_to('Sign up for a new account?', '@misc_guide_to_collecting'); ?>
          </span>
        </div>

        <div id="modal-login-openid-footer" class="tab-pane">
          <!-- nothing in the footer here -->
        </div>

      </div> <!-- .tab-content -->
    </div> <!-- .modal-footer -->

  </form>

</div> <!-- #modal-loginholder .modal.fade -->
