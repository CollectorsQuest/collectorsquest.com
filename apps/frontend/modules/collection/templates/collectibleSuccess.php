<?php
/**
 * @var $collectible  Collectible
 */
?>

<h1>
  <?= $collectible->getName(); ?>
  <small>by <?= link_to_collector($collectible->getCollector(), 'text'); ?></small>
</h1>

<!--
  Test with alternate images: http://www.collectorsquest.next/collectible/3515/rkw-teacup
  Test without alternate images: http://collectorsquest.next/collectible/70081/space-set
//-->

<div class="row-fluid">
  <?php
    $span = 10;
    if (empty($additional_multimedia))
    {
      $span += 2;
    }
  ?>
  <div class="span<?= $span; ?>">
    <div class="thumbnail">
      <?php
        link_to(
          image_tag_collectible(
            $collectible, '420x1000',
            array('max_width' => 420, 'class' => 'magnify', 'style' => 'margin-top: 5px;')
          ),
          src_tag_collectible($collectible, '1024x768'),
          array('id' => 'collectible_multimedia_primary')
        );
      ?>
      <!--
        4:3  = 0.7500
       16:10 = 0.6250
       16:9  = 0.5625
      //-->
      <img srcs="http://placehold.it/400x320"/>
      <img src="/images/frontend/mockups/collectible.700x1000.jpg"/>
      <img srcs="http://placehold.it/1024x768"/>
    </div>
  </div>

  <?php if (!empty($additional_multimedia)): ?>
  <div class="span2" style="width: 75px; height: 75px; margin: 0; padding-left: 15px;">
    <?php foreach ($additional_multimedia as $m): ?>
    <a class="zoom" href="<?php echo src_tag_multimedia($m, '1024x768'); ?>" title="<?php echo $m->getName(); ?>" onClick="return false;">
      <?php echo image_tag_multimedia($m, '75x75', array('width' => 75, 'title' => $m->getName())); ?>
      <br/><br/>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php if ($collectible->getDescription('stripped')): ?>
<br style="clear:both;"/>
<div class="row-fluid">
  <h3>
    <?php
    if ($sf_user->isOwnerOf($collectible))
    {
      echo __('This is what you said about this collectible:');
    }
    else
    {
      echo sprintf(__('What %s says about this collectible:'), link_to_collector($collectible, 'text'));
    }
    ?>
  </h3>
  <br style="clear:both;"/>
  <div>
    <dd id="collectible_<?= $collectible->getId(); ?>_description"
        style="border-left: 2px solid #eee; padding-left: 15px; font-size: 14px;"
      ><?= $collectible->getDescription('html'); ?></dd>
  </div>
</div>
<br style="clear:both;"/>
<?php endif; ?>
