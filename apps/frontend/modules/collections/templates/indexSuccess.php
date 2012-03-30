<?php cq_page_title('Collections'); ?>

<?php cq_section_title('Explore collections'); ?>

<div class="row-fluid">
  <div class="span12">Filters</div>
</div>

<div class="row">
  <div id="collectibles" class="row-content">
    <?php
    /** @var $collectible Collectible */
    foreach ($collections as $i => $collection)
    {
      echo '<div class="span4" style="margin-bottom: 30px;">';
      //echo link_to_collection($collection, 'image');
      echo ice_image_tag_placeholder('190x150');
      echo '</div>';
    }
    ?>
  </div>
</div>

