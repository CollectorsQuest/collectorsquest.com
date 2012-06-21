
<?php if ($bought_total > 0): ?>

<div id="sold-items-box" class="spacer-top-20">
  <div class="tab-content-inner spacer-inner-top">
    <div class="row-fluid sidebar-title spacer-inner-bottom">
      <div class="span5 link-align">
        <h3 class="Chivo webfont">Purchased Items (<?= $bought_total ?>)</h3>
      </div>
      <div class="span7">
        <?php if ($bought_total > 14): ?>
        <div class="sort-search-box">
          <div class="input-append">
            <form id="form-explore-collections" method="post" action="/search/collections">
              <div class="btn-group">
                <div class="append-left-gray">Sort by <strong id="sortByName">Most Relevant</strong></div>
                <a class="btn gray-button dropdown-toggle" data-toggle="dropdown" href="#">
                  <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a data-sort="most-relevant" data-name="Most Relevant" class="sortBy" href="javascript:">Sort by <strong>Most Relevant</strong></a></li>
                  <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                  <li><a data-sort="most-popular" data-name="Most Popular" class="sortBy" href="javascript:">Sort by <strong>Most Popular</strong></a></li>
                </ul>
              </div>
              <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
              <input type="hidden" value="most-relevant" id="sortByValue" name="s">
            </form>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="row collectible_sold_items">
      <div class="row-content">
        <?php include_component('mycq', 'collectiblesForSaleBought'); ?>
      </div>
    </div>

  </div><!-- /.tab-content-inner -->
</div>

<?php endif; ?>
