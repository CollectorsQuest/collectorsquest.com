<?php

/**
 * SellerPromotion form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors Quest, Inc.
 */
class SellerPromotionForm extends BaseSellerPromotionForm
{
  public function configure()
  {
    $this->useFields(array(
      'promotion_name', 'promotion_code', 'amount_type', 'promotion_desc',
      'amount', 'quantity', 'collectible_id',
    ));

    $this->widgetSchema['promotion_code']->setAttribute('maxlength', 8);
    $this->validatorSchema['promotion_code']->setOption('max_length', 8);

    $this->validatorSchema['amount'] = new cqValidatorPrice(
    array('required' => false,  'scale' => 3),
      array(
        'invalid' => 'Invalid value',
        'required' => 'This field is required'
      )
    );

    $this->widgetSchema['collectible_id']->setOption('add_empty', 'All Collectibles');
    /* @var $q CollectibleQuery */
    $q = CollectibleQuery::create()
      ->filterByCollector($this->getObject()->getCollectorRelatedBySellerId())
      ->filterByIsPublic(true)
      ->useCollectibleForSaleQuery()
      ->filterByIsSold(false)
      ->endUse();
    $this->widgetSchema['collectible_id']->setOption('criteria', $q);
    $this->validatorSchema['collectible_id']->setOption('criteria', $q);

    $this->widgetSchema['collector_email'] = new sfWidgetFormInput();
    $this->validatorSchema['collector_email'] = new sfValidatorPropelChoice(
      array('model' => 'Collector', 'column' => 'email', 'required' => false)
    );

    $this->widgetSchema['expire_days'] = new sfWidgetFormChoice(
      array('choices' => array('' => 'Unlimited', 1 => 1, 7 => 7, 30 => 30))
    );
    $this->validatorSchema['expire_days'] = new sfValidatorChoice(
      array('choices' => array('', 1, 7, 30), 'required' => false)
    );

    $this->validatorSchema['promotion_name']->setOption('required', 'required');
    $this->widgetSchema->setLabels(array(
      'promotion_name' => 'Name',
      'promotion_code' => 'Code',
      'amount_type' => 'Amount Type',
      'amount' => 'Amount',
      'collectible_id' => 'Collectible',
      'quantity' => 'Propositions quantity',
      'collector_email' => 'Collector email',
    ));

    $this->widgetSchema->setHelps(array(
      'quantity' => 'Set 0 for unlimited',
      'collector_id' => 'Promotion code will work only for user with this email',
    ));

    // add a pre validator
    $this->validatorSchema->setPreValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateAmount')))
    );

    // add a post validator
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateCode')))
    );

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function updateExpireDaysColumn($v)
  {
    if (0 != (int) $v)
    {
      $dt = new DateTime();
      $dt->add(new DateInterval(sprintf('P%sD', (int) $v)));
      $this->getObject()->setExpiryDate($dt);
    }
    else
    {
      $this->getObject()->setExpiryDate(null);
    }
  }

  public function updateCollectorEmailColumn($v)
  {
    $collector = null;
    if ($v)
    {
      $collector = CollectorQuery::create()->findOneByEmail($v);
      // We can send email notice to collector
    }
    $this->getObject()->setCollectorRelatedByCollectorId($collector);
  }

  public function validateAmount($validator, $values)
  {
    // Validate amount and clean up values
    if ($values['amount_type'] != 'Free Shipping')
    {
      $this->validatorSchema['amount']->setOption('required', true);
      $this->validatorSchema['amount']->setOption('min', 1);
      $this->validatorSchema['amount']->setMessage('min', 'Must be at least 0.01.');
    }
    else
    {
      $values['amount'] = 0;
    }
    if ($values['collectible_id'])
    {
      $values['expire_days'] = null;
      $values['quantity'] = null;
    }

    return $values;
  }

  public function validateCode($validator, $values)
  {
    $q = SellerPromotionQuery::create()
      ->filterByCollectorRelatedBySellerId($this->getObject()->getCollectorRelatedBySellerId())
      ->add(SellerPromotionPeer::PROMOTION_CODE, $values['promotion_code']);

    if ($q->count())
    {
      throw new sfValidatorError(
        $validator, sprintf('Sorry, you already have promotion with code "%s"', $values['promotion_code'])
      );
    }
    return $values;
  }
}
