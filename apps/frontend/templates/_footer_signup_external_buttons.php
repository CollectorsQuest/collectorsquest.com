<?= ucfirst($action); ?> with&nbsp;
<a href="<?= url_for('@login#rpx-login'); ?>" title="Sign in using Facebook" class="s-16-icon-facebook" onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') && false; ">
  <i class="hide-text">Sign in using Facebook</i>
</a>
<a href="<?= url_for('@login#rpx-login'); ?>" title="Sign in using Twitter" class="s-16-icon-twitter" onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') && false;">
  <i class="hide-text">Sign in using Twitter</i>
</a>
<a href="<?= url_for('@login#rpx-login'); ?>" title="Sign in using Google" class="s-16-icon-google" onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') && false;">
  <i class="hide-text">Sign in using Google</i>
</a>
<a href="<?= url_for('@login#rpx-login'); ?>" title="Sign in using Windows Live ID" class="s-16-icon-windows" onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') && false;">
  <i class="hide-text">Sign in using Windows Live ID</i>
</a>
