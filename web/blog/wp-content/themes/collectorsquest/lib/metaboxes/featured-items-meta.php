<div class="my_meta_control">
  <label>Items per page:
    <?php $mb->the_field('cq_items_per_page'); ?>
    <input name="<?php $mb->the_name(); ?>" value="<?= $mb->get_the_value() ?: 24; ?>" size="3" style="text-align: center;" />
  </label>

  <label>Enable infinite scroll:
    <?php $mb->the_field('cq_infinite_scroll'); ?>

    &nbsp; &nbsp;
    <input type="radio" name="<?php $mb->the_name(); ?>" value="true" <?= ($mb->get_the_value() == 'true') ? 'checked' : null; ?>> &nbsp;Yes
    &nbsp;
    <input type="radio" name="<?php $mb->the_name(); ?>" value="false" <?= ($mb->get_the_value() != 'true') ? 'checked' : null; ?>> &nbsp;No
  </label>

  <label>Enable full page width image and no sidebar:
    <?php $mb->the_field('cq_no_sidebar'); ?>

    &nbsp; &nbsp;
    <input type="radio" name="<?php $mb->the_name(); ?>" value="true" <?= ($mb->get_the_value() == 'true') ? 'checked' : null; ?>> &nbsp;Yes
    &nbsp;
    <input type="radio" name="<?php $mb->the_name(); ?>" value="false" <?= ($mb->get_the_value() != 'true') ? 'checked' : null; ?>> &nbsp;No
  </label>

  <label>Layout (default is 'grid' you can skip it, the other option is 'pinterest')
    <?php $mb->the_field('cq_layout'); ?>

    &nbsp; &nbsp;
    <input type="radio" name="<?php $mb->the_name(); ?>" value="true" <?= ($mb->get_the_value() == 'pinterest') ? 'checked' : null; ?>> &nbsp;Pinterest
    &nbsp;
    <input type="radio" name="<?php $mb->the_name(); ?>" value="false" <?= ($mb->get_the_value() != 'pinterest') ? 'checked' : null; ?>> &nbsp;Grid
  </label>

  <label>Homepage Collectible IDs:<br>
  <span style="color: gray;">
    (You can enter size formats like this - ID:size, example: 1111:2x1, 2222:2x2, 3333:1x2, 444:3x1<br/>
    Possible sizes are 2x2, 1x2, 2x1, 1x3, 2x3, 3x3, 3x2 and 3x1. Default size is 1x1)
  </span>
  <?php $mb->the_field('cq_homepage_collectible_ids'); ?>
  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collection IDs:
  <?php $mb->the_field('cq_collection_ids'); ?>
  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Category IDs:
  <?php $mb->the_field('cq_category_ids'); ?>
  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collectible IDs<br>
    <span style="color: gray;">
      You can enter size formats like this - ID:size, example: 1111:2x1, 2222:2x2, 3333:1x2, 444:3x1<br/>
      Possible sizes are 2x2, 1x2, 2x1, 1x3, 2x3, 3x3, 3x2 and 3x1. Default size is 1x1)
    </span>
    <?php $mb->the_field('cq_collectible_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collectibles for Sale IDs:
    <?php $mb->the_field('cq_collectibles_for_sale_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>WordPress Post IDs:
    <?php $mb->the_field('cq_wp_post_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Enter comma separated tags. (banner, basketball, NBA):
    <?php $mb->the_field('cq_tags'); ?>
    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea>
  </label>

  <h2>All of the following fields are for EXCLUDING content!</h2>

  <label>Collection IDs:
  <?php $mb->the_field('cq_collection_ids_exclude'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Category IDs:
  <?php $mb->the_field('cq_category_ids_exclude'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collectible IDs (do not enter sizes here):
    <?php $mb->the_field('cq_collectible_ids_exclude'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>
    Enter comma separated tags. (banner, basketball, NBA):
    <?php $mb->the_field('cq_tags_exclude'); ?>
    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea>
  </label>

  <?php $mb->the_field('cq_post_publish_status'); ?>
  <?php $id = $_GET['post'] ?>
  <input name="<?php $mb->the_name(); ?>" type="hidden" value="<?= get_post_status( $id ) ?>">
</div>
