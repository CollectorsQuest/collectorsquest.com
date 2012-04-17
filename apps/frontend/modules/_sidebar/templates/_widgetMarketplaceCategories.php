<?php cq_sidebar_title('Market Directory', link_to('see all', '@marketplace_categories', array('class' => 'text-v-middle link-align'))); ?>

<div class="twocolumn cf">
  <ul>
    <?php
      /** @var $categories CollectionCategory[] */
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
