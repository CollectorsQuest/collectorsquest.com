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
      <?php if ($pager->getNbResults() > 0): foreach ($pager->getResults() as $i => $collectible_for_sale): ?>
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
                      array('sf_subject' => $collectible_for_sale->getCollectible(), 'return_to' => 'market')
                    );
                  ?>
                </a>
              </div>
              <div class="span10">
                <span class="title">
                   <?= link_to_collectible($collectible_for_sale->getCollectible()); ?>
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
          <td>
            <?php // @todo should think of another way to approach this ?>
            <?php if ($collectible_for_sale->getIsSold()): ?>
            Sold
            <?php elseif ($collectible_for_sale->getExpiryDate() > date('Y-m-d H:i:s')): ?>
            Active
            <?php elseif($collectible_for_sale->getExpiryDate() == null): ?>
            Inactive
            <?php else: ?>
            Expired
            <?php endif; ?>
          </td>
          <td>
            <?php // @todo should optimize and not use same function calls as for the previous <td> ?>
            <?php if ($collectible_for_sale->getIsSold()): ?>
            -
            <?php elseif ($collectible_for_sale->getExpiryDate() > date('Y-m-d H:i:s')): ?>
            <button class="btn btn-mini" type="button">
              <i class="icon-minus-sign"></i>&nbsp;Deactivate
            </button>
            <?php elseif($collectible_for_sale->getExpiryDate() == null): ?>
            <button class="btn btn-mini" type="button">
              <i class="icon-ok"></i>&nbsp;Activate
            </button>
            <?php else: ?>
            <button class="btn btn-mini" type="button">
              <i class="icon-undo"></i>&nbsp;Re-list
            </button>
            <?php endif; ?>
          </td>
        </tr>
      <?php // @todo add cases for filters */ ?>
      <?php endforeach; elseif ('' == $search): ?>
      <tr>
        <td colspan="5">You have no items for sale yet.</td>
      </tr>
        <?php else: ?>
      <tr>
        <td colspan="5">No items for sale matched your search term "<?= $search?>".</td>
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
  });
</script>

<?php endif; ?>

