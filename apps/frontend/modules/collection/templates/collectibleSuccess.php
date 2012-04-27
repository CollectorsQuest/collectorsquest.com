<?php
/**
 * @var $collectible  Collectible
 */
?>

<?php
  cq_page_title(
    $collectible->getName(), null, array('left' => 11, 'right' => 1)
  );
?>

<!--
  Test with alternate images: http://www.collectorsquest.next/collectible/3515/rkw-teacup
  Test without alternate images: http://collectorsquest.next/collectible/70081/space-set
//-->

<br/>
<div class="row-fluid">
  <?php
    $span = 10;
    if (empty($additional_multimedia))
    {
      $span += 2;
    }
  ?>
  <div class="span<?= $span; ?>">
    <div class="thumbnail" style="text-align: center;">
      <?php
        echo link_to(
          image_tag_collectible(
            $collectible, '610x1000',
            array('max_width' => 610, 'class' => 'magnify')
          ),
          src_tag_collectible($collectible, 'original'),
          array('id' => 'collectible_multimedia_primary', 'target' => '_blank')
        );
      ?>
    </div>
  </div>

  <?php if (!empty($additional_multimedia)): ?>
  <div class="span2">
    <?php foreach ($additional_multimedia as $m): ?>
    <a class="zoom" href="<?php echo src_tag_multimedia($m, '1024x768'); ?>" title="<?php echo $m->getName(); ?>" onClick="return false;">
      <?php echo image_tag_multimedia($m, '100x100', array('max_width' => 85, 'title' => $m->getName())); ?>
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
