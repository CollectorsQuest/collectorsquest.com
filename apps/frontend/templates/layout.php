<?php
/**
 * @var $sf_content string
 * @var $sf_context sfContext
 */
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<?php include_partial('global/head'); ?>
<body>
  <div class="navbar">
    <div><!--class="navbar-inner"-->
      <div class="container-fluid container-fluid-header-fix">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand" href="#"><!-- Logo -->
          <img src="/images/frontend/dev/cq-logo.png" alt="Collectors' Quest" >
        </a>
        <div class="nav-collapse">
          <!-- nav -->
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </div>

  <div class="container-fluid" style="padding-right: 340px;">
    <div class="row-fluid" style="float: left">
      <div class="span12">

        <div class="row-fluid">
          <div class="span4">
            <h2>First column</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p><a class="btn" href="#">I need more energy &raquo;</a></p>
          </div><!--/span-->
          <div class="span4">
            <h2>Second column</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p><a class="btn" href="#">More &raquo;</a></p>
          </div><!--/span-->
          <div class="span4">
            <h2>Third column</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p><a class="btn" href="#">More &raquo;</a></p>
          </div><!--/span-->
        </div><!--/row-->
        <div class="row-fluid">
          <div class="span6">
            <h2>First column</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p><a class="btn" href="#">I need more energy &raquo;</a></p>
          </div><!--/span-->
          <div class="span6">
            <h2>Second column</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p><a class="btn" href="#">More &raquo;</a></p>
          </div><!--/span-->
        </div><!--/row-->
      </div><!--/span-->
    </div><!--/row-fluid-->
    <div class="fixed-right-column">
      <div>
        <img src="/images/frontend/dev/demo-banner-300x250.png" alt="banner 300x250">
        <img src="/images/frontend/dev/demo-banner-300x250.png" alt="banner 300x250">
      </div>
    </div><!--/span-->
    <hr>

  </div><!--/.fluid-container-->
  <footer class="footer-color">
    <div class="row-fluid">
      <div class="span4">
        <h2>About Collectors’ Quest</h2>
        <p>Collectors’ Quest is an interactive community and marketplace for the passionate collector. We enable collectors to meet others who share their interests, organize and catalog their collections, as well as buy, sell or trade with others. Collectors can also watch collecting related videos and read about the latest and greatest trends in the collecting arena.</p>
        <p><a href="#">more &raquo;</a></p>
      </div><!--/span-->
      <div class="span4">
        <h2>Sign Up</h2>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        <p><a href="#">More &raquo;</a></p>
      </div><!--/span-->
      <div class="span4">
        <h2>Show Off</h2>
        <p>Share your passion with a world of interested people by organizing your collections with our easy to use tools.</p>
        <p><a href="#">More &raquo;</a></p>
        <h2>Get Paid</h2>
        <p>It’s easy to sell an item once you’re a member. Just choose “I’m a seller” during the sign up process.</p>
        <p><a href="#">More &raquo;</a></p>
        <h2>Help/FAQ</h2>
        <p>Want to know how to get more out of your membership? Watch our helpful videos today!</p>
        <p><a href="#">More &raquo;</a></p>
      </div><!--/span-->
    </div><!--/row-->
    </div><!--/span-->
  </footer>

  <div id="fb-root"></div>
  <script type="text/javascript">
    window.fbAsyncInit = function()
    {
      FB.init(
        {
          appId: '',
          channelUrl: '//<?= sfConfig::get('app_www_domain') ?>/channel.php',
          status: true, cookie: true, xfbml: true
        });
    };

    // Load the SDK Asynchronously
    (function(d)
    {
      var js, id = 'facebook-jssdk'; if (d.getElementById(id)) { return; }
      js = d.createElement('script'); js.id = id; js.async = true;
      js.src = "//connect.facebook.net/en_US/all.js";
      d.getElementsByTagName('head')[0].appendChild(js);
    }(document));
  </script>

  <?php echo $sf_content; ?>

  <?php include_partial('global/javascripts'); ?>
  <?php include_partial('global/ad_slots'); ?>

  <?php
    cqStats::timing(
      'collectorsquest.modules.'. $sf_context->getModuleName() .'.'. $sf_context->getActionName(),
      cqTimer::getInstance()->getElapsedTime()
    );
  ?>
  <!-- Page generated in <?= cqTimer::getInstance()->getElapsedTime(); ?> seconds //-->
</body>
</html>
