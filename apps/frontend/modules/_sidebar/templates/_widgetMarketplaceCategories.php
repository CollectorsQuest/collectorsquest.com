<?php
  cq_sidebar_title(
    'Explore the Market',
    link_to('see all &raquo;', '@marketplace_categories', array('class' => 'text-v-middle link-align'))
  );
?>

<div class="twocolumn cf">
  <ul>
    <?php
      /** @var $categories ContentCategory[] */
      foreach ($categories as $i => $category):
    ?>
    <li>
      <?php
        echo link_to(
          $category->getName(), 'marketplace_category_by_slug',
          $category, array('title' => $category->getName())
        )
      ?>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
