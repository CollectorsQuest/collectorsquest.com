
<?php slot('mycq_create_collectible_for_sale'); ?>
<div id="mycq-create-collectible" class="span4 thumbnail link">
  <div class="row-fluid spacer-inner-top-20">
    <div class="span4">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollectibleForSale'); ?>"
         id="collectible-create-icon" class="open-dialog btn-create-collection-middle spacer-left-20">
        <i class="icon-plus icon-white"></i>
      </a>
    </div>
    <div class="span8">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollectibleForSale'); ?>"
         id="collectible-create-link" class="open-dialog create-collection-text">
        Add a new Item for Sale by clicking here.
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

  <?php if ($pager->haveToPaginate() && $pager->getPage() === 1): ?>

    <button class="btn btn-small see-more-full" id="seemore-mycq-collectibles-for-sale">
      See more
    </button>

    <script>
      $(document).ready(function()
      {
        var $url = '<?= url_for('@ajax_mycq?section=component&page=collectiblesForSale', true) ?>';
        var $form = $('#form-mycq-collectibles-for-sale');

        $('#seemore-mycq-collectibles-for-sale').click(function()
        {
          var $button = $(this);
          $button.html('loading...');

          $.post($url +'?p=2', $form.serialize(), function(data)
          {
            $('div.mycq-collectibles-for-sale .thumbnails').append(data);
            $button.hide();
          }, 'html');
        });
      });
    </script>

  <?php endif; ?>

<?php else: ?>
  <div class="span12 thumbnail link no-collections-uploaded-box">
    <?php if ($sf_params->get('q')): ?>
      <span class="Chivo webfont info-no-collections-uploaded" style="padding-top: 15px;">
        None of your Items for Sale match search term: <strong><?= $sf_params->get('q'); ?></strong>
      </span>
    <?php else: ?>
      <span class="Chivo webfont info-no-collections-uploaded">
        Sell your items in the marketplace today!<br/>
        Get Started Now!
      </span>
    <?php endif; ?>
  </div>
  <?php if (isset($seller) && $seller->hasPackageCredits()): ?>
    <?php include_slot('mycq_create_collectible_for_sale'); ?>
  <?php else: ?>
  <div id="mycq-create-collectible" class="span4 thumbnail link">
    <div class="row-fluid spacer-inner-top-20">
      <div class="span4">
        <a href="<?php echo url_for('@seller_packages'); ?>"
           class="btn-create-collection-middle spacer-left-20">
          <i class="icon-shopping-cart"></i>
        </a>
      </div>
      <div class="span8">
        <div class="btn-large-box">
          <a href="<?php echo url_for('@seller_packages'); ?>">
            Buy<br/> Credits
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
<?php endif; ?>


<script>
$(document).ready(function()
{
  $(document).controls();

  $(".mycq-collectibles-for-sale .drop-zone").droppable(
  {
    over: function(event, ui)
    {
      $(this)
        .addClass('ui-state-highlight')
        .removeClass('icon-plus')
        .addClass('icon-download-alt');
    },
    out: function(event, ui)
    {
      $(this)
        .removeClass('ui-state-highlight')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
    },
    drop: function(event, ui)
    {
      $(this)
        .removeClass('ui-state-highlight')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      ui.draggable.draggable('option', 'revert', false);

      $.ajax({
        url: '<?php echo url_for('@ajax_mycq?section=collectible&page=donateImage'); ?>',
        type: 'GET',
        data: {
          donor_id: ui.draggable.data('collectible-id'),
          recipient_id: $(this).data('collectible-id')
        },
        success: function()
        {
          ui.draggable.draggable('option', 'revert', false);
          ui.draggable.hide();
        },
        error: function()
        {
          ui.draggable.draggable('option', 'revert', true);
          ui.draggable.show();
        }
      });
    }
  });

});
</script>
