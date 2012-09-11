<div class="social-signin-wapper">
  <a href="<?= url_for('@login#rpx-login'); ?>" title="<?= ucfirst($action) ?> using Facebook"
     class="facebook-big-icon" rel="tooltip" data-placement="top"
     onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') &&
              $('#modal-login-holder').find('.social-only').hide() && false; ">
    <i class="hide-text"><?= ucfirst($action) ?> using Facebook</i>
  </a>
  <a href="<?= url_for('@login#rpx-login'); ?>" title="<?= ucfirst($action) ?> using Twitter"
     class="twitter-big-icon" rel="tooltip" data-placement="top"
     onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') &&
              $('#modal-login-holder').find('.social-only').hide() && false;">
    <i class="hide-text"><?= ucfirst($action) ?> using Twitter</i>
  </a>
  <a href="<?= url_for('@login#rpx-login'); ?>" title="<?= ucfirst($action) ?> using Google+"
     class="google-big-icon" rel="tooltip" data-placement="top"
     onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') &&
              $('#modal-login-holder').find('.social-only').hide() && false;">
    <i class="hide-text"><?= ucfirst($action) ?> using Google+</i>
  </a>
  <a href="<?= url_for('@login#rpx-login'); ?>" title="<?= ucfirst($action) ?> using Windows Live ID"
     class="live-id-big-icon" rel="tooltip" data-placement="top"
     onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') &&
              $('#modal-login-holder').find('.social-only').hide() && false;">
    <i class="hide-text"><?= ucfirst($action) ?> using Windows Live ID</i>
  </a>
</div>
