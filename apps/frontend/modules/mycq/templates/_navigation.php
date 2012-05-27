<?php
/**
 * @var $collector Collector
 * @var $sf_params sfParameterHolder
 */
?>

<div class="row" style="margin-left: 5px;">
  <div class="span2">
    <a href="<?= url_for('@collector_me') ?>" title="Go to your public profile">
      <?php
        echo image_tag_collector(
          $collector, '235x315',
          array('width' => 75, 'height' => 101, 'style' => 'margin-top: -37px;')
        );
      ?>
    </a>
  </div>
  <div class="span12" style="margin-left: 0; width: 87%;">
    <?php
      $links = link_to('Log Out', '@logout') .
               '<span style="color: #fff;">&nbsp; | &nbsp;</span>'.
               link_to('View Public Profile →', '@collector_me');

      cq_page_title($collector->getDisplayName(), $links);
    ?>

    <div id="profile-subnavbar" class="navbar">
      <div class="navbar-inner">
        <div class="container">
          <div class="nav-collapse">
            <ul class="nav">
              <?php
                $active = in_array($sf_params->get('action'), array('profile')) ? 'active' : null;
                echo '<li class="'. $active .'">', link_to('Profile', '@mycq_profile'), '</li>';
              ?>
              <?php
                $active = in_array($sf_params->get('action'), array('collections')) ? 'active' : null;
                echo '<li class="'. $active .'">', link_to('Collections', '@mycq_collections'), '</li>';
              ?>
              <?php
                if ($sf_user->getCollector()->getIsSeller())
                {
                  $active = in_array($sf_params->get('action'), array('marketplace')) ? 'active' : null;
                  echo '<li class="'. $active .'">', link_to('Store', '@mycq_marketplace'), '</li>';
                }
              ?>
              <?php $active = in_array($sf_params->get('module'), array('messages')) ? 'active' : null; ?>
              <li class="dropdown <?= $active ?>" style="border-right: 1px solid #4B3B3B;">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                  Messages (<?= $sf_user->getUnreadMessagesCount(); ?>)
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li style="text-transform: none;">
                    <a href="<?= url_for('@messages_inbox'); ?>" title="Go to Your Inbox">
                      <i class="icon icon-inbox"></i> Go to Inbox
                    </a>
                  </li>
                  <li style="text-transform: none;">
                    <a href="<?= url_for('@messages_compose'); ?>" title="Compose Message">
                      <i class="icon icon-edit"></i> Compose Message
                    </a>
                  </li>
                </ul>
              </li>
              <?php
                $active = in_array($sf_params->get('action'), array('wanted')) ? 'active' : null;
                // echo '<li class="'. $active .'" style="border-right: 1px solid #4B3B3B;">', link_to('Wanted', '@mycq_wanted'), '</li>';
              ?>
            </ul>
          </div><!-- /.nav-collapse -->
        </div>
      </div><!-- /navbar-inner -->
    </div>
  </div>
</div>
