<div class="slot2-inner">
  <?php cq_section_title('More from the Market', null, array('class' => 'row-fluid section-title blue')); ?>


  <div class="row-fluid sort-container">
    <div class="span6 sort-actions">
      <button type="button" class="btn thin">View all</button>

      <div class="sort-search-box">
        <div class="input-append">
          <form action="/search/collectibles-for-sale" method="post" id="form-discover-collectibles1">
            <div class="btn-group">
              <div class="append-left-gray">Refine By <strong id="sortByName">Category</strong></div>
              <a href="javascript:void(0)" data-toggle="dropdown" class="btn dropdown-toggle">
                <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="javascript:" class="sortBy">Category <strong>1</strong></a></li>
                <li><a href="javascript:" class="sortBy">Category <strong>2</strong></a></li>
                <li><a href="javascript:" class="sortBy">Category <strong>3</strong></a></li>
              </ul>
            </div>
            <input type="hidden" name="s" id="sortByValue" value="most-recent">
          </form>
        </div>
      </div>

      <div class="sort-search-box">
        <div class="input-append">
          <form action="/search/collectibles-for-sale" method="post" id="form-discover-collectibles2">
            <div class="btn-group">
              <div class="append-left-gray">Refine By <strong id="sortByName2">Price</strong></div>
              <a href="javascript:void(0)" data-toggle="dropdown" class="btn dropdown-toggle">
                <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="javascript:" class="sortBy" data-name="Most Recent" data-sort="most-recent">Sort by <strong>Most Recent</strong></a></li>
                <li><a href="javascript:" class="sortBy" data-name="Under $100" data-sort="under-100">Sort by <strong>Under $100</strong></a></li>
                <li><a href="javascript:" class="sortBy" data-name="$100 - $250" data-sort="100-200">Sort by <strong>$100 - $250</strong></a></li>
                <li><a href="javascript:" class="sortBy" data-name="Over $250" data-sort="over-250">Sort by <strong>Over $250</strong></a></li>
              </ul>
            </div>
            <input type="hidden" name="s" id="sortByValue2" value="most-recent">
          </form>
        </div>
      </div>
    </div>
    <div class="span6">
      <div class="mini-input-append-search">
        <div class="input-append pull-right">
          <form action="/search/collectibles-for-sale" id="form-mycq-collections" method="post">
            <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
            <input type="hidden" value="most-recent" id="sortByValue" name="s">
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="items-for-sale">
    <div id="collectibles-holder" class="row thumbnails">
      <?php include_component('marketplace', 'holidayCollectiblesForSale'); ?>
    </div>
  </div>

</div>

<script>
  $(document).ready(function()
  {
    var $url = '<?= url_for('@ajax_marketplace?section=component&page=discoverCollectiblesForSale') ?>';
    var $form = $('#form-discover-collectibles');

    $('.dropdown-toggle').dropdown();
    $('.dropdown-menu a.sortBy').click(function()
    {
      $('#sortByName').html($(this).data('name'));
      $('#sortByValue').val($(this).data('sort'));
      $form.submit();
    });

    $form.submit(function()
    {
      $('#collectibles').fadeOut();

      $.post($url +'?p=1', $form.serialize(), function(data)
      {
        $('#collectibles').html(data).fadeIn();
      },'html');

      return false;
    });

    if ($form.find('input').val() !== '')
    {
      $form.submit();
    }
  });
</script>
