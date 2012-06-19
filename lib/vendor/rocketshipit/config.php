<?php
//Copyright RocketShipIt LLC All Rights Reserved
// For Support email: support@rocketship.it

// Feel free to modify the following defaults:


/**
* This function is used to set generic defaults.  I.e. They are not carrier-specific.
*
* These defaults will be used across all carriers.  They can be overwritten on the
* shipment/package level.
*/
function getGenericDefault($key)
{
  return getSettingFromAppYml('generic', $key);
}


/**
* This function is used to set FedEx specfic defaults.
*
* These defaults will be used for FedEx calls only.  They can be overwritten on the
* shipment/package level using the setParameter() function.
*/
function getFEDEXDefault($key)
{
  return getSettingFromAppYml('fedex', $key);
}


/**
* This function is used to set UPS specfic defaults.
*
* These defaults will be used for UPS calls only.  They can be overwritten on the
* shipment/package level using the setParameter() function.
*/
function getUPSDefault($key)
{
  return getSettingFromAppYml('ups', $key);
}


/**
* This function is used to set USPS specfic defaults.
*
* These defaults will be used for USPS calls only.  They can be overwritten on the
* shipment/package level using the setParameter() function.
*/
function getUSPSDefault($key)
{
  return getSettingFromAppYml('usps', $key);
}


function getSettingFromAppYml($module, $key)
{
  $module_data = sfConfig::get('app_rocketshipit_'.$module);

  return isset($module_data[$key])
    ? $module_data[$key]
    : '';
}