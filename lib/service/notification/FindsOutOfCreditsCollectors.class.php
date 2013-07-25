<?php

/**
 * Description of FindsOutOfCreditsCollectors
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class FindsOutOfCreditsCollectors
{
    /**
     * Return an array of collectors that ran out of credits on the
     * specified date
     *
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
            ->where('PackageTransaction.ExpiryDate >= DATE(NOW())')
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
            ->joinPackageTransaction()
            ->withColumn('PackageTransaction.CollectorId', 'CollectorId')
            ->withColumn('MAX(PackageTransactionCredit.CreatedAt)', 'LatestCreatedAt')
            ->groupByPackageTransactionId()
            ->groupBy('CollectorId')
            ->having('DATE(LatestCreatedAt) = ?', $date->format('Y-m-d'), PDO::PARAM_STR)
            ->select(array('PackageTransactionId', 'CollectorId', 'LatestCreatedAt'))
            ->find($con)->toKeyValue('PackageTransactionId', 'CollectorId');

        // and finally return an array of collector objects, indexed by collector id
        return CollectorQuery::create()
            ->findPks($ran_out_on_date_collector_ids, $con)
            ->getArrayCopy('Id')
        ;
    }

    /**
     * @WIP DO NOT USE
     */
    public function _findExpiredOn(DateTime $date, PropelPDO $con = null)
    {
        // first find IDs of the latest package transaction for
        // collectors that have used up all their credits,
        // but the package transaction has not yet expired
        $collector_ids = PackageTransactionQuery::create()
            ->paidFor()
            ->withColumn('MAX(PackageTransaction.Id)', 'LastTransactionId')
            ->withColumn('MAX(PackageTransaction.ExpiresOn)', 'LatestExpiresOn')
            ->having('DATE(LatestExpiresOn) = ?', $date->format('Y-m-d'), PDO::PARAM_STR)
            ->groupBy('CollectorId')
            ->select('CollectorId')
            ->find($con)
        ;

        return CollectorQuery::create()
            ->findPks($collector_ids, $con)
            ->getArrayCopy('Id')
        ;
    }

}

