<? cq_sidebar_title('Collections Directory', link_to('See all &raquo;', '@collections_categories')); ?>

<ul class="twocolumn cf">
  <?php foreach ($categories as $i => $category): ?>
  <li>
    <?= ($category) ? link_to_collection_category($category, 'text') : ''; ?>
  </li>
  <?php endforeach; ?>
</ul>
