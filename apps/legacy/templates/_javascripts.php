<?php
  if ($sf_user->isAuthenticated())
  {
    use_javascript('tiny_mce/jquery.tinymce.js');
    use_javascript('jquery/autogrow.js');
    use_javascript('jquery/editable.js');
    use_javascript('jquery/rotate.js');
    use_javascript('jquery/uploadify.js');
    use_javascript('swfobject.js');

    include_partial('global/authenticated_js');
  }

  sfConfig::set('symfony.asset.javascripts_included', true);
  $js = @implode(',', array_keys($sf_response->getJavascripts()));

  if (!empty($js))
  {
    echo '<script type="text/javascript" src="/combine.php?type=javascript&files='. $js .'&revision='. SVN_REVISION .'"></script>';
  }
?>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  // Removing all hide-fouc classes which are to prevent flash of unstyled content
  $('.hide-fouc').removeClass('hide-fouc');

  // Initializing the menu...
  $("ul.sf-menu").superfish(
  {
    delay: 1000,
    speed: 'fast',
    autoArrows: false,
    dropShadows: false
  });

  $('<img>').attr('src', '/images/legacy/logo-highlighted.png');
  $('img#header-logo')
    .mouseover(function() { $(this).attr('src', '/images/legacy/logo-highlighted.png'); })
    .mouseout(function() { $(this).attr('src', '/images/legacy/logo.png'); });

  // all hover and click logic for buttons
  $(".fg-button")
  .hover(
    function()
    {
      $(this).addClass("ui-state-hover");
    },
    function()
    {
      $(this).removeClass("ui-state-hover");
    }
  )
  .mousedown(function()
  {
    $(this).parents('.fg-buttonset-single:first').find(".fg-button.ui-state-active").removeClass("ui-state-active");
    if($(this).is('.ui-state-active.fg-button-toggleable, .fg-buttonset-multi .ui-state-active'))
    {
      $(this).removeClass("ui-state-active");
    }
    else
    {
      $(this).addClass("ui-state-active");
    }
  })
  .mouseup(function()
  {
    if(! $(this).is('.fg-button-toggleable, .fg-buttonset-single .fg-button,  .fg-buttonset-multi .fg-button') )
    {
      $(this).removeClass("ui-state-active");
    }
  });
});

function cq_not_implemented_yet()
{
  alert('<?php echo __("We are sorry but this feature is not yet available. We are working on the final set of missing functonality.\\n\\nThank you for the understanding. (the CollectorsQuest.com team)"); ?>');

  return true;
}

function ajax_load(target, url)
{
  var $target = $(target);

  if ($target && url)
  {
    var pos = $target.offset();

    $('#loading').css(
    {
      "left": pos.left +"px",
      "top":  pos.top  +"px"
    });

    $('#loading').height($target.height() + 10);
    $('#loading').width($target.width());
    $('#loading').show();

    $target.load(url, function()
    {
      $('#loading').hide();
    });

    return true;
  }

  return false;
}

</script>
<?php cq_end_javascript_tag(); ?>

<?php
  // Echo all the javascript for the page
  cq_echo_javascripts();

  // Include analytics code only in production
  if (SF_ENV == 'prod')
  {
    include_partial('global/analytics_js');
  }
?>
