<br/>
<div class="clearfix sf_admin_form_row sf_admin_text sf_admin_form_field_market_seo_snipper">
<?php
  /** @var $ContentCategory ContentCategory */
  $ContentCategory = $form->getObject();

  $default = 'Buy and sell antique, collectible & vintage items on Collectors Quest!
              Find '. $ContentCategory->getName() .' posted by passionate collectors and trusted sellers.';

  include_partial(
    'global/seo_snippet',
    array(
      'title' => $ContentCategory->getSeoMarketTitle(),
      'href' => url_for_frontend('content_category', array('sf_subject' => $ContentCategory)),
      'description' => $ContentCategory->getSeoMarketDescription() ?: $default
    )
  );
?>
</div>
