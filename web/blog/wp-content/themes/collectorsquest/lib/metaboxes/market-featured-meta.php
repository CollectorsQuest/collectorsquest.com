<div class="my_meta_control">

  <p>
     Enter comma separated ID numbers. (1,2,3,4,5,6) 4,5,6 will be used
     in case 1,2,3 are not available for some reason
  </p>

  <div style="width:31%;margin-right:2%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_1'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_1'); ?>

    <label>Ribbon Text:
      <input name="<?php $mb->the_name(); ?>" value="<?php echo $mb->get_the_value() ?>" maxlength="12" />
    </label>
  </div>

  <div style="width:31%;margin-right:2%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_2'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_2'); ?>

    <label>Ribbon Text:
      <input name="<?php $mb->the_name(); ?>" value="<?php echo $mb->get_the_value() ?>" maxlength="12" />
    </label>
  </div>

  <div style="width:31%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_3'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_3'); ?>

    <label>Ribbon Text:
      <input name="<?php $mb->the_name(); ?>" value="<?php echo $mb->get_the_value() ?>" maxlength="12" />
    </label>
  </div>

  <div style="width:31%;margin-right:2%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_4'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_4'); ?>

    <label>Ribbon Text:
      <input name="<?php $mb->the_name(); ?>" value="<?php echo $mb->get_the_value() ?>" maxlength="12" />
    </label>
  </div>

  <div style="width:31%;margin-right:2%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_5'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_5'); ?>

    <label>Ribbon Text:
      <input name="<?php $mb->the_name(); ?>" value="<?php echo $mb->get_the_value() ?>" maxlength="12" />
    </label>
  </div>

  <div style="width:31%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_6'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_6'); ?>

    <label>Ribbon Text:
      <input name="<?php $mb->the_name(); ?>" value="<?php echo $mb->get_the_value() ?>" maxlength="12" />
    </label>
  </div>

  <div class="clear"></div>
</div>
