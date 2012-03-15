
<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <div class="row-fluid">
        <div class="span2">
          <a class="brand" href="#"><!-- Logo -->
            <img src="/images/frontend/dev/cq-logo.png" alt="Collectors' Quest">
          </a>
        </div>
        <div class="span10">
          <div class="pull-r">
            <form action="<?= url_for('@search'); ?>" method="get" class="form-search">
              <input type="text" name="q" class="input-medium search-query">
              <button type="submit" class="btn">Search</button>
            </form>
          </div>
          <div class="pull-r">
            <!-- <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a> -->
            <div class="nav-collapse collapse">
              <ul class="nav">
                <li class="active"><a href="#">HOME</a></li>
                <li><?= link_to('COLLECTIONS', 'collections/index'); ?></li>
                <li><?= link_to('NEWS', 'news/index'); ?></li>
                <li><?= link_to('VIDEO', 'video/index'); ?></li>
                <li><?= link_to('MARKET', 'marketplace/index'); ?></li>
              </ul>
            </div><!-- /.nav-collapse -->
          </div>

        </div>
      </div>



    </div>
  </div><!-- /navbar-inner -->
</div>
