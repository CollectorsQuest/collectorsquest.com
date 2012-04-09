<?php slot('sidebar'); ?>
  <ul id="sidebar-buttons" class="buttons">
    <?php include_partial('global/li_buttons', array('buttons' => $buttons)); ?>
  </ul>

  <?php if('seller' == $sf_user->getAttribute('signup_type', 'collector', 'registration')): ?>
  <h2><?php echo  __('Why Become a Seller?'); ?></h2>
  <div style="margin: 0 10px;">
    <ol style="margin: 0; padding-left: 25px;">
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Heavy Traffic'); ?></b><br>
        <?php echo  __('500,000+ collectors and growing with more monthly traffic than flea markets and floor shows.'); ?>
      </li>
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Broader Exposure'); ?></b><br>
        <?php echo  __('Have your items for sale matched against related content on the site. See example.'); ?>
      </li>
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Flat Rate with No Transaction Fees'); ?></b><br>
        <?php echo  __('No fancy math needed. It is what it is. Annual subscribers can sell and replace as many items as you want each month at no additional cost.'); ?>
      </li>
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Annual Expiration Dates and Payment Choice'); ?></b><br>
        <?php echo  __('You can continue listing your items for sale for up to one year. Payment method for your customers is YOUR decision.'); ?>
      </li>
    </ol>
  </div>
  <?php else: ?>
  <h2><?php echo  __('Why Join Our Community?'); ?></h2>
  <div style="margin: 0 10px;">
    <ol style="margin: 0; padding-left: 25px;">
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Create a profile:'); ?></b><br>
        <?php echo  __('Tell others about your collecting habits.'); ?>
      </li>
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Catalog your collections:'); ?></b><br>
        <?php echo  __('Use our easy templates for show and tell.'); ?>
      </li>
      <li style="margin-bottom: 10px;">
        <b><?php echo  __('Share your passion:'); ?></b><br>
        <?php echo  __('Meet interested collectors worldwide.'); ?>
      </li>
    </ol>
  </div>
  <?php endif; ?>
<?php end_slot(); ?>

<div id="collector_signup_div">
  <?php
    include_partial(
      'collector/signupStep'.$snStep,
      array(
        'form' => $form,
      )
    );
  ?>
</div>
