<?php
/**
 * @var $sf_user cqFrontendUser
 * @var $collector Collector
 */
$collector = $sf_user->getCollector();
$unread_messages = $collector->getUnreadMessagesCount();
?>

<h3 class="Chivo webfont no-margin-bottom">
  Welcome back, <?= $collector->getDisplayName() ?>!
</h3>
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
      in <?= link_to('your inbox', '@messages_inbox?ref=' . cq_link_ref('footer'), array('class' => 'bold-links')); ?>
    </p>
  </li>
  <?php if (100 > $profile_completed = $collector->getProfile()->getProfileCompleted()): ?>
    <li class="icon-big-battery">
      <p>Your profile is <?= $profile_completed ?>% complete.
        <?php if (75 <= $profile_completed): ?>
          <a href="<?= url_for('@mycq_profile?ref=' . cq_link_ref('footer')) ?>" class="bold-links">
            Add info about what you collect
          </a>.
          <?php elseif (50 <= $profile_completed): ?>
            <a href="<?= url_for('@mycq_collections?ref=' . cq_link_ref('footer')) ?>" class="bold-links">
              Add a collectible
            </a> in minutes.
          <?php else: ?>
            <a href="<?= url_for('@mycq_collections?ref=' . cq_link_ref('footer')) ?>#my-collections"
               class="bold-links">
              Add a collection
            </a> in minutes.
          <?php endif; ?>
          (+25%)
      </p>
    </li>
  <?php endif; ?>
</ul>
