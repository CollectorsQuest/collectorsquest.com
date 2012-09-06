<?php

$num_direct_collections = CollectorCollectionQuery::create()
  ->filterByContentCategory($ContentCategory)
  ->count();

$num_descendant_collections = CollectorCollectionQuery::create()
  // a bit faster than passing a PropelCollection, because hidration of a single column is simpler
  ->filterByContentCategoryId(ContentCategoryQuery::create()->descendantsOf($ContentCategory)->select(array('Id'))->find()->getArrayCopy(), Criteria::IN)
  ->count();

echo sprintf('%s (%s)', $num_direct_collections, $num_descendant_collections);