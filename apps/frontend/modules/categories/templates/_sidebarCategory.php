<?php
/**
 * @var $category ContentCategory
 */
?>


<?php
  include_component(
    '_sidebar', 'widgetContentSubCategories',
    array('current_category' => $category, 'fallback' => '1st_level_categories')
  );
?>
