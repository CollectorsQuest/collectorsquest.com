<?php

/**
 * Skeleton subclass for representing a row from the 'organization' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.organizations
 */
class Organization extends BaseOrganization
{

  public function getDisplayType()
  {
    if ($this->getOrganizationType())
    {
      return $this->getOrganizationType()->getName();
    }
    else
    {
      return $this->getTypeOther();
    }
  }

}