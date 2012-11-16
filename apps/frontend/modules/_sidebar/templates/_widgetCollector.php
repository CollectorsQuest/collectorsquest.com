<?php
/**
 * @var  $collector      Collector
 * @var  $collectible    Collectible
 * @var  $collections    Collection[]
 *
 * @var  $title          string
 * @var  $has_message    boolean
 * @var  $widget_height  integer
 * @var  $height         stdClass
 * @var  $sf_user        cqFrontendUser
 */

$_height = 0;
?>

<div class="row-fluid spacer-top-20 link">
  <?php cq_sidebar_title($title, null); ?>

  <?php if (!isset($message_only)): ?>
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
          <?php
          echo sprintf(
            '%s %s %s',
            in_array(strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'An' : 'A',
            '<strong>'. $collector->getCollectorType() .'</strong>', $collector->getSeller() ? 'seller' : 'collector'
          );
          ?>
        </li>
        <?php if ($country_iso3166 = $collector->getProfile()->getCountryIso3166()): ?>
        <li>
          From <?= ($country_iso3166 == 'US') ? 'the United States' : $collector->getProfile()->getCountryName(); ?>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>

<?php $_height -= 128; ?>

<?php if (!$sf_user->isOwnerOf($collector) && isset($message) && $message === true): ?>
  <div class="row-fluid spacer">
    <div class="send-pm">
      <form action="<?= url_for2('messages_compose', array('to'=>$collector->getUsername()), true); ?>"
            method="post" class="spacer-bottom-reset" id="form-private-message">
        <?= $pm_form->renderHiddenFields(); ?>
        <textarea class="requires-login" required data-login-title="Please log in to contact this member:"
                  data-signup-title="Create an account to contact this member:" name="message[body]"
                  placeholder="Send a message to <?= $collector; ?>"></textarea>
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
  <?php $_height -= 58; ?>
<?php endif; ?>

<?php if (!empty($collections) && count($collections) > 0): ?>
  <div class="row-fluid min-height-13 spacer-top cf">
    <div class="span9 text-word-wrap">
      <?= $collector; ?>'s Collections:
    </div>
    <?php if ($collector->countFrontendCollectorCollections() > 3): ?>
    <div class="span3">
      <?= link_to('View all &raquo;', 'collections_by_collector', $collector, array('class' => 'pull-right')); ?>
    </div>
    <?php endif; ?>
  </div>
  <?php $_height -= 28; ?>

  <?php foreach ($collections as $collection): ?>
  <div class="thumbnails-box-1x4-sidebar bgyellow-border">
    <div class="inner-thumbnails-box">
    <p><?= link_to_collection($collection, 'text'); ?></p>
      <div class="thumb-container">
          <?php
            foreach ($collection->getPublicCollectionCollectibles(4) as $i => $collectible)
            {
              $options = array('width' => 60, 'height' => 60);
              echo link_to(
                image_tag_collectible($collectible, '75x75', $options),
                'collectible_by_slug', $collectible,
                array('class' => 'thumbnails60')
              );
            }
          ?>
        </div>
      </div>
  </div>
  <?php $_height -= 120; ?>
  <?php endforeach; ?>
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
