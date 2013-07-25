<?php

/**
 * Description of ExpiringCollectiblesHolder
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class ExpiringCollectiblesHolder extends CollectorCollectiblesHolder
{
    protected $expiry_date;

    /**
     * @param   DateTime $expiry_date
     */
    public function setExpiryDate(DateTime $expiry_date)
    {
        $this->expiry_date = $expiry_date;
    }

    /**
     * @param   mixed $format Return a formatted datetime string or DateTime object
     * @return  DateTime|string
     */
    public function getExpiryDate($format = null)
    {
        if (null === $format) {
            return $this->expiry_date;
        } elseif (null !== $this->expiry_date) {
            return $this->expiry_date->format($format);
        } else {
            return null;
        }
    }

}
