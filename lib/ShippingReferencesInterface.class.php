<?php

/**
 * ShippingReferencesInterface
 *
 */
interface ShippingReferencesInterface
{

  /**
   * Return an array of ShippingReference objects, indexed by country code
   *
   * @param     PropelPDO $con
   *
   * @return    array
   *            <code>
   *            array(
   *              'country_code' => ShippingReference
   *            );
   *            </code>
   */
  public function getShippingReferencesByCountryCode(PropelPDO $con = null);

  /**
   * Return the Shipping Reference for a particular country
   *
   * If there is no reference for that specific country,
   * the International shipping reference is returned.
   *
   * @param     string $country_code
   * @param     PropelPDO $con
   *
   * @return    ShippingReference
   */
  public function getShippingReferenceForCountryCode($coutry_code, PropelPDO $con = null);

  /**
   * Return the Shipping References for the collector's country
   *
   * @param     PropelPDO $con
   *
   * @return    ShippingReference
   */
  public function getShippingReferenceDomestic(PropelPDO $con = null);

}