<div class="slot2-inner">
  <?php cq_section_title('More from the Market', null, array('class' => 'row-fluid section-title blue')); ?>


  <div class="row-fluid sort-container">
    <form action="<?= url_for('@search_collectibles_for_sale'); ?>" method="post" id="form-holiday-collectibles">
    <div class="span8 sort-actions">

      <button type="button" class="btn-primary thin" style="float: left;">View All</button>
      <div style="float: left; margin-top: 5px;">&nbsp;&nbsp;&nbsp;- or -</div>

      <div class="sort-search-box">
        <div class="input-append">
          <div class="btn-group">
            <div class="append-left-gray" style="width: auto;">Refine By <strong id="sortByName1">Category</strong></div>
            <a href="javascript:void(0)" data-toggle="dropdown" class="btn dropdown-toggle">
              <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
            </a>
            <ul class="dropdown-menu" id="dropDownMenu1">
              <li><a href="javascript:" class="sortBy" data-i="1" data-name="Category" data-sort="">Refine by Category</a></li>
              <?php foreach ($categories as $category): ?>
              <li><a href="javascript:" class="sortBy" data-i="1" data-name="<?= addcslashes($category->getName(), '"'); ?>" data-sort="<?= $category->getId(); ?>"><?= $category->getName(); ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <input type="hidden" name="s1" id="sortByValue1">
        </div>
      </div>

      <div class="sort-search-box">
        <div class="input-append">
          <div class="btn-group">
            <div class="append-left-gray" style="width: auto;">Refine By <strong id="sortByName2">Price</strong></div>
            <a href="javascript:void(0)" data-toggle="dropdown" class="btn dropdown-toggle">
              <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="javascript:" class="sortBy" data-i="2" data-name="Price" data-sort="">Refine by Price</a></li>
              <li><a href="javascript:" class="sortBy" data-i="2" data-name="Under $100" data-sort="under-100">Refine by <strong>Under $100</strong></a></li>
              <li><a href="javascript:" class="sortBy" data-i="2" data-name="$100 - $250" data-sort="100-200">Refine by <strong>$100 - $250</strong></a></li>
              <li><a href="javascript:" class="sortBy" data-i="2" data-name="Over $250" data-sort="over-250">Refine by <strong>Over $250</strong></a></li>
            </ul>
          </div>
          <input type="hidden" name="s2" id="sortByValue2">
        </div>
      </div>
    </div>
    <div class="span4">
      <div class="mini-input-append-search">
        <div class="input-append pull-right">
          <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
        </div>
      </div>
    </div>
    </form>
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
    var $url = '<?= url_for('@ajax_marketplace?section=component&page=holidayCollectiblesForSale') ?>';
    var $form = $('#form-holiday-collectibles');

    $('.dropdown-toggle').dropdown();
    $('.dropdown-menu a.sortBy').click(function()
    {
      $('#sortByName' + $(this).data('i')).html($(this).data('name'));
      $('#sortByValue' + $(this).data('i')).val($(this).data('sort'));

      $form.submit();
    });

    $form.submit(function()
    {
      $('#collectibles-holder').fadeOut();

      $.post($url +'?p=1', $form.serialize(), function(data)
      {
        $('#collectibles-holder').html(data).fadeIn();

        // Scroll to #main so that we can see the first row of results
        $.scrollTo('#main');

      },'html');

      return false;
    });

    if ($form.find('input').val() !== '')
    {
      $form.submit();
    }

    var zoom_zone = function(event)
    {
      event.preventDefault();

      var $a = $(this);
      var $div = $('<div></div>');

      $a.closest('.collectible_for_sale_grid_view_masonry_big').showLoading();
      $div.appendTo('body').load($(this).attr('href'), function()
      {
        $a.closest('.collectible_for_sale_grid_view_masonry_big').hideLoading();
        $('.modal', $div).modal('show');
      });

      return false;
    };

    <?php /* The click() does not work for new elements, on() does not work for current?!? */ ?>
    $('a.zoom-zone').click(zoom_zone);
    $('#items-for-sale').on('click', 'a.zoom-zone', zoom_zone);
  });
</script>
