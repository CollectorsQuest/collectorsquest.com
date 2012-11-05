<?php if (cqGateKeeper::open('holiday_marketplace', 'page')): ?>
  <div class="banner-sidebar-promo-300-90">
    <?php
      cq_ad_slot(
        cq_image_tag('headlines/20121018_300x90_Vintage_final.jpg',
          array(
            'width' => '300', 'height' => '90',
            'alt' => 'Check out items in our Holiday Market'
          )
        ),
        url_for('marketplace/holiday')
      );
    ?>
  </div>
<?php endif; ?>
