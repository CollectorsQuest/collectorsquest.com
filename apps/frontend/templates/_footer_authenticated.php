<?php
/**
 * @var $sf_user cqFrontendUser
 * @var $collector Collector
 */

$unread_messages = $collector->getUnreadMessagesCount();
?>

<h2 class="Chivo webfont no-margin-bottom">
  Welcome back, <?= $collector->getDisplayName() ?>!
</h2>
<ul class="footer-profile-box cf">
  <li class="footer-pm-box">
    <span class="big-email-icon">
      <span class="pm-counter">
        <?= $unread_messages < 1000 ? $unread_messages : '&#8734; <!-- infinity! -->'; ?>
      </span>
    </span>
    <p>
      You have <?= format_number_choice('[0]no messages|[1]1 message|(1, +Inf]%count% messages',
      array('%count%' => $unread_messages), $unread_messages); ?>
      in <?= link_to('your inbox', '@messages_inbox', array('class' => 'bold-links')); ?>
    </p>
  </li>
  <?php if (100 > $profile_completed = $collector->getProfile()->getProfileCompleted()): ?>
  <li class="icon-big-battery">
    <p>Your profile is <?= $profile_completed ?>% complete.
      <?php if (75 <= $profile_completed): ?>
        <a href="<?=url_for('mycq_collections')?>" class="bold-links">
          Add a collectible
        </a> in minutes.
      <?php elseif (50 <= $profile_completed): ?>
        <a href="<?=url_for('mycq_collections')?>#my-collections" class="bold-links">
          Add a collection
        </a> in minutes.
      <?php else: ?>
        <a href="<?=url_for('mycq_profile')?>" class="bold-links">
          Add info about what you collect
        </a>.
      <?php endif; ?>
      (+25%)
    </p>
  </li>
  <?php endif; ?>
  <li class="footer-profile-box-h-list" style="padding-top: 0;">
    <ul class="row-fluid">
      <li class="span6 add-collectible-img link">
        <a href="<?= url_for('@mycq_collections', true) ?>" class="bold-links target">
          Upload<br> an item
        </a>
      </li>
      <li class="span6 organize-collection link">
        <a href="<?= url_for('@mycq_collections', true) ?>#my-collections" class="bold-links target">
          Organize your<br> collections
        </a>
      </li>
    </ul>
  </li>
</ul> <!-- .footer-pofile-box -->

<div class="row-fluid spacer-inner-top">
  <div class="span12">
    <a href="<?= url_for('@mycq_profile', true); ?>" class="btn btn-primary blue-button">
      My Profile
    </a>
    <b><?= link_to('Log out', '@logout', array('class' => 'spacer-left logout-link')); ?></b>
  </div>
</div>
