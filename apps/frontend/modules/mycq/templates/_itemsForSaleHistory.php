<?php
/* @var $search        string */
/* @var $filter_by     string */
/* @var $pager         PropelModelPager */
/* @var $seller        Seller */
?>

<div id="items-for-sale">
    <table class="table table-striped table-items-for-sale-history">
      <thead>
        <tr>
          <th class="items-column">Listing</th>
          <th>Expires</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($pager->getNbResults()): ?>
        <?php foreach ($pager->getResults() as $i => $collectible_for_sale): ?>
        <tr>
          <?php include_partial(
            'mycq/partials/item_for_sale_history_table_row',
            array('collectible_for_sale' => $collectible_for_sale)
          ); ?>
        </tr>
      <?php endforeach; elseif ('' == $search) : ?>
      <tr>
        <td colspan="5">
            You have no <?= $filter_by == 'all' ? '' : '<strong>' . $filter_by. '</strong>' ?>
            items for sale yet.
        </td>
      </tr>
        <?php else: ?>
      <tr>
        <td colspan="5">
            No <?= $filter_by == 'all' ? '' : '<strong>' . $filter_by. '</strong>' ?>
            items for sale matched your search term "<?= $search?>".
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <?php if ($pager->haveToPaginate()): ?>
  <div class="row-fluid pagination-wrapper">
    <?php
    include_component(
      'global', 'pagination',
      array(
        'pager' => $pager,
        'options' => array(
          'id' => 'collectibles-for-sale-pagination',
          'show_all' => false,
          'page_param' => 'p',
        )
      )
    );
    ?>
  </div>
  <?php endif; ?>
</div>
<!-- #items-for-sale -->