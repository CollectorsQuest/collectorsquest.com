<h1>Feedback for CollectorsQuest.com</h1>

<?php if ($sf_user->hasFlash('error')): ?>
  <h2 id="message">
    <strong style="font-variant: small-caps;"><?= __('Error(s):'); ?></strong>&nbsp;
    <?= has_slot('flash_error') ? get_slot('flash_error') : implode('<br/><br/>', array_filter((array) $sf_user->getFlash('error', null, true))); ?>
  </h2>
<?php elseif ($sf_user->hasFlash('success')): ?>
  <h2 id="message">
    <strong style="font-variant: small-caps;"><?= __('Success:'); ?></strong>&nbsp;
    <?= $sf_user->getFlash('success', null, true); ?>
  </h2>

   <?php return; ?>
<?php endif; ?>

<div class="clear append-bottom">&nbsp;</div>
<form action="<?php echo url_for('@feedback'); ?>" method="post" id="form_feedback">
  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'fullname', __('Full Name:')); ?>
    <div class="required"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?= cq_input_tag($form, 'fullname', array('width' => 500)); ?>
    <?= $form['fullname']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?= cq_label_for($form, 'email', __('Email:')); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?= cq_input_tag($form, 'email', array('width' => 500)); ?>
    <?= $form['email']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'message', __('Feedback:')); ?>
    <div class="required"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?php echo cq_textarea_tag($form, 'message', array('width' => 500, 'height' => 200, 'rich' => false)); ?>
    <?= $form['message']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-18" style="text-align: right;">
    <?php cq_button_submit(__('Send Feedback'), null, 'float: right;'); ?>
  </div>

  <?php echo $form['page']; ?>
  <?php echo $form['f_javascript_enabled']; ?>
  <?php echo $form['f_browser_type']; ?>
  <?php echo $form['f_browser_color_depth']; ?>
  <?php echo $form['f_resolution']; ?>
  <?php echo $form['f_browser_size']; ?>
  <?= $form['_csrf_token']; ?>
</form>

<script type="text/javascript">
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
