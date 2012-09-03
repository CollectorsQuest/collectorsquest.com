<?php
  /**
   * @var $categories ContentCategory[]
   * @var $height stdClass
   */

  $_height = 0;

  $link = link_to(
    'See all &raquo;', '@marketplace_categories',
    array('class' => 'text-v-middle link-align')
  );
  $link = null;

  cq_sidebar_title('Explore the Market', $link);
?>

<?php $_height -= 63 ?>

<div class="twocolumn cf">
  <ul>
    <?php foreach ($categories as $i => $category): ?>
    <li>
      <?php
        echo link_to(
          $category->getName(), 'marketplace_category_by_slug',
          $category, array('title' => $category->getName())
        );
      ?>
    </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php
  $_height -= 336;

  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
