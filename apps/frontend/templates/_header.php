
<header>
  <div class="header-inner">
      <div class="row-fluid">
        <div class="span3">
          &nbsp;
        </div>
        <div class="span5">
          <div class="input-append search-header">
            <form action="<?= url_for('@search') ?>" method="get">
              <input name="q" id="headerSearchInput" data-provide="typeahead" autocomplete="off" data-source='["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"]' type="text" size="16" value="<?= $sf_params->get('q'); ?>">
              <button class="btn btn-large" type="submit">Search</button>
            </form>
            <!--
               <div class="btn-group">
                <a href="#" class="btn btn-primary">Search</a>
                <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Collectibles</li>
                  <li><a href="#">Collections</li>
                  <li><a href="#">Collectors</a></li>
                  <li><a href="#">Blog post</a></li>
                  <li class="divider"></li>
                  <li><a href="#"><i class="i"></i> Advanced Search</a></li>
                </ul>
              </div>
            -->
          </div>
        </div>
        <div class="span4">
          <form class="form-inline pull-right">
            <a href="<?= url_for('@shopping_cart'); ?>" class="link_cart">
              <span class="shopping_cart_inner shopping_cart">
              <?php if (0 < $k = $sf_user->getShoppingCartCollectiblesCount()): ?>
                <span id="shopping_cart_count"><?= $k; ?></span>
              <?php else: ?>
                <span id="shopping_cart_count" class="empty_cart">0</span>
              <?php endif; ?>
              </span>
            </a>
            <span class="nav-divider"></span>
            <?= link_to('Log In', '@login', array('class' => 'bold-links padding-signup')); ?>
            &nbsp;or&nbsp;
            <?= link_to('Sign Up', '@collector_signup', array('class' => 'sing-up-now-btn sing-up-now-red')); ?>
          </form>
        </div>
      </div>
  </div><!-- /navbar-inner -->

  <div class="navbar">
    <div class="navbar-inner">
      <div class="container dark-bg">
        <a href="/" title="Home page" class="cq-logo logo hide-text">Collectors Quest</a>
        <ul class="nav pull-right">
          <li>
            <a href="<?= url_for('@homepage'); ?>" class="home-icon-pos">
              <i class="icon-home icon-white"></i>
            </a>
          </li>
          <li><?= link_to('Collections', 'collections/index'); ?></li>
          <li><?= link_to('News', 'news/index'); ?></li>
          <li><?= link_to('Video', 'video/index'); ?></li>
          <li><?= link_to('Market', 'marketplace/index'); ?></li>
        </ul>
      </div>
    </div>
  </div>
</header>
