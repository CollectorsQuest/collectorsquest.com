<?php
//  cq_section_title(
//    "Welcome to Collectors' Quest, {$collector->getDisplayName()}!", null,
//    array('class' => 'row-fluid sidebar-title spacer')
//  );
?>

<div class="row-fluid spacer-top-20">
  <div class="span3">
    <?php
      cq_ad_slot(
        cq_image_tag('headlines/2012-08-18_CQGuide_160x600.png',
          array(
            'width' => '160', 'height' => '600',
            'alt' => 'Quest Your Best: The Essential Guide to Collecting'
          )
        ),
        '@misc_guide_download'
      );
    ?>
    <p style="padding: 10px 0 10px 10px;">
      The download link should have been sent to your email address.
      <strong><?= link_to('Click here', '@misc_guide_download'); ?></strong>
      if you did not receive it.
    </p>
  </div>
  <div class="span9 welcome-mycq">
    <?php if (100 > $collector->getProfile()->getProfileCompleted()): ?>
    <p class="spacer-bottom-20">
      Show off your stuff to other Collectors Quest members by
      <?= link_to('filling out your profile', '@mycq_profile') ?>, so you can buy,
      sell, trade and share with people who have the same passions as you.
      <strong>Welcome to the community!</strong>
    </p>
    <?php endif; ?>

    <div class="icons-wrapper">
      <div class="rectangle"></div>
      <div class="row-fluid icons-inner">
        <div class="span4 text-center">
          <a href="<?= url_for('@mycq_profile'); ?>" title="Edit My Profile">
            <i class="edit-profile"></i>
            <h3>Edit My Profile</h3>
          </a>
          <p>
            Update your profile picture, edit your profile, email, or password.<br>
            <?php
              echo link_to(
                '<i class="icon-question-sign"></i>&nbsp;Show me how!',
                '/blog/wp-admin/admin-ajax.php?action=load_how_to&group_id=1&redirect='. url_for('@mycq_profile'),
                array('class' => 'open-dialog', 'onclick' => 'return false;')
              );
            ?>
          </p>
        </div>
        <div class="span4 text-center">
          <a href="<?= url_for('@mycq_collections'); ?>" title="Edit My Collections">
            <i class="edit-collections"></i>
            <h3>Edit My Collections</h3>
          </a>
          <p>
            Upload new collections, add to existing ones or organize the collections that you have.<br>
            <?php
              echo link_to(
                '<i class="icon-question-sign"></i>&nbsp;Show me how!',
                '/blog/wp-admin/admin-ajax.php?action=load_how_to&group_id=4&redirect='. url_for('@mycq_collections'),
                array('class' => 'open-dialog', 'onclick' => 'return false;')
              );
            ?>
          </p>
        </div>
        <div class="span4 text-center">
          <a href="<?= url_for('@mycq_marketplace'); ?>" title="Sell Your Stuff">
            <i class="marketplace"></i>
            <h3>Sell My Stuff</h3>
          </a>
          <p>
            List your collectible, vintage and antique items for sale in our Market.<br>
            <?php
              echo link_to(
                '<i class="icon-question-sign"></i>&nbsp;Show me how!',
                '/blog/wp-admin/admin-ajax.php?action=load_how_to&group_id=5&redirect='. url_for('@mycq_marketplace'),
                array('class' => 'open-dialog', 'onclick' => 'return false;')
              );
            ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
