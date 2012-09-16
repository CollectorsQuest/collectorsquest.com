<?php cq_section_title('Incomplete Collectibles'); ?>

<div class="row mycq-collectibles">
  <div class="row-content" id="collectibles">
    <?php
      foreach ($pager->getResults() as $i => $collectible)
      {
        include_partial(
          'mycq/collectible_grid_view',
          array('collectible' => $collectible, 'i' => $i)
        );
      }
    ?>

    <div class="row-fluid pagination-wrapper">
      <?php
        include_component(
          'global', 'pagination',
          array(
            'pager' => $pager,
            'options' => array(
              'id' => 'collectibles-pagination',
              'show_all' => false
            )
          )
        );
      ?>
    </div>
  </div>
</div>
