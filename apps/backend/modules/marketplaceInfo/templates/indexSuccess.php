<?php use_helper('Form'); ?>

<div id="sf_admin_container">
  <h1>List of Marketplace Items</h1>
  <div id="sf_admin_header"> </div>
  <!-- start for filter -->
  <div id="sf_admin_bar">
    <div class="sf_admin_filter"> <?php echo form_tag('@marketplace_list');?>
      <table cellspacing="0" border="0">
        <tfoot>
          <tr>
            <td colspan="2"><?php 
					echo reset_tag('Reset',array('title' => 'Reset', 'style' => 'border:none; cursor:pointer;')).'&nbsp;&nbsp;';
					echo button_to('Show All',url_for('@marketplace_list'),array('title' => 'Show All','style' => 'border:none; cursor:pointer;')).'&nbsp;&nbsp;';
					echo submit_tag('Filter',array('title' => 'Filter', 'style' => 'cursor:pointer;'));					
				?>
            </td>
          </tr>
        </tfoot>
        <tbody>
          <tr class="sf_admin_form_row">
            <td><label for="collection_filters_name">Find Collectibles:</label>
            </td>
            <td><?php echo input_tag('search-term',$sf_params->get('search-term'));?> </td>
          </tr>
          <tr>
            <td> Price:
              <?php 
					echo input_tag('price-min', $sf_params->get('price-min', 'MIN'), array('style' => 'width: 50px; text-align: center;'));
					echo ' - ';
					echo input_tag('price-max', $sf_params->get('price-max', 'MAX'), array('style' => 'width: 50px; text-align: center;')); 
				?>
            </td>
          </tr>
          <tr>
            <td> Condition:
              <?php 
					echo select_tag('condition', options_for_select(array('' => 'Any', 'excellent' => 'Excellent', 'very good' => 'Very Good', 'good' => 'Good', 'fair' => 'Fair', 'poor' => 'Poor'),$sf_params->get('condition')));
				?>
            </td>
          </tr>
		  <tr>
		  	<td> Listings:
				<?php echo select_tag('addtional_listing', options_for_select(array('' => 'Active', 'Sold' => 'Sold', 'All' => 'All'),$sf_params->get('addtional_listing')));?>
			</td>
		  </tr>
        </tbody>
      </table>
      </form>
    </div>
  </div>
  <!-- end for filter -->
  <div id="sf_admin_content">
    <div class="sf_admin_list">
      <table cellspacing="0" border="0">
        <thead>
          <tr>
            <th class="sf_admin_text"><?php echo link_to('Item Name',url_for('@marketplace_list?sort=ITEM_ID&sory_type='.$ssSortType),array('title' => $ssSortType));?></th>
            <th class="sf_admin_text"><?php echo link_to('Price',url_for('@marketplace_list?sort=PRICE&sory_type='.$ssSortType),array('title' => $ssSortType));?></th>
            <th class="sf_admin_text">Is Price Negotiable</th>
            <th class="sf_admin_text">Is Shipping Free</th>
            <th class="sf_admin_date">Is Sold</th>
            <th class="sf_admin_date">Created On</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th colspan="6"> 
				<?php echo $pager->getNbResults();?> results (page <?php echo $pager->getPage().'/'.$pager->getLastPage();?>)
				<span style="float:right;">
					<?php 
						$ssSearchTerm	= ($sf_params->get('search-term')) ? '&search-term='.$sf_params->get('search-term') : '';
						$snPriceMax 	= (str_replace ('max', '', $sf_params->get('price-max',''))) ? '&price-min='.$sf_params->get('price-min') : '';
						$snPriceMin 	= (str_replace ('min', '', $sf_params->get('price-min',''))) ? '&price-max='.$sf_params->get('price-max') : '';						
						$ssCondition 	= ($sf_params->get('condition')) ? '&condition='.$sf_params->get('condition') : '';
						$ssSort 		= ($sf_params->get('sort')) ? '&sort='.$sf_params->get('&sort') : '';
						$ssSortType		= ($sf_params->get('sory_type')) ? '&sory_type='.$sf_params->get('sory_type') : '';						
						$ssOtherParams = $ssSearchTerm.$snPriceMax.$snPriceMin.$ssCondition.$ssSort.$ssSortType;
						include_partial('marketplaceInfo/pager', array('pager' => $pager, 'options' => array('url' => '@marketplace_list', 'ssOtherParams' => $ssOtherParams) ));?>
				</span>
			</th>
          </tr>
        </tfoot>
        <tbody>
          <?php if ($pager->getNbResults() > 0):
				$snI = 1;
				foreach ($pager->getResults() as $omItemForSale):
				
        /* @var $omItem Collectible */
				$omItem = $omItemForSale->getCollectible();
				$ssOddEven = ($snI%2 == 0) ? 'even' : 'odd';
			?>
          <tr class="sf_admin_row <?php echo $ssOddEven;?>">
            <td class="sf_admin_text"><?php echo link_to($omItem->getName(),url_for('@item_offers?id='.$omItemForSale->getId()),array('title'=> $omItem->getName()));?> </td>
            <td class="sf_admin_text"><?php echo $omItemForSale->getPrice();?> </td>
            <td class="sf_admin_text" align="center"><?php echo ($omItemForSale->getIsPriceNegotiable()) ? 'Yes' : 'No'; ?></td>
            <td class="sf_admin_text" align="center"><?php echo ($omItemForSale->getIsShippingFree()) ? 'Yes' : 'No'; ?></td>
            <td class="sf_admin_text" align="center"><?php echo ($omItemForSale->getIsSold()) ? '<b>Sold</b>' : 'No'; ?></td>
            <td class="sf_admin_text"><?php echo $omItemForSale->getCreatedAt() ?></td>
          </tr>
          <?php $snI++;endforeach;?>
          <?php else: ?>
          <tr>
            <td colspan="6" align="center"><span style="color:#FF0000; font-weight:bold"> No Records Found! </span> </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div id="sf_admin_footer"> </div>
</div>