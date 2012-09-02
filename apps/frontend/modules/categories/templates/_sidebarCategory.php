<?php
/**
 * @var $category ContentCategory
 */
?>


<?php
  include_component(
    '_sidebar', 'widgetContentSubCategories',
    array('current_category' => $category)
  );
?>
