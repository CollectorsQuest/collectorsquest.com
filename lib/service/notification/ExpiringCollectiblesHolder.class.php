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
     * @return  DateTime
     */
    public function getExpireDate()
    {
        return $this->expire_date;
    }

}
