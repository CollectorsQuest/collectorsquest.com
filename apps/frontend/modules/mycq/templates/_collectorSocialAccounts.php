<?php
  /* @var $providers array */
?>
<?php cq_sidebar_title('Social Accounts') ?>

<?php if (in_array('facebook', $providers)): ?>
  <div class="already-connected">
    <span>Already<br>connected</span>

    <i class="facebook-big-icon"></i>
    <?php
      echo link_to(
        '<i class="icon icon-remove-sign"></i>',
        '@mycq_profile_account_info?remove_provider=facebook'
      );
    ?>
  </div>
<?php endif; ?>

<?php if (in_array('twitter', $providers)): ?>
  <div class="already-connected">
    <span>Already<br>connected</span>

    <i class="twitter-big-icon"></i>
    <?php
      echo link_to(
        '<i class="icon icon-remove-sign"></i>',
        '@mycq_profile_account_info?remove_provider=twitter'
      );
    ?>
  </div>
<?php endif; ?>

<?php if (in_array('google', $providers)): ?>
  <div class="already-connected">
    <span>Already<br>connected</span>

    <i class="google-big-icon"></i>
    <?php
      echo link_to(
        '<i class="icon icon-remove-sign"></i>',
        '@mycq_profile_account_info?remove_provider=google'
      );
    ?>
  </div>
<?php endif; ?>

<?php if (in_array('live', $providers)): ?>
  <div class="already-connected">
    <span>Already<br>connected</span>

    <i class="live-id-big-icon"></i>
    <?php
      echo link_to(
        '<i class="icon icon-remove-sign"></i>',
        '@mycq_profile_account_info?remove_provider=live'
      );
    ?>
  </div>
<?php endif; ?>

<?php if (count($providers) < 4): ?>
<div class="connect-to-social-icons">
  <fieldset>
    <legend>Connect your account to</legend>
    <div class="icon-container">
      <?php
        if (!in_array('facebook', $providers))
        {
          echo link_to(
            '<i class="hide-text">Sign up using Facebook</i>',
            '@ajax?section=partial&page=socialModalLogin&provider=facebook',
            array(
              'class' => 'open-dialog facebook-big-icon', 'rel' => 'tooltip', 'data-placement' => 'bottom',
              'onclick' => 'return false', 'title' => 'Sign up using Facebook'
            )
          );
        }
        if (!in_array('twitter', $providers))
        {
          echo link_to(
            '<i class="hide-text">Sign up using Twitter</i>',
            '@ajax?section=partial&page=socialModalLogin&provider=twitter',
            array(
              'class' => 'open-dialog twitter-big-icon', 'rel' => 'tooltip', 'data-placement' => 'bottom',
              'onclick' => 'return false', 'title' => 'Sign up using Twitter'
            )
          );
        }
        if (!in_array('google', $providers))
        {
          echo link_to(
            '<i class="hide-text">Sign up using Google+</i>',
            '@ajax?section=partial&page=socialModalLogin&provider=google',
            array(
              'class' => 'open-dialog google-big-icon', 'rel' => 'tooltip', 'data-placement' => 'bottom',
              'onclick' => 'return false', 'title' => 'Sign up using Google+'
            )
          );
        }
        if (!in_array('live', $providers))
        {
          echo link_to(
            '<i class="hide-text">Sign up using Windows Live ID</i>',
            '@ajax?section=partial&page=socialModalLogin&provider=live_id',
            array(
              'class' => 'open-dialog live-id-big-icon', 'rel' => 'tooltip', 'data-placement' => 'bottom',
              'onclick' => 'return false', 'title' => 'Sign up using Windows Live ID'
            )
          );
        }
      ?>
    </div>
  </fieldset>
</div>
<?php endif; ?>




