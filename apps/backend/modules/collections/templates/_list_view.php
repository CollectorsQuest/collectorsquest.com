<?php /** @var Collection $collection */ ?>

<strong><?php echo $collection->getName(); ?></strong>&nbsp;<font style="color:#ccc;">(<?php echo (int) $collection->countCollectibles(); ?>)</font>&nbsp;<?php if ($collection->countCollectiblesSince('7 day ago') > 0) echo image_tag('icons/new.png'); ?>
<small>by</small> <?php echo $collection->getCollector(); ?>
