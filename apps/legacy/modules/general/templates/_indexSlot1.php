<?php if ($sf_user->isAuthenticated()): ?>
  <div id="header-actions" class="span-9 last" style="float: right; margin-bottom: 0; padding-bottom: 0;">
    <div class="span-2" style="text-align: center; margin-left: 15px;">
      <div class="icon" style="width: 40px; height: 50px;">
        <?php
          echo link_to(
            image_tag('s.gif', array('width' => 40, 'height' => 50, 'alt_title' => __('Your Profile'))),
            '@collector_by_id?id='.$sf_user->getId().'&slug='.$sf_user->getSlug(),
            array('title' => __('Your Profile'))
          );
        ?>
      </div>
    </div>
    <div class="span-2" style="text-align: center;">
      <div class="icon" style="background-position: -42px 3px;">
        <?php
          echo link_to(
            image_tag('s.gif', array('width' => 50, 'height' => 50, 'alt_title' => __('Your Collections'))),
            '@manage_collections',
            array('title' => __('Your Collections'))
          );
        ?>
      </div>
    </div>
    <div class="span-2" style="text-align: center;">
      <div class="icon" style="background-position: -95px 3px; margin: auto;">
        <?php
          echo link_to(
            image_tag('s.gif', array('width' => 50, 'height' => 50, 'alt_title' => __('Your&nbsp; Market'))),
            '@manage_marketplace',
            array('title' => __('Your&nbsp; Market'))
          );
        ?>
      </div>
    </div>
    <?php $unread = $sf_user->getUnreadMessagesCount(); ?>
    <div class="span-2 last" style="text-align: center;">
      <div class="icon" style="background-position: -154px 0; margin: auto;">
        <?php
          echo link_to(
            image_tag('s.gif', array('width' => 50, 'height' => 50, 'alt_title' => __('Your Messages'))),
            ($unread > 0) ? '@messages_inbox?show=unread' : '@messages_inbox?show=all',
            array('title' => __('Your Messages'))
          );
        ?>
        <?php if ($unread > 0): ?>
          <div class="notification" style="<?= ($unread > 9) ? 'background-position: 1px 0;' : ''; ?>">
            <?= $unread; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="span-12 last" style="text-align: center; padding-top: 5px;">
    <a href="http://www.facebook.com/pages/Collectors-Quest/119338990397" target="_blank" title="Follow Collectors' Quest on Facebook">
      <img src="/images/icons/facebook-follow.png" width="165" height="52" alt="Follow Collectors' Quest on Facebook">
    </a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://twitter.com/collectorsquest" target="_blank" title="Follow Collectors' Quest on Twitter">
      <img src="/images/icons/twitter-follow.png" width="165" height="52" alt="Follow Collectors' Quest on Twitter">
    </a>
  </div>
<?php endif; ?>
