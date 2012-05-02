<?php /* @var $sf_user cqFrontendUser */ ?>
<header>
  <div class="header-inner">
      <div class="row-fluid">
        <div class="span3">&nbsp;</div>
        <div class="span5">
          <div class="input-append search-header pull-right">
            <form action="<?= url_for('@search') ?>" method="get">
              <?= $form['q']->render(array('value' => $q, 'autocomplete' => 'off')); ?>
              <button class="btn btn-large" type="submit">Search</button>
            </form>
          </div>
        </div>
        <div class="span4 pull-right" style="float: right; text-align: right; padding-top: 2px;">
          <a href="<?= url_for('@shopping_cart'); ?>" class="link-cart" title="<?= (0 < $k) ? 'View your shopping cart' : 'Your shopping cart is empty!'; ?>">
            <span class="shopping-cart-inner shopping-cart">
            <?php if (0 < $k): ?>
              <span id="shopping-cart-count"><?= $k; ?></span>
            <?php else: ?>
              <span id="shopping-cart-count" class="empty-cart">0</span>
            <?php endif; ?>
            </span>
          </a>
          <span class="nav-divider"></span>
          <?php if ($sf_user->isAuthenticated()): ?>
            &nbsp;
            <div id="menu-my-account" class="btn-group dropdown" style="float: right; text-align: left; margin-top: -2px;">
              <a class="btn btn-large dropdown-toggle" data-toggle="dropdown" href="#menu-my-account">
                My Account &nbsp;<span class="caret"></span>
              </a>
              <?php if ($sf_params->get('module') === '_video'): ?>
                <ul class="dropdown-menu" style="min-width: 123px;">
                  <li>
                    <a href="<?= url_for('@collector_me'); ?>" title="Go to your CollectorsQuest.com profile!">
                      <i class="icon icon-user"></i> My Profile
                    </a>
                  </li>
                  <li class="divider"></li>
                  <li>
                    <a href="<?= url_for('@logout'); ?>" title="Log Out from your CollectorsQuest.com account!">
                      <i class="icon icon-signout"></i> Log Out
                    </a>
                  </li>
                </ul>
              <?php else: ?>
                <ul class="dropdown-menu" style="min-width: 123px;">
                  <li>
                    <a href="<?= url_for('@collector_me'); ?>" title="Go to your CollectorsQuest.com profile!">
                      <i class="icon icon-user"></i> My Profile
                    </a>
                  </li>
                  <li>
                    <a href="<?= url_for('@messages_inbox'); ?>" title="Go to your CollectorsQuest.com inbox!">
                      <i class="icon icon-envelope"></i> My Inbox
                    </a>
                  </li>
                  <li class="divider"></li>
                  <li>
                    <a href="<?= url_for('@logout'); ?>" title="Log Out from your CollectorsQuest.com account!">
                      <i class="icon icon-signout"></i> Log Out
                    </a>
                  </li>
                </ul>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <?= link_to('Log In', '@login', array('class' => 'requires-login bold-links padding-signup')); ?>
            &nbsp;or&nbsp;
            <?= link_to('&nbsp;', '@collector_signup', array('class' => 'sing-up-now-btn sing-up-now-red')); ?>
          <?php endif; ?>
        </div>
      </div>
  </div><!-- /navbar-inner -->

  <div class="navbar">
    <div class="navbar-inner">
      <div class="container dark-bg">
        <?= link_to('Collectors Quest', '@homepage', array('class' => 'cq-logo logo hide-text', 'title' => 'Home')) ?>
        <ul class="nav">
          <?php $class = in_array($sf_params->get('module'), array('collection', 'collections', 'aent')) ? 'active' : null; ?>
          <li class="<?= $class ?>"><?= link_to('Collections', '@collections'); ?></li>

          <?php $class = in_array($sf_params->get('module'), array('news', '_blog')) ? 'active' : null; ?>
          <li class="<?= $class ?>"><?= link_to('Blog', '@blog'); ?></li>

          <?php $class = in_array($sf_params->get('module'), array('_video')) ? 'active' : null; ?>
          <li class="<?= $class ?>"><?= link_to('Video', '@video'); ?></li>

          <?php $class = in_array($sf_params->get('module'), array('marketplace', 'shopping')) ? 'active' : null; ?>
          <li class="<?= $class ?>"><?= link_to('Market', '@marketplace'); ?></li>
        </ul>
      </div>
    </div>
  </div>
</header>
