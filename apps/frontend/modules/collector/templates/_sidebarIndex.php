<?php
/**
 * @var  $collector  Collector
 * @var  $profile    CollectorProfile
 * @var  $sf_user    cqFrontendUser
 */

  $has_sidebar_text = $about_me || $about_collections || $about_interests ||
                      $store_welcome || $store_shipping || $store_refunds ||
                      $store_return_policy || $store_additional_policies;
?>

<div class="blue-actions-panel spacer-bottom">
  <div class="row-fluid">
    <div class="pull-left">
      <ul>
        <li>
          <?php
          echo format_number_choice(
            '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
            array('%1%' => number_format($profile->getNumViews())), $profile->getNumViews()
          );
          ?>
        </li>
      </ul>
    </div>
    <div id="social-sharing" class="pull-right share">
      <?php // removing the addthis_button_email causes a JS error - no toolbar displayed ?>
      <a class="addthis_button_email" style="display: none;"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="75"></a>
    </div>
  </div>
</div>

<?php if (isset($pm_form)): ?>
<div class="row-fluid spacer">
  <div class="send-pm">
    <form action="<?= url_for2('messages_compose', array('to'=>$collector->getUsername()), true); ?>" method="post" class="spacer-bottom-reset" id="form-private-message">
      <?= $pm_form->renderHiddenFields(); ?>
      <textarea class="requires-login" required data-login-title="Please log in to contact this member:" data-signup-title="Create an account to contact this member:" name="message[body]" style="width: 97%; margin-bottom: 0;" placeholder="Send a message to <?= $collector; ?>"></textarea>
      <div class="buttons-container" id="buttons-private-message">
        <?php /* <button type="button" class="btn cancel" value="cancel">cancel</button>
         &nbsp; - or - &nbsp;
        <input type="submit" class="btn-lightblue-normal" value="Send the Message"> */?>
        <button type="submit" class="btn-lightblue-normal textright requires-login">
          <i class="mail-icon-mini"></i> &nbsp;Send message
        </button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php if ($about_me || $about_collections || $about_interests || $store_welcome): ?>

  <?php cq_section_title('More About '. $collector->getDisplayName()); ?>
  <div class="personal-info-sidebar" itemprop="description">
    <?php if ($about_me): ?>
      <p><strong>About me:</strong> <?= nl2br($about_me); ?></p>
    <?php endif; ?>
    <?php if ($about_collections): ?>
      <p><strong>My collections:</strong> <?= nl2br($about_collections); ?></p>
    <?php endif; ?>
    <?php if ($about_interests): ?>
      <p><strong>My interests:</strong> <?= nl2br($about_interests); ?></p>
    <?php endif; ?>
    <?php if ($store_welcome): ?>
      <p><strong>About my store:</strong> <?= nl2br($store_welcome); ?></p>
    <?php endif; ?>
  </div>
<?php endif; ?>


<?php if ($store_shipping || $store_refunds || $store_return_policy ||
          $store_additional_policies): ?>

  <?php cq_section_title('Store Policies'); ?>
  <div class="personal-info-sidebar">
    <?php if ($store_shipping): ?>
      <p><strong>Shipping Policy:</strong> <?= nl2br($store_shipping); ?></p>
    <?php endif; ?>
    <?php if ($store_refunds): ?>
      <p><strong>Refunds and Exchanges:</strong> <?= nl2br($store_refunds); ?></p>
    <?php endif; ?>
    <?php if ($store_return_policy): ?>
      <p><strong>Return Policy:</strong> <?= nl2br($store_return_policy); ?></p>
    <?php endif; ?>
    <?php if ($store_additional_policies): ?>
      <p><strong>Additional Policies and FAQ:</strong> <?= nl2br($store_additional_policies); ?></p>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php
  if (!$has_sidebar_text)
  {
    include_component(
      '_sidebar', 'widgetCollections',
      array('collector' => $collector)
    );
  }
?>

<?php
//  include_component(
//    '_sidebar', 'widgetCollectorMostWanted',
//    array('collector' => $collector)
//  );
?>

<script>
$(document).ready(function()
{
  $('#form-private-message textarea').focus(function()
  {
    $(this).css('height', '100px');
    $('#buttons-private-message').slideDown();
  });

  $('#buttons-private-message .cancel').click(function()
  {
    $('#buttons-private-message').slideUp();
    $('#form-private-message textarea').css('height', 'auto');
  });
});
</script>
