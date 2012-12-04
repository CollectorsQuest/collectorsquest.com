
<?php
/*
 * @var $height stdClass
 * @var $collections Collection[]
 */

$_height = 0;
?>

<?php cq_sidebar_title('Collections of Interest') ?>
<?php $_height -= 63; ?>

<div class="optimize-mobile-300">
  <?php if (false) foreach ($collections as $collection): ?>
  <div class="thumbnails-box-1x4-sidebar bgyellow-noborder">
    <div class="inner-thumbnails-box">
      <p>
        <?= link_to_collection($collection, 'text', array('class' => 'target')); ?>
        by <?= link_to_collector($collection, 'text'); ?>
      </p>
      <div class="thumb-container">
        <a href="javascript:void(0)" class="thumbnails54">
          <img width="54" height="54" alt="" src="http://multimedia.cqcdns.dev/image/75x75/p1-704738-76682.jpg?1328815166">
        </a>
        <a href="javascript:void(0)" class="thumbnails54">
          <img width="54" height="54" alt="" src="http://multimedia.cqcdns.dev/image/75x75/p1-704738-76682.jpg?1328815166">
        </a>
        <a href="javascript:void(0)" class="thumbnails54">
          <img width="54" height="54" alt="" src="http://multimedia.cqcdns.dev/image/75x75/p1-704738-76682.jpg?1328815166">
        </a>
        <a href="javascript:void(0)" class="thumbnails54">
          <img width="54" height="54" alt="" src="http://multimedia.cqcdns.dev/image/75x75/p1-704738-76682.jpg?1328815166">
        </a>
        <a href="javascript:void(0)" class="thumbnails54">
          <img width="54" height="54" alt="" src="http://multimedia.cqcdns.dev/image/75x75/p1-704738-76682.jpg?1328815166">
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <?php foreach ($collections as $collection): ?>
    <div id="sidebar_collection_<?php echo  $collection->getId(); ?>" class="row-fluid collection-container link">
      <div class="span3 text-center">
        <?php
          echo link_to_collection($collection, 'image', array(
              'image_tag' => array('width' => 50, 'height' => 50)
          ));
        ?>
      </div>
      <div class="span9">
        <?= link_to_collection($collection, 'text', array('class' => 'target')); ?>
        <br/>by <?= link_to_collector($collection, 'text'); ?>
      </div>
    </div>
    <?php $_height -= 66; ?>
  <?php endforeach; ?>
</div>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>


