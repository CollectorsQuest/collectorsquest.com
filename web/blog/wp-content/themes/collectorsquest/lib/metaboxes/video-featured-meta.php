<?php
/**
 * @var $mb WPAlchemy_MetaBox
 */
?>
<div class="my_meta_control video_featured_box">

  <?php $mb->the_field('video_featured'); ?>

  <label for="video_featured">
    <?php _e('Featured Video:'); ?>
  </label>
  <input type="checkbox" id="video_featured" name="<?php $mb->the_name(); ?>" class="video_featured_input"
         value="1"<?php echo $mb->is_value('1')?' checked="checked"':''; ?> />



</div>
