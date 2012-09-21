<?php
/**
 * @var $pager PropelModelPager
 * @var $collection Collection
 * @var $total integer
 */

SmartMenu::setSelected('mycq_incomplete_tabs', 'collections');
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
              <div class="thumbnail link no-collections-uploaded-box">
                <span class="Chivo webfont info-no-collections-uploaded">
                  Great! <br>
                  You do not have any incomplete collections.
                </span>
              </div>
            <?php endif; ?>

            <?php foreach ($pager->getResults() as $i => $collection): ?>
              <div class="span3 collectible_grid_view_square"
                   data-collection-id="<?= $collection->getId(); ?>">
                <?php
                  echo link_to(
                    image_tag_collection(
                      $collection, '150x150', array('width' => 140, 'height' => 140)
                    ),
                    'mycq_collection_by_section',
                    array(
                      'id' => $collection->getId(), 'section' => 'details', 'return_to' => 'incomplete_collections'
                    )
                  );
                ?>
                <p>
                  <a href="<?= url_for('mycq_collection_by_section',
                      array(
                        'id' => $collection->getId(), 'section' => 'details',
                        'return_to' => 'incomplete_collections'
                      )
                  ) ?>" class="target">
                    <?= cqStatic::reduceText($collection->getName() . ' ('. $collection->countCollectionCollectibles() .')', 35, '[...]'); ?>
                  </a>
                </p>

              </div>

            <?php endforeach;?>

            <div class="row-fluid pagination-wrapper">
              <?php
              include_component(
                'global', 'pagination',
                array(
                  'pager' => $pager,
                  'options' => array(
                    'id' => 'collections-pagination',
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
