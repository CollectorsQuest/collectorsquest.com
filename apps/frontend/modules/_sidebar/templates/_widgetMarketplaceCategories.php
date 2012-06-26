<?php
  $link = link_to(
    'See all &raquo;', '@marketplace_categories',
    array('class' => 'text-v-middle link-align')
  );
  $link = null;

  cq_sidebar_title('Explore the Market', $link);
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
