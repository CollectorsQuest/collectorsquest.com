<?php
/**
 * @var $pager PropelModelPager
 * @var $forms ShoppingOrderFeedbackForm[]
 * @var $shopping_order_feedback ShoppingOrderFeedback
 */


  SmartMenu::setSelected('mycq_incomplete_tabs', 'collectibles');
?>

<div id="mycq-tabs">
    <ul class="nav nav-tabs">
      <li <?= (!$tab ? 'class="active"' : '') ?>><?= link_to('All', '@mycq_feedback_leave') ?></li>
      <?php if ($tab): ?>
        <li class="active"><a href="href="javascript:void(0)" ><?= $tab ?></a ></li>
      <?php endif ?>
    </ul>
  <div class="tab-content">
    <div class="tab-pane active">

      <div class="tab-content-inner">
        <?= form_tag(isset($shopping_order_feedback)
        ? '@mycq_collectible_feedback_leave?id='.$shopping_order_feedback->getId()
        : '@mycq_feedback_leave?page='.$pager->getPage()); ?>
        <div class="row mycq-collectibles">
            <?php if (count($forms)): ?>
            <?php foreach ($forms as $form):
                /** @var $collectible Collectible */
                $collectible = $form->getObject()->getCollectible();
                ?>
                <div class="row-fluid">
                  <div class="span2 offset2 collectible_sold_items_grid_view_square link">
                    <?php
                    echo link_to(image_tag_collectible(
                      $collectible, '140x140',
                      array('width' => 130, 'height' => 130)
                    ), 'mycq_collectible_by_slug', $collectible);
                    ?>
                    <?php if ($form->getObject()->getRatingFor() == 'buyer'): ?>
                      <span class="sold">SOLD</span>
                    <?php endif ?>
                    <?php if ($form->getObject()->getRatingFor() == 'seller'): ?>
                      <span class="purchased">PURCHASED</span>
                    <?php endif ?>
                    <p>
                      <?php
                      echo link_to(
                        cqStatic::truncateText(
                          $collectible->getName(), 36, '...', true
                        ),
                        'mycq_collectible_by_slug', $collectible,
                        array('class' => 'target')
                      ) ;
                      ?>
                      <strong class="pull-right">
                        <?= money_format('%.2n', (float) $collectible->getCollectibleForSale()->getPrice()); ?>
                      </strong>
                    </p>
                  </div>
                  <div class="span8">
                    <?php echo $form ;  ?>
                  </div>
                </div>
             <?php endforeach; ?>

          <div class="form-actions">
            <input type="submit" class="btn btn-primary" value="Submit" />
          </div>
          <?php else: ?>
          <div class="rwo3 offset1">
            You have no items that awaiting for feedback.
          <div>
          <?php endif ?>


            <?php if (!$tab): ?>
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
            <?php endif; ?>
          </div>
        </div>


        </form>

      </div><!-- /.tab-content-inner -->
    </div><!-- .tab-pane.active -->
  </div><!-- .tab-content -->

</div><!-- #mycq-tabs -->
