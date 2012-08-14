<?php
//  cq_sidebar_title(
//    "Welcome to Collectors' Quest, {$collector->getDisplayName()}!", null,
//    array('class' => 'row-fluid sidebar-title spacer')
//  );
?>

<div class="row-fluid spacer-top-20">
  <div class="span3">
    <?php
      echo link_to(
        cq_image_tag('banners/2012-06-24_CQGuide_160x600.png'),
        '@misc_guide_download'
      );
    ?>
  </div>
  <div class="span9 welcome-mycq">
    <?php if (100 > $collector->getProfile()->getProfileCompleted()): ?>
    <p>
      One of the best parts about collecting is sharing your love and treasures with others,
      so make sure to <?= link_to('fill out your profile', '@mycq_profile') ?>
      so we can match you with people who share the same passion as you.
      <strong>Welcome to the community!</strong>
    </p>
    <br><br>
    <?php endif; ?>

    <br>
    <div class="rectangle"></div>
    <div class="row-fluid content-box">
      <div class="span4 text-center">
        <a href="<?= url_for('@mycq_profile'); ?>" title="Edit My Profile">
          <i class="edit-profile"></i>
          <h3>Edit My Profile</h3>
        </a>
        <p>
          Update your profile picture, edit your profile, email, or password
          <br>
          <!--<a href="javascript:void(0)">Show me how!</a>//-->
        </p>
      </div>
      <div class="span4 text-center">
        <a href="<?= url_for('@mycq_collections'); ?>" title="Edit My Collections">
          <i class="edit-collections"></i>
          <h3>Edit My Collections</h3>
        </a>
        <p>
          Upload new collections, add to existing ones or
          organize the collections that you have
          <br>
          <!--<a href="javascript:void(0)">Show me how!</a>//-->
        </p>
      </div>
      <div class="span4 text-center">
        <a href="<?= url_for('@messages_inbox'); ?>" title="Check My Messages">
          <i class="check-messages"></i>
          <h3>Check My Messages</h3>
        </a>
        <p>
          Check your private messages from other members or compose new messages
          <br>
          <!--<a href="javascript:void(0)">Show me how!</a>//-->
        </p>
      </div>
    </div>
  </div>
</div>


