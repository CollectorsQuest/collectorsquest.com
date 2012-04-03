<?php cq_page_title('Collections'); ?>

<?php cq_section_title('Explore collections'); ?>

<div class="row-fluid">
  <div class="span12">Filters</div>
</div>

<div class="row">
  <div id="collectibles" class="row-content">
    <?php
    /** @var $collections Collection[] */
    foreach ($collections as $i => $collection)
    {
      echo '<div class="span4" style="margin-bottom: 15px">';
      include_partial(
        'collection/collection_grid_view',
        array('collection' => $collection, 'i' => $i)
      );
      echo '</div>';
    }
    ?>
  </div>
</div>
