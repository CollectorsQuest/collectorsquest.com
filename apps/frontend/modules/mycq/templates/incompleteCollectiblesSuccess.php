<?php
/**
 * @var $pager PropelModelPager
 * @var $total integer
 */

  SmartMenu::setSelected('mycq_incomplete_tabs', 'collectibles');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_incomplete_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">

      <div class="tab-content-inner">

        <div class="row mycq-collectibles">
          <div class="row-content">
            <?php if ($total == 0): ?>
              &nbsp;&nbsp;<strong>Great!</strong> You do not have any incomplete items.<br/><br/>
            <?php endif; ?>

            <?php
              foreach ($pager->getResults() as $i => $collectible)
              {
                include_partial(
                  'mycq/collectible_grid_view',
                  array('collectible' => $collectible, 'i' => $i, 'return_to' => 'incomplete_collectibles')
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

      </div><!-- /.tab-content-inner -->
    </div><!-- .tab-pane.active -->
  </div><!-- .tab-content -->

</div><!-- #mycq-tabs -->
