<?php slot('sidebar'); ?>
  <ul id="sidebar-buttons" class="buttons">
    <?php include_partial('global/li_buttons', array('buttons' => $buttons)); ?>
  </ul>
  <h2><?php echo  __('Why Join Our Community?'); ?></h2>
  <div style="margin: 0 10px;">
    <ol style="margin: 0; padding-left: 25px;">
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Broaden your exposure:'); ?></b><br>
        <?php echo  __('Items for sale are matched with related content in addition to a marketplace posting.'); ?>
      </li>
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Sell your items:'); ?></b><br>
        <?php echo  __('The biggest question we get asked on our site is &quot;Where can I buy that?&quot;'); ?>
      </li>
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Reduce your stress:'); ?></b><br>
        <?php echo  __('We offer flat annual rates with no transaction fees. No scheduling needed.'); ?>
      </li>
    </ol>
  </div>
<?php end_slot(); ?>

<div id="seller_signup_div">
  <?php include_partial('seller/signupStep'.$snStep, array('form' => $form, 'amPreviousData' => $amPreviousData)); ?>
</div>
