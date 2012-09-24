<div class="mwba-wrapper">
  <div class="top-banner">
    <?= image_tag('frontend/misc/mwba-top-banner.png', array('alt' => 'The Men Who Built America'));  ?>
  </div>
  <div class="labels-ribbons">
      <a href="<?= url_for('@aetn_mwba_petroliana', true); ?>" class="rockefeller_button"></a>
      <a href="<?= url_for('@aetn_mwba_rooseveltiana', true); ?>" class="roosevelt_button"></a>
      <a href="<?= url_for('@aetn_mwba_railroadiana', true); ?>" class="vanderbilt_button"></a>
  </div>

  <div class="center-graphic">
    <?= image_tag('frontend/misc/mwba-center-graphic.png'); ?>
  </div>

  <div class="banner">
    <?php
      echo link_to(cq_image_tag(
        'headlines/history-banner-mwba-landing-page.jpg',
        array('alt_title' => 'The Men Who Built America')
      ), 'http://www.history.com/shows/men-who-built-america', array('target' => '_blank'));
    ?>
  </div>
</div>
