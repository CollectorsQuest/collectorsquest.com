<?php
/*
 * @var $height stdClass
 * @var $collections Collection[]
 */
  if (!isset($height)):
    $height = new stdClass;
    $height->value=0;
  endif;
?>

<?php if ($height->value > (count($collections)* 66 + 63)): ?>
  <?php cq_sidebar_title('Collections of Interest') ?>
  <?php $height->value -= 63; ?>

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
    <?php $height->value -= 66; ?>
  <?php endforeach; ?>

<?php endif;


