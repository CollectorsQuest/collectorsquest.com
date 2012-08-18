
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

<?php slot('mycq_buy_credits'); ?>
<a href="<?php echo url_for('@seller_packages'); ?>"
   id="buy-credits-html" class="span3 collectible_sold_items_grid_view_square add-new-zone"
   title="Click here to buy more listings">
  <span class="btn-upload-collectible">
    <i class="icon-shopping-cart icon-white"></i>
  </span>
  <span class="btn-upload-collectible-txt">
    Click Here<br> To Buy More Listings
  </span>
</a>
<?php end_slot(); ?>


<?php if ($pager->getNbResults() > 0): ?>

  <?php foreach ($pager->getResults() as $i => $collectible_for_sale): ?>

  <div class="span3 collectible_sold_items_grid_view_square link">
    <?php
      echo link_to(
        image_tag_collectible(
          $collectible_for_sale->getCollectible(), '140x140',
          array('width' => 130, 'height' => 130)
        ),
        'mycq_collectible_by_slug',
        array('sf_subject' => $collectible_for_sale->getCollectible(), 'return_to' => 'market')
      );
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
          );
        ?>
        <strong class="pull-right">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
        </strong>
      </p>
    </div>

    <?php
      if (($pager->getPage() === 1 && $i === 4) || ($pager->count() === $i+1 && $pager->count() < 5))
      {
        if (isset($seller) && $seller->hasPackageCredits())
        {
          include_slot('mycq_create_collectible_for_sale');
        }
        else
        {
          include_slot('mycq_buy_credits');
        }
      }
    ?>
  <?php endforeach; ?>

<?php if ($pager->haveToPaginate()): ?>
  <div class="row-fluid pagination-wrapper">
    <?php
    include_component(
      'global', 'pagination',
      array(
        'pager' => $pager,
        'options' => array(
          'id' => 'collectibles-for-sale-pagination',
          'show_all' => false
        )
      )
    );
    ?>
  </div>

  <script>
    $(document).ready(function()
    {
      var $url = '<?= url_for('@ajax_mycq?section=component&page=collectiblesForSale', true) ?>';
      var $form = $('#form-mycq-collectibles-for-sale');

      $('#collectibles-for-sale-pagination a').click(function(e)
      {
        e.preventDefault();
        var page = $(this).data('page');

        $('#items-for-sale').showLoading();

        $('#items-for-sale').load(
          $url +'?p='+ page, $form.serialize(),
          function(data) {
            $('#items-for-sale').hideLoading();
          }
        );

        // Scroll to #slot1 so that we can see the first row of results
        $.scrollTo('#slot1');

        return false;
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
      $(this).addClass('ui-state-highlight')
    },
    out: function(event, ui)
    {
      $(this).removeClass('ui-state-highlight')
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
