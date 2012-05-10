<?php cq_page_title('Market Categories'); ?>

<div class="row">
  <?php foreach ($level1_categories as $k => $category):
    $level2_links = array();
  ?>

  <div class="span3">
    <h2><?= link_to($category, 'marketplace_category_by_slug', $category); ?></h2>

    <?php foreach ($category->getChildren(ContentCategoryQuery::create()->withCollectiblesForSale()->orderBy('Name')) as $child_category): ?>
      <?php $level2_links[] = link_to($child_category, 'marketplace_category_by_slug', $child_category); ?>
    <?php endforeach; ?>
    <?php echo implode(', ', $level2_links); ?>
  </div>

  <?php endforeach; ?>
</div>