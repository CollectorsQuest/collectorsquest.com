<div class="my_meta_control">

  <p>Enter comma separated ID numbers. (1,2,3)</p>

  <label>Collection IDs:
  <?php $mb->the_field('cq_collection_ids'); ?>

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

</div>
