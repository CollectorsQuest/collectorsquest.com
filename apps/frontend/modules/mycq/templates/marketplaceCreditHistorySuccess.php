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

        <br class="cf"/>

        <!-- Items listing history -->
        <div id="items-for-sale-history" class="row-fluid sidebar-title spacer-top-20" style="margin-bottom: 0;">
          <div class="span8">
            <h3 class="Chivo webfont">Items for Sale History</h3>
          </div>
        </div><!-- /.sidebar-title -->

        <div class="row-fluid messages-row gray-well cf">
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
            <div class="mini-input-append-search">
              <div class="input-append pull-right">
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
    var $url = '<?= url_for('@ajax_mycq?section=component&page=itemsForSaleHistory', true) ?>';
    var $form = $('#form-mycq-collectibles-for-sale');

    $form.submit(function()
    {
        var filter_by = $('.btn-filter.active').attr('id').replace('filter-items-', '');
        $('#items-for-sale')
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

    $('.btn-filter').click(function()
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
  });
</script>
