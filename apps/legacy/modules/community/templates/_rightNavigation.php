<?php 
use_helper("Asset");
use_stylesheet("right-menu", "last");
?>

<td width ="180" valign ="top" style ="padding: 0 10px 50px 0;"><!-- Right Navigation -->
  <div style ="height: 30px; width: 100%; background: #FFF;">
    <div id="indicator" style="display: none; padding: 6px; background: #CC4444; color: #FFF"> Loading content... </div>
    <ul class="buttons">
      <?php
			foreach ($menu as $item):
				echo '<li>'.((@$item["remote"] === true)?link_to_remote($item["text"], $item["url"]):link_to($item["text"], $item["url"])).'</li>';
		  	endforeach;			
		?>
    </ul>
    <?php if (has_slot('featured_offer')):?>
    <div style ="height: 18px; background: #63A0B3 url(/images/bullet-arrow-blue.png) no-repeat 0 2px; padding: 4px 0 2px 15px;"> <font style ="color: #fff; font-size: 14px; font-weight: bold;"> <?php echo (@$rnd_flag) ? 'See Something New ...' : 'You May Also Like ...'; ?> </font> <br />
      <?php include_slot('featured_offer');
		endif;	
		if (has_slot('right navigation')): include_slot('right navigation'); endif;
		?>
    </div>
  </div>
</td>
