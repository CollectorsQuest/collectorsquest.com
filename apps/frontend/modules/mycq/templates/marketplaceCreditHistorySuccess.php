<?php
/*
 * @var $package_transactions PackageTransaction[]
 * @var $package_transaction  PackageTransaction
 * @var $has_no_credits       boolean
 * @var $filter_by            string
 */

  SmartMenu::setSelected('mycq_marketplace_tabs', 'packages');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <?php include_component('mycq', 'creditPurchaseHistory'); ?>

        <!-- Items listing history -->
        <div id="items-for-sale-history" class="row-fluid sidebar-title spacer-top-20" style="margin-bottom: 0;">
          <div class="span8">
            <h3 class="Chivo webfont">Listing History</h3>
          </div>
        </div><!-- /.sidebar-title -->

        <div class="row-fluid gray-well spacer-inner-right-reset">
          <div class="span8">
            <div class="filter-container">
                <span class="show-all-text pull-left">
                  Show: &nbsp;
                </span>
              <div class="control-group">
                <div class="btn-filter-all btn-group">
                  <?php
                    echo link_to('All', '@mycq_marketplace_credit_history?filter_by=all',
                      array(
                        'id' => 'filter-items-all',
                        'class' => 'btn btn-mini btn-filter '.('all' == $filter_by ? 'active' : '')
                      )
                    );
                    echo link_to('Active', '@mycq_marketplace_credit_history?filter_by=active',
                      array(
                        'id' => 'filter-items-active',
                        'class' => 'btn btn-mini btn-filter '.('active' == $filter_by ? 'active' : '')
                      )
                    );
                    echo link_to('Inactive', '@mycq_marketplace_credit_history?filter_by=inactive',
                      array(
                        'id' => 'filter-items-inactive',
                        'class' => 'btn btn-mini btn-filter '.('inactive' == $filter_by ? 'active' : '')
                      )
                    );
                    echo link_to('Sold', '@mycq_marketplace_credit_history?filter_by=sold',
                      array(
                        'id' => 'filter-items-sold',
                        'class' => 'btn btn-mini btn-filter '.('sold' == $filter_by ? 'active' : '')
                      )
                    );
                    echo link_to('Expired', '@mycq_marketplace_credit_history?filter_by=expired',
                      array(
                        'id' => 'filter-items-expired',
                        'class' => 'btn btn-mini btn-filter '.('expired' == $filter_by ? 'active' : '')
                      )
                    );
                  ?>
                </div>
              </div> <!-- /.control-group -->
            </div>
          </div>

          <div class="span4">
            <div class="mini-input-append-search pull-right spacer-right-5">
              <div class="input-append">
                <form action="<?= url_for('@ajax_mycq?section=component&page=itemsForSaleHistory') ?>"
                      id="form-mycq-collectibles-for-sale" method="post">
                  <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
                  <!-- keep INPUT and BUTTON elements in same line, if you break to two lines, you will see the "gap" between the text box and button -->
                  <input type="hidden" value="most-recent" id="sortByValue" name="s">
                  <input type="hidden" name="filter_by" id="filter-hidden" value="<?= $filter_by; ?>">
                </form>
              </div>
            </div>
          </div>
        </div>

        <?php include_component('mycq', 'itemsForSaleHistory', array('filter_by' => $filter_by)); ?>

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div> <!-- .tab-content -->
</div> <!-- #mycq-tabs -->

<script>
  $(document).ready(function()
  {
    var $items_for_sale = $('#items-for-sale');
    var $url = '<?= url_for('@ajax_mycq?section=component&page=itemsForSaleHistory', true) ?>';
    var $form = $('#form-mycq-collectibles-for-sale');

    $form.submit(function()
    {
      var filter_by = $('.btn-filter.active').attr('id')
          .replace('filter-items-', '');

      $items_for_sale
        .showLoading()
        .load(
          $url + '?p=1', $form.serialize(),
          function(data) {
            $('#items-for-sale').hideLoading();
          }
        );

      $.scrollTo('#items-for-sale-history');

      return false;
    });

    $('.btn-filter').on('click', function()
    {
      $('.btn-filter-all .active').removeClass('active');
      $(this).addClass('active');
      $('#filter-hidden').val($(this).attr('id').replace('filter-items-', ''));
      loadingTable();

      return false;
    });

    // @todo optimize function an use it more than once
    function loadingTable()
    {
      var $url = '<?= url_for('@ajax_mycq?section=component&page=itemsForSaleHistory', true) ?>';
      var $form = $('#form-mycq-collectibles-for-sale');

      $('#items-for-sale').parent().showLoading();
      var filter_by = $('.btn-filter.active').attr('id').replace('filter-items-', '');

      $('#items-for-sale').load(
        $url + '?p=1&filter_by=' + filter_by, $form.serialize(),
        function(data) {
          $('#items-for-sale').parent().hideLoading();
        }
      );

      $.scrollTo('#items-for-sale-history');
    }

    $items_for_sale.on('click', '#collectibles-for-sale-pagination a', function(e)
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

    // attach a live click event for item actions
    $items_for_sale.on('click', 'a.collectible-action', function(e)
    {
      var $this = $(this);
      e.preventDefault();

      if (confirm($this.data('confirm')) || 'Are you sure?')
      {
        $this.parents('tr').showLoading();
        $this.parents('tr').load(
          '<?php echo url_for('@ajax_mycq?section=collectibleForSale&page=updateStatus') ?>',
          {
            id: $this.data('id'),
            execute: $this.data('action')
          },
          function() {
            // we need the actual TD here, so we use $(this) instead of
            // $this, which points to an anchor tag
            $(this).parents('tr').hideLoading();
          }
        );
      }

      return false;
    })

  });
</script>
