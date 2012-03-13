<!DOCTYPE html>
<!--[if IE]><![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<?php include_partial('global/head'); ?>
<body>
  <!-- Include the VML behavior -->
  <style>v\:image {
    behavior: url(#default#VML);
    display: inline-block
  }</style>
  <!-- Declare the VML namespace -->
  <xml:namespace ns="urn:schemas-microsoft-com:vml" prefix="v"/>

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

  <?php echo $sf_content ?>
</body>
</html>
