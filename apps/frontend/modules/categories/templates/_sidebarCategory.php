<?php
/**
 * @var $category ContentCategory
 */
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectionSubCategories',
    array(
      'current_category' => $category,
      'fallback' => 'widgetCollectionCategories', 'level' => 1
    )
  );
?>
