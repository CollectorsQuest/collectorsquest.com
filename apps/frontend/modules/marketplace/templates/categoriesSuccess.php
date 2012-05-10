<?php cq_page_title('Market Categories'); ?>

<div class="row">
  <?php foreach ($level1_categories as $category): ?>
  <div class="span3">
    <h2><?= link_to($category, 'marketplace_category_by_slug', $category); ?></h2>
    <div>
    <?php foreach ($category->getChildren(ContentCategoryQuery::create()->withCollectiblesForSale()->orderBy('Name')) as $child_category): ?>
      <?= link_to($child_category, 'marketplace_category_by_slug', $child_category); ?>
    <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>