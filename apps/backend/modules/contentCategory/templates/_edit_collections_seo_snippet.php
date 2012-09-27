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
