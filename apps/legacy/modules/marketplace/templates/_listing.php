<div style="padding: 20px">
  <?php if ($pager->getNbResults() > 0): ?>
    <?php foreach ($pager->getResults() as $collectible_for_sale): ?>
      <?php $collectible = $collectible_for_sale->getCollectible(); ?>
      <div class="list_item" style="clear: left; margin-bottom: 15px;">
        <div class="span-5 stack"><?php echo link_to_collectible($collectible, 'image'); ?></div>
        <div class="span-12 details">
          <?php if ($collectible_for_sale->getIsSold()): ?>
            <b><?php echo link_to_collectible($collectible, 'text'); ?></b> - <font style="color: red"><b>SOLD</b></font>
          <?php else: ?>
            <b><?php echo link_to_collectible($collectible, 'text'); ?></b> - <font style="color: red">For Sale at <b><?php echo money_format('%.2n', $collectible_for_sale->getPrice()); ?></b></font>
          <?php endif; ?>
          <br /><br />
          <?php echo truncate_text($collectible->getDescription('stripped'), 200, '...', true); ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <br clear="all" /><br />
  <div class="pagination">
    <?php include_partial('global/pager', array('pager' => $pager, 'options' => array('url' => $sf_request->getUri()))); ?>
  </div>
<?php else: ?>
  <p>No collectibles for sale found.</p>
<?php endif; ?>
