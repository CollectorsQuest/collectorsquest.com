<?php


/**
 * Notify seller that an unsold item is about to expire 1 week before and on the day
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class notifyExpiringItemsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->namespace        = 'notify';
    $this->name             = 'expiring-items';
    $this->briefDescription = 'Notifies sellers that they have expiring items';
    $this->detailedDescription = <<<EOF
The [notify:expiring-items|INFO] task notifies collectors of expiring items

Call it with:

  [php symfony notify:expiring-items|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $_SERVER['HTTP_HOST'] = sfConfig::get('app_www_domain');

    cqContext::createInstance($this->configuration);

    $databaseManager = new sfDatabaseManager($this->configuration);
    /* @var $con PropelPDO */
    $con = Propel::getConnection();

    $this->notifyByTime('+1 week', $con);
    $this->notifyByTime('today', $con);
  }

  /**
   * @param  type $time
   * @param  PropelPDO $con
   */
  public function notifyByTime($time, PropelPDO $con = null)
  {
    $this->log('Searching for items expiring in: ' . (string) $time);

    $cqEmail = new cqEmail($this->getMailer());
    $finder = new FindsExpiringCollectibles();
    $now = new DateTime();

    $expires = $finder->findExpiringOn(new DateTime($time), $con);

    $this->log(sprintf('Found %d collectors to notify', count($expires)));

    foreach ($expires as $holder)
    {
      $this->log('Sending email to: '. $holder->getCollector()->getEmail());
      /* @var $holder ExpiringCollectiblesHolder */

      $cqEmail->send('Notify/expiring_collectibles', array(
          'to' => $holder->getCollector()->getEmail(),
          'params' => array(
              'oSeller' => $holder->getCollector()->getSeller($con),
              'oExpireDate' => $holder->getExpireDate(),
              'oCollectiblesHolder' => $holder,
              // diff between 2 DateTime obj, formatted to "a" (total number of days)
              'bExpiresToday' => 0 == $holder->getExpireDate()->diff($now)->format('%a'),
          ),
      ));
    }
  }

}
