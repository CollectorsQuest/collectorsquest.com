<?php
/**
 * @var $pager cqPropelModelPager
 */

decorate_with(false);
echo $pager->asRssFeed('@collector_by_slug');
