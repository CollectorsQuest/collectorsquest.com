<?php
/**
 * @var $seller                  Seller
 * @var $incomplete_collections  boolean
 * @var $total                   integer
 * @var $sold_total              integer
 */

  SmartMenu::setSelected('mycq_marketplace_tabs', 'collectibles_for_sale');
?>

<?php
  // include_partial('mycq/seller_snapshot', array('seller' => $seller));
?>

<?php if ($incomplete_collections && IceGateKeeper::open('mycq_incomplete', 'page')): ?>
<div class="alert alert-block alert-notice in">
  <h4 class="alert-heading">Some items for sale need your attention!</h4>
  <p class="spacer-top">
    You have items for sale which are not fully described yet.
    If you would like other users to be able to find and buy your items,
    you should describe them as best as you can!
  </p>
  <br/>
  <a class="btn btn-primary" href="<?php echo url_for('@mycq_incomplete_collectibles') ?>">Fix Incomplete Items</a>
  <button type="button" class="btn" data-dismiss="alert">Ok</button>
</div>
<?php endif; ?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="tab1">

      <div class="tab-content-inner">
        <?php if ($total > 0): ?>
        <div class="row-fluid sidebar-title spacer-inner-bottom-5">
          <div class="span5 link-align">
            <h3 class="Chivo webfont">My Items for Sale (<?= $total ?>)</h3>
          </div>
          <div class="span7">
            <?php if ($total > 11): ?>
            <div class="mini-input-append-search">
              <div class="input-append pull-right">
                <form action="<?= url_for('@ajax_mycq?section=component&page=collectiblesForSale') ?>"
                      id="form-mycq-collectibles-for-sale" method="post">
                  <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
                  <!-- keep INPUT and BUTTON elements in same line, if you break to two lines, you will see the "gap" between the text box and button -->
                  <input type="hidden" value="most-recent" id="sortByValue" name="s">
                </form>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="row collectible-sell-sold-items">
          <div id="items-for-sale" class="row-content">
            <?php
              if ($total > 0 || (isset($seller) && $seller->hasPackageCredits()))
              {
                include_component('mycq', 'collectiblesForSale', array('seller' => $seller));
              }
              else if (!isset($seller) || !$seller->hasPackageCredits())
              {
                // We need to show the Upload Photos button
                // because there is nothing to with dragging and dropping on this page
                $this->setComponentSlot('mycq_upload_photos', null, null);

                include_partial('mycq/partials/buy_package_listing');
              }
            ?>
          </div>
        </div>

        <?php if ($sold_total > 0): ?>
          <!-- Sold Items -->
          <div class="row-fluid sidebar-title spacer-inner-bottom spacer-inner-top">
            <div class="span5 link-align">
              <h3 class="Chivo webfont">My Sold Items (<?= $sold_total ?>)</h3>
            </div>
            <div class="span7">
              &nbsp;
            </div>
          </div>

          <div class="row collectible-sell-sold-items">
            <div id="items-sold" class="row-content">
              <?php include_component('mycq', 'collectiblesForSaleSold', array('seller' => $seller)); ?>
            </div>
          </div>
          <!-- /Sold Items -->
        <?php endif; ?>

      </div><!-- /.tab-content-inner -->
    </div><!-- .tab-pane.active -->
  </div><!-- .tab-content -->

</div><!-- #mycq-tabs -->


<script>
$(document).ready(function()
{
  $("#collection-create-html").droppable(
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
      }
  });

  $('.dropdown-menu a.sortBy').click(function()
  {
    $('#sortByName').html($(this).data('name'));
    $('#sortByValue').val($(this).data('sort'));

    $('#form-mycq-collectibles-for-sale').submit();
  });

  var $url = '<?= url_for('@ajax_mycq?section=component&page=collectiblesForSale', true) ?>';
  var $form = $('#form-mycq-collectibles-for-sale');

  $form.submit(function()
  {
    $('#items-for-sale').parent().showLoading();

    $('#items-for-sale').load(
      $url +'?p=1', $form.serialize(),
      function(data) {
        $('#items-for-sale').parent().hideLoading();
      }
    );

    return false;
  });
});
</script>
