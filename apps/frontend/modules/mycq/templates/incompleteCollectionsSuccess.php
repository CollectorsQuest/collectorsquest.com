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
        <?php cq_section_title('Incomplete Collections (' . $total . ')'); ?>

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
              <div class="span5 collectible_grid_view_square link"
                   data-collection-id="<?= $collection->getId(); ?>">
                <p>
                  <a href="<?= url_for('mycq_collection_by_section',
                      array(
                        'id' => $collection->getId(), 'section' => 'details',
                        'return_to' => 'incomplete_collections'
                      )
                  ) ?>" class="target">
                    <?= cqStatic::reduceText($collection->getName() . ' ('. $collection->getNumItems() .')', 35, '[...]'); ?>
                  </a>
                </p>
                <ul class="thumbnails">
                  <?php
                  $c = new Criteria();
                  $c->setLimit(8);
                  $collectibles = $collection->getCollectionCollectibles($c);

                  for ($k = 0; $k < 9; $k++)
                  {
                    if (isset($collectibles[$k]))
                    {
                      echo '<li>';
                      echo link_to(
                        image_tag_collectible(
                          $collectibles[$k], '75x75',
                          array('width' => 64, 'height' => 64)
                        ),
                        url_for(
                          'mycq_collection_by_section',
                          array('id' => $collection->getid(), 'section' => 'details', 'return_to' => 'incomplete_collections')
                        )
                      );
                      echo '</li>';
                    }
                    else
                    {
                      echo '<li><i class="icon icon-plus drop-zone" data-collection-id="'.  $collection->getId() .'"></i></li>';
                    }
                  }
                  ?>
                </ul>
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
