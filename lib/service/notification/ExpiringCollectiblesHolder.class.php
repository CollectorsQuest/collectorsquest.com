<?php

/**
 * Description of ExpiringCollectiblesHolder
 *
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class ExpiringCollectiblesHolder extends CollectorCollectiblesHolder
{
    protected $expire_date;

    /**
     * @param   DateTime $expire_date
     */
    public function setExpireDate(DateTime $expire_date)
    {
        $this->expire_date = $expire_date;
    }

    /**
     * @param   mixed $format Return a formatted datetime string or DateTime object
     * @return  DateTime|string
     */
    public function getExpireDate($format = null)
    {
        if (null === $format) {
            return $this->expire_date;
        } elseif (null !== $this->expire_date) {
            return $this->expire_date->format($format);
        } else {
            return null;
        }
    }

}
