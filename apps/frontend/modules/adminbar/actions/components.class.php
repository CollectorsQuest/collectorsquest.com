<?php

class adminbarComponents extends cqFrontendComponents
{
  public function executeAdminBar()
  {
    //TO DO move AdminBar from global in next commit
  }
  public function executeRateMenuItem()
  {
    $this->label = sfToolkit::pregtr($this->class, array('/([A-Z]+)([A-Z][a-z])/' => '\\1 \\2',
                                                         '/([a-z\d])([A-Z])/'     => '\\1 \\2'));
  }

  public function executeRateObject()
  {
    // TO DO This component should work with any objects
    // For now it works only with Collectible

    /* @var $object Collectible */
    $object = $this->object;

    $this->class = get_class($object);
    $this->id = $object->getId();

    $c = new Criteria();
    $c->add(CollectibleRatePeer::COLLECTOR_ID, $this->getUser()->getCollector()->getId());
    /** @var $objRates CollectibleRate[] */
    $objRates = $object->getCollectibleRates($c);
    $temp = array();
    //resort by Dimension
    foreach ($objRates as $rate)
    {
      $temp[$rate->getDimension()] = $rate;
    }
    $objRates = $temp;
    unset($temp);

    $forms = array();
    foreach (CollectibleRatePeer::getDimensions() as $key => $label)
    {
      if (isset($objRates[$key]))
      {
        $rate = $objRates[$key];
      }
      else
      {
        $rate = new CollectibleRate();
        $rate
          ->setCollector($this->getUser()->getCollector())
          ->setCollectibleId($object->getId())
          ->setDimension($key);
      }
      $forms[$key] = new CollectibleRateForm($rate);
    }

    $this->forms = $forms;
  }
}
