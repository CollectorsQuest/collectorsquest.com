<?php
/**
 * @var $collectible Collectible
 * @var $shopping_order ShoppingOrder
 */
?>

<div class="row-fluid">
  <div class="span4">
    <div id="main-image-set">
      <div class="main-image-set-container">
        <ul class="thumbnails">
          <li class="span12 main-thumb">
            <?php if ($image = $collectible->getPrimaryImage()): ?>
            <div class="thumbnail drop-zone-large" data-is-primary="1">
              <?php
                echo image_tag_multimedia(
                  $image, '300x0',
                  array(
                    'width' => 294, 'id' => 'multimedia-'. $image->getId(),
                  )
                );
              ?>
            </div>
            <?php else: ?>
            <div class="thumbnail drop-zone-large empty" data-is-primary="1">
              &nbsp;
            </div>
            <?php endif; ?>
          </li>
          <?php foreach ($multimedia as $m): ?>
          <li class="span4 square-thumb ui-state-full">
            <div class="thumbnail drop-zone" data-is-primary="0">
              <?php
                echo image_tag_multimedia(
                  $m, '150x150', array('width' => 92, 'height' => 92)
                );
              ?>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

  </div><!-- ./span4 -->
  <div class="span8">
    <?php
      $link = link_to(
        'Go to Store &raquo;', '@mycq_marketplace',
        array('class' => 'text-v-middle link-align')
      );

      cq_sidebar_title(
        $collectible->getName(), $link,
        array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
      );
    ?>
  </div><!-- ./span8 -->

</div>
