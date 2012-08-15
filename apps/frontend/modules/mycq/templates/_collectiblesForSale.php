
<?php slot('mycq_create_collectible_for_sale'); ?>
  <a href="<?php echo url_for('@ajax_mycq?section=collectibleForSale&page=create'); ?>"
     id="collectible-create-html" class="span3 collectible_sold_items_grid_view_square add-new-zone ui-droppable open-dialog"
     title="Add a new Item for Sale by clicking here." onclick="return false;">
      <span id="collection-create-icon" class="btn-upload-collectible">
        <i class="icon-plus icon-white"></i>
      </span>
      <span id="collection-create-link" class="btn-upload-collectible-txt">
        Add New<br> Item for Sale
      </span>
  </a>
<?php end_slot(); ?>


<?php if ($pager->getNbResults() > 0): ?>

  <?php foreach ($pager->getResults() as $i => $collectible_for_sale): ?>

  <div class="span3 collectible_sold_items_grid_view_square link">
    <?php
    echo link_to(image_tag_collectible(
      $collectible_for_sale->getCollectible(), '140x140',
      array('width' => 130, 'height' => 130)
    ), 'mycq_collectible_by_slug', $collectible_for_sale->getCollectible());
    ?>
    <span class="for-sale">FOR SALE</span>
      <p>
        <?php
        echo link_to(
          cqStatic::truncateText(
            $collectible_for_sale->getCollectible()->getName(), 36, '...', true
          ),
          'mycq_collectible_by_slug', $collectible_for_sale->getCollectible(),
          array('class' => 'target')
        ) ;
        ?>
        <strong class="pull-right">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
        </strong>
      </p>
    </div>

    <?php
      if (($pager->getPage() === 1 && $i === 4) || ($pager->count() === $i+1 && $pager->count() < 5))
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
  <?php if (isset($seller) && $seller->hasPackageCredits()): ?>
    <?php include_slot('mycq_create_collectible_for_sale'); ?>
      <div class="no-collections-uploaded-box spacer-bottom link">
        <?php if ($sf_params->get('q')): ?>
        <span class="Chivo webfont info-no-collections-uploaded spacer-top-15">
            None of your Items for Sale match search term: <strong><?= $sf_params->get('q'); ?></strong>
          </span>
        <?php else: ?>
        <span class="Chivo webfont info-no-collections-uploaded spacer-bottom">
            Sell your items in the marketplace today!<br/>
            Get Started Now!
          </span>
        <?php endif; ?>
      </div>
  <?php else: ?>
    <?php
      include_partial(
        'mycq/partials/buy_package_listing'
      );
    ?>
  <?php endif; ?>
<?php endif; ?>


<script>
$(document).ready(function()
{
  $(document).controls();

  $("#collectible-create-html").droppable(
    {
      activeClass: 'ui-state-hover',
      over: function(event, ui)
      {
        $(this)
          .addClass('ui-state-highlight')
      },
      out: function(event, ui)
      {
        $(this)
          .removeClass('ui-state-highlight')
      },
      drop: function(event, ui)
      {
        $(this).removeClass('ui-state-highlight');
        ui.draggable.draggable('option', 'revert', false);

        var href = $(this).attr('href');
        href = href +'?collectible_id=' + ui.draggable.data('collectible-id');

        var options = {
          modal: true,
          autOpen: true,
          content: href
        };

        $("<div></div>").dialog2(options);
      }
    });

});
</script>
