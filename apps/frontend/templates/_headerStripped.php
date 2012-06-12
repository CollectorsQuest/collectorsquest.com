
<header>
  <div class="header-inner">
    <div class="row-fluid">
      <div class="span3">
        &nbsp;
      </div>
    </div>
  </div>

  <div class="navbar">
    <div class="navbar-inner">
      <div class="container dark-bg">
      <?php
        if (sfConfig::get('sf_environment') === 'dev') {
          $class = 'cq-logo logo-development';
        } else if (sfConfig::get('sf_environment') === 'next') {
          $class = 'cq-logo logo-staging';
        } else {
          $class = 'cq-logo logo';
        }

        echo link_to(
          'Collectors Quest', '@homepage',
          array('class' => $class .' hide-text', 'title' => 'Home', 'absolute' => true)
        );
      ?>
      </div>
    </div>
  </div>
</header>
