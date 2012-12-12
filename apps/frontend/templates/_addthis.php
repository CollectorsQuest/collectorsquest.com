<?php
/* @var $image string */
/* @var $text string */
/* @var $url string */

// By default show all the sharing providers
$providers = isset($providers) ? $providers : array('email', 'pinterest', 'twitter', 'google+', 'facebook');

$image = isset($image) ? $image : null;
$text = isset($text) ? $text : 'Hey, look what I found';
$url = isset($url) ? $url : null;
?>

<!-- AddThis Button BEGIN -->
<?php foreach ($providers as $provider): ?>

  <?php // We need this "dummy" div to avoid addthis javascript errors ?>
  <a class="addthis_button_dummy" style="display: none;"></a>

  <?php if ($provider === 'email'): ?>
  <a class="btn-lightblue btn-mini-social addthis_button_email">
    <i class="mail-icon-mini"></i> Email
  </a>
  <?php endif; ?>

  <?php if ($provider === 'pinterest'): ?>
  <a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"
     pi:pinit:media="<?= $image ?>" pi:pinit:url="<?= $url ?>"
     pi:pinit:description="<?= addcslashes($text, '"') ?> on Collectors Quest"></a>
  <?php endif; ?>

  <?php if ($provider === 'twitter'): ?>
  <a class="addthis_button_tweet" tw:twitter:data-count="none"
     tw:url="<?= $url ?>" tw:text="<?= addcslashes($text, '"') ?> on #CollectorsQuest"></a>
  <?php endif; ?>

  <?php if ($provider === 'google+'): ?>
  <a class="addthis_button_google_plusone" g:plusone:size="medium"
     addthis:url="<?= $url ?>" addthis:title="<?= addcslashes($text, '"') ?>"
     g:plusone:annotation="none" g:plusone:count="false" g:plusone:expandTo="bottom,left,top,right"></a>
  <?php endif; ?>

  <?php if ($provider === 'facebook'): ?>
  <a class="addthis_button_facebook_like" fb:like:layout="button_count"
     fb:like:width="75" fb:like:href="<?= $url; ?>"></a>
  <?php endif; ?>
<?php endforeach; ?>
<!-- AddThis Button END -->
