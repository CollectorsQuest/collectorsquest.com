<?php
/**
 * @var $categories ContentCategory[]
 */

cq_page_title('Market Categories');
?>

<div id="all-market-categories" class="row">
  <?php foreach ($categories as $k => $category): ?>

  <div class="span4">
    <h2><?= link_to($category, 'marketplace_category_by_slug', $category); ?></h2>
  </div>

  <?php endforeach; ?>
</div>
