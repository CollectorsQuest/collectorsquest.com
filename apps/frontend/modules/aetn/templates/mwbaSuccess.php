<div class="mwba-wrapper">
  <div class="top-banner"></div>
  <div class="labels-ribbons">
      <a href="<?= url_for('@aetn_mwba_petroliana', true); ?>" class="rockefeller-button"></a>
      <a href="<?= url_for('@aetn_mwba_rooseveltiana', true); ?>" class="roosevelt-button"></a>
      <a href="<?= url_for('@aetn_mwba_railroadiana', true); ?>" class="vanderbilt-button"></a>
  </div>

  <div class="center-graphic">
    <?= image_tag('frontend/misc/mwba/mwba-center-graphic.png'); ?>
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
