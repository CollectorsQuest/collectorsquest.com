<?php
/**
 * @var  $collector  Collector
 * @var  $profile    CollectorProfile
 */
?>

<div class="statistics-share-panel bottom-margin-double">
  <div class="row-fluid">
    <div class="span4">
      <ul>
        <li>
          <a href="#">XXXX Views</a>
        </li>
      </ul>
    </div>
    <div class="span8 text-right">
      <a href="#" class="btn btn-mini-share btn-lightblue">
        <i class="mail-icon-mini"></i> Mail
      </a>
      <a class="btn-mini-social" href="http://facebook.com/Collectors.Quest" target="_blank" >
        <i class="s-16-icon-facebook social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="http://twitter.com/CollectorsQuest" target="_blank" >
        <i class="s-16-icon-twitter social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="#" target="_blank" >
        <i class="s-16-icon-google social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="http://pinterest.com/CollectorsQuest" target="_blank">
        <i class="s-16-icon-pinterest social-ico-padding"></i>
      </a>
    </div>
  </div>
</div>


<?php cq_section_title('More About '. $collector->getDisplayName()); ?>
<div class="personal-info-sidebar">
  <p><?= $profile->getProperty('about.me'); ?></p>
  <p><strong>About my collections:</strong> <?= $profile->getProperty('about.collections'); ?></p>
</div>

<?php
//  include_component(
//    '_sidebar', 'widgetCollectorMostWanted',
//    array('collector' => $collector)
//  );
?>
