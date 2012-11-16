<!-- Footer //-->

<div id='networkedblogs_nwidget_container' style='height:360px;padding-top:10px;'><div id='networkedblogs_nwidget_above'></div><div id='networkedblogs_nwidget_widget' style="border:1px solid #D1D7DF;background-color:#F5F6F9;margin:0px auto;"><div id="networkedblogs_nwidget_logo" style="padding:1px;margin:0px;background-color:#edeff4;text-align:center;height:21px;"><a href="http://www.networkedblogs.com/" target="_blank" title="NetworkedBlogs"><img style="border: none;" src="http://static.networkedblogs.com/static/images/logo_small.png" title="NetworkedBlogs"/></a></div><div id="networkedblogs_nwidget_body" style="text-align: center;"></div><div id="networkedblogs_nwidget_follow" style="padding:5px;"><a style="display:block;line-height:100%;width:90px;margin:0px auto;padding:4px 8px;text-align:center;background-color:#3b5998;border:1pxsolid #D9DFEA;border-bottom-color:#0e1f5b;border-right-color:#0e1f5b;color:#FFFFFF;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;font-size:11px;text-decoration:none;" href="http://www.networkedblogs.com/blog/collectors_quest?ahash=ccdcf553c6ff8190c53b5539a93323f8">Follow this blog</a></div></div><div id='networkedblogs_nwidget_below'></div></div><script type="text/javascript">
if(typeof(networkedblogs)=="undefined"){networkedblogs = {};networkedblogs.blogId=493033;networkedblogs.shortName="collectors_quest";}
</script><script src="http://nwidget.networkedblogs.com/getnetworkwidget?bid=493033" type="text/javascript"></script>

<?php // fix to have shadowbox and JetPack carousel work together ?>
<script type="text/javascript">
  <?php // add shadowbox to all images that are links ?>
  $('div.singular div.post a img').parent().attr('rel','shadowbox');

  <?php // all external links should not have shadowbox ?>
  $("a:not([href*='//www.collectorsquest.com/']):not([href^='/']) img").parent().removeAttr("rel");

  <?php // remove shadowbox from links that point to the blog post itself (or another blog post) ?>
  $("a[href*='//www.collectorsquest.com/blog'] img").parent().removeAttr("rel");

  <?php // remove shadowbox from links that open new browser tab ?>
  $("a[target='_blank'] img").parent().removeAttr("rel");

  <?php // remove shadowbox from galleries so only JetPack gallery appears ?>
  $('.gallery-icon a').removeAttr("rel");

  // remove upPrev box if page has not enough height
  $(document).ready(function()
  {
    if ($(document).height() < 2600)
    {
      $('#upprev_box').remove();
    }
  });
</script>
