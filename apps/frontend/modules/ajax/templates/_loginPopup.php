<div id="modal-login-holder" class="modal fade">
  <div class="modal-header">
    <ul class="nav nav-pills">
      <li class="active"><a href="#modal-login-username-pane" data-toggle="tab">Collectors' Quest</a></li>
      <li><a href="#modal-login-openid-pane" data-toggle="tab">OpenID</a></li>
      <li class="pull-right"><a href="#modal-sign-up-pane" data-toggle="tab">Sign up!</a></li>
    </ul>
  </div>

  <div class="modal-body">
    <div class="tab-content">

      <div id="modal-login-username-pane" class="tab-pane active">
        <form action="<?= url_for('@login'); ?>" class="form-horizontal" method="post">
          <?= $login_form->renderUsing('Bootstrap') ?>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Log&nbsp;In</button>
            <span class="pull-right"><?= link_to('Forgot your password?', '@recover_password'); ?></span>
          </div>
        </form>
      </div> <!-- #modal-login-username-pane .tab-pane.active -->

      <div id="modal-login-openid-pane" class="tab-pane">
        <fieldset class="rpxnow-login clearfix">
          <legend><?= __('Third Party Accounts:'); ?></legend>
          <iframe
            src="<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
            scrolling="no"
            frameBorder="no"
            style="width:350px; height:217px;"
            width="350"
            height="217">
          </iframe>
        </fieldset>
      </div> <!-- #modal-login-openid-pane .tab-pane -->

      <div id="modal-sign-up-pane" class="tab-pane">
        <?= form_tag('@collector_signup?step=1', array('class' => 'form-horizontal')) ?>
          <fieldset>
            <?= $signup_form ?>
            <div class="form-actions">
              <input type="submit" class="btn btn-primary" value="Submit" />
            </div>
          </fieldset>
        </form>
      </div> <!-- #modal-sign-up-pane .tab-pane -->

    </div> <!-- .tab-content -->
  </div> <!-- .modal-body -->

</div> <!-- #modal-loginholder .modal.fade -->