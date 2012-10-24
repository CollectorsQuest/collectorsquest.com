<div class="slot2-inner">
  <?php cq_section_title('Discover More Items for Sale', null, array('class' => 'row-fluid section-title')); ?>

  <div class="sort-search-box full-lenght-blue">
    <div class="input-append">
      <form action="<?= url_for('@search_collectibles_for_sale'); ?>" method="post" id="form-discover-collectibles">
        <div class="btn-group">
          <div class="append-left-gray">Sort By <strong id="sortByName">Most Recent</strong></div>
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
        <input name="q" type="text" size="16" id="appendedPrependedInput" class="input-sort-by" style="width: 660px;"><button type="submit" class="btn"><strong>Search</strong></button>
        <!-- keep INPUT and BUTTON elements in same line, if you break to two lines, you will see the "gap" between the text box and button -->
        <input type="hidden" name="s" id="sortByValue" value="most-recent">
      </form>
    </div>
  </div>

  <div id="items-for-sale">
    <div id="collectibles-holder">
      <?php include_component('marketplace', 'discoverCollectiblesForSale'); ?>
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
      $('#collectibles-holder').fadeOut();

      $.post($url +'?p=1', $form.serialize(), function(data)
      {
        $('#collectibles-holder').html(data).fadeIn();
      },'html');

      return false;
    });

    if ($form.find('input').val() !== '')
    {
      $form.submit();
    }
  });
</script>
