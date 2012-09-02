<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_list_sender">
  <?php if ('sender_name' == $sort[0]): ?>
  <?php echo link_to(__('Sender', array(), 'messages'), '@private_message', array('query_string' => 'sort=sender_name&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
  <?php echo ice_cdn_image_tag('theme/' . $sort[1] . '.png', 'backend', array('alt' => __($sort[1], array(), 'ice_backend_plugin'), 'title' => __($sort[1], array(), 'ice_backend_plugin'))) ?>
  <?php else: ?>
  <?php echo link_to(__('Sender', array(), 'messages'), '@private_message', array('query_string' => 'sort=sender_name&sort_type=asc')) ?>
  <?php endif; ?>

</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_list_receiver">
  <?php if ('receiver_name' == $sort[0]): ?>
  <?php echo link_to(__('Receiver', array(), 'messages'), '@private_message', array('query_string' => 'sort=receiver_name&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
  <?php echo ice_cdn_image_tag('theme/' . $sort[1] . '.png', 'backend', array('alt' => __($sort[1], array(), 'ice_backend_plugin'), 'title' => __($sort[1], array(), 'ice_backend_plugin'))) ?>
  <?php else: ?>
  <?php echo link_to(__('Receiver', array(), 'messages'), '@private_message', array('query_string' => 'sort=receiver_name&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>


<?php /* generated part */ ?>



<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_subject">
  <?php if ('subject' == $sort[0]): ?>
  <?php echo link_to(__('Subject', array(), 'messages'), '@private_message', array('query_string' => 'sort=subject&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
  <?php echo ice_cdn_image_tag('theme/' . $sort[1] . '.png', 'backend', array('alt' => __($sort[1], array(), 'ice_backend_plugin'), 'title' => __($sort[1], array(), 'ice_backend_plugin'))) ?>
  <?php else: ?>
  <?php echo link_to(__('Subject', array(), 'messages'), '@private_message', array('query_string' => 'sort=subject&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_boolean sf_admin_list_th_is_read">
  <?php if ('is_read' == $sort[0]): ?>
  <?php echo link_to(__('Is read', array(), 'messages'), '@private_message', array('query_string' => 'sort=is_read&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
  <?php echo ice_cdn_image_tag('theme/' . $sort[1] . '.png', 'backend', array('alt' => __($sort[1], array(), 'ice_backend_plugin'), 'title' => __($sort[1], array(), 'ice_backend_plugin'))) ?>
  <?php else: ?>
  <?php echo link_to(__('Is read', array(), 'messages'), '@private_message', array('query_string' => 'sort=is_read&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_boolean sf_admin_list_th_is_replied">
  <?php if ('is_replied' == $sort[0]): ?>
  <?php echo link_to(__('Is replied', array(), 'messages'), '@private_message', array('query_string' => 'sort=is_replied&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
  <?php echo ice_cdn_image_tag('theme/' . $sort[1] . '.png', 'backend', array('alt' => __($sort[1], array(), 'ice_backend_plugin'), 'title' => __($sort[1], array(), 'ice_backend_plugin'))) ?>
  <?php else: ?>
  <?php echo link_to(__('Is replied', array(), 'messages'), '@private_message', array('query_string' => 'sort=is_replied&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_boolean sf_admin_list_th_is_deleted">
  <?php if ('is_deleted' == $sort[0]): ?>
  <?php echo link_to(__('Is deleted', array(), 'messages'), '@private_message', array('query_string' => 'sort=is_deleted&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
  <?php echo ice_cdn_image_tag('theme/' . $sort[1] . '.png', 'backend', array('alt' => __($sort[1], array(), 'ice_backend_plugin'), 'title' => __($sort[1], array(), 'ice_backend_plugin'))) ?>
  <?php else: ?>
  <?php echo link_to(__('Is deleted', array(), 'messages'), '@private_message', array('query_string' => 'sort=is_deleted&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_date sf_admin_list_th_created_at">
  <?php if ('created_at' == $sort[0]): ?>
  <?php echo link_to(__('Date', array(), 'messages'), '@private_message', array('query_string' => 'sort=created_at&sort_type=' . ($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
  <?php echo ice_cdn_image_tag('theme/' . $sort[1] . '.png', 'backend', array('alt' => __($sort[1], array(), 'ice_backend_plugin'), 'title' => __($sort[1], array(), 'ice_backend_plugin'))) ?>
  <?php else: ?>
  <?php echo link_to(__('Date', array(), 'messages'), '@private_message', array('query_string' => 'sort=created_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
