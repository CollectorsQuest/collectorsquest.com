<?php
/**
 * @var  $pager        sfPropelPager
 * @var  $collector    Collector
 * @var  $collection   CollectorCollection
 * @var  $collectible  Collectible
 * @var  $sf_user      cqFrontendUser
 */
?>

<?php
  if ($pager->haveToPaginate())
  {
    $link = $sf_user->isOwnerOf($collector) ? link_to('Edit Collections', '@mycq_collections', array('class' => 'text-v-middle link-align')) .'&nbsp; | &nbsp;' : null ;
    $link = $link . link_to(
      'See all »',
      '@collections_by_collector?collector_id='. $collector->getId() .'&collector_slug='. $collector->getSlug(),
      array('class' => 'text-v-middle link-align')
    );
    $title = $collector->getDisplayName() ."'s Latest Collections";
  }
  else
  {
    $link = null;
    $title = $collector->getDisplayName() ."'s Collections";
  }

  cq_section_title($title, $link);
?>

<div id="user-collections">
  <div class="row">
    <?php foreach ($pager->getResults() as $collection): ?>
      <div class="span4">
        <?php
          include_partial(
            'collection/collection_stack_grid_view',
            array('collection' => $collection)
          );
        ?>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($pager->getPage() > 1 && $pager->getPage() < $pager->getLastPage()): ?>
    <div class="well clearfix">
      <i class="icon icon-search"></i>&nbsp;
      <?php
        echo link_to(
          sprintf('Want to see more? Click here for all collections of %s!', $collector->getDisplayName()),
          '@collections_by_collector?collector_id='. $collector->getId() .'&collector_slug='. $collector->getSlug()
        );
      ?>
    </div>
  <?php elseif ($pager->getPage() == 1 && $pager->haveToPaginate()): ?>
    <button id="seemore-collections" class="btn btn-small see-more-full">
      See more
    </button>

    <script>
      $(document).ready(function()
      {
        $('#seemore-collections').click(function()
        {
          var $url = '<?= url_for('@ajax_collector?section=component&page=indexCollections&id='. $collector->getId()); ?>';
          var $button = $(this);

          $button.html('loading...');

          $("<div>").load($url +'&p=2 #user-collections', function()
          {
            $('#user-collections').append($(this).find('#user-collections').html());
            $button.hide();
          });
        });
      });
    </script>
  <?php endif; ?>

</div>
