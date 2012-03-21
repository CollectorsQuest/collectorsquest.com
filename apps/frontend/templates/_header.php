
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
      <li>
        <a href="<?= url_for('@homepage'); ?>" style="padding: 9px 10px;">
          <i class="icon-home icon-white"></i>
        </a>
      </li>
      <li><?= link_to('Collections', 'collections/index'); ?></li>
      <li><?= link_to('News', 'news/index'); ?></li>
      <li><?= link_to('Video', 'video/index'); ?></li>
      <li><?= link_to('Market', 'marketplace/index'); ?></li>

      <!--
      <li id="nav-my-account" class="dropdown pull-right">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Kiril Angov <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </li>
      //-->
    </ul>
  </div>
</nav>

<script>
  $(document).ready(function()
  {
    $('.dropdown-toggle').dropdown();
  });
</script>
