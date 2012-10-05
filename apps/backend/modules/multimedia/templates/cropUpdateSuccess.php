<?php
/* @var $multimedia iceModelMultimedia */
/* @var $model string */

/* @var cqApplicationConfiguration $configuration */
$configuration = sfProjectConfiguration::getActive();
$configuration->loadHelpers(array('cqImages'));

// @todo display all generated thumbnail


if ($model == 'Collectible')
{
  echo image_tag_multimedia($multimedia, 'thumbnail');
}
else if ($model == 'Collection')
{

}
else if ($model == 'Collector')
{

}


?>
