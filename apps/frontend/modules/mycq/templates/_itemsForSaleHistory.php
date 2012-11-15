<?php
/**
 * @var $search        string
 * @var $filter_by     string
 * @var $pager         PropelModelPager
 * @var $seller        Seller
 */
?>

<div id="items-for-sale">
    <table class="table table-striped table-items-for-sale-history">
      <thead>
        <tr>
          <th class="items-column">&nbsp;</th>
          <th>Expires</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($pager->getNbResults()): ?>
        <?php foreach ($pager->getResults() as $i => $collectible_for_sale): ?>
        <?php /* @var $collectible_for_sale CollectibleForSale */ ?>
        <tr>
          <td>
            <div class="row-fluid items">
              <div class="span2">
                <a href="" class="thumb">
                  <?php
                    echo link_to(
                      image_tag_collectible(
                        $collectible_for_sale->getCollectible(), '75x75',
                        array('width' => 75, 'height' => 75)
                      ),
                      'mycq_collectible_by_slug',
                      array('sf_subject' => $collectible_for_sale->getCollectible(), 'return_to' => 'market'),
                      array('target' => '_blank')
                    );
                  ?>
                </a>
              </div>
              <div class="span10">
                <span class="title">
                  <?php
                    echo link_to_collectible(
                      $collectible_for_sale->getCollectible(), $type = 'text',
                      array('link_to' => array ('target' => '_blank'))
                    );
                  ?>
                </span>
                <span class="description">
                  <?= $collectible_for_sale->getCollectible()->getDescription('stripped') ?>
                </span>
                <span class="price">
                  <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
                </span>
              </div>
            </div>
          </td>
          <td>
            <?= $collectible_for_sale->getExpiryDate($format = 'F j, Y'); ?>
          </td>
          <td class="status">
            <?php if ($collectible_for_sale->getIsSold()): ?>
              Sold
            <?php elseif ($collectible_for_sale->isForSale() && $collectible_for_sale->hasActiveCredit()): ?>
              Active
            <?php elseif(!$collectible_for_sale->hasActiveCredit() && $collectible_for_sale->isForSale()): ?>
              Expired
            <?php else: ?>
              Inactive
            <?php endif; ?>
          </td>
          <td>
            <?php if ($collectible_for_sale->getIsSold()): ?>
              -
            <?php elseif ($collectible_for_sale->isForSale() && $collectible_for_sale->hasActiveCredit()) : ?>
              <a data-id="<?= $collectible_for_sale->getCollectible()->getId(); ?>"
                 class="deactivate btn btn-mini"
                 onclick="return confirm('Are you sure you sure you want to deactivate this item?')">
                <i class="icon-minus-sign"></i>&nbsp;Deactivate
              </a>
            <?php elseif (!$seller->hasPackageCredits()) : ?>
              <a href="<?php echo url_for('@seller_packages'); ?>" class="btn btn-mini">
                <i class="icon-plus-sign"></i>&nbsp;Buy credits
              </a>
            <?php else: ?>
            <a data-id="<?= $collectible_for_sale->getCollectible()->getId(); ?>"
               class="relist btn btn-mini"
               onclick="return confirm('Are you sure you sure you want to re-list this item?')">
              <i class="icon-undo"></i>&nbsp;Re-list
            </a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; elseif ('' == $search) : ?>
      <tr>
        <td colspan="5">
            You have no <?= $filter_by == 'all' ? '' : '<strong>' . $filter_by. '</strong>' ?>
            items for sale yet.
        </td>
      </tr>
        <?php else: ?>
      <tr>
        <td colspan="5">
            No <?= $filter_by == 'all' ? '' : '<strong>' . $filter_by. '</strong>' ?>
            items for sale matched your search term "<?= $search?>".
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>

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
</div> <!-- #items-for-sale -->

<script>
  $(document).ready(function()
  {
    var $url = '<?= url_for('@ajax_mycq?section=component&page=itemsForSaleHistory', true) ?>';
    var $form = $('#form-mycq-collectibles-for-sale');

    $('#collectibles-for-sale-pagination a').click(function(e)
    {
      e.preventDefault();
      var page = $(this).data('page');

      $('#items-for-sale').showLoading();
      var filter_by = $('.btn-filter.active').attr('id').replace('filter-items-', '');

      $('#items-for-sale').load(
        $url + '?p='+ page + '&filter_by=' + filter_by, $form.serialize(),
        function(data) {
          $('#items-for-sale').hideLoading();
        }
      );

      // Scroll to #items-for-sale-history so that we can see the first row of results
      $.scrollTo('#items-for-sale-history');

      return false;
    });

    $('a.deactivate').click(function(e)
    {
      e.preventDefault();
      $(this).parent().parent().showLoading();

      $(this).parent().load(
        '<?php echo url_for('@ajax_mycq?section=collectibleForSale&page=deactivate&id=') ?>' + $(this).data('id'),
        function() {
          $(this).parent().parent().hideLoading();
          $(this).parent().find('td.status').html('Inactive');
        }
      );

      return false;
    });

    $('a.relist').click(function(e)
    {
      e.preventDefault();
      $(this).parent().parent().showLoading();

      $(this).parent().load(
        '<?php echo url_for('@ajax_mycq?section=collectibleForSale&page=relist&id=') ?>' + $(this).data('id'),
        function() {
          $(this).parent().parent().hideLoading();
          $(this).parent().find('td.status').html('Active');
        }
      );

      return false;
    });

  });
</script>

<?php endif; ?>

