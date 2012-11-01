<p class="text-center">
  <?php
    if (IceGateKeeper::open('holiday_marketplace', 'page'))
    {
      cq_ad_slot(
        cq_image_tag('headlines/20121018_160x345_final.jpg',
          array(
            'width' => '160', 'height' => '600', 'style' => 'margin-bottom: 15px;',
            'alt' => 'Shop now - Visit our Holiday Marketplace!'
          )
        ),
        url_for('marketplace/holiday')
      );
    }

    /* @var $sf_user cqFrontendUser */
    if (!$sf_user->isAuthenticated())
    {
      cq_ad_slot(
        cq_image_tag('headlines/2012-06-24_CQGuidePromo_160x600.png',
          array(
            'width' => '160', 'height' => '600',
            'alt' => 'Quest Your Best: The Essential Guide to Collecting'
          )
        ),
        '@misc_guide_to_collecting'
      );
    }
    else
    {
      cq_dart_slot('160x600', 'homepage', null, 'sidebar');
    }
  ?>
</p>

<?= ($cms_slot1 instanceof wpPost) ? $cms_slot1->getPostContent() : null; ?>
