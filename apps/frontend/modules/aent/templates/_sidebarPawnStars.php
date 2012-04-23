<?php cq_ad_slot('300x250', 300, 250) ?>
<br>
<img src="/images/banners/040412_pawnstars_sidebar_banner_02.jpg" alt="">
<br><br>
<img src="/images/banners/040412_pawnstars_sidebar_banner_03.jpg" alt="">

<?php
$link = link_to(
  'See all video &raquo;',
  'http://'. sfConfig::get('app_magnify_channel', 'video.collectorsquest.com'),
  array('class' => 'text-v-middle link-align')
);
cq_sidebar_title('Now Playing', $link, array('left' => 8, 'right' => 4));
?>


