<?php
  cq_sidebar_title(
    'Popular Topics',
    link_to('See all &raquo;', '@content_categories', array('class' => 'text-v-middle link-align'))
  );
?>

<div class="twocolumn cf">
  <ul>
    <?php
      /** @var $categories CollectionCategory[] */
      foreach ($categories as $i => $category):
    ?>
    <li><?= ($category) ? link_to_content_category($category, 'text') : ''; ?></li>
    <?php endforeach; ?>
  </ul>
</div>
