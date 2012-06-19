<?php
/**
 * @var $total integer
 */
?>

<?php /**
<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#" target="_self" title="Your Collection">Your Collection</a></li>
    <li><a href="#" target="_self" title="Edit Collection Description">Edit Collection Description</a></li>

  </ul>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer-inner-top-reset">

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
        <div class="blue-well">
          <div class="row-fluid">
            <div class="span8">
              <button class="btn btn-primary blue-button" type="submit">
                Set Main Image
              </button>
              <button class="btn btn-primary blue-button" type="submit">
                Re-orderCollection
              </button>
              <button class="btn btn-primary blue-button" type="submit">
                Public View
              </button>
              <a onclick="return confirm('Are you sure you want to delete this Collection?');"
                 class="btn gray-button" href="#">
                <i class="icon icon-trash"></i>
                Delete Collection
              </a>
            </div>
            <div class="span4">
              <div class="input-append pull-right search-mycq">
                <input type="text" size="16" id="appendedInputButtons" class="input-medium pull-left">
                <button type="button" class="btn pull-left">Search</button>
              </div>
            </div>
          </div>
        </div>


        <div class="row mycq-collectibles">
          <div class="row-content" id="collectibles">
            <div class="span3 collectible_grid_view_square link">
            <a href="#" title="">
              <img src="http://placehold.it/140x140" alt="">
            </a>
            <p>
              <a href="#" class="target" title="">Indie Spotlight : Scud</a>
            </p>
          </div>
            <div class="span3 collectible_grid_view_square link">
              <a href="#" title="">
                <img src="http://placehold.it/140x140" alt="">
              </a>
              <p>
                <a href="#" class="target" title="">Indie Spotlight : Scud</a>
              </p>
            </div>
            <div class="span3 collectible_grid_view_square link">
              <a href="#" title="">
                <img src="http://placehold.it/140x140" alt="">
              </a>
              <p>
                <a href="#" class="target" title="">Indie Spotlight : Scud</a>
              </p>
            </div>
            <div class="span3 collectible_grid_view_square link">
              <a href="#" title="">
                <img src="http://placehold.it/140x140" alt="">
              </a>
              <p>
                <a href="#" class="target" title="">Indie Spotlight : Scud</a>
              </p>
            </div>
            <div class="span3 collectible_grid_view_square link">
              <a href="#" title="">
                <img src="http://placehold.it/140x140" alt="">
              </a>
              <p>
                <a href="#" class="target" title="">Indie Spotlight : Scud</a>
              </p>
            </div>
            <div class="span3 collectible_grid_view_square link">
              <a href="#" title="">
                <img src="http://placehold.it/140x140" alt="">
              </a>
              <p>
                <a href="#" class="target" title="">Indie Spotlight : Scud</a>
              </p>
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

      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
    <div id="tab4" class="tab-pane">
      <div class="tab-content-inner spacer">

      </div><!-- .tab-content-inner -->
    </div><!-- #tab4.tab-pane -->
  </div><!-- .tab-content -->
</div>
*/ ?>

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
