<div class="my_meta_control" style="height:220px;">

  <p>Enter comma separated ID numbers. (1,2,3)</p>

  <div style="width:31%;margin-right:2%;float:left">
    <label>Item 1:
    <?php $mb->the_field('cq_collectible_id_1'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="5"><?php $mb->the_value(); ?></textarea></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_1'); ?>

    <label>Item 1 text:
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
    <label>Item 2:
    <?php $mb->the_field('cq_collectible_id_2'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="5"><?php $mb->the_value(); ?></textarea></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_2'); ?>

    <label>Item 2 text:
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
    <label>Item 3:
    <?php $mb->the_field('cq_collectible_id_3'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="5"><?php $mb->the_value(); ?></textarea></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_3'); ?>

    <label>Item 3 text:
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
