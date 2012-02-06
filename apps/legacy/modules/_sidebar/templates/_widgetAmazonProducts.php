<h2><?= __('You May Want To Buy...'); ?></h2>
<?php foreach ($products as $asin => $product): ?>
  <div id="sidebar_amazon_product_<?= $asin; ?>" class="amazon_product" style="padding: 10px;">
    <div style="float: left; margin: 0 10px 5px 0;">
      <?php
        if (!empty($product['image']))
        {
          echo link_to(
            image_tag($product['image'], array('alt_title' => $product['title'])),
            $product['url'],
            array('target' => '_blank', 'title' => $product['title'])
          );
        }
      ?>
    </div>
    <div style="padding-right: 10px;">
      <?php
        echo link_to(
          truncate_text($product['title'], 70, '...', true),
          $product['url'],
          array('target' => '_blank', 'title' => $product['title'])
        );
      ?>
    </div>
    <?php if (isset($product['price'])): ?>
      <div style="clear: both;">
        &nbsp;&#8627;
        <a href="<?= $product['url']; ?>" target="_blank"><?php echo sprintf('%d new & used', $product['total']); ?></a>
        <?= __('from:'); ?>
        <span style="font-size: 14px; color: #990000; font-weight: bold;"><?= $product['price']; ?></span>
      </div>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
<br class="clear">

<script type="text/javascript">
$(document).ready(function()
{
  $("#sidebar div.amazon_product a").bigTarget(
  {
    hoverClass: 'pointer',
    clickZone : 'div:eq(1)'
  });
});
</script>
