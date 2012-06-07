<div style="padding: 10px;">
  <div style="color: #6FA1B4; font-size: 16px;">
    Item for Sale: "<?= $collectible_for_sale->getCollectible()->getName(); ?>"
  </div>
  <br />
  <div style="float: left; margin-right: 20px;"> <?php echo image_tag_collectible($collectible); ?> </div>
  <div style="height: 120px;"> <?php echo $collectible->getDescription(); ?> </div>
  <div style="height: 30px;">
    <div style='min-width:60px; text-align: center; background: #AFD85D; padding: 3px; color: #fff; margin-top: 2px; float: left; margin-right: 20px;'> <?php echo money_format('%.2n', $collectible_for_sale->getPrice()); ?> </div>
    <div style='width: 160px; text-align: center; background: #F2F8D5; padding: 3px;margin-top: 2px; float: left;'> <?php echo $collectible_for_sale->getCondition(); ?> condition </div>
  </div>
  <br clear="all">
  <table style="border: 1px solid #DEDEDE;" width="100%">
    <?php foreach ($offers as $i => $offer): ?>
    <tr style="background: <?php echo ($i%2==0) ? '#F2F8D5': '#FFFFFF'; ?>">
      <td width="100" style="padding: 5px;"><?php echo $offer->getCreatedAt('%Y-%m-%d'); ?></td>
      <td width="60" style="padding: 5px;"><?php echo money_format('%.2n', $offer->getPrice()); ?></td>
      <td style="padding: 5px; text-align: center; background: #AFD85D; color: #fff;"><b>
	  	  <?php echo link_to('accept offer', '@marketplace_item_offer?cmd=accept&id='. $offer->getId(), array('style' => 'color: #fff;'))?></b>
	    </td>
      <td style="padding: 5px; text-align: center; background: #FD0303; color: #fff;">
        <strong>
          <?php echo link_to('reject offer', '@marketplace_item_offer?cmd=reject&id='. $offer->getId(), array('style' => 'color: #fff;'))?>
        </strong>
	    </td>
      <?php if ($offer->getStatus() == 'pending'): ?>
      <td style="padding: 5px;" colspan="3">
	  	  You made an offer on <?php echo $offer->getUpdatedAt('%Y-%m-%d'); ?>
	    </td>
      <?php elseif ($offer->getStatus() == 'accepted'): ?>
      <td style="padding: 5px;" colspan="3">
        The seller has accepted your offer on <?php echo $offer->getUpdatedAt('%Y-%m-%d'); ?>
      </td>
      <?php elseif ($offer->getStatus() == 'rejected'): ?>
      <td style="padding: 5px;" colspan="3">
        The seller has rejected your offer on <?php echo $offer->getUpdatedAt('%Y-%m-%d'); ?>
      </td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
  </table>
  <br>
</div>
