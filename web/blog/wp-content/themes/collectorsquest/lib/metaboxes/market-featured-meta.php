<div class="my_meta_control" style="height:130px;">

  <p>Enter comma separated ID numbers. (1,2,3)</p>

  <div style="width:31%;margin-right:2%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_1'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_1'); ?>

    <label>Ribbon Text:
      <select name="<?php $mb->the_name(); ?>">
        <option value=""></option>
        <option value="affordable"<?php if ($mb->get_the_value() == 'affordable') echo $selected; ?>>Affordable</option>
        <option value="rare"<?php if ($mb->get_the_value() == 'rare') echo $selected; ?>>Rare</option>
        <option value="unique"<?php if ($mb->get_the_value() == 'unique') echo $selected; ?>>Unique</option>
        <option value="as-seen-on"<?php if ($mb->get_the_value() == 'as-seen-on') echo $selected; ?>>As Seen On</option>
      </select>
    </label>
  </div>

  <div style="width:31%;margin-right:2%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_2'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_2'); ?>

    <label>Ribbon Text:
      <select name="<?php $mb->the_name(); ?>">
        <option value=""></option>
        <option value="affordable"<?php if ($mb->get_the_value() == 'affordable') echo $selected; ?>>Affordable</option>
        <option value="rare"<?php if ($mb->get_the_value() == 'rare') echo $selected; ?>>Rare</option>
        <option value="unique"<?php if ($mb->get_the_value() == 'unique') echo $selected; ?>>Unique</option>
        <option value="as-seen-on"<?php if ($mb->get_the_value() == 'as-seen-on') echo $selected; ?>>As Seen On</option>
      </select>
    </label>
  </div>

  <div style="width:31%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_3'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_3'); ?>

    <label>Ribbon Text:
      <select name="<?php $mb->the_name(); ?>">
        <option value=""></option>
        <option value="affordable"<?php if ($mb->get_the_value() == 'affordable') echo $selected; ?>>Affordable</option>
        <option value="rare"<?php if ($mb->get_the_value() == 'rare') echo $selected; ?>>Rare</option>
        <option value="unique"<?php if ($mb->get_the_value() == 'unique') echo $selected; ?>>Unique</option>
        <option value="as-seen-on"<?php if ($mb->get_the_value() == 'as-seen-on') echo $selected; ?>>As Seen On</option>
      </select>
    </label>
  </div>

</div>
