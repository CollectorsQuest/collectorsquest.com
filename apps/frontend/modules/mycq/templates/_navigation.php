<?php
/**
 * @var $collector Collector
 * @var $sf_params sfParameterHolder
 */
?>

<div style="margin: 20px; margin-top: 0;">
  <?php cq_page_title($collector->getDisplayName() ."'s Profile", link_to('log off &raquo;', '@logout')); ?>

  <div id="profile-subnavbar" class="navbar">
    <div class="navbar-inner">
      <div class="container">
        <div class="nav-collapse">
          <ul class="nav">
            <?php
              $active = in_array($sf_params->get('action'), array('index')) ? 'active' : null;
              echo '<li class="'. $active .'">', link_to('Home', '@mycq'), '</li>';
            ?>
            <?php
              $active = in_array($sf_params->get('action'), array('profile')) ? 'active' : null;
              echo '<li class="'. $active .'">', link_to('Profile', '@mycq_profile'), '</li>';
            ?>
            <?php $active = in_array($sf_params->get('module'), array('messages')) ? 'active' : null; ?>
            <li class="dropdown <?= $active ?>">
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">Messages (2) <b class="caret"></b></a>
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
              $active = in_array($sf_params->get('action'), array('collections')) ? 'active' : null;
              echo '<li class="'. $active .'">', link_to('Collections', '@mycq_collections'), '</li>';
            ?>
            <?php
              $active = in_array($sf_params->get('action'), array('marketplace')) ? 'active' : null;
              echo '<li class="'. $active .'">', link_to('Store', '@mycq_marketplace'), '</li>';
            ?>
            <?php
              $active = in_array($sf_params->get('action'), array('wanted')) ? 'active' : null;
              echo '<li class="'. $active .'">', link_to('Wanted', '@mycq_wanted'), '</li>';
            ?>
          </ul>
        </div><!-- /.nav-collapse -->
      </div>
    </div><!-- /navbar-inner -->
  </div>
</div>
