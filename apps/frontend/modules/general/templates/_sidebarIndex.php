<p class="text-center">
  <?php // cq_dart_slot('160x600', 'homepage', null, 'sidebar') ?>
  <?= link_to(image_tag('banners/040812_showandsell_160.jpg'), '@collector_signup'); ?>
</p>

<?= ($cms_slot1 instanceof wpPost) ? $cms_slot1->getPostContent() : null; ?>
