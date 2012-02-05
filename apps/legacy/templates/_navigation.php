<div id="navigation" class="span-13 rounded-bottom">
  <div style="float: left;">
    <?php
      if (sfConfig::get('sf_environment') === 'dev')
      {
        echo link_to(
          image_tag('legacy/logo-development.png', array('align' => 'left', 'style' => 'margin: -54px -35px 0 10px;')),
          '@homepage'
        );
      }
      else if (sfConfig::get('sf_environment') === 'stg')
      {
        echo link_to(
          image_tag('legacy/logo-staging.png', array('align' => 'left', 'style' => 'margin: -54px -35px 0 10px;')),
          '@homepage'
        );
      }
      else
      {
        echo link_to(
          image_tag('legacy/logo.png', array('id' => 'header-logo', 'align' => 'left', 'style' => 'margin: -54px -35px 0 10px;')),
          '@homepage'
        );
      }
    ?>
  </div>

  <div class="span-10 last">
  <ul class="sf-menu">
    <li id="menu-community" class="current">
      <?php echo link_to(__('Community'), '@community'); ?>
      <ul>
        <li><?php echo link_to(__('Spotlight'), '@community_spotlight'); ?></li>
        <li><?php echo link_to(__('Collections'), '@collections'); ?></li>
        <li><?php echo link_to(__('Collectors'), '@collectors'); ?></li>
        <li><?php echo link_to(__('Sellers'), '@sellers'); ?></li>
      </ul>
    </li>
    <li>
      <a href="<?php echo url_for('@marketplace'); ?>" title="<?= __('Sell yours and buy a wide variety of collectible items.'); ?>">
        <?= __('Marketplace'); ?>
      </a>
      <ul>
        <li><?php echo link_to(__('Buy Items'), '@marketplace') ?></li>
        <li><?php echo link_to(__('Sell Items'), $sf_user->isAuthenticated() ? '@manage_marketplace' : '@login') ?></li>
      </ul>
    </li>
    <li id="menu-videos">
      <?php echo link_to(__('Videos'), '@videos'); ?>
      <ul>
        <li><?php echo link_to(__('Hot Videos'), '@videos'); ?></li>
        <li><?php echo link_to(__('Show & Tell'), '@video_playlist?playlist_id=1&slug=show-and-tell'); ?></li>
      </ul>
    </li>
    <li>
      <?php echo link_to(__('Blog'), '@blog'); ?>
      <ul>
        <li><?php echo link_to(__("Today's Post"), '@blog_latest_post'); ?></li>
        <li><?php echo link_to(__('Hot Topics'), '@blog_hot_topics'); ?></li>
      </ul>
    </li>
  </ul>
  </div>
</div>

<?php if (has_component_slot('slot_1')): ?>
  <?= include_component_slot('slot_1', array('collector' => $sf_user->getCollector())); ?>
<?php elseif (has_slot('slot_1')): ?>
  <?= get_slot('slot_1'); ?>
<?php elseif ($sf_user->isAuthenticated()): ?>

  <?php $active = $sf_request->getAttribute('header_icons_active', 'homepage'); ?>

  <div id="header-actions" class="span-10 last" style="margin-left: 80px;">
    <div class="span-2 profile">
      <div class="<?php echo ($active == 'profile') ? 'icon active' : 'icon' ?>">
        <img src="/images/s.gif" width="40" height="50" alt="<?= __('Your Profile'); ?>">
      </div>
      <?= link_to(__('Your&nbsp; Profile'), '@collector_me'); ?>
    </div>
    <div class="span-2 collections">
      <div class="<?php echo ($active == 'collections') ? 'icon active' : 'icon' ?>">
        <img src="/images/s.gif" width="50" height="50" alt="<?= __('Your Collections'); ?>">
      </div>
      <?php
        if ($sf_user->hasCredential('seller'))
        {
          echo link_to(__('&nbsp;&nbsp;Your&nbsp;&nbsp; Sale Items'), '@manage_collections');
        }
        else
        {
          echo link_to(__('Your&nbsp; Collections'), '@manage_collections');
        }
      ?>
    </div>
    <div class="span-2 marketplace">
      <div class="<?php echo ($active == 'marketplace') ? 'icon active' : 'icon' ?>">
        <img src="/images/s.gif" width="50" height="50" alt="<?= __('Your Market'); ?>">
      </div>
      <?= link_to(__('Your&nbsp; Market'), '@manage_marketplace'); ?>
    </div>
    <!--
    <div class="span-2 friends">
      <div class="<?php echo ($active == 'friends') ? 'icon active' : 'icon' ?>">
        <img src="/images/s.gif" width="50" height="50" alt="<?= __('Your Friends'); ?>">
      </div>
      <?= link_to(__('Your&nbsp; Friends'), '@manage_friends'); ?>
    </div>
    //-->
    <div class="span-2 messages">
      <div class="<?php echo ($active == 'messages') ? 'icon active' : 'icon' ?>">
        <img src="/images/s.gif" width="50" height="50" alt="<?= __('Your Messages'); ?>">
        <?php if ($sf_user->getUnreadMessagesCount() > 0): ?>
          <div class="notification" style="<?= ($sf_user->getUnreadMessagesCount() > 9) ? 'background-position: 1px 0;' : ''; ?>">
            <?= $sf_user->getUnreadMessagesCount(); ?>
          </div>
        <?php endif; ?>
      </div>
      <?= link_to(__('Your&nbsp; Messages'), ($sf_user->getUnreadMessagesCount() > 0) ? '@messages_inbox?show=unread' : '@messages_inbox?show=all'); ?>
    </div>
  </div>
<?php else: ?>
  <div id="header-ads" class="span-12 last">
    <?php cq_ad_slot('collectorsquest_com_-_Header_468x60', '468', '60'); ?>
  </div>
<?php endif; ?>
