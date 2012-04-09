<?php cq_ad_slot('300x250', 300, 250) ?>

<? cq_sidebar_title('Market Directory', link_to('see all', '@marketplace_categories')); ?>
<div class="row-fluid">
  <?php foreach ($categories as $i => $category): ?>
  <div class="span6" style="margin-left: 0;">
    <?php if ($category): ?>
    <?php echo link_to($category->getName(), 'marketplace_category_by_slug', $category, array('title' => $category->getName())) ?>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>

<? cq_sidebar_title('Featured Sellers', link_to('browse profiles', '@collectors')); ?>
