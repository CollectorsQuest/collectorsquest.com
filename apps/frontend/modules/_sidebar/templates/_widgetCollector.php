<?php
/**
 * @var  $title  string
 * @var  $collector  Collector
 * @var  $collectible Collectible
 * @var  $collections  Collection[]
 */
?>

<div class="row-fluid spacer-top-20 link">
  <?php cq_sidebar_title($title, null); ?>

  <div class="span3">
    <?php
      echo link_to_collector(
        $collector, 'image', array('class' => 'target'),
        array('max_width' => 60, 'max_height' => 60)
      );
    ?>
  </div>
  <div class="span8">
    <ul style="list-style: none; margin-left: 5px;">
      <li>
        <?php
        echo sprintf(
          '%s %s collector',
          in_array(strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'An' : 'A',
          '<strong>'. $collector->getCollectorType() .'</strong>'
        );
        ?>
      </li>
      <?php if ($country_iso3166 = $collector->getProfile()->getCountryIso3166()): ?>
      <li>
        From <?= ($country_iso3166 == 'US') ? 'the United States' : $collector->getProfile()->getCountry(); ?>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</div>

<?php if (!$sf_user->isOwnerOf($collector) && isset($message) && $message === true): ?>
<div class="row-fluid spacer">
  <div class="send-pm">
    <form action="<?= url_for2('messages_compose', array('to'=>$collector->getUsername()), true); ?>" method="post" class="spacer-bottom-reset" id="form-private-message">
      <?= $pm_form->renderHiddenFields(); ?>
      <textarea class="requires-login" required data-login-title="Please log in to contact this member:" data-signup-title="Create an account to contact this member:" name="message[body]" style="width: 97%; margin-bottom: 0;" placeholder="Send a message to <?= $collector; ?>"></textarea>
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
<?php endif; ?>

<?php if (!empty($collections) && count($collections) > 0): ?>
  <div class="row-fluid min-height-13 spacer-top cf">
    <div class="span9 text-word-wrap">
      Other collections by <?= $collector; ?>
    </div>
    <div class="span3">
      <?= link_to('View all &raquo;', 'collections_by_collector', $collector, array('class' => 'pull-right')); ?>
    </div>
  </div>

  <?php foreach ($collections as $collection): ?>
  <div class="thumbnails-box-1x4-sidebar bgyellow-border">
    <div class="inner-thumbnails-box">
    <p><?= link_to_collection($collection, 'text'); ?></p>
      <div class="thumb-container">
          <?php
            $c = new Criteria();
            $c->setLimit(4);
            foreach ($collection->getCollectionCollectibles($c) as $i => $collectible)
            {
              $options = array('width' => 60, 'height' => 60);
                  echo link_to(image_tag_collectible($collectible, '75x75', $options), 'collectible_by_slug', $collectible, array('class' => 'thumbnails60'));
            }
          ?>
        </div>
      </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<script>
$(document).ready(function()
{
  $('#form-private-message textarea').focus(function()
  {
    $(this).css('height', '100px');
    $('#buttons-private-message').slideDown();
  });

  $('#buttons-private-message .cancel').click(function()
  {
    $('#buttons-private-message').slideUp();
    $('#form-private-message textarea').css('height', 'auto');
  });
});
</script>
