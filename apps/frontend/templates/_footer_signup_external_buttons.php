<?= ucfirst($action); ?> with&nbsp;
<a href="<?= url_for('@login#rpx-login'); ?>" title="Login using Facebook" class="s-16-icon-facebook"
   onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') &&
            $('#modal-login-holder').find('.social-only').hide() && false; ">
  <i class="hide-text">Login using Facebook</i>
</a>
<a href="<?= url_for('@login#rpx-login'); ?>" title="Login using Twitter" class="s-16-icon-twitter"
   onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') &&
            $('#modal-login-holder').find('.social-only').hide() && false;">
  <i class="hide-text">Login using Twitter</i>
</a>
<a href="<?= url_for('@login#rpx-login'); ?>" title="Login using Google+" class="s-16-icon-google"
   onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') &&
            $('#modal-login-holder').find('.social-only').hide() && false;">
  <i class="hide-text">Login using Google+</i>
</a>
<a href="<?= url_for('@login#rpx-login'); ?>" title="Login using Windows Live ID" class="s-16-icon-windows"
   onclick="return jQuery && $('#modal-login-holder').modal('show').find('a[href=#modal-login-openid-pane]').tab('show') &&
            $('#modal-login-holder').find('.social-only').hide() && false;">
  <i class="hide-text">Login using Windows Live ID</i>
</a>
