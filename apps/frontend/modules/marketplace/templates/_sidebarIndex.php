<?php cq_ad_slot('300x250', 300, 250) ?>

<? cq_sidebar_title('Market Directory', link_to('see all', '@marketplace_categories', array('class' => 'text-v-middle link-align'))); ?>
<div class="twocolumn cf">
  <ul>
  <?php foreach ($categories as $i => $category): ?>
    <li>
      <?php if ($category): ?>
      <?php echo link_to($category->getName(), 'marketplace_category_by_slug', $category, array('title' => $category->getName())) ?>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
  </ul>
</div>

<? cq_sidebar_title('Featured Sellers', link_to('browse profiles', '@collectors')); ?>
