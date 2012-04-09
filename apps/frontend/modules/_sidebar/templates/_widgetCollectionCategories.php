<div class="row-fluid red-dashes-sidebar">
  <div class="span9">
    <span class="sidebar-title">Collections Directory</span>
  </div>
  <div class="span3 text-right">
    <?= link_to('See all &raquo;', '@collections_categories'); ?>
  </div>
</div>
<?php /* <div class="row-fluid">
  <?php foreach ($categories as $i => $category): ?>
  <div class="span<?= 12 / $columns ?>" style="margin-left: 0;">
    <?= ($category) ? link_to_collection_category($category, 'text') : ''; ?>
  </div>
  <?php endforeach; ?>
</div>
 */ ?>


<ul class="twocolumn cf">
  <?php foreach ($categories as $i => $category): ?>
  <li>
    <?= ($category) ? link_to_collection_category($category, 'text') : ''; ?>
  </li>
  <?php endforeach; ?>
</ul>
