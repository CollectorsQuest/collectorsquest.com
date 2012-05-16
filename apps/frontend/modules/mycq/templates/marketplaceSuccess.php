
<div id="not-a-seller-box">
  <div class="row-fluid">
    <div class="span2">
      <div class="inner-blue-bg">
        <a href="#" class="btn-create-collection-middle h-center">
          <i class="icon-plus icon-white"></i>
        </a>
        <a href="#" class="blue-link">
          Click to start adding items to your store
        </a>
      </div>
    </div>
    <div class="span10">
      <div class="inner-yellow-bg">
        <div class="row-fluid">
          <div class="span12">
            <span class="Chivo webfont buy-credits">
              Got something to sell? List your collectibles for a small fee.
            </span>
            <div class="row-fluid spacer-inner-top-15">
              <div class="span8">
                <div class="row-fluid">
                  <div class="span4">
                    <label class="radio">
                      <input type="radio" value="option1" id="optionsRadios1" name="optionsRadios">
                      <strong>25 credits /</strong> $30
                    </label>
                    <label class="radio">
                      <input type="radio" value="option4" id="optionsRadios4" name="optionsRadios">
                      <strong>100 credits /</strong> $85
                    </label>
                  </div>
                  <div class="span4">
                    <label class="radio">
                      <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">
                      <strong>50 credits /</strong> $55
                    </label>
                    <label class="radio">
                      <input type="radio" value="option5" id="optionsRadios5" name="optionsRadios">
                      <strong>150 credits /</strong> $120
                    </label>
                  </div>
                  <div class="span4">
                    <label class="radio">
                      <input type="radio" value="option3" id="optionsRadios3" name="optionsRadios">
                      <strong>75 credits /</strong> $65
                    </label>
                    <label class="radio">
                      <input type="radio" value="option6" id="optionsRadios6" name="optionsRadios">
                      <strong>200 credits /</strong> $140
                    </label>
                  </div>
                </div>
              </div>
              <div class="span4">
                <a class="buy-credits-button pull-right spacer-top" href="#">&nbsp;</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<div id="mycq-tabs">
<ul class="nav nav-tabs">
  <li class="active">
    <a href="#tab1" data-toggle="tab">Collectibles for Sale</a>
  </li>
  <li class="pull-right styles-reset">
    <span>
      <a href="#" class="add-new-items-button pull-right">&nbsp;</a>
    </span>
  </li>
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
      <div class="mycq-sort-search-box">
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
            <a href="#" class="btn-create-collection-middle spacer-left-20">
              <i class="icon-plus icon-white"></i>
            </a>
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
              <a href="#" class="btn-create-collection-middle spacer-left-20">
                <i class="icon-plus icon-white"></i>
              </a>
            </div>
            <div class="span7">
              <div class="modal" id="addNewCollection">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">×</button>
                  <h3>Add a new collection</h3>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal">
                    <fieldset>
                      <div class="control-group">
                        <label class="control-label" for="input01">Collection Name</label>
                        <div class="controls">
                          <input type="text" class="input-xlarge" id="input01">
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="input01">Tags</label>
                        <div class="controls">
                          <input type="text" class="input-xlarge" id="input02">
                          <p class="help-block">Choose at least three descriptive words for your collection, separated by commas</p>
                        </div>
                      </div>
                      <div class="control-group">
                        <label for="select01" class="control-label">Category</label>
                        <div class="controls">
                          <select id="select01">
                            <option>Please Select One:</option>
                            <option>Category 1</option>
                            <option>Category 2</option>
                            <option>Category 3</option>
                            <option>Category 4</option>
                          </select>
                        </div>
                      </div>
                    </fieldset>
                  </form>
                </div>
                <div class="modal-footer">
                  <a href="#" class="btn btn-primary blue-button spacer-right-15">Create Collection</a>
                  <a href="#" class="btn btn-primary gray-button">Cancel</a>
                </div>
              </div>
              <a href="#addNewCollection" data-toggle="modal" class="create-collection-text target">
                Create a new collection by clicking here
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- /No Items to sort OR for sale -->
  </div><!-- /#remove me -->

</div><!-- /.tab-content-inner -->



<!-- Sold Items -->
<div id="sold-items-box" class="spacer-top-20">
  <div class="tab-content-inner spacer-inner-top-20">
    <div class="row-fluid sidebar-title spacer-inner-bottom">
      <div class="span5 link-align">
        <h3 class="Chivo webfont">Sold Items</h3>
      </div>
      <div class="span7">
        <div class="mycq-sort-search-box">
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



    <div class="row collectible_sold_items">
      <div class="row-content">
        <div class="span3 collectible_sold_items_grid_view_square link">
          <a href="#">
            <img alt="" src="http://placehold.it/130x130">
          </a>
          <span class="sold">SOLD</span>
          <p>
            <a href="#" class="target" title="Transformer - Perfect...">
              Transformer - Perfect...
            </a>
            <strong class="pull-right">$15.00</strong>
          </p>
        </div>
        <div class="span3 collectible_sold_items_grid_view_square link">
          <a href="#">
            <img alt="" src="http://placehold.it/130x130">
          </a>
          <span class="sold">SOLD</span>
          <p>
            <a href="#" class="target" title="Transformer - Perfect...">
              Transformer - Perfect...
            </a>
            <strong class="pull-right">$15.00</strong>
          </p>
        </div>
        <div class="span3 collectible_sold_items_grid_view_square link">
          <a href="#">
            <img alt="" src="http://placehold.it/130x130">
          </a>
          <span class="sold">SOLD</span>
          <p>
            <a href="#" class="target" title="Transformer - Perfect...">
              Transformer - Perfect...
            </a>
            <strong class="pull-right">$15.00</strong>
          </p>
        </div>
        <div class="span3 collectible_sold_items_grid_view_square link">
          <a href="#">
            <img alt="" src="http://placehold.it/130x130">
          </a>
          <span class="sold">SOLD</span>
          <p>
            <a href="#" class="target" title="Transformer - Perfect...">
              Transformer - Perfect...
            </a>
            <strong class="pull-right">$15.00</strong>
          </p>
        </div>
        <div class="span3 collectible_sold_items_grid_view_square link">
          <a href="#">
            <img alt="" src="http://placehold.it/130x130">
          </a>
          <span class="sold">SOLD</span>
          <p>
            <a href="#" class="target" title="Transformer - Perfect...">
              Transformer - Perfect...
            </a>
            <strong class="pull-right">$15.00</strong>
          </p>
        </div>
        <div class="span3 collectible_sold_items_grid_view_square link">
          <a href="#">
            <img alt="" src="http://placehold.it/130x130">
          </a>
          <span class="sold">SOLD</span>
          <p>
            <a href="#" class="target" title="Transformer - Perfect...">
              Transformer - Perfect...
            </a>
            <strong class="pull-right">$15.00</strong>
          </p>
        </div>
        <div class="span3 collectible_sold_items_grid_view_square link">
          <a href="#">
            <img alt="" src="http://placehold.it/130x130">
          </a>
          <span class="sold">SOLD</span>
          <p>
            <a href="#" class="target" title="Transformer - Perfect...">
              Transformer - Perfect...
            </a>
            <strong class="pull-right">$15.00</strong>
          </p>
        </div>
        <div class="span3 collectible_sold_items_grid_view_square link">
          <a href="#">
            <img alt="" src="http://placehold.it/130x130">
          </a>
          <span class="sold">SOLD</span>
          <p>
            <a href="#" class="target" title="Transformer - Perfect...">
              Transformer - Perfect...
            </a>
            <strong class="pull-right">$15.00</strong>
          </p>
        </div>

      </div>
    </div>


    <a href="#" class="btn btn-small gray-button see-more-button">
      See more
    </a>

  </div><!-- /.tab-content-inner -->
</div>

<!-- /Sold Items -->



</div>

</div><!-- /.tab-content -->
</div>


