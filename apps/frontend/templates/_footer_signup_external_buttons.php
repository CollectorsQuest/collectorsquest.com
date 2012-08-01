<?/* ucfirst($action); with&nbsp; */?>
<div class="social-signin-wapper">
  <a href="<?= url_for('@login#rpx-login'); ?>" title="Sign in using Facebook" class="facebook-big-icon" onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') && false; ">
    <i class="hide-text">Sign in using Facebook</i>
  </a>
  <a href="<?= url_for('@login#rpx-login'); ?>" title="Sign in using Twitter" class="twitter-big-icon" onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') && false;">
    <i class="hide-text">Sign in using Twitter</i>
  </a>
  <a href="<?= url_for('@login#rpx-login'); ?>" title="Sign in using Google" class="google-big-icon" onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') && false;">
    <i class="hide-text">Sign in using Google</i>
  </a>
  <a href="<?= url_for('@login#rpx-login'); ?>" title="Sign in using Windows Live ID" class="live-id-big-icon" onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') && false;">
    <i class="hide-text">Sign in using Windows Live ID</i>
  </a>
  <p class="hover-info-f">Sign in using Facebook</p>
  <p class="hover-info-t">Sign in using Twitter</p>
  <p class="hover-info-g">Sign in using Google</p>
  <p class="hover-info-w">Sign in using Windows Live ID</p>
</div>
