
<?php slot('mycq_create_collectible_for_sale'); ?>
<div id="mycq-create-collectible" class="span4 thumbnail link">
  <div class="row-fluid spacer-inner-top-20">
    <div class="span4">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollectible'); ?>"
         id="collectible-create-icon" class="open-dialog btn-create-collection-middle spacer-left-20">
        <i class="icon-plus icon-white"></i>
      </a>
    </div>
    <div class="span8">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollectibleForSale'); ?>"
         id="collectible-create-link" class="open-dialog create-collection-text">
        Add a new Collectible for Sale by clicking here.
      </a>
    </div>
  </div>
</div>
<?php end_slot(); ?>


<?php if ($pager->getNbResults() > 0): ?>

  <?php foreach ($pager->getResults() as $i => $collectible_for_sale): ?>

    <div class="span4 thumbnail link">
      <div class="collectibles-container">
        <?php
          $q = iceModelMultimediaQuery::create()
            ->filterByModel('Collectible')
            ->filterByModelId($collectible_for_sale->getCollectibleId())
            ->orderByIsPrimary(Criteria::DESC)
            ->orderByCreatedAt(Criteria::DESC);
          $multimedia = $q->limit(2)->find();

          for ($k = 0; $k < 3; $k++)
          {
            if (isset($multimedia[$k]))
            {
              echo link_to(image_tag_multimedia(
                $multimedia[$k], '75x75',
                array('max_width' => 64, 'max_height' => 64,)
              ), url_for('mycq_collectible_by_slug', $collectible_for_sale->getCollectible()));
            }
            else
            {
              echo '<i class="icon icon-plus drop-zone" data-collectible-id="'.  $collectible_for_sale->getCollectibleId() .'"></i>';
            }
          }
        ?>
      </div>
      <span>
        <a href="<?= url_for('mycq_collectible_by_slug', $collectible_for_sale->getCollectible()) ?>" style="margin-left: 0px;" class="target">
          <?= Utf8::truncateHtmlKeepWordsWhole($collectible_for_sale->getName(), 32); ?>
        </a>
      </span>
      <div class="prices">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </div>
    </div>

    <?php
      if (($pager->getPage() === 1 && $i === 2) || ($pager->count() === $i+1 && $pager->count() < 3))
      {
        include_slot('mycq_create_collectible_for_sale');
      }
    ?>
  <?php endforeach; ?>
<?php else: ?>
  <?php include_slot('mycq_create_collectible_for_sale'); ?>
<?php endif; ?>
