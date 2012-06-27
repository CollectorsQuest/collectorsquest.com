<?= ucfirst($action); ?> with&nbsp;
<a href="<?= url_for('@login#modal-rpx-login-holder'); ?>" title="Login using Facebook" class="s-16-icon-facebook" onclick="$('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show'); return false;">
  <i class="hide-text">Login using Facebook</i>
</a>
<a href="<?= url_for('@login#modal-rpx-login-holder'); ?>" title="Login using Twitter" class="s-16-icon-twitter" onclick="$('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show'); return false;">
  <i class="hide-text">Login using Twitter</i>
</a>
<a href="<?= url_for('@login#modal-rpx-login-holder'); ?>" title="Login using Google" class="s-16-icon-google" onclick="$('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show'); return false;">
  <i class="hide-text">Login using Google</i>
</a>
