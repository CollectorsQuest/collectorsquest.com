
<header>
  <div class="header-inner">
      <div class="row-fluid">
        <div class="span3">
          &nbsp;
        </div>
        <div class="span5">
          <div class="input-append search-header">
            <form action="<?= url_for('@search') ?>" method="get">
              <input name="q" id="headerSearchInput" type="text" size="16" value="<?= $sf_params->get('q'); ?>">
              <button class="btn btn-large" type="button">Search</button>
            </form>
          </div>
        </div>
        <div class="span4">
          &nbsp;
        </div>
      </div>
  </div><!-- /navbar-inner -->
</header>

<nav id="nav" role="navigation">
  <div class="cq-logo">
    <a href="/" title="Home page"><img src="/images/frontend/logo.png"></a>
  </div>
  <div class="menu-wrapper cf">
    <ul>
      <li class="active"><a href="#">HOME</a></li>
      <li><?= link_to('COLLECTIONS', 'collections/index'); ?></li>
      <li><?= link_to('NEWS', 'news/index'); ?></li>
      <li><?= link_to('VIDEO', 'video/index'); ?></li>
      <li><?= link_to('MARKET', 'marketplace/index'); ?></li>
    </ul>
  </div>
</nav>
