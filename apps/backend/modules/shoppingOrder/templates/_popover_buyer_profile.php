<div class="row">
  <div class="span1">
    <?= image_tag_collector($collector, '100x100'); ?>
  </div>
  <div class="span2">
    <?php
      echo sprintf(
        '%s %s collector',
        in_array(
          strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')
        ) ? 'An' : 'A',
        '<strong>'. $collector->getCollectorType() .'</strong>'
      );
    ?>
    <br />
    <?php
      if ($country_iso3166 = $collector->getProfile()->getCountryIso3166())
      {
        echo 'From ', (($country_iso3166 == 'US') ? 'the United States' : $collector->getProfile()->getCountryName());
      }
    ?>
    <?= mail_to($collector->getEmail(), $collector->getEmail()) ?><br />
  </div>
</div>
