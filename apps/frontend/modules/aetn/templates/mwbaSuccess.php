<div class="mwba-wrapper">
  <div class="top-banner"></div>

    <div class="center-graphic">
      <?= image_tag('frontend/misc/mwba/mwba-center-graphic.png', array('class' => 'spacer-top-20', 'usemap' => '#Map')); ?>
      <map name="Map" id="Map">
        <area shape="poly" id="map-petroliana"
         coords="41,281,94,266,116,244,115,227,94,191,115,132,151,107,200,107,229,144,219,201,199,268,237,303,304,394,226,401,205,378,126,381,64,345,38,311"
         href="<?= url_for('@aetn_mwba_petroliana', true); ?>" alt="John D. Rockefeller" title="John D. Rockefeller"
        />
        <area shape="poly" id="map-rooseveltiana"
          coords="310,394,237,300,293,278,313,254,294,184,307,129,349,110,395,123,415,158,411,225,400,267,479,304,430,323,397,396,354,407"
          href="<?= url_for('@aetn_mwba_rooseveltiana', true); ?>" alt="Theodore Roosevelt" title="Theodore Roosevelt"
        />
        <area shape="poly" id="map-railroadiana"
          coords="405,395,433,329,483,309,516,286,491,188,535,111,617,137,639,193,613,266,633,300,660,327,609,373,535,384,503,376,491,401"
          href="<?= url_for('@aetn_mwba_railroadiana', true); ?>" alt="Cornelius Vanderbilt" title="Cornelius Vanderbilt"
        />
      </map>
    </div>

  <div class="labels-ribbons">
    <a href="<?= url_for('@aetn_mwba_petroliana', true); ?>" class="rockefeller-button"></a>
    <a href="<?= url_for('@aetn_mwba_rooseveltiana', true); ?>" class="roosevelt-button"></a>
    <a href="<?= url_for('@aetn_mwba_railroadiana', true); ?>" class="vanderbilt-button"></a>
  </div>

  <div class="banner">
    <?php
      echo link_to(cq_image_tag(
        'headlines/history-banner-mwba-landing-page-10-16.jpg',
        array('alt_title' => 'The Men Who Built America')
      ), 'http://www.history.com/shows/men-who-built-america', array('target' => '_blank'));
    ?>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $("#map-petroliana").hover(function() {
    $('.rockefeller-button').toggleClass('over');
  });
  $("#map-rooseveltiana").hover(function() {
      $('.roosevelt-button').toggleClass('over');
  });
  $("#map-railroadiana").hover(function() {
      $('.vanderbilt-button').toggleClass('over');
  });
});
</script>
