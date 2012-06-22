

<div class="no-collection-help">

  <div class="row-fluid sidebar-title spacer-top-reset spacer-inner-bottom-5">
    <div class="span5 link-align">
      <h3 class="Chivo webfont">My Collections (0)</h3>
    </div>
    <div class="span7">
      <?php /*
      <?php if ($total > 11): ?>
      <div class="sort-search-box">
        <div class="input-append pull-right">
          <form action="<?= url_for('@ajax_mycq?section=component&page=collections') ?>"
                id="form-mycq-collections" method="post">
            <div class="btn-group">
              <div class="append-left-gray">Sort by <strong id="sortByName">Most Recent</strong></div>
              <a class="btn gray-button dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                <li><a data-sort="most-relevant" data-name="Most Relevant" class="sortBy" href="javascript:">Sort by <strong>Most Relevant</strong></a></li>
              </ul>
            </div>
            <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
            <input type="hidden" value="most-recent" id="sortByValue" name="s">
          </form>
        </div>
      </div>
      <?php endif; ?>
    */ ?>
    </div>
  </div>


  <div class="row-fluid">
    <div class="span9 welcome-mycq">
      <div class="rectangle">
        <h2 class="Chivo webfont">
          Share your collections with the community today.<br>
          Get started now!
        </h2>

      </div>
      <div class="row-fluid content-box">
        <div class="span4 text-center">
          <a href="<?= url_for('@mycq_profile'); ?>" title="Upload Images">
            <i class="upload-images"></i>
            <h3>Upload Images</h3>
          </a>
          <p>
            Take pictures of your treasures and upload them to the site.
            Click on the Upload Items button to get started<br>
            <a href="#">Show me how!</a>
          </p>
        </div>
        <div class="span4 text-center">
          <a href="<?= url_for('@mycq_collections'); ?>" title="Create a Collection">
            <i class="edit-collections"></i>
            <h3>Create a Collection</h3>
          </a>
          <p>
            Create a listing for your collection populate your
            new grouping with the images you uploaded
            <br>
            <a href="#">Show me how!</a>
          </p>
        </div>
        <div class="span4 text-center">
          <a href="<?= url_for('@messages_inbox'); ?>" title="Show and Tell">
            <i class="show-and-tell"></i>
            <h3>Show and Tell</h3>
          </a>
          <p>
            Describe your items and set alternate views.
            Share your collectibles with the CQ community<br>
            <a href="#">Show me how!</a>
          </p>
        </div>
      </div>
    </div>
    <div class="span3">
      <a href="#" class="zone-add-new" title="Create a new collection by clicking here">
          <span id="collection-create-icon"
                class="btn-upload-collectible">
            <i class="icon-plus icon-white"></i>
          </span>
          <span id="collection-create-link"
                class="btn-upload-collectible-txt">
            Create a new<br> collection by<br> clicking here
          </span>
      </a>
    </div>
  </div>

  <div class="help-wrapper">
    <div class="row-fluid">
      <div class="span8">
        <i class="icon-question-sign icon-white"></i>
        <span class="blue-bolder-txt">Need help? Let us guide you!</span>
      </div>
      <div class="span4">
        <span class="faq-txt">
          <a href=# title="Go to FAQs">Go to FAQs &raquo;</a>
        </span>
      </div>
    </div>
  </div>

</div>
