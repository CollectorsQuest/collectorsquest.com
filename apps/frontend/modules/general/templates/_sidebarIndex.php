<p class="text-center">
  <?php cq_dart_slot('160x600', 'homepage', null, 'sidebar') ?>
  <?php // link_to(image_tag('banners/040812_showandsell_160.jpg'), '@collector_signup'); ?>
</p>

<?php cq_sidebar_title('Discover'); ?>
<ul class="unstyled sidebar-ul">
  <li><?= link_to('Seen on Pawn Stars', '@aetn_pawn_stars'); ?></li>
  <li><?= link_to('Seen on American Pickers', '@aetn_american_pickers'); ?></li>
  <li><?= link_to('Latest Blog Posts', '@blog'); ?></li>
</ul>
