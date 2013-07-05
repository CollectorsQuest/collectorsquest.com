<?php

/**
 * Description of ExpiringCollectibleForSaleNotifier
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class ExpiringCollectibleForSaleNotifier
{
    protected $cqEmail;

    public function __construct(cqEmail $cqEmail)
    {
        $this->cqEmail = $cqEmail;
    }

    public function notify($cutoff_date = '+1 week')
    {
        $expires = $this->getExpiringIn($cutoff_date);

        $this->sendNotificationEmails($expires);
    }

    public function sendNotificationEmails($expires)
    {
        foreach ($expires as $expire) {
            $this->cqEmail->send('Notification/expiring_collectible', array(
                'to' => $expire->collector->getEmail(),
                'params' => array(
                    'oCollector' => $expire['collector'],
                    'aCollectibles' => $expire['collectibles'],
                ),
            ));
        }
    }

    /**
     * @param   string $cutoff_date strtotime
     * @return  array
     */
    public function getExpiringIn($cutoff_date = '+1 week', PropelPDO $con = null)
    {
        $ret = array();
        $expiring_collectible_ids = PackageTransactionCreditQuery::create()
            ->filterByExpiryDate(array(
                'min' => date('Y-m-d 00:00:00', strtotime($cutoff_date)),
                'max' => date('Y-m-d 23:59:59', strtotime($cutoff_date)),
            ))
            ->select('CollectibleId')
            ->find($con)->toArray();

        $collectibles = CollectibleForSaleQuery::create()
            ->isForSale()
            ->filterByIsSold(false)
            ->findPks($expiring_collectible_ids, $con);

        foreach ($collectibles as $collectible) {
            /* @var $collectible Collectible */

            if (!isset($ret[$collectible->getCollectorId()])) {
                $ret[$collectible->getCollectorId()] = array(
                    'collector' => $collectible->getCollector($con),
                    'collectibles' => array()
                );

            }

            $ret[$collectible->getCollectorId()]['collectibles'][] = $collectible;
        }

        return $ret;
    }

}
