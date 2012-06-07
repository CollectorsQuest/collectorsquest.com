<?php if (!empty($buttons)): ?>
  <ul id="sidebar-buttons" class="buttons">
    <?php include_partial('global/li_buttons', array('buttons' => $buttons)); ?>
  </ul>
<?php endif; ?>

<div style="text-align: center;">
  <span class="st_twitter_large" displayText="Tweet"></span>
  <span class="st_facebook_large" displayText="Facebook"></span>
  <span class="st_gbuzz_large" displayText="Google Buzz"></span>
  <span class="st_email_large" displayText="Email"></span>
  <span class="st_sharethis_large" displayText="ShareThis"></span>
</div>
<div class="clear">&nbsp;</div>

<?php // include_component('_sidebar', 'widgetAmazonProducts', array('collectible' => $collectible)); ?>
<?php include_component('_sidebar', 'widgetRelatedCollections', array('collectible' => $collectible)); ?>
