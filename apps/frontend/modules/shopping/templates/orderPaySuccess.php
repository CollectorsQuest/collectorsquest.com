<div class="row-fluid spacer-top-30">
  <div class="span4">
    <div class="pull-right">
      <?= cq_image_tag('frontend/logo/175x80.png'); ?>
    </div>
  </div>
  <div class="span3" style="text-align: center;">
    <center><?= cq_image_tag('loading.arrows.gif', array('class' => 'block spacer-top-30')); ?></center>
  </div>
  <div class="span5">
    <div class="pull-left">
      <?= cq_image_tag('frontend/paypal-logo-175.png'); ?>
    </div>
  </div>
</div>

<br>
<h3 class="text-center spacer-top-20">Please wait while we redirect you to PayPal</h3>
<p id="manual" class="brown text-center js-hide">
  If you're not redirected within 5 seconds
  <?= link_to_function('click here.', "$('input#submit').click();"); ?>
</p>

<form action="<?= sfConfig::get('app_paypal_url'); ?>" name="frmpaypal" id="frmpaypal" method="post">
  <input type="hidden" name="cmd" value="_ap-payment"/>
  <input type="hidden" name="paykey" value="<?= $pay_key ?>"/>
  <input type="submit" id="submit" value="Continue to PayPal →" style="display: block; margin: 0 auto;" />
  <script>
    $(document).ready(function()
    {
      $('#manual').show();
      $('#submit').hide().click();
    });
  </script>
  <noscript>
    <p>Javascript is disabled. Please click on the 'Continue to PayPal' button to be redirected to PayPal.</p>
  </noscript>
</form>
