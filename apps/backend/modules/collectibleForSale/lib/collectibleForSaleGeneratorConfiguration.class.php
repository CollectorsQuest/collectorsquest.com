<?php

/**
 * collectibleForSale module configuration.
 *
 * @package    CollectorsQuest
 * @subpackage collectibleForSale
 * @author     Kiril Angov
 * @version    SVN: $Id: collectibleForSaleGeneratorConfiguration.class.php 2177 2011-06-19 21:37:36Z yanko $
 */
class collectibleForSaleGeneratorConfiguration extends BaseCollectibleForSaleGeneratorConfiguration
{
  public function getDefaultSort()
  {
    return array('created_at', 'desc');
  }

}
