<?php cq_sidebar_title('Collections of Interest') ?>

<div class="thumbnails-box-1x4-sidebar bgyellow-noborder">
  <div class="inner-thumbnails-box">
    <p>
      <a href="#" title="Vintage kitchenalia cookie jars">
        Vintage kitchenalia cookie jars
      </a>
    </p>
    <div class="thumb-container">
      <a href="#" class="thumbnails54">
        <img width="54" height="54" alt="" src="http://multimedia.collectorsquest.dev/image/75x75/p1-704738-76682.jpg?1328815166">
      </a>
      <a href="#" class="thumbnails54">
        <img width="54" height="54" alt="" src="http://multimedia.collectorsquest.dev/image/75x75/p1-704738-76682.jpg?1328815166">
      </a>
      <a href="#" class="thumbnails54">
        <img width="54" height="54" alt="" src="http://multimedia.collectorsquest.dev/image/75x75/p1-704738-76682.jpg?1328815166">
      </a>
      <a href="#" class="thumbnails54">
        <img width="54" height="54" alt="" src="http://multimedia.collectorsquest.dev/image/75x75/p1-704738-76682.jpg?1328815166">
      </a>
      <a href="#" class="thumbnails54">
        <img width="54" height="54" alt="" src="http://multimedia.collectorsquest.dev/image/75x75/p1-704738-76682.jpg?1328815166">
      </a>
    </div>
  </div>
</div>

<?php cq_sidebar_title('Collections of Interest') ?>

<?php foreach ($collections as $collection): ?>
  <div id="sidebar_collection_<?php echo  $collection->getId(); ?>" class="row-fluid link">
    <div class="span3" style="text-align: center">
      <?= link_to_collection($collection, 'image', array('width' => 50, 'height' => 50)); ?>
    </div>
    <div class="span9">
      <?= link_to_collection($collection, 'text', array('class' => 'target')); ?>
      <br/>by <?= link_to_collector($collection, 'text'); ?>
    </div>
  </div>
  <br clear="all">
<?php endforeach; ?>




