<?php 
$actions = array(
  'counter' => 'made a counter',
  'buyer_counter' => 'made a counter',
  'accepted' => 'accepted this',
  'rejected' => 'rejected this',
  'pending' => 'made an',
  );
?>
<div id="sf_admin_container">
  <h1>List of Offers for Item:  "<?php echo $collectible_for_sale->getCollectible()->getName(); ?>"</h1>
  <span style="float:right"><?php echo link_to('Back to Marketplace Info','@marketplace_list',array('title' => 'Back to Marketplace Info'));?></span>
  <div id="sf_admin_header"> </div>
  <div id="sf_admin_content">
    <div class="sf_admin_list">
		<div style="height: 30px;">
		<div style="text-align: left; background: #AFD85D; padding: 3px; color: #fff; margin-top: 2px; float: left; margin-right: 20px;"> 
			<?php echo money_format('%.2n', $collectible_for_sale->getPrice()); ?> </div>
		<div style='width: 160px; text-align: center; background: #F2F8D5; padding: 3px;margin-top: 2px; float: left;'> 
			<?php echo $collectible_for_sale->getCondition(); ?> condition </div>
		</div>
		<table style="border: 1px solid #DEDEDE;" width="100%">
		<?php if($offers):
			$latest_collector_id = 0;
			foreach ($offers as $i => $offer):
				if ($latest_collector_id != $offer->getCollector()->getId()): ?>
					</table>
					<br />
					<div style="margin-bottom: 5px;">
						<div style="float: left; margin-top: 1px; margin-right: 5px;">
							<?php echo image_tag('black-arrow.png');?>
						</div>
						<div class="section-title"><?php echo 'Offers by <b>'.$offer->getCollector()->getDisplayName(); ?></b></div>
					</div>
					<table style="border: 1px solid #DEDEDE;" width="100%">
			<?php endif; ?>
			<tr style="background: <?php echo ($i%2==0) ? '#F2F8D5': '#FFFFFF'; ?>">
				
				<td width="100" style="padding: 5px;"><?php echo $offer->getCreatedAt('%Y-%m-%d'); ?></td>
				<td width="60" style="padding: 5px;"><?php echo money_format('%.2n', $offer->getPrice()); ?></td>
        <?php 
        if (in_array($offer->getStatus(), array('counter', 'accepted', 'rejected'))):
          $left = $omCollectibleOwner->getDisplayName();
          $right = $offer->getCollector()->getDisplayName();
        else: 
          $left = $offer->getCollector()->getDisplayName();
          $right = $omCollectibleOwner->getDisplayName();
        endif; 
        ?>
				<td style="padding: 5px;" colspan="3">
          <b><?php echo $left ?></b> <?php echo $actions[$offer->getStatus()] ?> offer on <?php echo $right ?> at <?php echo $offer->getUpdatedAt('%Y-%m-%d'); ?>
        </td>
			</tr>
			<?php $latest_collector_id = $offer->getCollector()->getId(); ?>
			<?php endforeach; ?>
		<?php else: ?>
		<tr>
			<td colspan="5" align="center"><span style="color:#FF0000; font-weight:bold"> No offers made by any users. </span> </td>
		</tr>
		<?php endif; ?>
		</table>
    </div>
  </div>
  <div id="sf_admin_footer"> </div>
</div>