<?php

/**
 * ShippingRatesInterface
 *
 */
interface ShippingRatesInterface
{

  /**
   * Return an array of ShippingRate[] arrays, indexed by country code
   *
   * @param     PropelPDO $con
   *
   * @return    array
   *            <code>
   *            array(
   *              'country_code' => ShippingRate
   *            );
   *            </code>
   */
  public function getShippingRatesByCountryCode(PropelPDO $con = null);

  /**
   * Return the shipping rates for a particular country
   *
   * @param     string $country_code
   * @param     PropelPDO $con
   *
   * @return    ShippingRate
   */
  public function getShippingRateForCountryCode($coutry_code, PropelPDO $con = null);

  /**
   * Return the shipping rates for the collector's country
   *
   * @param     PropelPDO $con
   *
   * @return    ShippingRate
   */
  public function getShippingRateDomestic(PropelPDO $con = null);

  /**
   * @return    string
   */
  public function getDomesticCountryCode();

}
