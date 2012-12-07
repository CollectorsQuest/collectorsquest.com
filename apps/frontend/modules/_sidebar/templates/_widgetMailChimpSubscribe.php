<?php
/**
 * @var  $height  stdClass
 */

$_height = 0;
?>

<div id="mc_embed_signup">
  <?php cq_sidebar_title('Subscribe To Our Newsletter', null); ?>
  <div class="mobile-optimized-300">
    <form action="http://collectorsquest.us5.list-manage.com/subscribe/post?u=0335e4e35c5166241792a7a47&id=87adbea7fa"
          method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
          class="form-horizontal validate" target="_blank" novalidate>

      <div class="row-fluid spacer-7 ">
        <div class="span4">
          <label class=" control-label" for="mce-EMAIL" style=" width: 100%; text-align: right;">
            Email Address &nbsp;
          </label>
        </div>
        <div class="span7 ">
          <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" style="width: 100%;">
        </div>
      </div>
      <div class="row-fluid spacer-7 ">
        <div class="span4">
          <label class=" control-label" style=" width: 100%; text-align: right">I am a &nbsp;</label>
        </div>
        <div class="span7 ">
          <div style="padding-top: 3px;" class="radio_list">
            <label style="padding-top: 3px;" class="radio">
              <input style="padding-top: 3px;" name="TYPE" type="radio" value="Collector" id="mce-TYPE-0" checked="checked">
              Collector
            </label>
            <label style="padding-top: 3px;" class="radio">
              <input style="padding-top: 3px;" name="TYPE" type="radio" value="Seller" id="mce-TYPE-1">
              Seller
            </label>
          </div>
        </div>
      </div>
      <div id="mce-responses" class="clear">
        <div class="response" id="mce-error-response" style="display:none"></div>
        <div class="response" id="mce-success-response" style="display:none"></div>
      </div>
      <div class="clear" style="margin-left: 100px;">
        <input type="submit" value="Subscribe"
               name="subscribe" id="mc-embedded-subscribe" class="btn btn-primary">
      </div>
    </form>
  </div>
</div>

<?php
  $_height -= 190;
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
