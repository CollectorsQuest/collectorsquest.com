<br/>
<div class="clearfix sf_admin_form_row sf_admin_text sf_admin_form_field_collections_seo_snipper">
<?php
  /** @var $ContentCategory ContentCategory */
  $ContentCategory = $form->getObject();

  $default = 'Browse collectible, vintage and antique items on Collectors Quest, like these
              '. $ContentCategory->getName() .' items, posted by trusted sellers and passionate collectors!';

  include_partial(
    'global/seo_snippet',
    array(
      'title' => $ContentCategory->getSeoCollectionsTitle(),
      'href' => url_for_frontend('content_category', array('sf_subject' => $ContentCategory)),
      'description' => $ContentCategory->getSeoCollectionsDescription() ?: $default
    )
  );
?>
</div>
