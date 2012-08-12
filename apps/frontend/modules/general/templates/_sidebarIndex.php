<p class="text-center">
  <?php
    if (!$sf_user->isAuthenticated())
    {
      echo link_to(
        cq_image_tag('banners/2012-06-24_CQGuidePromo_160x600.png'),
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
