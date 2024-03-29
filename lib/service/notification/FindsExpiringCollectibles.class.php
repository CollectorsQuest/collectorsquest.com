<?php

/**
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class FindsExpiringCollectibles
{
    /**
     * @param   DateTime $date
     * @return  array
     */
    public function findExpiringOn(DateTime $date, PropelPDO $con = null)
    {
        $ret = array();
        $expiring_collectible_ids = PackageTransactionCreditQuery::create()
            ->filterByExpiryDate($date->format('Y-m-d'))
            ->select('CollectibleId')
            ->find($con)->toArray();

        $collectibles = CollectibleForSaleQuery::create()
            ->isForSale()
            ->filterByIsSold(false)
            ->findPks($expiring_collectible_ids, $con);

        foreach ($collectibles as $collectible_for_sale) {
            /* @var $collectible Collectible */
            $collectible = $collectible_for_sale->getCollectible();

            if (!isset($ret[$collectible->getCollectorId()])) {
                $holder = new ExpiringCollectiblesHolder(
                    $collectible->getCollector($con)
                );
                $holder->setExpiryDate($date);

                $ret[$collectible->getCollectorId()] = $holder;
            }

            $ret[$collectible->getCollectorId()][] = $collectible;
        }

        return $ret;
    }

}
