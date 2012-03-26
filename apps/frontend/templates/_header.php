
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
              <button class="btn btn-large" type="submit">Search</button>
            </form>
          </div>
        </div>
        <div class="span4">
          <button class="btn btn-large btn-primary">Primary action</button>
        </div>
      </div>
  </div><!-- /navbar-inner -->

  <div class="navbar">
    <div class="navbar-inner">
      <div class="container dark-bg">
        <a href="/" alt="Collectors Quest" title="Home page" class="cq-logo logo ir"></a>
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
          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li class="nav-header">Nav header</li>
              <li><a href="#">Separated link</a></li>
              <li><a href="#">One more separated link</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</header>

<?php /*
<nav id="nav" role="navigation">
  <a href="/" alt="Collectors Quest" title="Home page" class="cq-logo logo ir"></a>
  <div class="menu-wrapper cf">
    <ul>
      <li>
        <a href="<?= url_for('@homepage'); ?>" style="padding: 9px 10px;">
          <i class="icon-home icon-white"></i>
        </a>
      </li>
      <li><?= link_to('Collections', 'collections/index'); ?></li>
      <li><?= link_to('News', 'news/index'); ?></li>
      <li><?= link_to('Video', 'video/index'); ?></li>
      <li><?= link_to('Market', 'marketplace/index'); ?></li>
    </ul>
  </div>
</nav>
*/?>

<script>
  $(document).ready(function()
  {
    $('.dropdown-toggle').dropdown();
  });
</script>
