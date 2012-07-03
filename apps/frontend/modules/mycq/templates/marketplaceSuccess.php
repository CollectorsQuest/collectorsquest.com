<?php
  /**
 * @var $seller Seller
 */

  SmartMenu::setSelected('mycq_marketplace_tabs', 'collectibles_for_sale');
?>

<?php
  // include_partial('mycq/seller_snapshot', array('seller' => $seller));
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="tab1">

      <div class="tab-content-inner">
        <div class="row-fluid sidebar-title spacer-inner-bottom">
          <div class="span5 link-align">
            <h3 class="Chivo webfont">My Items for Sale (<?= $total ?>)</h3>
          </div>
          <div class="span7">
            <?php if ($total > 11): ?>
            <div class="sort-search-box">
              <div class="input-append">
                <form id="form-mycq-collectibles-for-sale" method="post"
                      action="<?= url_for('@ajax_mycq?section=component&page=collectiblesForSale') ?>">
                  <div class="btn-group">
                    <div class="append-left-gray">Sort by <strong id="sortByName">Most Recent</strong></div>
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                      <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                      <li><a data-sort="most-relevant" data-name="Most Relevant" class="sortBy" href="javascript:">Sort by <strong>Most Relevant</strong></a></li>
                    </ul>
                  </div>
                  <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn" type="submit"><strong>Search</strong></button>
                  <!-- keep INPUT and BUTTON elements in same line, if you break to two lines, you will see the "gap" between the text box and button -->
                  <input type="hidden" value="most-recent" id="sortByValue" name="s">
                </form>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="mycq-collectibles-for-sale">
          <div class="row thumbnails">
            <?php include_component('mycq', 'collectiblesForSale', array('seller' => $seller)); ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>


<?php if ($sold_total > 0): ?>
<!-- Sold Items -->
<div id="sold-items-box" class="spacer-top-20">
  <div class="tab-content-inner spacer-inner-top">
    <div class="row-fluid sidebar-title spacer-inner-bottom">
      <div class="span5 link-align">
        <h3 class="Chivo webfont">My Sold Items (<?= $sold_total ?>)</h3>
      </div>
      <div class="span7">
        <?php if (false && $sold_total > 14): ?>
        <div class="sort-search-box">
          <div class="input-append">
            <form id="form-explore-collections" method="post" action="/search/collections">
              <div class="btn-group">
                <div class="append-left-gray">Sort by <strong id="sortByName">Most Relevant</strong></div>
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                  <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a data-sort="most-relevant" data-name="Most Relevant" class="sortBy" href="javascript:">Sort by <strong>Most Relevant</strong></a></li>
                  <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                  <li><a data-sort="most-popular" data-name="Most Popular" class="sortBy" href="javascript:">Sort by <strong>Most Popular</strong></a></li>
                </ul>
              </div>
              <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn" type="submit"><strong>Search</strong></button>
              <!-- keep INPUT and BUTTON elements in same line -->
              <input type="hidden" value="most-relevant" id="sortByValue" name="s">
            </form>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="row collectible_sold_items">
      <div class="row-content">
        <?php include_component('mycq', 'collectiblesForSaleSold', array('seller' => $seller)); ?>
      </div>
    </div>

  </div><!-- /.tab-content-inner -->
</div>
<!-- /Sold Items -->
<?php endif; ?>
