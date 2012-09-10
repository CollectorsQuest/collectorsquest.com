<?php
/**
 * @var $sf_params sfParameterHolder
 * @var $sf_user cqFrontendUser
 * @var $form SearchHeaderForm
 * @var $q string
 * @var $k integer
 */
?>

<header>
  <?php
    if (has_slot('header_pushdown'))
    {
      echo '<div id="header-pushdown-ad">', get_slot('header_pushdown'), '</div>';
    }
  ?>

  <div class="header-inner">
    <div class="row-fluid">
      <div class="span2">&nbsp;</div>
      <div class="span6">
        <div class="input-append search-header pull-right">
          <form action="<?= url_for('@search', true) ?>" method="get">
            <?php
              echo $form['q']->render(array(
                'value' => $q, 'autocomplete' => 'off', 'required' => 'required',
                'placeholder' => 'Find collectibles, blog posts, videos and more...')
              );
            ?>
            <button class="btn btn-large append-search-button" type="submit">Search</button>
          </form>
        </div>
      </div>
      <div class="span4 right-section-header">

        <?php if (IceGateKeeper::open('shopping_cart')): ?>
          <a href="<?= url_for('shopping_cart', array('ref' => cq_link_ref('header')), true); ?>" class="link-cart"
             title="<?= (0 < $k) ? 'View your shopping cart' : 'Your shopping cart is empty!'; ?>">
            <span class="shopping-cart-inner shopping-cart">
            <?php if (0 < $k): ?>
              <span id="shopping-cart-count"><?= $k; ?></span>
            <?php else: ?>
              <span id="shopping-cart-count" class="empty-cart">0</span>
            <?php endif; ?>
            </span>
          </a>
          <span class="nav-divider"></span>
        <?php endif; ?>

        <?php if ($sf_user->isAuthenticated()): ?>
          &nbsp;
          <div id="menu-my-account" class="btn-group dropdown button-my-account">
            <a class="btn btn-large dropdown-toggle" data-toggle="dropdown" href="#menu-my-account">
              My Account &nbsp;<span class="caret"></span>
            </a>
            <?php if ($sf_params->get('module') === '_video'): ?>
              <ul class="dropdown-menu dd-menu-min-width">
                <li>
                  <a href="<?= url_for('mycq_profile', array('ref' => cq_link_ref('header')), true); ?>"
                     title="Manage your CollectorsQuest.com profile!">
                    <i class="icon icon-user"></i> Profile
                  </a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="http://<?= sfConfig::get('app_magnify_channel', 'video.collectorsquest.com') ?>/login/logout"
                     class="logout-link" title="Log Out from your CollectorsQuest.com account!">
                    <i class="icon icon-signout"></i> Log Out
                  </a>
                </li>
              </ul>
            <?php else: ?>
              <ul class="dropdown-menu dd-menu-min-width">
                <li>
                  <a href="<?= url_for('mycq_profile', array('ref' => cq_link_ref('header')), true); ?>"
                     title="Manage your CollectorsQuest.com profile!">
                    <i class="icon icon-user"></i> Profile
                  </a>
                </li>
                <li>
                  <a href="<?= url_for('mycq_collections', array('ref' => cq_link_ref('header')), true); ?>"
                     title="Manage your Collections!">
                    <i class="icon icon-th-large"></i> Collections
                  </a>
                </li>
                <?php if (IceGateKeeper::open('mycq_marketplace')): ?>
                <li>
                  <a href="<?= url_for('mycq_marketplace', array('ref' => cq_link_ref('header')), true); ?>"
                     title="Manage your Items for Sale!">
                    <i class="icon icon-shopping-cart"></i> My Market
                  </a>
                </li>
                <?php endif; ?>
                <li>
                  <a href="<?= url_for('messages_inbox', array('ref' => cq_link_ref('header')), true); ?>"
                     title="Read and send private messages!">
                    <i class="icon icon-envelope"></i> Messages
                  </a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="<?= url_for('logout', array('ref' => cq_link_ref('header')), true); ?>"
                     class="logout-link"
                     title="Log Out from your CollectorsQuest.com account!">
                    <i class="icon icon-signout"></i> Log Out
                  </a>
                </li>
              </ul>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <?php
            echo link_to(
              'Sign In', 'login', array('ref' => cq_link_ref('header')),
              array('class' => 'requires-login bold-links padding-signup', 'absolute' => true)
            );
          ?>
          &nbsp;or&nbsp;
          <?php
            echo link_to(
              // '&nbsp;', '@misc_guide_to_collecting',
              '&nbsp;', 'misc_guide_to_collecting', array('ref' => cq_link_ref('header')),
              array('class' => 'sign-up-now-btn sign-up-now-red', 'absolute' => true)
            );
          ?>
        <?php endif; ?>
      </div>
    </div>
  </div><!-- /navbar-inner -->

  <div class="navbar">
    <div class="navbar-inner">
      <div class="container dark-bg">
        <?php
          if (sfConfig::get('sf_environment') === 'dev')
          {
            $class = 'cq-logo logo-development';
          }
          else if (sfConfig::get('sf_environment') === 'next')
          {
            $class = 'cq-logo logo-staging';
          }
          else
          {
            $class = 'cq-logo logo';
          }

          echo link_to(
            'Collectors Quest', 'homepage',
            array('ref' => cq_link_ref('logo')),
            array('class' => $class .' hide-text', 'title' => 'Home', 'absolute' => true)
          );
        ?>
        <ul class="nav">
          <?= SmartMenu::generate('header'); ?>
        </ul>
        <div class="menu-wrapper-social-icons">
          <span class="white">Follow us:</span>
          <a href="https://www.facebook.com/pages/Collectors-Quest/119338990397"
             target="_blank" class="social-link" rel="tooltip" title="Follow us on Facebook">
            <i class="s-24-icon-facebook"></i>
          </a>
          <a href="https://twitter.com/CollectorsQuest"
             target="_blank" class="social-link" rel="tooltip" title="Follow us on Twitter">
            <i class="s-24-icon-twitter"></i>
          </a>
          <a href="https://plus.google.com/113404032517505188429"
             target="_blank" class="social-link" rel="tooltip" title="Follow us on Google+">
            <i class="s-24-icon-google"></i>
          </a>
          <a href="http://pinterest.com/CollectorsQuest"
             target="_blank" class="social-link" rel="tooltip" title="Follow us on Pinterest">
            <i class="s-24-icon-pinterest"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</header>
