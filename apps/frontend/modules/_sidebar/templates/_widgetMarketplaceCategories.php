<?php
  /**
   * @var $subcategories                ContentCategory[]
   * @var $current_subcategory          ContentCategory
   * @var $height stdClass
   * @var $widget_title string
   */

  // remove the 'false' to enable 'See all' link
  if (false && cqGateKeeper::open('marketplace_categories', 'page'))
  {
    $link = link_to(
      'See all &raquo;', '@marketplace_categories',
      array('class' => 'text-v-middle link-align')
    );
  }
  else
  {
    $link = null;
  }

  cq_sidebar_title($widget_title, $link);
  $_height = -63;
?>

<div class="onecolumn cf">
  <ul>
    <?php foreach ($subcategories as $i => $subcategory): ?>
      <li>
        <?php
          if ($subcategory && $subcategory != $current_subcategory)
          {
            echo link_to(
              $subcategory->getName(), 'marketplace_category_by_slug',
              $subcategory, array('title' => $subcategory->getName())
            );
          }
          else
          {
            echo ($subcategory) ? $subcategory : '';
          } // endif ($subcategory != $current_subcategory)

          $_height -= 20;
        ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
