<div class="social-signin-wapper">
  <?php
    echo link_to(
      '<i class="hide-text"><?= ucfirst($action) ?> using Facebook</i>',
      '@modal_login?social_only=true',
      array(
        'class' => 'open-dialog facebook-big-icon', 'rel' => 'tooltip', 'data-placement' => 'top',
        'onclick' => 'return false', 'title' => ucfirst($action).' using Facebook'
      )
    );

    echo link_to(
      '<i class="hide-text"><?= ucfirst($action) ?> using Twitter</i>',
      '@modal_login?social_only=true',
      array(
        'class' => 'open-dialog twitter-big-icon', 'rel' => 'tooltip', 'data-placement' => 'top',
        'onclick' => 'return false', 'title' => ucfirst($action).' using Facebook'
      )
    );

    echo link_to(
      '<i class="hide-text"><?= ucfirst($action) ?> using Google+</i>',
      '@modal_login?social_only=true',
      array(
        'class' => 'open-dialog google-big-icon', 'rel' => 'tooltip', 'data-placement' => 'top',
        'onclick' => 'return false', 'title' => ucfirst($action).' using Facebook'
      )
    );

    echo link_to(
      '<i class="hide-text"><?= ucfirst($action) ?> using Windows Live ID</i>',
      '@modal_login?social_only=true',
      array(
        'class' => 'open-dialog live-id-big-icon', 'rel' => 'tooltip', 'data-placement' => 'top',
        'onclick' => 'return false', 'title' => ucfirst($action).' using Facebook'
      )
    );
  ?>
</div>
