<?php

/**
 * Description of FindsOutOfCreditsCollectors
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class FindsOutOfCreditsCollectors
{
    /**
     * @param   DateTime $date
     * @param   PropelPDO $con
     * @return  Collector[]
     */
    public function findRanOutOn(DateTime $date, PropelPDO $con = null)
    {
        // first find IDs of the latest package transaction for
        // collectors that have used up all their credits,
        // but the package transaction has not yet expired
        $ran_out_package_transaction_ids = PackageTransactionQuery::create()
            ->paidFor()
            ->where('PackageTransaction.ExpiryDate >= NOW()')
            ->withColumn('MAX(PackageTransaction.Id)', 'LastTransactionId')
            ->withCreditsLeftColumn()
            ->having('CreditsLeft = ?', 0, PDO::PARAM_INT)
            ->groupBy('CollectorId')
            ->select(array('CollectorId', 'LastTransactionId', 'CreditsLeft'))
            ->find($con)->toKeyValue('CollectorId', 'LastTransactionId')
        ;

        // then find the IDs of collectors who have made their last use of said
        // package on the desired date
        $ran_out_on_date_collector_ids = PackageTransactionCreditQuery::create()
            ->filterByPackageTransactionId($ran_out_package_transaction_ids)
            ->groupByPackageTransactionId()
            ->joinPackageTransaction()
            ->withColumn('PackageTransaction.CollectorId', 'CollectorId')
            ->withColumn('MAX(PackageTransactionCredit.CreatedAt)', 'LatestCreatedAt')
            ->having('DATE(LatestCreatedAt) = ?', $date->format('Y-m-d'), PDO::PARAM_STR)
            ->select(array('CollectorId', 'LatestCreatedAt'))
            ->find($con)->toKeyValue('CollectorId', 'CollectorId');

        // and finally return an array of collector objects, indexed by collector id
        return CollectorQuery::create()
            ->findPks($ran_out_on_date_collector_ids, $con)
            ->getArrayCopy('Id')
        ;
    }

}

