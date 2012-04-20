<?php
  cq_page_title(
    $category->getName(),
    link_to('Back to Categories »', '@content_categories')
  );
?>

<br/>
<div class="row" style="margin-left: -12px;">
  <div id="collections" class="row-content">
    <?php
    /** @var $collections Collection[] */
    foreach ($collections as $i => $collection)
    {
      include_partial(
        'collection/collection_grid_view_square_small',
        array('collection' => $collection, 'i' => $i)
      );
    }
    ?>
  </div>
</div>
