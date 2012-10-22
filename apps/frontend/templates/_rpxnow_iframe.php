<?php

/* @var $rpxnow array */
$rpxnow = sfConfig::get('app_credentials_rpxnow');

?>

<fieldset class="rpxnow-login clearfix">
  <iframe
    class="modal-rpx-iframe" src="" width="350" height="217"
    style="width:350px; height:217px; overflow: hidden; border: 0; margin-left: 70px;">
  </iframe>
</fieldset>

<script type="text/javascript">
  $(document).ready(function()
  {
    $('.modal-rpx-iframe').attr(
      'src',
        "<?php echo sprintf(
          '%s/openid/embed?token_url=%s&flags=%s&default_provider=%s',
          rtrim($rpxnow['application_domain'], '/'), url_for('@rpx_token', true),
          isset($flags) ? $flags : '', isset($provider) ? $provider : ''
        ); ?>"
    );
  });
</script>
