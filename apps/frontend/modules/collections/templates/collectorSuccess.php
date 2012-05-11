<?php
  /* @var $collector Collector */
  cq_page_title(
    sprintf('Collections by %s', $collector->getDisplayName()),
    link_to('Back to Collections &raquo;', '@collections')
  );
