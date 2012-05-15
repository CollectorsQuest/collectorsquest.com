<?php
  /** @var $collection Collection */
  foreach ($pager->getResults() as $i => $collection)
  {
    include_partial(
      'collection/collection_grid_view_square_small',
      array('collection' => $collection, 'i' => $collection->getId())
    );
  }
?>

<?php if ($pager->getNbResults() === 0): ?>
  <div style="margin: 15px 20px;">
    <i class="icon-exclamation-sign" style="float: left; font-size: 46px; margin-right: 10px; color: #DF912F;"></i>
      Sorry! We can't find anything that matches your search.
      Try a broader search, or browse around for other neat stuff.
      (Or you can <?= link_to('upload something new', '@collection_create'); ?> to the site!)
  </div>
<?php elseif ($pager->getPage() > 1): ?>
  <br clear="all"/>
  <div class="well clearfix">
    <i class="icon icon-search"></i>&nbsp;
    <?= link_to('Not finding what you are looking for? Click here to find it on our search page!', $url); ?>
  </div>
<?php elseif ($pager->haveToPaginate()): ?>
  <div class="see-more-under-image-set">
    <button class="btn btn-small gray-button see-more-full" id="seemore-explore-collections">
      See more
    </button>
  </div>
<?php endif; ?>

<script>
$(document).ready(function()
{
  var $url = '<?= url_for('@ajax_collections?section=component&page=exploreCollections', true) ?>';
  var $form = $('#form-explore-collections');

  $('#seemore-explore-collections').click(function()
  {
    var $button = $(this);
    $button.html('loading...');

    $.post($url +'?p=2', $form.serialize(), function(data)
    {
      $('#collections').append(data);

      $button.hide();
      $button.parent().hide();
    },'html');
  });

  $('.fade-white').mosaic();
  $("a.target").bigTarget({
    hoverClass: 'over',
    clickZone : 'div.link'
  });
});
</script>
