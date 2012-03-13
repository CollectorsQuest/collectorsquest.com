<a class="brand" href="#"><!-- Logo -->
  <img src="/images/frontend/dev/cq-logo.png" alt="Collectors' Quest">
</a>

<div class="navbar" style="width: 450px;">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="nav-collapse collapse" style="height: 0;">
        <ul class="nav">
          <li class="active"><a href="#">HOME</a></li>
          <li><?= link_to('COLLECTIONS', 'collections/index'); ?></li>
          <li><?= link_to('NEWS', 'news/index'); ?></li>
          <li><?= link_to('VIDEO', 'video/index'); ?></li>
          <li><?= link_to('MARKET', 'marketplace/index'); ?></li>
        </ul>
      </div><!-- /.nav-collapse -->
    </div>
  </div><!-- /navbar-inner -->
</div>
