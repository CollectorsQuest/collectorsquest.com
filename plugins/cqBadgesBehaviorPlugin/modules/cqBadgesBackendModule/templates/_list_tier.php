<?php
/* @var $cqBadge cqBadge */
echo $cqBadge->getTier() == cqBadgePeer::TIER_CUSTOM
  ? sprintf('%s:%s', $cqBadge->getParentModel(), $cqBadge->getParentModelId()) : $cqBadge->getTier();