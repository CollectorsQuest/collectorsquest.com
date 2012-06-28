
<?php cq_page_title("Independence Day - 4th of July") ?>

<br/>
<div class="row">
  <div class="row-content">

    <div class="span6" style="width: 320px;">
      <?= image_tag('frontend/mockups/Untitled-2.jpg') ?>
    </div>
    <?php foreach ($collectibles as $i => $collectible): ?>
    <div class="span2" style="width: 100px; margin-bottom: 10px; <?= (($i-12)%7 !== 0 || $i < 12) ? 'margin-left: 10px' : ''; ?>">
      <?= link_to_collectible($collectible, 'image', array('width' => 100, 'height' => 100)); ?>
    </div>
    <?php endforeach; ?>

    <!-- This is for the stars of the flag to take two rows instead of 3
    <div class="span6" style="width: 320px;">
      <?= image_tag('frontend/mockups/Untitled-2.png') ?>
    </div>
    <?php foreach ($collectibles as $i => $collectible): ?>
    <div class="span2" style="width: 100px; margin-bottom: 10px; <?= (($i-8)%7 !== 0 || $i < 8) ? 'margin-left: 10px' : ''; ?>">
      <?= link_to_collectible($collectible, 'image', array('width' => 100, 'height' => 100)); ?>
    </div>
    <?php endforeach; ?>
    //-->

  </div>
</div>
