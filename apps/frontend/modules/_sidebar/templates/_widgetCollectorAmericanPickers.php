<?php
/**
 * @var  $collector      Collector
 * @var  $collectible    Collectible
 *
 * @var  $title          string
 * @var  $height         stdClass
 * @var  $sf_user        cqFrontendUser
 */

$_height = 0;
?>

<div class="row-fluid collector-info-sidebar link">
  <?php cq_sidebar_title($title, null); ?>
  <div class="span3">
    <?php
      echo link_to_collector($collector, 'image', array(
        'link_to' => array('class' => 'target'),
        'image_tag' => array('max_width' => 60, 'max_height' => 60)
      ));
    ?>
  </div>
  <div class="span8">
    <ul style="list-style: none; margin-left: 5px;">
      <li>
        These unique finds were picked by Frank Fritz, host of HISTORY's <em>American Pickers</em>.
      </li>
    </ul>
  </div>
</div>

<?php $_height -= 128; ?>

<?php if (!$sf_user->isOwnerOf($collector) && isset($message) && $message === true): ?>
  <div class="row-fluid spacer">
    <div class="send-pm">
      <form action="<?= url_for2('messages_compose', array('to'=>$collector->getUsername()), true); ?>"
            method="post" class="spacer-bottom-reset" id="form-private-message">
        <?= $pm_form->renderHiddenFields(); ?>
        <textarea class="requires-login" required data-login-title="Please log in to contact American Pickers:"
                  data-signup-title="Create an account to contact American Pickers:" name="message[body]"
                  placeholder="Got a question about this item? Send a message here and the folks at HISTORY will be happy to help." style="height: 53px;"></textarea>
        <div class="buttons-container" id="buttons-private-message">
          <?php /* <button type="button" class="btn cancel" value="cancel">cancel</button>
           &nbsp; - or - &nbsp;
          <input type="submit" class="btn-lightblue-normal" value="Send the Message"> */?>
          <button type="submit" class="btn-lightblue-normal textright requires-login">
            <i class="mail-icon-mini"></i> &nbsp;Send message
          </button>
        </div>
      </form>
    </div>
  </div>
  <?php $_height -= 62 ?>
<?php endif; ?>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>

<script>
$(document).ready(function()
{
  $('textarea', '#form-private-message').focus(function()
  {
    $(this).css('height', '100px');
    $('#buttons-private-message').slideDown();
  });

  $('.cancel', '#buttons-private-message').click(function()
  {
    $('#buttons-private-message').slideUp();
    $('textarea', '#form-private-message').css('height', 'auto');
  });
});
</script>
