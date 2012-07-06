<?php
/**
 * @var  $collector  Collector
 * @var  $profile    CollectorProfile
 * @var  $sf_user    cqFrontendUser
 */
?>

<div class="blue-actions-panel spacer-bottom-10">
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
    <div class="pull-right share">
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
    </div>
  </div>
</div>

<?php if (isset($pm_form)): ?>
<div class="row-fluid spacer">
  <div class="send-pm">
    <form action="<?= url_for2('messages_compose', array('to'=>$collector->getUsername()), true); ?>" method="post" style="margin-bottom: 0;" id="form-private-message">
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

<?php if ($about_me || $about_collections || $about_interests): ?>

  <?php cq_section_title('More About '. $collector->getDisplayName()); ?>
  <div class="personal-info-sidebar">
    <?php if ($about_me): ?>
      <p><strong>About me:</strong> <?= nl2br($about_me); ?></p>
    <?php endif; ?>
    <?php if ($about_collections): ?>
      <p><strong>My collections:</strong> <?= nl2br($about_collections); ?></p>
    <?php endif; ?>
    <?php if ($about_interests): ?>
      <p><strong>My interests:</strong> <?= nl2br($about_interests); ?></p>
    <?php endif; ?>
  </div>

<?php else: ?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collector' => $collector)
  );
?>

<?php endif; ?>

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
