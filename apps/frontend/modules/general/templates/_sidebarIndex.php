<p class="text-center">
  <?php
    if (cqGateKeeper::open('holiday_marketplace', 'page'))
    {
      echo link_to(
        cq_image_tag(
          'headlines/20121018_160x345_final.jpg',
          array('style' => 'margin-bottom: 15px;')
        ),
        'marketplace/holiday', array('ref' => cq_link_ref('sidebar'))
      );
    }

    if (!$sf_user->isAuthenticated())
    {
      echo link_to(
        cq_image_tag('headlines/2012-06-24_CQGuidePromo_160x600.png'),
        'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
      );
    }
    else
    {
      cq_dart_slot('160x600', 'homepage', null, 'sidebar');
    }
  ?>
</p>

<?= ($cms_slot1 instanceof wpPost) ? $cms_slot1->getPostContent() : null; ?>
