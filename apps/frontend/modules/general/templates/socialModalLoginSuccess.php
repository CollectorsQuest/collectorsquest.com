<?php /* @var $rpxnow array */ ?>

<div id="modal-login-openid-pane" class="tab-pane">
  <fieldset class="rpxnow-login clearfix">
    <iframe
      id="modal-rpx-iframe" src="" width="350" height="217"
      style="width:350px; height:217px; overflow: hidden; border: 0; margin-left: 70px;">
    </iframe>
  </fieldset>
</div> <!-- #modal-login-openid-pane .tab-pane -->

<script type="text/javascript">
  $(document).ready(function()
  {
    $('#modal-rpx-iframe').attr('src', "<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>");

    $('.close').click(function() {
      $('.modal-backdrop').remove();
      $('.modal').remove();
    });
  });
</script>
