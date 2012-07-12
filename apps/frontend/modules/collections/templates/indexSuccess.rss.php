<?php
/**
 * @var $pager cqPropelModelPager
 */

decorate_with(false);
echo $pager->asRssFeed('@collection_by_slug');
