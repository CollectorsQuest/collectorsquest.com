<?php
  // We do not really want the comments in development as it only slows the pages down
  if (SF_ENV == 'dev') return;
?>

<?php use_javascript('jquery/insert.js'); ?>

<div id="disqus_thread" class="span-18" style="margin-left: 18px;"></div>
<noscript><a href="http://disqus.com/forums/collectorsquest/?url=ref"><?php echo __('Read the Comments'); ?></a></noscript>
<script type="text/javascript">

var disqus_shortname = "collectorsquest";
var disqus_no_style = true;
var disqus_identifier = '<?php echo @$identifier; ?>';

$(function()
{
  var replies = ['http://disqus.com/forums/', disqus_shortname, '/get_num_replies.js'].join('');
  $.insert(replies);

  var forum = 'http://disqus.com/forums/' + disqus_shortname + '/embed.js';
  $.insert(forum).ready(function()
  {
    var callback = function()
    {
      var auth = document.getElementById('dsq-authenticated');
      if (!auth) {
        return setTimeout(callback, 500);
      }
      var a = auth.getElementsByTagName('a');
      if (!a.length || !(/\/AnonymousUser\/$/).test(a[0].href)) {
        return;
      }
      document.getElementById('disqus_thread').className += ' dsq-anonymous';
    };
    setTimeout(callback, 500);
  });
});
</script>
