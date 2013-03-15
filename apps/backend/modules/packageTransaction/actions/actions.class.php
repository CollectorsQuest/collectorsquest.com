<?php

require_once dirname(__FILE__).'/../lib/packageTransactionGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/packageTransactionGeneratorHelper.class.php';

/**
 * packageTransaction actions.
 *
 * @package    collectornew
 * @subpackage packageTransaction
 * @author     Prakash Panchal
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class packageTransactionActions extends autoPackageTransactionActions
{

  /**
   * Add extra credits to the collector of the selected transaction
   * without the need to exdecute a purchase
   */
  public function executeAddExtraCredits(cqWebRequest $request)
  {
    /* @var $oldTransaction PackageTransaction */
    $oldTransaction = $this->getRoute()->getObject();
    /* @var $collector Collector */
    $collector = $oldTransaction->getCollector();

    $form = new CollectorAddCreditsForm();

    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
        $transaction = new PackageTransaction();
        $transaction->setPackageId(PackagePeer::PACKAGE_ID_ADMIN);
        $transaction->setCollectorId($collector->getId());
        //$transaction->setPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PAID);
        $transaction->setCredits($form->getValue('num_credits'));
        $transaction->setExpiryDate(strtotime('+1 year'));
        $transaction->confirmPayment(); // includes save()

        $this->getUser()->setFlash('success', sprintf(
          '%d credits added for the user %s.',
          $form->getValue('num_credits'),
          $collector
        ));

        return $this->redirect(
          'package_transaction_add_extra_credits',
          $oldTransaction
        );
      }
    }

    $this->collector = $collector;
    $this->package_transactions = PackageTransactionQuery::create()
      ->filterByCollector($collector)
      ->find();
    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeFilter(sfWebRequest $request)
  {
    $this->setPage(1);

    if ($request->hasParameter('_reset'))
    {
      $this->setFilters($this->configuration->getFilterDefaults());

      $this->redirect('@package_transaction');
    }

    $this->filters = $this->configuration->getFilterForm($this->getFilters());
    unset($this->filters['payment_status']);
    unset($this->filters['credits']);
    unset($this->filters['credits_used']);
    unset($this->filters['package_price']);
    unset($this->filters['discount']);
    unset($this->filters['promotion_transaction_id']);
    unset($this->filters['expiry_date']);

    $this->filters->bind($request->getParameter($this->filters->getName()));
    if ($this->filters->isValid())
    {
      $this->setFilters($this->filters->getValues());

      $filter_action = $request->getParameter('filter_action');
      if (array_key_exists('calculate_profit_submit', $filter_action))
      {
        $profits = $this
          ->buildQuery()
          ->withColumn('SUM(PackageTransaction.PackagePrice)', 'Profits')
          ->select('Profits')
          ->findOne();
        $this->getUser()->setFlash('notice', sprintf('Profits for the selected filters: %s', number_format($profits, 2)));
      }

      $this->redirect('@package_transaction');
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();

    $this->setTemplate('index');
  }
}
