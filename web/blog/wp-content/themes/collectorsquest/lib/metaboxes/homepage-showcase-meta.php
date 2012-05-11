<div class="my_meta_control">

  <p>Enter comma separated ID numbers. (1,2,3)</p>

    <label>Collectible IDs:
    <?php $mb->the_field('cq_collectible_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

    <label>Collection IDs:
    <?php $mb->the_field('cq_collection_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

    <label>Collector IDs:
    <?php $mb->the_field('cq_collector_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

    <label>Magnify Video IDs:
    <?php $mb->the_field('magnify_video_ids'); ?>

    <textarea name="<?php $mb->the_name(); ?>" cols="5" rows="3"><?php $mb->the_value(); ?></textarea></label>

</div>
