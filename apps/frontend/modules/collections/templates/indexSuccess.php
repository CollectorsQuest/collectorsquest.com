<?php cq_page_title('Collectibles Now On Display'); ?>

<br/>
<?php include_component('collections', 'featuredWeek'); ?>

<?php cq_section_title('Explore Collections') ?>
<div id="sort-search-box">
  <div class="input-append">
    <form action="<?= url_for('@search_collections', true); ?>" method="post" id="form-explore-collections">
    <div class="btn-group">
      <div class="append-left-gray">Sort by <strong id="sortByName">Most Relevant</strong></div>
      <a href="#" data-toggle="dropdown" class="btn gray-button dropdown-toggle">
        <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="javascript:" class="sortBy" data-name="Most Relevant" data-sort="most-relevant">Sort by <strong>Most Relevant</strong></a></li>
        <li><a href="javascript:" class="sortBy" data-name="Most Recent" data-sort="most-recent">Sort by <strong>Most Recent</strong></a></li>
        <li><a href="javascript:" class="sortBy" data-name="Most Popular" data-sort="most-popular">Sort by <strong>Most Popular</strong></a></li>
      </ul>
    </div>
    <input type="text" name="q" id="appendedPrependedInput" class="input-sort-by"><button type="submit" class="btn gray-button"><strong>Search</strong></button>
    <input type="hidden" name="s" id="sortByValue" value="most-relevant">
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
});
</script>
