<?php
  // default case for return_to
  if (!isset($return_to))
  {
    $return_to = 'collection';
  }
?>

<div class="span3 collectible_grid_view_square link">
  <div class="collectible-view-slot <?= !$collectible->getIsPublic() ? 'incomplete' : null; ?>">
    <div class="overlay"></div>
    <?php
      echo link_to(
        image_tag_collectible(
          $collectible, '150x150', array('width' => 140, 'height' => 140)
        ),
        'mycq_collectible_by_slug', array('sf_subject' => $collectible, 'return_to' => $return_to),
        array('class' => 'target')
      );
    ?>

    <?php if ($collectible->isSold()): ?>
      <span class="sold">SOLD</span>
    <?php elseif ($collectible->isForSale()): ?>
      <span class="for-sale">FOR SALE</span>
    <?php endif; ?>

    <p>
      <?php
        echo link_to(
          $collectible->getName() ? cqStatic::reduceText($collectible->getName(), 30) : 'Untitled',
          'mycq_collectible_by_slug', array('sf_subject' => $collectible, 'return_to' => $return_to),
          array('class' => 'target')
        );
      ?>
    </p>
  </div>
  <div class="hidden">
    <div class="add-new-zone ui-droppable ui-state-hover ui-state-highlight mycq-create-collectible">
      <div class="btn-upload-collectible">
        <i class="icon-plus icon-white"></i>
      </div>
      <div class="btn-upload-collectible-txt">
        ADD NEW ITEM
      </div>
    </div>
  </div>
</div>
