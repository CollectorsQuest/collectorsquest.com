<div id="mycq-tabs">
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">

      <?php
        // Do not display the dropbox when there are no collections
        if ($total > 0)
        {
          include_component(
            'mycq', 'dropbox',
            array('instructions' => array(
              'position' => 'bottom',
              'text' => 'Drag and drop collectibles into your collections.')
            )
          );
        }
      ?>

      <br style="clear: both;"/>
      <div class="tab-content-inner spacer-top-35">
        <div class="row-fluid sidebar-title spacer-inner-bottom">
          <div class="span5 link-align">
            <h3 class="Chivo webfont">My Collections (<?= $total ?>)</h3>
          </div>
          <div class="span7">
            <?php if ($total > 7): ?>
            <div class="mycq-sort-search-box">
              <div class="input-append">
                <form id="form-explore-collections" method="post" action="<?= url_for('@ajax_mycq?section=component&page=collections') ?>">
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

        <?php include_component('mycq', 'collections'); ?>

      </div><!-- /.tab-content-inner -->
    </div>
  </div><!-- /.tab-content -->
</div>
