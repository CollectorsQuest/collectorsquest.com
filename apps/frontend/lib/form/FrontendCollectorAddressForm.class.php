<?php

/**
 * Form class to be used for frontend collector address edit/create
 */
class FrontendCollectorAddressForm extends CollectorAddressForm
{

  public function configure()
  {
    parent::configure();

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
  }

  protected function unsetFields()
  {
    parent::unsetFields();

    unset ($this['id']);
    unset ($this['collector_id']);
    unset ($this['is_primary']);
  }

  public function getDefaults()
  {
    return array_merge(array(
        'country_iso3166' => $this->getCountryCodeFromCollector() ?: 'US',
    ), parent::getDefaults());
  }

  protected function getCountryCodeFromCollector()
  {
    if (( $collector = $this->getObject()->getCollector() ))
    {
      if (( $profile = $collector->getProfile() ))
      {
        return $profile->getCountryIso3166();
      }
    }

    return null;
  }

  /**
   * @param     PropelPDO $con
   */
  protected function doSave($con = null)
  {
    parent::doSave($con);

    // if possible, check if this is the only collector address and make it
    // primary if it is
    if (( $collector = $this->getObject()->getCollector($con) ))
    {
      if (1 == $collector->countCollectorAddresses(new Criteria(), $distinct = false, $con))
      {
        $this->getObject()->setIsPrimary(true)->save($con);
      }
    }
  }

}