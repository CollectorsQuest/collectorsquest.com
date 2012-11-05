<?php cq_page_title('Collections Now On Display'); ?>

<?php include_component('collections', 'featuredWeek'); ?>

<?php cq_section_title('Explore Collections') ?>
<div class="sort-search-box full-length-blue">
  <div class="input-append">
    <form action="<?= url_for('@search_collections', true); ?>" method="post" id="form-explore-collections">
    <div class="btn-group">
      <div class="append-left-gray">Sort by <strong id="sortByName">Most Recent</strong></div>
      <a href="javascript:void(0)" data-toggle="dropdown" class="btn dropdown-toggle">
        <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="javascript:" class="sortBy" data-name="Most Recent" data-sort="most-recent">Sort by <strong>Most Recent</strong></a></li>
        <li><a href="javascript:" class="sortBy" data-name="Most Popular" data-sort="most-popular">Sort by <strong>Most Popular</strong></a></li>
      </ul>
    </div>
    <input type="text" name="q" id="appendedPrependedInput" class="input-sort-by"><button type="submit" class="btn"><strong>Search</strong></button>
    <!-- keep INPUT and BUTTON elements in same line, if you break to two lines, you will see the "gap" between the text box and button -->
    <input type="hidden" name="s" id="sortByValue" value="most-recent">
    </form>
  </div>
</div>

<div class="row collections-container">
  <div id="collections" class="row-content">
    <?php include_component('collections', 'exploreCollections'); ?>
  </div>
</div>

<script>
$(document).ready(function()
{
  var $url = '<?= url_for('@ajax_collections?section=component&page=exploreCollections', true) ?>';
  var $form = $('#form-explore-collections');

  $('.dropdown-toggle').dropdown();

  $('.dropdown-menu a.sortBy').click(function()
  {
    $('#sortByName').html($(this).data('name'));
    $('#sortByValue').val($(this).data('sort'));
    $form.submit();
  });

  $form.submit(function()
  {
    $('#collections').fadeOut();

    $.post($url +'?p=1', $form.serialize(), function(data)
    {
      $('#collections').html(data).fadeIn();
    },'html');

    return false;
  });

  if ($form.find('input').val() !== '')
  {
    $form.submit();
  }
});
</script>
