<div class="social-signin-wapper">
  <?php
    echo link_to(
      '<i class="hide-text">' . ucfirst($action) . ' using Facebook</i>',
      '@ajax?section=partial&page=socialModalLogin&provider=facebook',
      array(
        'class' => 'open-dialog facebook-big-icon', 'rel' => 'tooltip', 'data-placement' => 'top',
        'onclick' => 'return false', 'title' => ucfirst($action) . ' using Facebook'
      )
    );

    echo link_to(
      '<i class="hide-text">' . ucfirst($action) . ' using Twitter</i>',
      '@ajax?section=partial&page=socialModalLogin&provider=twitter',
      array(
        'class' => 'open-dialog twitter-big-icon', 'rel' => 'tooltip', 'data-placement' => 'top',
        'onclick' => 'return false', 'title' => ucfirst($action) . ' using Twitter'
      )
    );

    echo link_to(
      '<i class="hide-text">' . ucfirst($action) . ' using Google+</i>',
      '@ajax?section=partial&page=socialModalLogin&provider=google',
      array(
        'class' => 'open-dialog google-big-icon', 'rel' => 'tooltip', 'data-placement' => 'top',
        'onclick' => 'return false', 'title' => ucfirst($action) . ' using Google+'
      )
    );

    echo link_to(
      '<i class="hide-text">' . ucfirst($action) . ' using Windows Live ID</i>',
      '@ajax?section=partial&page=socialModalLogin&provider=live_id',
      array(
        'class' => 'open-dialog live-id-big-icon', 'rel' => 'tooltip', 'data-placement' => 'top',
        'onclick' => 'return false', 'title' => ucfirst($action) . ' using Windows Live ID'
      )
    );

  ?>
</div>
