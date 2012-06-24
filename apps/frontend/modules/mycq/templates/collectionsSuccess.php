<?php
/**
 * @var $total integer
 */
?>

<div class="mycq-collections-wrapper">

              </div> <div class="row-fluid sidebar-title spacer-top-reset spacer-inner-bottom-5">
    <div class="span5 link-align">
      <h3 class="Chivo webfont">My Collections (<?= $total ?>)</h3>
    </div>
    <div class="span7">
      <?php if ($total > 0): ?>
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
    </div>
  </div>

  <div class="row mycq-collections-4x9">
    <div class="row-content" id="collectibles">
      <div class="span5 collectible_grid_view_square link">
        <p>
          <a href="#" class="target" title="">
            Collection Name sometimes is very long & should... (12)
          </a>
        </p>
        <ul class="thumbnails">
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <i data-collection-id="3269"
               class="icon drop-zone ui-droppable icon-plus">
            </i>
          </li>
        </ul>
      </div>
      <div class="span5 collectible_grid_view_square link">
        <p>
          <a href="#" class="target" title="">
            Sometimes not... (12)
          </a>
        </p>
        <ul class="thumbnails">
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <i data-collection-id="3269"
               class="icon drop-zone ui-droppable icon-plus">
            </i>
          </li>
        </ul>
      </div>
      <div class="span5 collectible_grid_view_square link">
        <p>
          <a href="#" class="target" title="">
            Collection Name sometimes is very long & should... (12)
          </a>
        </p>
        <ul class="thumbnails">
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <i data-collection-id="3269"
               class="icon drop-zone ui-droppable icon-plus">
            </i>
          </li>
        </ul>
      </div>
      <a href="#" class="span5 add-new-zone" title="Create a new collection by clicking here">
          <span id="collection-create-icon"
                class="btn-upload-collectible">
            <i class="icon-plus icon-white"></i>
          </span>
          <span id="collection-create-link"
                class="btn-upload-collectible-txt">
            Create a new<br> collection by<br> clicking here
          </span>
      </a>
      <div class="span5 collectible_grid_view_square link">
        <p>
          <a href="#" class="target" title="">
            Sometimes not... (12)
          </a>
        </p>
        <ul class="thumbnails">
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <a href="#" title="">
              <img src="http://placehold.it/60x60" alt="">
            </a>
          </li>
          <li>
            <i data-collection-id="3269"
               class="icon drop-zone ui-droppable icon-plus">
            </i>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="pagination">
    <ul>
      <li class="active">
        <a href="#">1</a>
      </li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">4</a></li>
      <li><a href="#">Next</a></li>
    </ul>
  </div>
  <button class="btn btn-small gray-button see-more-full"
          id="seemore-collections"
          data-target="">
    See more
  </button>
</div>





<br><br><br>
<div id="mycq-tabs">
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">

      <a name="my-collections"></a>
      <div class="tab-content-inner spacer-top-35">
        <div class="row-fluid sidebar-title spacer-inner-bottom">
          <div class="span5 link-align">
            <h3 class="Chivo webfont">My Collections (<?= $total ?>)</h3>
          </div>
          <div class="span7">
            <?php if ($total > 11): ?>
            <div class="sort-search-box">
              <div class="input-append">
                <form action="<?= url_for('@ajax_mycq?section=component&page=collections') ?>"
                      id="form-mycq-collections" method="post">
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
                  <input type="hidden" value="most-recent" id="sortByValue" name="s">
                </form>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="mycq-collections">
          <div class="row thumbnails">
            <?php include_component('mycq', 'collections'); ?>
          </div>
        </div>

      </div><!-- /.tab-content-inner -->
    </div>
  </div><!-- /.tab-content -->
</div>

<script>
  $(document).ready(function()
  {
    $('.dropdown-menu a.sortBy').click(function()
    {
      $('#sortByName').html($(this).data('name'));
      $('#sortByValue').val($(this).data('sort'));

      $('#form-mycq-collections').submit();
    });

    var $url = '<?= url_for('@ajax_mycq?section=component&page=collections', true) ?>';
    var $form = $('#form-mycq-collections');

    $form.submit(function()
    {
      $('div.mycq-collections .thumbnails').fadeOut();

      $.post($url +'?p=1', $form.serialize(), function(data)
      {
        $('div.mycq-collections .thumbnails').html(data).fadeIn();
      },'html');

      return false;
    });
  });
</script>
