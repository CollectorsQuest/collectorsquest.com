<?php
/* @var $collector Collector */
?>
<userinfo>
  <id><?= $collector->getId() ?></id>
  <handle><?= $collector->getUsername() ?></handle>
  <email><?= $collector->getEmail() ?></email>
  <name><?= $collector->getDisplayName() ?></name>
  <photo><?=src_tag_collector($collector, '100x100', array('absolute'=>true));?></photo>
</userinfo>
