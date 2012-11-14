<?php if ($form->getObject()->getMultimedia()): ?>
  <div style="float: left;">
    <?php echo link_to(image_tag('backend/icons/refresh.png'), 'organization/edit?id='. $form->getObject()->getId()); ?>
  </div>
  <?php include_partial('global/form_images', array('multimedia' => $form->getObject()->getMultimedia())); ?>
<?php endif; ?>
