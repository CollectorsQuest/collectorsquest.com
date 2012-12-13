<header>

  <div class="header-inner spacer-bottom-20">
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
    <div class="row-fluid">
      <div class="span4 right-section-header">
        &nbsp;
      </div>

      <div class="span8 search-header">
        <div class="input-append pull-right">
          &nbsp;
        </div>
      </div>
    </div>
  </div><!-- /navbar-inner -->

  <div class="navbar">
    <div class="navbar-inner">
      <div class="container dark-bg">
        <ul class="nav"></ul>
      </div>
    </div>
  </div>
</header>
