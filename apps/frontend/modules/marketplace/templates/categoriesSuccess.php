<?php cq_page_title('Market Categories'); ?>

<div id="all-market-categories" class="row">
  <?php foreach ($categories as $k => $category):
    //$level2_links = array();
  ?>

  <div class="span4">
    <h2><?= link_to($category, 'marketplace_category_by_slug', $category); ?></h2>

    <?php /* we won't display level 2 categories this way for now
    <?php foreach ($category->getChildren(ContentCategoryQuery::create()->withCollectiblesForSale()->orderBy('Name')) as $child_category): ?>
      <?php $level2_links[] = link_to($child_category, 'marketplace_category_by_slug', $child_category); ?>
    <?php endforeach; ?>
    <?php echo implode(', ', $level2_links); ?>
    */ ?>
  </div>

  <?php endforeach; ?>
</div>
