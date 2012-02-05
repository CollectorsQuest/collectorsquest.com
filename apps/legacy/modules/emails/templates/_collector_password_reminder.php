<?php include_partial('emails/header'); ?>

<p style="margin-bottom: 10px; font-weight: bold;">
  <?= sprintf(__('Dear %s', null, 'emails'), $collector->getDisplayName()); ?>,
</p>
<p>
  Your new password is <b><?php echo isset($password) ? $password : null; ?></b> and after you login you can go to 
  <?= link_to('your account settings', '@manage_profile', array('absolute' => true)); ?>
  and change your password to something easier to remember.
</p>

<p>
  For your convenience, you can also
  <?= link_to('click here', '@collector_auto_login?hash='. $collector->getAutoLoginHash().'&goto='. url_for('@manage_profile', false), array('absolute' => true)); ?>
  and you will be automatically taken to your Collectors' Quest account.
</p>

<?php include_partial('emails/footer'); ?>