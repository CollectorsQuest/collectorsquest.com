<?php

ini_set('memory_limit', '256M');

use Aws\Common\Aws;
use Aws\Common\Enum\Region;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\S3Exception;

class batchAwsProvisionTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('scale', null, sfCommandOption::PARAMETER_REQUIRED, 'The direction of scaling', 'up'),
    ));

    $this->namespace        = 'batch';
    $this->name             = 'aws-provision';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [batch:aws-provision|INFO] task manages our AWS capacity

Call it with:

  [php symfony batch:aws-provision|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // Create a service building using shared credentials for each service
    $aws = Aws::factory(array(
      'key'    => cqConfig::getCredentials('aws', 'key'),
      'secret' => cqConfig::getCredentials('aws', 'secret_key'),
      'region' => Region::US_EAST_1
    ));

    return 0;
  }
}
