<?php

if ($collection_category = $ContentCategory->getCollectionCategory())
{
  echo link_to($collection_category, array('sf_route' => 'collection_category_edit', 'sf_subject' => $collection_category));
}