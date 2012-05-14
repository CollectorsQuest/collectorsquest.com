<div class="my_meta_control">

	<p>Use this to get a better crop of the hero image.</p>

	<label>Align Crop</label>

  <?php
    $mb->the_field('cq_thumb_align');

    if(is_null($mb->get_the_value()))
      $mb->meta[$mb->name] = 'top';
  ?>

		<input type="radio" name="<?php $mb->the_name(); ?>" value="top"<?php $mb->the_radio_state('top'); ?><?php echo $mb->is_value('top')?' checked="checked"':''; ?>/> Top<br/>
		<input type="radio" name="<?php $mb->the_name(); ?>" value="middle"<?php $mb->the_radio_state('middle'); ?><?php $mb->the_radio_state('middle'); ?>/> Middle<br/>
		<input type="radio" name="<?php $mb->the_name(); ?>" value="bottom"<?php $mb->the_radio_state('bottom'); ?><?php $mb->the_radio_state('bottom'); ?>/> Bottom<br/>

</div>
