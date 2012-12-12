<?php
/**
 * @var $level1_categories ContentCategory[]
 * @var $category_other    ContentCategory
 */
?>

<?php cq_page_title('Categories'); ?>

<div id="all-categories" class="row">
<?php
  $i = 0;
  foreach ($level1_categories as $category)
  {
    $level2_links = array();

    foreach ($category->getChildrenWithCollections() as $child_category)
    {
      $level2_links[] = link_to_content_category($child_category);
    }

    if (!empty($level2_links))
    {
      echo '<div class="span4">';
      cq_section_title(link_to_content_category($category));
      echo implode(', ', $level2_links);
      echo '</div>';

      $i++;
    }

    //3 columns for mobile devices, 4 columns for desktop
    echo ($i % 3 == 0) ? '<br class="clearfix three-col">' : null;
    echo ($i % 4 == 0) ? '<br class="clearfix">' : null;
  }
?>
</div>
