<div class="my_meta_control">

  <p>Enter comma separated ID numbers. (1,2,3)</p>

  <label>Homepage Collectible IDs:
  <?php $mb->the_field('cq_homepage_collectible_ids'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collection IDs:
  <?php $mb->the_field('cq_collection_ids'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Category IDs:
  <?php $mb->the_field('cq_category_ids'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collectible IDs:
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
    <b>All of the following fileds are for EXCLUDING the relevant models</b>
  </p>
  <p>Enter comma separated ID numbers. (1,2,3)</p>

  <label>Collection IDs:
  <?php $mb->the_field('cq_collection_ids_exclude'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Category IDs:
  <?php $mb->the_field('cq_category_ids_exclude'); ?>

  <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <label>Collectible IDs:
    <?php $mb->the_field('cq_collectible_ids_exclude'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

  <p>Enter comma separated tags. (banner,basketball,NBA)</p>

  <label>Tags:
    <?php $mb->the_field('cq_tags_exclude'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

</div>
