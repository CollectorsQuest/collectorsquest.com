<?php
  /** @var $collectible Collectible */
  foreach ($pager->getResults() as $i => $collectible)
  {
    include_partial(
      'marketplace/collectible_for_sale_grid_view_square_small',
      array('collectible_for_sale' => $collectible->getCollectibleForSale())
    );
  }
?>

<?php if ($pager->getNbResults() === 0): ?>
<div style="margin: 15px 20px;">
  <i class="icon-exclamation-sign" style="float: left; font-size: 46px; margin-right: 10px; color: #DF912F;"></i>
  Sorry! We can't find anything that matches your search.
  Try a broader search, or browse around for other neat stuff.
  (Or you can <?= link_to('sell something of your own', '@mycq_collections'); ?> on the site!)
</div>
<?php elseif ($pager->getPage() > 1): ?>
<!--<div class="well clear-both" style="margin: 0; margin-left: 13px; padding: 10px;">-->
<!--  <i class="icon icon-search"></i>&nbsp;-->
<!--  --><?//= link_to('Not finding what you are looking for? Click here to find it on our search page!', $url); ?>
<!--</div>-->
<br>
<?php elseif ($pager->haveToPaginate()): ?>
<div class="see-more-under-image-set" style="padding: 0; margin-left: 13px;">
  <button class="btn btn-small see-more-full" id="seemore-explore-collectibles">
    See more
  </button>
</div>
<?php endif; ?>

<script>
  $(document).ready(function()
  {
    var $url = '<?= url_for('@ajax_marketplace?section=component&page=discoverCollectiblesForSale') ?>';
    var $form = $('#form-discover-collectibles');

    $('#seemore-explore-collectibles').click(function()
    {
      var $button = $(this);
      $button.html('loading...');

      $.post($url +'?p=2', $form.serialize(), function(data)
      {
        $('#collectibles').append(data);

        $button.hide();
        $button.parent().hide();
      },'html');
    });

    $("a.target").bigTarget({
      hoverClass: 'over',
      clickZone : 'div.link'
    });
  });
</script>
