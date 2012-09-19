<?php
  /*
   * @var $rpxnow  string
   */
?>

<div id="modal-login-holder" class="modal">
  <div class="modal-header">

    <h3>Sign in with Social Networks</h3>
    <button class="close" data-dismiss="modal">×</button>

  </div>

  <div class="modal-body">

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

  </div> <!-- .modal-body -->

  <div class="modal-footer">
    <!-- nothing in the footer here, modal looks better -->
  </div>

</div> <!-- #modal-loginholder .modal.fade -->

<script type="text/javascript">
  $(document).ready(function() {
    $('#modal-rpx-iframe').attr('src', "<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>");

    $('.close').click(function() {
      $('.modal-backdrop').remove();
      $('.modal').remove();
    });
  });
</script>
