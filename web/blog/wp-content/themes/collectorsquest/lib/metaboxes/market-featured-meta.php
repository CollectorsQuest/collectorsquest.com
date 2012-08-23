<div class="my_meta_control" style="height:130px;">

  <p>Enter comma separated ID numbers. (1,2,3)</p>

  <div style="width:31%;margin-right:2%;float:left">
    <label>Collectible for Sale (ID):
    <?php $mb->the_field('cq_collectible_id_1'); ?>

    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"></label>

    <?php $selected = ' selected="selected"'; ?>
    <?php $mb->the_field('cq_collectible_text_1'); ?>

    <label>Ribbon Text:
      <select name="<?php $mb->the_name(); ?>" id="select_1">
        <option value=""></option>
        <option value="Affordable"<?php if ($mb->get_the_value() == 'Affordable') echo $selected; ?>>Affordable</option>
        <option value="Rare"<?php if ($mb->get_the_value() == 'Rare') echo $selected; ?>>Rare</option>
        <option value="Unique"<?php if ($mb->get_the_value() == 'Unique') echo $selected; ?>>Unique</option>
        <option value="As Seen On"<?php if ($mb->get_the_value() == 'As Seen On') echo $selected; ?>>As Seen On</option>

        <?php
          $free_text_1_selected = false;
           if (!in_array($mb->get_the_value(), array('', 'Affordable', 'Rare', 'Unique', 'As Seen On'))):
             $free_text_1_selected = true;
           endif;
        ?>

        <option value="Enter Free Text"<?php if ($free_text_1_selected) echo $selected; ?>>Enter Free Text</option>
      </select>
      <input name="<?php $mb->the_name(); ?>" value="<?php echo $mb->get_the_value() ?>" id="input_1" style="display: none;" />
    </label>
  </div>

  <script>
    jQuery(document).ready(function() {
      <?php if ($free_text_1_selected): ?>
          jQuery('input#input_1').show();
      <?php endif; ?>

      jQuery("select#select_1").change(function () {
        jQuery("select#select_1 option:selected").each(function (i, selected) {
          var text = jQuery(selected).text();
          if (text != 'Enter Free Text')
          {
            jQuery('input#input_1').val(text);
            jQuery('input#input_1').hide();
          }
          else
          {
            jQuery('input#input_1').val('');
            jQuery('input#input_1').show();
          }
        });
      });
    });
  </script>

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
