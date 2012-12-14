<?php /** @var $collector Collector */ ?>

<div id="collector_<?= $collector->getId(); ?>_grid_view"
     data-id="<?= $collector->getId(); ?>" class="collector_grid_view span6 brick">

  <div class="row-fluid profile-info link">
    <div class="span3">
      <?php
        echo link_to_collector($collector, 'image', array(
            'image_tag' => array('max_width' => 64, 'max_height' => '64')
        ));
      ?>
    </div>
    <div class="span9">
      <h2 class="spacer-bottom-5">
        <?php
          echo link_to_collector($collector, 'text', array(
            'link_to' => array('class' => 'target')
          ));
        ?>
      </h2>
      <ul style="list-style: none; margin-left: 0;">
        <li>
        <?php
          echo sprintf(
            'Is %s %s collector',
            in_array(strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'an' : 'a',
            '<strong>'. $collector->getCollectorType() .'</strong>'
          );
        ?>
        </li>
        <?php if ($country_iso3166 = $collector->getProfile()->getCountryIso3166()): ?>
        <li>
          Is from <?= ($country_iso3166 == 'US') ? 'the United States' : $collector->getProfile()->getCountryName(); ?>
        </li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="span12 about">
    <?php
      if (!empty($excerpt) && trim($excerpt) !== '.')
      {
        echo $excerpt;
      }
      else if ($collector->getProfile()->getAboutMe())
      {
        echo cqStatic::truncateText(strip_tags($collector->getProfile()->getAboutMe()), 80, '...', true);
      }
    ?>
    </div>
  </div>
</div>
