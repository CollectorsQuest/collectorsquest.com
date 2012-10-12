<div id="modal-login-holder" class="modal hide">
  <div class="modal-header" style="min-height: 28px;">
    <button class="close" data-dismiss="modal">×</button>
    <div class="social-only">
      <ul class="nav nav-pills">
        <li class="active"><a href="#modal-login-username-pane" class="btn" data-toggle="tab">Sign in with Collectors Quest</a></li>
        <li><a href="#modal-login-openid-pane" class="btn spacer-left" data-toggle="tab">Sign in with Social Networks</a></li>
        <!--<li class="pull-right spacer-right"><a href="#modal-sign-up-pane" data-toggle="tab">Sign up!</a></li>//-->
        <!--<li class="pull-right spacer-right"><?= link_to('Sign up!', '@misc_guide_to_collecting'); ?></li>//-->
      </ul>
     </div>
  </div>

  <div class="modal-body">
    <div class="tab-content">

      <div id="modal-login-username-pane" class="tab-pane active">
        <h3 class="text-center">Sign in to your account:</h3>
        <br />
        <form action="<?= url_for('@login', true); ?>" class="form-horizontal" method="post">
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
        </form>
      </div> <!-- #modal-login-username-pane .tab-pane.active -->

      <div id="modal-login-openid-pane" class="tab-pane">
        <fieldset class="rpxnow-login clearfix">
          <iframe
            id="modal-rpx-iframe"
            src=""
            frameBorder="0"
            style="width:350px; height:217px; overflow: hidden; border: 0; margin-left: 70px;"
            width="350"
            height="217">
          </iframe>
        </fieldset>
      </div> <!-- #modal-login-openid-pane .tab-pane -->

      <div id="modal-sign-up-pane" class="tab-pane">
        <h3 class="text-center">Create an account with us:</h3>
        <br />
        <?= form_tag('@collector_signup', array('class' => 'form-horizontal')) ?>
          <fieldset>
            <?= $signup_form ?>
          </fieldset>
        </form>
      </div> <!-- #modal-sign-up-pane .tab-pane -->

    </div> <!-- .tab-content -->
  </div> <!-- .modal-body -->

  <div class="social-only">
    <div class="modal-footer">
      <div class="tab-content">

        <div id="modal-login-username-footer" class="tab-pane active">
          <button type="button" class="btn btn-primary">Sign&nbsp;In</button>
          <span class="spacer-left-15 modal-link text-left">
            - <?= link_to('Forgot your password?', '@recover_password'); ?> <br/>
            - <?= link_to('Sign up for a new account?', '@misc_guide_to_collecting'); ?>
          </span>
        </div>

        <div id="modal-login-openid-footer" class="tab-pane">
          <!-- nothing in the footer here -->
        </div>

        <div id="modal-sign-up-footer" class="tab-pane">
          <button type="button" class="btn btn-primary">Submit</button>
        </div>

      </div> <!-- .tab-content -->
    </div> <!-- .modal-body -->
  </div>

</div> <!-- #modal-loginholder .modal.fade -->

<script>
$(document).ready(function() {
  $('#modal-rpx-iframe').attr('src', "<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>");
});
</script>
