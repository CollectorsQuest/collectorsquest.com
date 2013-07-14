<?php

/**
 * Description of CollectorPromotionGenerator
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class CollectorPromotionGenerator
{
    /**
     * @param   Collector $collector
     * @param   PromotionPeer::AMOUNT_TYPE $type
     * @param   float $amount
     *
     * @return  Promotion
     */
    public function generate(Collector $collector, $type, $amount)
    {
        $this->validateType($type);
        $this->validateAmount($amount);

        $promo = new Promotion();
        $promo->setAmountType($type);
        $promo->setAmount($amount);

        $promo->setPromotionName(
            $this->generatePromotionName($collector, $type, $amount)
        );

        $promo->setPromotionCode(
            $this->generatePromotionCode($collector, $type, $amount)
        );

        $promo->setExpiryDate(new DateTime('+1 month'));
        $promo->setNoOfTimeUsed(1);

        return $promo;
    }

    /**
     * @param   Collector $collector
     * @param   PromotionPeer::AMOUNT_TYPE $type
     * @param   float $amount
     * @param   PropelPDO $con
     *
     * @return  Promotion
     */
    public function generateAndSave(
        Collector $collector,
        $type,
        $amount,
        PropelPDO $con = null
    ) {
        $promo = $this->generate($collector, $type, $amount);
        $promo->save($con);

        return $promo;
    }

    /**
     * @param   Collector $collector
     * @param   PromotionPeer::AMOUNT_TYPE $type
     * @param   $amount
     * @param   DateTime $now
     *
     * @return  string
     */
    public function generatePromotionName(
        Collector $collector,
        $type,
        $amount,
        DateTime $now = null)
    {
        $this->validateType($type);
        $this->validateAmount($amount);

        $time = $now ?: new DateTime();

        if (PromotionPeer::AMOUNT_TYPE_FIX == $type) {
            $promo_value = is_float($amount) ? number_format($amount, 2) : $amount;
            $promo_value .= '$';
        } else {
            $promo_value = $amount.'%';
        }

        $return = sprintf('%s - %s off [%s]',
            $collector->getDisplayName(),
            $promo_value,
            $time->format('Y-m-d')
        );

        return $return;
    }

    /**
     * @param   Collector $collector
     * @param   PromotionPEER::AMOUNT_TYPE $type
     * @param   float $amount
     * @param   DateTime $now
     *
     * @return  string
     */
    public function generatePromotionCode(
        Collector $collector,
        $type,
        $amount,
        DateTime $now = null
    ) {
        $this->validateType($type);
        $this->validateAmount($amount);

        $time = $now ?: new DateTime();

        if (PromotionPeer::AMOUNT_TYPE_FIX == $type) {
            $promo_value = is_float($amount) ? number_format($amount, 2) : $amount;
            $promo_value .= '$';
        } else {
            $promo_value = $amount.'%';
        }

        $hash_string = sprintf('%d-%s', $collector->getId(), $time->format('Y-m-d'));

        $return = sprintf('CQ-%sOFF-%08X',
            $promo_value,
            crc32($hash_string)
        );

        return $return;
    }


    /**
     * @param   PromotionPeer::AMOUNT_TYPE $type
     * @throws  InvalidArgumentException
     */
    protected function validateType($type)
    {
        if (!in_array($type, PromotionPeer::getValueSet(PromotionPeer::AMOUNT_TYPE))) {
            throw new InvalidArgumentException('You must supply a valid Promotion Amount Type');
        }
    }

    /**
     * @param   PromotionPeer::AMOUNT_TYPE $amount
     * @throws  InvalidArgumentException
     */
    protected function validateAmount($amount)
    {
        if (!is_numeric($amount)) {
            throw new InvalidArgumentException('You must supply a numeric value for Promotion Amount');
        }
    }
}
