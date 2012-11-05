<a name="discover"></a>
<div class="slot2-inner">
  <?php cq_section_title('More from the Market', null, array('class' => 'row-fluid section-title blue')); ?>

  <div id="fixed-filter-bar">
    <div class="row-fluid sort-container">
      <form action="<?= url_for('@search_collectibles_for_sale'); ?>" method="post" id="form-holiday-collectibles">
      <div class="span8 sort-actions">

        <a href="<?= url_for('@marketplace?x='. rand(1, 99), true); ?>#discover"
           class="btn-primary view-all-button" style="padding: 2px 5px; margin-top: -1px;">
          View All
        </a>
        <div class="pull-left spacer-top-5">&nbsp;&nbsp;&nbsp;- or -</div>

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
      $('#main').showLoading();

      $.post($url +'?p=1', $form.serialize(), function(data)
      {
        var $container = $('#collectibles');

        if ($container.data('infinitescroll'))
        {
          $container.infinitescroll('destroy');
          $container.data('infinitescroll', null);
          $container.masonry('destroy');
        }

        $('#collectibles-holder').html(data);
        $('#main').hideLoading();

        // Scroll to #main so that we can see the first row of results
        $.scrollTo('#main');
      },'html');

      return false;
    });

    var zoom_zone = function(event)
    {
      event.preventDefault();

      var $a = $(this);
      var $div = $('<div></div>');

      $div.appendTo('body').load($(this).attr('href'), function()
      {
        $('.modal', $div).modal('show');
      });

      return false;
    };

    <?php /* The click() does not work for new elements, on() does not work for current?!? */ ?>
    $('a.zoom-zone').click(zoom_zone);
    $('#items-for-sale').on('click', 'a.zoom-zone', zoom_zone);
    $('#holiday-market-body').on('click', 'a.zoom-zone', zoom_zone);

    var fixadent = $("#fixed-filter-bar"), pos = fixadent.offset();
    $(window).scroll(function()
    {
      if ($(this).scrollTop() > (pos.top + 10) && fixadent.css('position') == 'static') {
        fixadent.addClass('fixed');
      }
      else if ($(this).scrollTop() <= pos.top && fixadent.hasClass('fixed')) {
        fixadent.removeClass('fixed');
      }
    });

  });
</script>
