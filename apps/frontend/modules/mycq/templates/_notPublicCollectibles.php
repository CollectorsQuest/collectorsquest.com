<?php
/**
 * @var $pager sfPropelPager
 */
?>

<?php if ($pager->getNbResults() > 0): ?>

  <?php foreach ($pager->getResults() as $i => $collectible): ?>
  <div class="span3 collectible_grid_view_square link">
    <div class="collectible-view-slot">
      <?php
        echo link_to(
          image_tag_collectible(
            $collectible, '150x150', array('width' => 140, 'height' => 140)
          ),
          'mycq_collectible_by_slug', array('sf_subject' => $collectible, 'return_to' => 'collection')
        );
      ?>

      <?php if ($collectible->isSold()): ?>
        <span class="sold">SOLD</span>
      <?php elseif ($collectible->isForSale()): ?>
        <span class="for-sale">FOR SALE</span>
      <?php endif; ?>

      <p>
      <?php
        echo link_to_if(
          $collectible->getName(),
          cqStatic::reduceText($collectible->getName(), 30),
          'mycq_collectible_by_slug', array('sf_subject' => $collectible, 'return_to' => 'collection'),
          array('class' => 'target')
        );
      ?>
      </p>
    </div>
  </div>

  <?php endforeach; ?>

  <?php if ($pager->haveToPaginate()): ?>
  <div class="row-fluid pagination-wrapper">
    <?php
    include_component(
      'global', 'pagination',
      array(
        'pager' => $pager,
        'options' => array(
          'id' => 'collectibles-pagination',
          'show_all' => false
        )
      )
    );
    ?>
  </div>

  <script>
    $(document).ready(function()
    {
      var $url = '<?= url_for('@ajax_mycq?section=component&page=notPublicCollectibles', true) ?>';
      var $form = $('#form-mycq-collectibles');

      $('#collectibles-pagination a').click(function(e)
      {
        e.preventDefault();
        var page = $(this).data('page');

        $('#collectibles').parent().showLoading();

        $('#collectibles').load(
          $url +'?p='+ page, $form.serialize(),
          function(data) {
            $('#collectibles').parent().hideLoading();
          }
        );

        // Scroll to #slot1 so that we can see the first row of results
        $.scrollTo('#slot1');

        return false;
      });
    });
  </script>

  <?php endif; ?>

<?php else: ?>

  <?php /* if ($sf_params->get('q')): ?>
    <div class="alert alert-no-results">
      <i class="icon-warning-sign"></i>&nbsp;
      None of your items match search term: <strong><?= $sf_params->get('q'); ?></strong>.
      Do you want to <?= link_to('see all items', 'mycq_collection_by_slug', $collection); ?> or
      <?= link_to('add a new item', '@ajax_mycq?section=collectible&page=create&collection_id='. $collection->getId(), array('class' => 'open-dialog', 'onclick' => 'return false;')); ?>?
    </div>
  <?php endif; */ ?>

<?php endif; ?>
