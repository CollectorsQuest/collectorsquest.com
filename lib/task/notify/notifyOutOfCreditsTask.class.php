<?php

/**
 * Notify collectors that they ran out of credits 1 week after.
 * Second reminder 2 weeks after, offering a 20% discount.
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class notifyOutOfCreditsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->namespace        = 'notify';
    $this->name             = 'out-of-credits';
    $this->briefDescription = 'Notifies sellers that they have ran out of credits';
    $this->detailedDescription = <<<EOF
The [notify:out-of-credits|INFO] task notifies collectors that they are out of credits
and advices them to buy more. The second notification includes a 20% discount
code.

Call it with:

  [php symfony notify:out-of-credits|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $_SERVER['HTTP_HOST'] = sfConfig::get('app_www_domain');

    cqContext::createInstance($this->configuration);

    $databaseManager = new sfDatabaseManager($this->configuration);
    /* @var $con PropelPDO */
    $con = Propel::getConnection();

    $this->notifyByTime('-1 week', $with_discount = false, $con);
    $this->notifyByTime('-2 weeks', $with_discount = true, $con);
  }

  /**
   * @param  mixed $time strtotime compatible string or a unix timestamp
   * @param  boolean $with_discount should the notification include a discount code
   * @param  PropelPDO $con
   */
  public function notifyByTime($time, $with_discount, PropelPDO $con = null)
  {
    $this->log('Searching for sellers that ran out of credits: ' . (string) $time);

    $cqEmail = new cqEmail($this->getMailer());
    $finder = new FindsOutOfCreditsCollectors();
    $discounter = new CollectorPromotionGenerator();

    $date_ran_out = new DateTime($time);
    $ran_outs = $finder->findRanOutOn($date_ran_out, $con);

    $this->log(sprintf('Found %d collectors to notify', count($ran_outs)));

    foreach ($ran_outs as $collector)
    {
      /* @var $collector Collector */

      $this->log('Sending email to: '. $collector->getEmail());

      if ($with_discount)
      {
        $promo = $discounter->generateAndSave(
            $collector, PromotionPeer::AMOUNT_TYPE_PERCENTAGE, 20
        );
      }
      else
      {
        $promo = false;
      }

      $cqEmail->send('Notify/ran_out_of_credits', array(
          'to' => $collector->getEmail(),
          'params' => array(
              'oSeller' => $collector->getSeller($con),
              'oRanOutDate' => $date_ran_out,
              'sDiscountCode' => $promo ? $promo->getPromotionCode() : null,
          ),
      ));
    }
  }

}
