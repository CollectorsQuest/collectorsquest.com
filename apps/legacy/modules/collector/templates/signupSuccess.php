<?php slot('sidebar'); ?>
  <ul id="sidebar-buttons" class="buttons">
    <?php include_partial('global/li_buttons', array('buttons' => $buttons)); ?>
  </ul>
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
<?php end_slot(); ?>

<div id="collector_signup_div">
  <?php
    include_partial(
      'collector/signupStep'.$snStep,
      array(
        'form' => $form,
        'amStep1Data' => $amStep1Data,
        'amStep2Data' => $amStep2Data
      )
    );
  ?>
</div>
