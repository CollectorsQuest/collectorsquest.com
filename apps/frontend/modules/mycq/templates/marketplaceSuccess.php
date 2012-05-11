
<?php include_component('mycq', 'snapshot'); ?>

<div id="cp-tabs">
<ul class="nav nav-tabs">
  <li class="active">
    <a href="#tab1" data-toggle="tab">Collectibles for Sale</a>
  </li>
  <a href="#" class="add-new-items-button pull-right">&nbsp;</a>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab1">
<div class="tab-content-inner">
  <?php cq_section_title('Items to Sort (14)') ?>

  <div class="collectibles-to-sort">
    <ul class="thumbnails">
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
      <li class="span2">
        <a href="#" class="thumbnail">
          <img src="http://placehold.it/72x72" alt="">
        </a>
      </li>
    </ul>
  </div>

</div><!-- /.tab-content-inner -->
<div class="row-fluid instruction-box">
  <div class="row-fluid instruction-box-inner">
    <div class="span2">
      <span class="gray-arrow pull-right"></span>
    </div>
    <div class="span8">
      <span class="hint-text"><strong>Hint:</strong> Drag and drop your items into collections.</span>
    </div>
    <div class="span2">
      <span class="gray-arrow"></span>
    </div>
  </div>
</div><!-- /.instruction-box -->

<div class="tab-content-inner spacer-top">
  <div class="row-fluid sidebar-title spacer-inner-bottom">
    <div class="span5 link-align">
      <h3 class="Chivo webfont">My Items for Sale (34)</h3>
    </div>
    <div class="span7">
      <div class="mycq-sort-search-box-mini">
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
    </div>
  </div>


  <div class="mycq-collectibles-for-sale">
    <div class="row thumbnails">

      <div class="span4 thumbnail link">
        <div class="collectibles-container">
          <a href="#">
            <img src="http://placehold.it/64x64" alt="">
          </a>
          <div class="add-white-icon drop-zone"></div>
          <div class="add-white-icon drop-zone"></div>
        </div>
        <span>Test text-overflow: ellipsis text-overflow: ellipsis </span>
        <div class="prices">$32.00</div>
      </div>

      <div class="span4 thumbnail link">
        <div class="collectibles-container">
          <a href="#">
            <img src="http://placehold.it/64x64" alt="">
          </a>
          <div class="add-white-icon drop-zone"></div>
          <div class="add-white-icon drop-zone"></div>
        </div>
        <span>Test text-overflow: ellipsis text-overflow: ellipsis </span>
        <div class="prices">$32.00</div>
      </div>

      <div class="span4 thumbnail link">
        <div class="collectibles-container">
          <a href="#">
            <img src="http://placehold.it/64x64" alt="">
          </a>
          <div class="add-white-icon drop-zone"></div>
          <div class="add-white-icon drop-zone"></div>
        </div>
        <span>Test text-overflow: ellipsis text-overflow: ellipsis </span>
        <div class="prices">$32.00</div>
      </div>

      <div class="span4 thumbnail link">
        <div class="collectibles-container">
          <a href="#">
            <img src="http://placehold.it/64x64" alt="">
          </a>
          <div class="add-white-icon drop-zone"></div>
          <div class="add-white-icon drop-zone"></div>
        </div>
        <span>Test text-overflow: ellipsis text-overflow: ellipsis </span>
        <div class="prices">$32.00</div>
      </div>

      <div class="span4 thumbnail link">
        <div class="collectibles-container">
          <div class="add-white-icon drop-zone"></div>
          <div class="add-white-icon drop-zone"></div>
          <div class="add-white-icon drop-zone"></div>
        </div>
        <span>Transformer - Perfect Condition</span>
        <div class="prices">$32.00</div>
      </div>

      <div class="span4 thumbnail link">
        <div class="row-fluid spacer-inner-top-20">
          <div class="span5">
            <div class="add-white-icon create-collection pull-right"></div>
          </div>
          <div class="span7">
            <a href="#" class="create-collection-text">Create a new sale listing by clicking here</a>
          </div>
        </div>
      </div>

    </div>
  </div>


  <a href="#" class="btn btn-small gray-button see-more-button">
    See more
  </a>

  <div id="Remove me" class="spacer-top-25">
    <!-- No Items to sort OR for sale -->

    <div class="mycq-collections">
      <div class="row thumbnails">
        <div class="span12 thumbnail link no-collections-box">
                  <span class="Chivo webfont info-no-collectibles-for-sale">
                    Got something to sell? List your collectibles for a small fee.
                  </span>

          <div class="row-fluid spacer-bottom-15">
            <div class="span8">
                      <span class="Chivo webfont bolder-title spacer-inner-l">
                        Casual Sellers
                      </span>
            </div>
            <div class="span4">
                      <span class="Chivo webfont bolder-title">
                        POWER Sellers
                      </span>
            </div>
          </div>
          <div class="buttons-inner">
            <div class="row-fluid">
              <div class="span4">
                <button type="submit" class="btn btn-primary blue-button pull-left">1 Item</button>
                <span class="plan-price">$2.50</span>
              </div>
              <div class="span4">
                <button type="submit" class="btn btn-primary blue-button pull-left">15 Items</button>
                <span class="plan-price">$30.00</span>
              </div>
              <div class="span4">
                <button type="submit" class="btn btn-primary blue-button pull-left">1000 Items</button>
                <span class="plan-price">$150.00</span>
              </div>
            </div>
            <div class="row-fluid spacer-top">
              <div class="span4">
                <button type="submit" class="btn btn-primary blue-button pull-left">5 Items</button>
                <span class="plan-price">$11.25</span>
              </div>
              <div class="span4">
                <button type="submit" class="btn btn-primary blue-button pull-left">25 Items</button>
                <span class="plan-price">$41.50</span>
              </div>
              <div class="span4">
                <button type="submit" class="btn btn-primary blue-button pull-left">Unlimited</button>
                <span class="plan-price">$250.00</span>
              </div>
            </div>
          </div>

        </div>
        <div class="span4 thumbnail link">
          <div class="row-fluid spacer-inner-top-15">
            <div class="span5">
              <div class="add-white-icon create-collection pull-right"></div>
            </div>
            <div class="span7">
              <a href="#" class="create-collection-text">Create a new collection by clicking here</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- /No Items to sort OR for sale -->
  </div><!-- /#remove me -->


</div><!-- /.tab-content-inner -->
</div>

</div><!-- /.tab-content -->
</div>



