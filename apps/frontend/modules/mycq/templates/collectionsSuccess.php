
<?php include_component('mycq', 'collectorSnapshot'); ?>

<div id="mycq-tabs">
<ul class="nav nav-tabs">
  <li class="active">
    <a href="#tab1" data-toggle="tab">Show Collections</a>
  </li>
  <li class="pull-right styles-reset">
    <span>
      <a href="#" class="add-new-items-button pull-right">&nbsp;</a>
    </span>
  </li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab1">
  <?php if (false): ?>

  <?php endif; ?>

<div class="tab-content-inner spacer-top">
  <div class="row-fluid sidebar-title spacer-inner-bottom">
    <div class="span5 link-align">
      <h3 class="Chivo webfont">My Collections (<?= $collector->countCollectorCollections(); ?>)</h3>
    </div>
    <div class="span7">
      <?php if (false): ?>
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
      <?php endif; ?>
    </div>
  </div>

  <?php if (true): ?>

    <?php include_component('mycq', 'collections'); ?>

  <?php else: ?>
  <div class="spacer-top-25">
    <!-- No Collection Uploaded -->

    <div class="mycq-collections">
      <div class="row thumbnails">
        <div class="span12 thumbnail link no-collections-uploaded-box">
          <span class="Chivo webfont info-no-collections-uploaded">
            Share your collection with the community today!<br>
            Upload then sort your collectibles to get started.
          </span>
        </div>
        <div class="span4 thumbnail link">
          <div class="row-fluid spacer-inner-top-15">
            <div class="span5">
              <i class="add-white-icon create-collection pull-right"></i>
            </div>
            <div class="span7">
              <a href="#" class="create-collection-text">Create a new collection by clicking here</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- /No Collection Uploaded -->
  </div>
  <?php endif; ?>

</div><!-- /.tab-content-inner -->
</div>
</div><!-- /.tab-content -->
</div>



