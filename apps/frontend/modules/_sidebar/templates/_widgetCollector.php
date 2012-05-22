<?php
/**
 * @var  $title  string
 * @var  $collector  Collector
 * @var  $collectible Collectible
 * @var  $collections  Collection[]
 */
?>

<?php cq_sidebar_title($title, null); ?>

<div class="row-fluid">
  <div class="span3">
    <?= link_to_collector($collector, 'image', array('width' => 60, 'height' => 60)); ?>
  </div>
  <div class="span8">
    <h4><?= link_to_collector($collector, 'text'); ?></h4>
    <ul>
      <li>
        <?php
        echo sprintf(
          '%s %s collector',
          in_array(strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'An' : 'A',
          '<strong>'. $collector->getCollectorType() .'</strong>'
        );
        ?>
      </li>
      <li>
        From <?= $collector->getProfile()->getCountry(); ?>
      </li>
    </ul>
  </div>
</div>

<?php if (isset($message) && $message === true): ?>
<?php
  $subject = null;

  if (isset($collectible))
  {
    $subject = 'Regarding your item: '. addslashes($collectible->getName());
  }
  else if (isset($collection))
  {
    $subject = 'Regarding your collection: '. addslashes($collection->getName());
  }
?>
<div class="row-fluid spacer-15">
  <div class="send-pm">
    <form action="<?= url_for2('messages_compose', array('to'=>$collector->getUsername()), true); ?>" method="post" style="margin-bottom: 0;" id="form-private-message">
      <input type="hidden" name="message[receiver]" value="<?= $collector->getUsername(); ?>">
      <input type="hidden" name="message[subject]" value="<?= $subject; ?>">
      <textarea class="requires-login" data-login-title="Please log in to contact this member:" data-signup-title="Create an account to contact this member:" name="message[body]" style="width: 97%; margin-bottom: 0;" placeholder="Send a message to <?= $collector; ?>"></textarea>
      <div class="buttons-container" id="buttons-private-message">
        <?php /* <button type="button" class="btn cancel" value="cancel">cancel</button>
         &nbsp; - or - &nbsp;
        <input type="submit" class="btn btn-lightblue-normal" value="Send the Message"> */?>
        <a href="#" class="btn btn-lightblue-normal textright">
          <i class="mail-icon-mini"></i> &nbsp;Send a message
        </a>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php if (!empty($collections) && count($collections) > 0): ?>
  <div>
    Other collections by <?= $collector; ?><br/>
    <?= link_to('View all collections &raquo;', 'collections_by_collector', $collector); ?>
  </div>

  <?php foreach ($collections as $collection): ?>
  <div style="border: 1px solid #dcd7d7; margin-top: 10px;">
    <div style="border: 1px solid #f2f1f1; padding: 10px;">
    <p><?= link_to_collection($collection, 'text'); ?></p>
    <?php
      $c = new Criteria();
      $c->setLimit(4);
      foreach ($collection->getCollectionCollectibles($c) as $i => $collectible)
      {
        $options = array('width' => 60, 'height' => 60, 'style' => 'margin-right: 12px;');

        if ($i == 3) unset($options['style']);
        echo link_to(image_tag_collectible($collectible, '75x75', $options), 'collectible_by_slug', $collectible);
      }
    ?>
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
