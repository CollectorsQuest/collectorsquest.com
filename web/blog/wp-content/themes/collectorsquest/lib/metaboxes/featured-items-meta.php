<div class="my_meta_control">
  <label>Items per page (if blank default is 20):
  <?php $mb->the_field('cq_items_per_page'); ?>

  <input name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" /></label>

  <p>Enter comma separated ID numbers. (1,2,3)</p>

  <label>Homepage Collectible IDs<br>
         You can enter size formats like this - ID:size, example: 1111:2x1,2222:2x2,3333:1x2.<br>
         Possible sizes are 2x2, 1x2 and 2x1, default size is 1x1
  <?php $mb->the_field('cq_homepage_collectible_ids'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collection IDs:
  <?php $mb->the_field('cq_collection_ids'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Category IDs:
  <?php $mb->the_field('cq_category_ids'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collectible IDs<br>
    You can enter size formats like this - ID:size, example: 1111:2x1,2222:2x2,3333:1x2.<br>
    Possible sizes are 2x2, 1x2 and 2x1, default size is 1x1
    <?php $mb->the_field('cq_collectible_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collectibles for Sale IDs:
    <?php $mb->the_field('cq_collectibles_for_sale_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>WordPress Post IDs:
    <?php $mb->the_field('cq_wp_post_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <p>Enter comma separated tags. (banner,basketball,NBA)</p>

  <label>Tags:
    <?php $mb->the_field('cq_tags'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <p>
    <b>All of the following fields are for EXCLUDING the relevant models</b>
  </p>
  <p>Enter comma separated ID numbers. (1,2,3)</p>

  <label>Collection IDs:
  <?php $mb->the_field('cq_collection_ids_exclude'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Category IDs:
  <?php $mb->the_field('cq_category_ids_exclude'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collectible IDs (do not enter sizes here):
    <?php $mb->the_field('cq_collectible_ids_exclude'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <p>Enter comma separated tags. (banner,basketball,NBA)</p>

  <label>Tags:
    <?php $mb->the_field('cq_tags_exclude'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <?php $mb->the_field('cq_post_publish_status'); ?>
  <?php $id = $_GET['post'] ?>
  <input name="<?php $mb->the_name(); ?>" type="hidden" value="<?= get_post_status( $id ) ?>">
</div>
