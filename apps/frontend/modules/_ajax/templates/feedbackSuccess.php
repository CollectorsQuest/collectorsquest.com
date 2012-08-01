<?php
/**
 * @var $form CollectionCreateForm
 * @var $collection CollectorCollection
 */
?>

<h1>Feedback</h1>

<?php
  $success = $sf_user->hasFlash('success_ajax');
  include_partial('global/flash_messages');
?>
<?php if ($success):?>
<script>
window.setTimeout(closeModal, 5000);

function closeModal()
{
  $('.modal a.close').click();
}
</script>
<?php 
  return;
  endif; 
?>

<form action="<?= url_for('@ajax_feedback')?>"
      method="post" id="form-create-collection" class="ajax form-horizontal form-modal">

  <?=  $form ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">
      Send Feedback
    </button>
  </div>

</form>

<script>
$(document).ready(function()
{
  // Screen Res
  if (self.screen)
  {
    S_RESOLUTION = screen.width + ' x ' + screen.height;
    S_COLOR_DEPTH = screen.colorDepth + ' bit';
  }
  else if (self.java)
  {
    var javaobj = java.awt.Toolkit.getDefaultToolkit();
    var screenobj = javaobj.getScreenSize();

    S_RESOLUTION = screenobj.width + ' x ' + screenobj.height;

    if (self.screen)
    {
      S_COLOR_DEPTH = screen.colorDepth + ' bit';
    }
  }

  // Browser size
  var bsw = '';
  var bsh = '';

  if (window.innerWidth)
  {
    bsw = window.innerWidth;
    bsh = window.innerHeight;
  }
  else if (document.documentElement)
  {
    bsw = document.documentElement.clientWidth;
    bsh = document.documentElement.clientHeight;
  }
  else if (document.body)
  {
    bsw = document.body.clientWidth;
    bsh = document.body.clientHeight;
  }
  if (bsw != '' && bsh != '')
  {
    S_BROWSER_SIZE = bsw + ' x ' + bsh;
  }

  // Browser Type
  var browser = $.browser.name + " " + $.browser.version;
  S_BROWSER_TYPE = browser;

  S_BROWSER_TYPE = S_BROWSER_TYPE.replace("msie", "Internet Explorer");

  if (S_BROWSER_TYPE.length > 0)
  {
    S_BROWSER_TYPE = S_BROWSER_TYPE.substring(0, 1).toUpperCase() + S_BROWSER_TYPE.substring(1, S_BROWSER_TYPE.length);
  }

  S_BROWSER_VERSION = "";

  $("#feedback_f_javascript_enabled").val("1");
  $("#feedback_f_browser_type").val(S_BROWSER_TYPE);
  $("#feedback_f_browser_color_depth").val(S_COLOR_DEPTH);
  $("#feedback_f_resolution").val(S_RESOLUTION);
  $("#feedback_f_browser_size").val(S_BROWSER_SIZE);
});
</script>
