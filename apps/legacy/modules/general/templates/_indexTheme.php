<?php $error_reporting = error_reporting(0); ?>
<?php if ($theme == 1): ?>
<div class="span-25 last" style="margin: auto; text-align: center;">
  <div style="width: 242px; float: left; position: relative;">
    <?php
      if ($collectibles[2] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[2], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        ), '@collectible_by_slug?id='. $collectibles[2]->getId() .'&slug='. $collectibles[2]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        );
      }
    ?>
    <?php
      if ($collectibles[3] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[3], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        ), '@collectible_by_slug?id='. $collectibles[3]->getId() .'&slug='. $collectibles[3]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        );
      }
    ?>
    <?php
      if ($collectibles[4] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[4], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        ), '@collectible_by_slug?id='. $collectibles[4]->getId() .'&slug='. $collectibles[4]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        );
      }
    ?>
    <?php
      if ($collectibles[0] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[0], '230x150',
          array('width' => 230, 'height' => 150, 'style' => 'margin: 1px 3px; border: 2px solid transparent')
        ), '@collectible_by_slug?id='. $collectibles[0]->getId() .'&slug='. $collectibles[0]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/230x150.png',
          array('width' => 230, 'height' => 150, 'style' => 'margin: 1px 3px; border: 2px solid transparent')
        );
      }
    ?>
  </div>
  <div style="width: 85px; float: left;">
    <?php
      if ($collectibles[5] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[5], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px 3px 1px 3px;')
        ), '@collectible_by_slug?id='. $collectibles[5]->getId() .'&slug='. $collectibles[5]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px 3px 1px 3px;')
        );
      }
    ?>
    <?php
      if ($collectibles[6] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[6], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        ), '@collectible_by_slug?id='. $collectibles[6]->getId() .'&slug='. $collectibles[6]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        );
      }
    ?>
    <?php
      if ($collectibles[7] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[7], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        ), '@collectible_by_slug?id='. $collectibles[7]->getId() .'&slug='. $collectibles[7]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'margin: 3px;')
        );
      }
    ?>
  </div>
  <div style="float: left; width: 181px;">
    <?php
      if ($collectibles[1] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[1], '170x230',
          array('width' => 170, 'height' => 230, 'style' => 'margin: 3px 5px 3px 2px;')
        ), '@collectible_by_slug?id='. $collectibles[1]->getId() .'&slug='. $collectibles[1]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/170x230.png',
          array('width' => 170, 'height' => 230, 'style' => 'margin: 3px 5px 3px 2px;')
        );
      }
    ?>
  </div>
  <div style="float: left; width: 482px;">
    <?php
      if ($collections[0] instanceof Collection)
      {
        echo link_to(image_tag_collection(
          $collections[0], '150x150',
          array('width' => 150, 'height' => 150, 'style' => 'float: left; margin: 3px 4px 3px 3px;')
        ), route_for_collection($collections[0]));
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collection/150x150.png',
          array('width' => 150, 'height' => 150, 'style' => 'float: left; margin: 3px 4px 3px 3px;')
        );
      }
    ?>
    <?
      if ($collections[1] instanceof Collection)
      {
        echo link_to(image_tag_collection(
          $collections[1], '150x150',
          array('width' => 150, 'height' => 150, 'style' => 'float: left; margin: 3px 4px 3px 3px;')
        ), route_for_collection($collections[1]));
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collection/150x150.png',
          array('style' => 'float: left; margin: 3px 4px 3px 3px;')
        );
      }
    ?>
    <?php
      if ($collectibles[8] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[8], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 4px;')
        ), '@collectible_by_slug?id='. $collectibles[8]->getId() .'&slug='. $collectibles[8]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 4px;')
        );
      }
    ?>
    <?php
      if ($collectibles[9] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[9], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 4px;')
        ), '@collectible_by_slug?id='. $collectibles[9]->getId() .'&slug='. $collectibles[9]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 4px;')
        );
      }
    ?>
    <?php
      if ($collectibles[10] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[10], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 4px;')
        ), '@collectible_by_slug?id='. $collectibles[10]->getId() .'&slug='. $collectibles[10]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 4px;')
        );
      }
    ?>
    <?php
      if ($collectibles[11] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[11], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 4px;')
        ), '@collectible_by_slug?id='. $collectibles[11]->getId() .'&slug='. $collectibles[11]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 4px;')
        );
      }
    ?>
    <div class="clear"></div>
    <?php
      if ($collectibles[12] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[12], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 5px 3px 4px;')
        ), '@collectible_by_slug?id='. $collectibles[12]->getId() .'&slug='. $collectibles[12]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 5px 3px 4px;')
        );
      }
    ?>
    <?php
      if ($collectibles[13] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[13], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 4px 3px 3px;')
        ), '@collectible_by_slug?id='. $collectibles[13]->getId() .'&slug='. $collectibles[13]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 4px 3px 3px;')
        );
      }
    ?>
    <?php
      if ($collectibles[14] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[14], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 5px 3px 5px;')
        ), '@collectible_by_slug?id='. $collectibles[14]->getId() .'&slug='. $collectibles[14]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 4px 3px 3px;')
        );
      }
    ?>
    <?php
      if ($collectibles[15] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[15], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 3px 3px 5px;')
        ), '@collectible_by_slug?id='. $collectibles[15]->getId() .'&slug='. $collectibles[15]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 3px 3px 5px;')
        );
      }
    ?>
    <?php
      if ($collectibles[16] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[16], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 4px 4px 4px;')
        ), '@collectible_by_slug?id='. $collectibles[16]->getId() .'&slug='. $collectibles[16]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 4px 4px 4px;')
        );
      }
    ?>
    <?php
      if ($collectibles[17] instanceof Collectible)
      {
        echo link_to(image_tag_collectible(
          $collectibles[17], '75x75',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 4px 4px 4px;')
        ), '@collectible_by_slug?id='. $collectibles[17]->getId() .'&slug='. $collectibles[17]->getSlug());
      }
      else
      {
        echo image_tag(
          'legacy/multimedia/Collectible/75x75.png',
          array('width' => 70, 'height' => 70, 'style' => 'float: left; margin: 5px 4px 4px 4px;')
        );
      }
    ?>
  </div>
</div>
<?php elseif ($theme == 2): ?>

<?php endif; ?>

<?php error_reporting($error_reporting); ?>
