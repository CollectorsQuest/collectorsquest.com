<?php use_helper('Form'); ?>
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" style="vertical-align:top;" width="75%">
      <p style="margin-left: 20px; margin-bottom: 0.7em;">A place where you can buy, sell or trade among the collectible community</p>
      <div style="background: #FAFDE0; border: 1px solid #C7C7C7; padding: 20px; margin-left: 20px; width: 90%;">
        <form action="<?php echo url_for('@marketplace') ?>" method="post">
          <?php
          echo 'Find Collectibles:<br/>';
          echo input_tag('search-term', $sf_params->get('search-term'), array('style' => 'width: 370px; font-size: 16px;')) . '&nbsp;&nbsp;&nbsp;';
          echo submit_tag('Search', array("class" => "button"));
          echo '<br/><br/>';
          echo 'Price: ' . input_tag('price-min', $sf_params->get('price-min', 'Min'), array('style' => 'width: 50px; text-align: center;')) . ' - ';
          echo input_tag('price-max', $sf_params->get('price-max', 'Max'), array('style' => 'width: 50px; text-align: center;')) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
          echo 'Condition: ';
          echo select_tag('condition', options_for_select(array('' => 'Any', 'excellent' => 'Excellent', 'very good' => 'Very Good', 'good' => 'Good', 'fair' => 'Fair', 'poor' => 'Poor'), $sf_params->get('condition'))) . '&nbsp;&nbsp;&nbsp;';
          echo 'Listings: ';
          echo select_tag('addtional_listing', options_for_select(array('' => 'Active', 'Sold' => 'Sold', 'All' => 'All'), $sf_params->get('addtional_listing')));
          ?>
        </form>
      </div>

      <br clear="all" />
      <?php include_component('marketplace', 'listing', array('categorySelected' => $categorySelected)); ?>
    </td>
    <td valign="top" width="25%" style="vertical-align:top;">
        <div style="border: 1px solid #C7C7C7; padding: 20px 0; height: 90px; margin-bottom: 15px; text-align: center;">
          <span style="color: #4A8887; font-weight: bold">POST TO MARKETPLACE</span><br/><br/>
          <?php echo button_to('Sell Your Collectibles', '@manage_collections', array('style' => 'background:#FAFDE0; border:1px solid #C7C7C7; width:170px; height: 30px; cursor:pointer;')); ?>
        </div>
      <div style="background: #5592A7; color: #fff; font-weight: bold; padding: 5px; padding-left: 10px;">BROWSE BY CATEGORY</div>
      <?php foreach ($categories as $category): ?>
        <div class="category_item<?php if (isset($categorySelected) && $categorySelected->getId() == $category->getId())
        echo ' current' ?>">
             <?php if (isset($categorySelected) && $category->getId() == $categorySelected->getId()): ?>
               <?php echo link_to($category->getName(), '@marketplace') ?>
             <?php else: ?>
               <?php echo link_to($category->getName(), 'marketplace_category_by_slug', $category, array('title' => $category->getName())) ?>
             <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </td>
  </tr>
</table>
