<?php
/**
 * Copyright RocketShipIt LLC All Rights Reserved
 * Author: Mark Sanborn
 * Version: 1.1.2.8-1
 * PHP Version 5
 * For Support email: support@rocketship.it
 **/

// RocketShipIt Config
require __DIR__ . '/config.php';


/**
 * Ensures that only settable paramaters are allowed.
 *
 * This function aids the setPramater() function in that it only
 * allows known paramaters to be set.  This helps to avoid typos when
 * setting parameters.
 */
function rocketshipit_getOKparams($carrier)
{
  // Force fedex, FedEx, FEDEX to all read the same
  $carrier = strtoupper($carrier);

  // Generic parameters that are accessible in each class regardless of carrier
  $generic = array('shipper', 'enableRocketShipAPI', 'shipContact', 'shipPhone',
    'accountNumber', 'shipAddr1', 'shipAddr2', 'shipAddr3',
    'shipCity', 'shipState', 'shipCode', 'shipCountry',
    'toCompany', 'toName', 'toPhone', 'toAddr1', 'toAddr2',
    'toAddr3', 'toCity', 'toState', 'toCountry', 'toCode',
    'service', 'weightUnit', 'length', 'width', 'height',
    'weight', 'toExtendedCode', 'currency', 'toAttentionName',
    'fromName', 'fromAddr1', 'fromAddr2', 'fromCity', 'fromState',
    'fromCode', 'fromExtendedCode');

  // Carrier specific parameters
  switch ($carrier) {
    case 'UPS':
      $specific = array('earliestTimeReady', 'latestTimeReady',
        'httpUserAgent', 'labelPrintMethodCode',
        'labelDescription', 'labelHeight', 'labelWidth',
        'labelImageFormat', 'residentialAddressIndicator',
        'PickupType', 'pickupDescription',
        'shipmentDescription', 'packagingType',
        'packageLength', 'packageWidth', 'packageHeight',
        'packageWeight', 'referenceCode', 'referenceValue',
        'insuredCurrency', 'monetaryValue',
        'referenceCode2', 'referenceValue2', 'pickupDate',
        'lengthUnit', 'serviceDescription', 'returnCode',
        'fromAddr2', 'fromAddr3', 'fromCountry',
        'fromAttentionName', 'fromPhoneNumber', 'fromFaxNumber',
        'packageDescription', 'returnEmailAddress',
        'returnUndeliverableEmailAddress',
        'returnFromEmailAddress', 'returnEmailFromName',
        'verifyAddress', 'negotiatedRates',
        'saturdayDelivery', 'billThirdParty',
        'thirdPartyAccount', 'codFundType',
        'codAmount', 'flexibleAccess', 'signatureType',
        'customerClassification', 'pickupAddr1',
        'pickupCity', 'pickupState', 'pickupCode',
        'pickupCountry', 'pickupResidential', 'pickupAlternative',
        'closeTime', 'readyTime', 'pickupCompanyName', 'pickupContactName',
        'pickupPhone', 'pickupServiceCode', 'pickupQuantity', 'pickupDestination',
        'pickupContainerCode', 'pickupAlternative', 'pickupOverweight',
        'paymentMethodCode', 'pickupCardHolder', 'pickupCardType',
        'pickupCardNumber', 'pickupCardExpiry', 'pickupCardSecurity',
        'pickupCardAddress', 'pickupCardCountry', 'pickupPRN', 'trackingNumber',
        'pickupRoom', 'pickupFloor', 'thirdPartyPostalCode',
        'thirdPartyCountryCode', 'invoiceLineNumber', 'invoiceLineDescription',
        'invoiceLineValue', 'invoiceLinePartNumber', 'invoiceLineOriginCountryCode',
        'invoice', 'invoiceDate', 'invoiceReason', 'invoiceCurr', 'additionalDocs',
        'soldName', 'soldCompany', 'soldTaxId', 'soldPhone', 'soldAddr1', 'soldAddr2',
        'soldCity', 'soldState', 'soldCode', 'soldCountry');
      break;
    case 'USPS':
      $specific = array('userid', 'imageType', 'weightPounds',
        'weightOunces', 'firstClassMailType',
        'packagingType', 'pickupDate', 'permitNumber',
        'permitIssuingPOCity', 'permitIssuingPOState',
        'permitIssuingPOZip5', 'pduFirmName', 'pduPOBox',
        'pduCity', 'pduState', 'pduZip5', 'pduZip4',
        'returnEmailAddress', 'returnFromName',
        'returnFromEmailAddress', 'returnEmailFromName',
        'returnToName', 'referenceValue');
      break;
    case 'FEDEX':
      $specific = array('key', 'packagingType', 'weightUnit', 'lengthUnit',
        'dropoffType', 'residential', 'paymentType',
        'labelFormatType', 'imageType', 'labelStockType',
        'packageCount', 'sequenceNumber', 'trackingIdType',
        'trackingNumber', 'shipmentIdentification',
        'pickupDate', 'signatureType', 'referenceCode',
        'referenceValue', 'smartPostIndicia', 'smartPostHubId',
        'smartPostEndorsement', 'smartPostSpecialServices',
        'insuredCurrency', 'insuredValue', 'saturdayDelivery',
        'residentialAddressIndicator', 'customsDocumentContent',
        'customsValue', 'customsNumberOfPieces',
        'countryOfManufacture', 'customsWeight',
        'customsCurrency', 'collectOnDelivery', 'codCollectionType',
        'codCollectionAmount', 'holdAtLocation', 'holdPhone', 'holdStreet',
        'holdCity', 'holdState', 'holdCode', 'holdCountry', 'holdResidential',
        'saturdayDelivery', 'futureDay', 'shipDate', 'nearPhone',
        'nearCode', 'nearAddr1', 'nearCity', 'nearState', 'returnCode', 'referenceValue2', 'referenceCode2', 'referenceValue3', 'referenceCode3');
      break;
    case 'STAMPS':
      $specific = array('weightPounds', 'imageType', 'packagingType',
        'declaredValue', 'customsContentType', 'customsComments',
        'customsLicenseNumber', 'customsCertificateNumber',
        'customsInvoiceNumber', 'customsOtherDescribe',
        'customsDescription', 'customsQuantity', 'customsValue',
        'customsWeight', 'customsHsTariff', 'customsOriginCountry',
        'insuredValue', 'referenceValue');
      break;
    default:
      throw new RuntimeException("Invalid carrier '$carrier' in getOKparams");
  }
  return array_merge($generic, $specific);
}

/**
 * Gets defaults
 *
 * This function will grab defaults from config.php
 */
function rocketshipit_getParameter($param, $value, $carrier)
{
  // Force fedex, FedEx, FEDEX to all read the same
  $carrier = strtoupper($carrier);

  // If the default is not in the getOKparams function an exception is thrown
  if (!in_array($param, rocketshipit_getOKparams($carrier)) && $param != '') {
    throw new RuntimeException("Invalid parameter '$param' in setParameter");
  }

  if ($value == "") { // get the default, if set
    $value = getGenericDefault($param);
    if ($value == "") { // not in the generics? look in the specific carrier params
      switch ($carrier) {
        case 'UPS':
          $value = getUPSDefault($param);
          break;
        case 'USPS':
          $value = getUSPSDefault($param);
          break;
        case 'FEDEX':
          $value = getFEDEXDefault($param);
          break;
        case 'STAMPS':
          $value = getSTAMPSDefault($param);
          break;
        default:
          throw new RuntimeException("Unknown carrier in setParameter: '$carrier'");
      }
    }
  }
  return $value;
}

/**
 * Validates carrier name
 *
 * This function will return true when given a proper
 * carier name.
 */
function rocketshipit_validateCarrier($carrier)
{
  switch (strtoupper($carrier)) {
    case 'UPS':
      return true;
    case 'FEDEX':
      return true;
    case 'USPS':
      return true;
    case 'STAMPS':
      return true;
    default:
      throw new RuntimeException("Unknown carrier in RocketShipShipment: '$carrier'");
  }
}

/**
 * Create html code for base64 embedded image
 *
 * This function will return valid html for an
 * embedded base64 image.  This html does not
 * work in all browsers.
 */
function rocketshipit_label_html($base64_encoded_label, $imageType)
{
  return "<img src=\"data:image/$imageType;base64,$base64_encoded_label\" alt=\"Label\" />";
}


/**
 * Main class for tracking shipments and packages
 *
 * This class is a wrapper for use with all carriers to track packages
 * Valid carriers are: UPS, USPS, and FedEx.
 */
class RocketShipTrack
{

  function __Construct($carrier, $license = '', $username = '', $password = '')
  {
    rocketshipit_validateCarrier($carrier);
    $this->OKparams = rocketshipit_getOKparams($carrier);
    $this->carrier = strtoupper($carrier);
    switch ($this->carrier) {
      case 'UPS':
        $this->core = new ups($license, $username, $password); // This class depends on ups

        if ($license != '') {
          $this->core->license = $license;
        }
        if ($username != '') {
          $this->core->username = $username;
        }
        if ($password != '') {
          $this->core->password = $password;
        }

        break;
      case 'FEDEX':
        $this->core = new fedex();
        $this->setParameter('trackingIdType', '');
        break;
      case 'USPS':
        $this->core = new usps();
        $this->setParameter('userid', '');
        break;
      default:
        exit ("Unknown carrier $this->carrier in RocketShipTrack.");
    }
  }

  function track($trackingNumber)
  {
    switch (strtoupper($this->carrier)) {
      case 'UPS':
        $retArr = $this->trackUPS($trackingNumber);
        $a = $retArr['TrackResponse'];
        if ($a['Response']['ResponseStatusCode']['VALUE'] != "1") {
          $this->result = "FAIL";
          $this->reason = $a['Response']['Error']['ErrorDescription']['VALUE'] .
            " (" . $a['Response']['Error']['ErrorCode']['VALUE'] . ")";
        } else {
          if (array_key_exists("TrackingNumber", $a['Shipment']['Package'])) {
            // single package
            $p = $a['Shipment']['Package'];
          } else {
            // multi-package
            $p = $a['Shipment']['Package'][0];
          }
          $this->result = "OK";
          if (array_key_exists("Status", $p['Activity'])) {
            // just the one
            $this->status = $p['Activity']['Status']['StatusType']['Description']['VALUE'];
          } else {
            // multiple activities - grab the most recent
            $this->status = $p['Activity'][0]['Status']['StatusType']['Description']['VALUE'];
          }
        }
        return $retArr;
      case 'FEDEX':
        return $this->trackFEDEX($trackingNumber);
      case 'USPS':
        return $this->trackUSPS($trackingNumber);
      default:
        exit("Unknown carrier $this->carrier in RocketShipTrack");
    }
  }

  function trackByReference($referenceNumber)
  {
    switch (strtoupper($this->carrier)) {
      case 'UPS':
        $this->referenceNumber = $referenceNumber;
        $retArr = $this->trackUPS($referenceNumber);
        return $retArr;
      case 'FEDEX':
        break;
      case 'USPS':
        break;
      default:
        exit("Unknown carrier $this->carrier in RocketShipTrack");
    }
  }


  // Builds xml for tracking and sends the xml string to the ups->request method
  // recieves a response from UPS and outputs an array.
  function trackUPS($trackingNumber)
  {
    $xml = $this->core->xmlObject;

    $xml->push('TrackRequest', array('xml:lang' => 'en-US'));
    $xml->push('Request');
    $xml->push('TransactionReference');
    $xml->element('CustomerContext', 'RocketShipIt');
    $xml->pop(); // close TransactionReference
    $xml->element('RequestAction', 'Track');
    $xml->element('RequestOption', 'activity');
    $xml->pop(); // close Request
    if (!isset($this->referenceNumber)) {
      $xml->element('TrackingNumber', $trackingNumber);
    } else {
      $xml->element('ShipperNumber', getUPSDefault('accountNumber'));
      $xml->push('ReferenceNumber');
      $xml->element('Value', $this->referenceNumber);
      $xml->pop(); // close ReferenceNumber
    }
    $xml->pop();

    // Convert xml object to a string
    $xmlString = $xml->getXml();

    // Send the xmlString to UPS and store the resonse in a class variable, xmlResponse.
    $this->core->request('Track', $xmlString);

    // Return response xml as an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();

    return $xmlArray;
  }


  private function trackFEDEX($trackingNumber)
  {
    $xml = $this->core->xmlObject;
    $xml->push('ns:TrackRequest', array('xmlns:ns' => 'http://fedex.com/ws/track/v4', 'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation' => 'http://fedex.com/ws/track/v4 TrackService v4.xsd'));
    $this->core->xmlObject = $xml;
    $this->core->access();
    $xml = $this->core->xmlObject;

    $xml->push('ns:Version');
    $xml->element('ns:ServiceId', 'trck');
    $xml->element('ns:Major', '4');
    $xml->element('ns:Intermediate', '0');
    $xml->element('ns:Minor', '0');
    $xml->pop(); // end Version
    $xml->push('ns:PackageIdentifier');
    $xml->element('ns:Value', $trackingNumber);
    $xml->element('ns:Type', $this->trackingIdType);
    $xml->pop(); // end PackageIdentifier
    $xml->element('ns:IncludeDetailedScans', 'true');

    $xml->pop(); // end TrackRequest

    $xmlString = $xml->getXml();

    $this->core->request($xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  private function trackUSPS($trackingNumber)
  {
    $xml = $this->core->xmlObject;

    $xml->push('TrackRequest', array('USERID' => $this->userid));
    $xml->push('TrackID', array('ID' => $trackingNumber));
    $xml->pop(); // end TrackID
    $xml->pop(); // end TrackRequest

    $xmlString = $xml->getXml();

    $postData = 'API=TrackV2&XML=' . $xmlString;

    $this->core->request('ShippingAPI.dll', $postData);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  function setparameter($param, $value)
  {
    $value = rocketshipit_getparameter($param, $value, $this->carrier);
    $this->{$param} = $value;
  }

}


/**
 * Main Rate class for producing rates for various packages/shipments
 *
 * This class is a wrapper for use with all carriers to produce rates
 * Valid carriers are: UPS, USPS, and FedEx.
 */
class RocketShipRate
{

  var $OKparams;

  var $packageCount;

  function __Construct($carrier, $license = '', $username = '', $password = '')
  {
    rocketshipit_validateCarrier($carrier);
    $carrier = strtoupper($carrier);
    $this->carrier = $carrier;
    $this->OKparams = rocketshipit_getOKparams($carrier);
    $this->packageCount = 0;

    // Set up core class and grab carrier-specific defaults that are unique to the current carrier
    if ($carrier == 'UPS') {
      $this->core = new ups($license, $username, $password); // This class depends on ups

      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }

      if ($license != '') {
        $this->core->license = $license;
      }
      if ($username != '') {
        $this->core->username = $username;
      }
      if ($password != '') {
        $this->core->password = $password;
      }

    }
    if ($carrier == 'USPS') {
      $this->core = new usps(); // This class depends on usps

      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }

    }

    if ($carrier == 'FEDEX') {
      $this->core = new fedex(); // This class depends on fedex

      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }
    }
    if ($carrier == 'STAMPS') {
      $this->core = new stamps(); // This class depends on stamps

      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }
    }
  }

  /**
   * Retruns a single rate from the carrier.
   */
  function getRate()
  {
    switch ($this->carrier) {
      case 'UPS':
        return $this->getUPSRate();
      case 'USPS':
        return $this->getUSPSRate();
      case 'FEDEX':
        return $this->getFEDEXRate();
    }
  }

  /**
   * Retruns all available rates from the carrier.
   */
  function getAllRates()
  {
    switch ($this->carrier) {
      case 'UPS':
        return $this->getAllUPSRates();
      case 'FEDEX':
        return $this->getAllFEDEXRates();
      case 'USPS':
        return $this->getAllUSPSRates();
      case 'STAMPS':
        return $this->getAllSTAMPSRates();
    }
  }

  /**
   * This is a wrapper to create a running package for each carrier.
   *
   * This is used to add packages to a shipment for any carrier.
   * You use the {@link RocketShipPackage} class to create a package
   * object.
   */
  function addPackageToShipment($packageObj)
  {
    $this->packageCount++;
    switch ($this->carrier) {
      case 'UPS':
        return $this->addPackageToUPSShipment($packageObj);
      case 'USPS':
        return $this->addPackageToUSPSShipment($packageObj, $this->isInternational($this->toCountry));
      case 'FEDEX':
        return $this->addPackageToFEDEXShipment($packageObj);
      default:
        return false;
    }
  }

  /**
   * Return a simple rate from carrier.
   */
  function getSimpleRate()
  {
    switch ($this->carrier) {
      case 'UPS':
        $upsArray = $this->getUPSRate();
        $status = $upsArray['RatingServiceSelectionResponse']['Response']['ResponseStatusCode']['VALUE'];
        if ($status == '1') {
          $rate = $upsArray['RatingServiceSelectionResponse']['RatedShipment']['TotalCharges']['MonetaryValue']['VALUE'];
          return $rate;
        } else {
          $errorMessage = $upsArray['RatingServiceSelectionResponse']['Response']['Error']['ErrorDescription']['VALUE'];
          return $errorMessage;
        }
      case 'FEDEX':
        $fedex = $this->getFEDEXRate();
        if (isset($fedex['v7:RateReply']['v7:HighestSeverity']['VALUE']) && $fedex['v7:RateReply']['v7:HighestSeverity']['VALUE'] != 'ERROR') {
          return $fedex['v7:RateReply']['v7:RateReplyDetails']['v7:RatedShipmentDetails'][1]['v7:ShipmentRateDetail']['v7:TotalNetCharge']['v7:Amount']['VALUE'];
        } else {
          return $fedex['v7:RateReply']['v7:Notifications']['v7:Message']['VALUE'];
        }
        return $fedex;
      case 'USPS':
        $usps = $this->getUSPSRate();
        if (!isset($usps['Error']) && !isset($usps['RateV4Response']['Package']['Error'])) {
          return $usps['RateV4Response']['Package']['Postage']['Rate']['VALUE'];
        } else {
          if (isset($usps['Error']['Description']['VALUE'])) {
            return $usps['Error']['Description']['VALUE'];
          } else {
            return $usps['RateV4Response']['Package']['Error']['Description']['VALUE'];
          }
        }
    }
  }

  /**
   * Return all available rates from carrier in a simple array.
   */
  function getSimpleRates()
  {
    switch ($this->carrier) {
      case 'UPS':
        $upsArray = $this->getAllUPSRates();
        $status = $upsArray['RatingServiceSelectionResponse']['Response']['ResponseStatusCode']['VALUE'];

        if ($status == '1') {
          $rate = $upsArray['RatingServiceSelectionResponse']['RatedShipment'][0]['TotalCharges']['MonetaryValue']['VALUE'];
          $service = $upsArray['RatingServiceSelectionResponse']['RatedShipment'];

          $rates = Array();
          if (array_key_exists('Service', $service)) {
            $r = $service['Service']['Code']['VALUE'];
            $desc = $this->core->getServiceDescriptionFromCode($r);
            $rates["$desc"] = $service['TotalCharges']['MonetaryValue']['VALUE'];
          } else {
            foreach ($service as $s) {
              $r = $s['Service']['Code']['VALUE'];
              $desc = $this->core->getServiceDescriptionFromCode($r);
              $rates["$desc"] = $s['TotalCharges']['MonetaryValue']['VALUE'];
              if (isset($s['NegotiatedRates'])) {
                $rates["$desc"] = array(
                  'Rate'       => $s['TotalCharges']['MonetaryValue']['VALUE'],
                  'Negotiated' => $s['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue']['VALUE']
                );
              }
            }
          }

          return $rates;
        } else {
          $errorMessage = $upsArray['RatingServiceSelectionResponse']['Response']['Error']['ErrorDescription']['VALUE'];
          $errorArray['error'] = $errorMessage;
          return $errorArray;
        }
      case 'FEDEX':
        $fedex = $this->getAllFEDEXRates();
        if (isset($fedex['v7:RateReply']['v7:HighestSeverity']['VALUE']) && $fedex['v7:RateReply']['v7:HighestSeverity']['VALUE'] != 'ERROR') {
          $service = $fedex['v7:RateReply']['v7:RateReplyDetails'];

          $rates = Array();
          if (array_values($service) === $service) {
            foreach ($service as $s) {
              $serviceType = $s['v7:ServiceType']['VALUE'];
              if (isset($s['v7:RatedShipmentDetails'][0])) {
                $value = $s['v7:RatedShipmentDetails'][0]['v7:ShipmentRateDetail']['v7:TotalNetCharge']['v7:Amount']['VALUE'];
              } else {
                $value = $s['v7:RatedShipmentDetails']['v7:ShipmentRateDetail']['v7:TotalNetCharge']['v7:Amount']['VALUE'];
              }
              $rates["$serviceType"] = $value;
            }
          } else {
            $serviceType = $service['v7:ServiceType']['VALUE'];
            $value = $service['v7:RatedShipmentDetails'][0]['v7:ShipmentRateDetail']['v7:TotalNetCharge']['v7:Amount']['VALUE'];
            $rates["$serviceType"] = $value;
          }
          return $rates;
        } else {
          $errorMessage = $fedex['v7:RateReply']['v7:Notifications']['v7:Message']['VALUE'];
          $errorArray['error'] = $errorMessage;
          return $errorArray;
        }
      case 'USPS':
        $usps = $this->getAllUSPSRates();
        if (!isset($usps['Error']) && !isset($usps['RateV4Response']['Package']['Error'])) {
          if (array_key_exists('RateV4Response', $usps)) {
            $services = $usps['RateV4Response']['Package']['Postage'];
            $rates = Array();
            if (isset($services[0])) {
              foreach ($services as $s) {
                $service = $s['MailService']['VALUE'];
                $value = $s['Rate']['VALUE'];
                $rates["$service"] = $value;
              }
            } else {
              $service = $services['MailService']['VALUE'];
              $value = $services['Rate']['VALUE'];
              $rates["$service"] = $value;
            }
            return $rates;
          } else {
            $services = $usps['IntlRateV2Response']['Package']['Service'];
            $rates = Array();
            foreach ($services as $s) {
              $service = $s['SvcDescription']['VALUE'];
              $value = $s['Postage']['VALUE'];
              $rates["$service"] = $value;
            }
            return $rates;
          }
        } else {
          if (isset($usps['Error']['Description']['VALUE'])) {
            $errorMessage = $usps['Error']['Description']['VALUE'];
            $errorArray['error'] = $errorMessage;
            return $errorArray;
          } else {
            $errorMessage = $usps['RateV4Response']['Package']['Error']['Description']['VALUE'];
            $errorArray['error'] = $errorMessage;
            return $errorArray;
          }
        }
      case 'STAMPS':
        $stamps = $this->getAllSTAMPSRates();
        // TODO: If error do an array with error as key and description as value
        // else, for each rate find the description and value and put it into an array
        $rates = Array();
        foreach ($stamps->Rates->Rate as $rte) {
          if ($rte) {
            $svc_code = $rte->ServiceType;
            $service = $this->core->getServiceDescriptionFromCode($svc_code);
            $value = $rte->Amount;
            $packageType = $rte->PackageType;
            $rates["$service - $packageType"] = $value;
          }
        }
        return $rates;
    }
  }

  private function getAllUPSRates()
  {
    return $this->getUPSRate('Shop');
  }

  private function getAllFEDEXRates()
  {
    return $this->getFEDEXRate(true);
  }

  private function getAllUSPSRates()
  {
    return $this->getUSPSRate(true);
  }


  private function getUPSRate($requestOption = 'Rate')
  {

    $xmlString = $this->buildUPSRateXml($requestOption);

    $this->core->request('Rate', $xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  function addPackageToUPSShipment($package)
  {

    if (!isset($this->core->packagesObject)) {
      $this->core->packagesObject = new xmlBuilder(true);
    }

    $xml = $this->core->packagesObject;

    $xml->push('Package');
    $xml->push('PackagingType');
    $xml->element('Code', $package->packagingType);
    //$xml->element('Description', $this->packageTypeDescription);
    $xml->pop(); // close PacakgeType
    if ($package->length != '') {
      $xml->push('Dimensions');
      $xml->push('UnitOfMeasurement');
      $xml->element('Code', $package->lengthUnit);
      $xml->pop(); // close UnitOfMeasurement
      $xml->element('Length', $package->length);
      $xml->element('Width', $package->width);
      $xml->element('Height', $package->height);
      $xml->pop(); // close Dimensions
    }
    //$xml->element('Description', $this->packageDescription);
    $xml->push('PackageWeight');
    $xml->push('UnitOfMeasurement');
    $xml->element('Code', $package->weightUnit);
    $xml->pop(); // close UnitOfMeasurement
    $xml->element('Weight', $package->weight);
    $xml->pop(); // close PackageWeight
    if ($package->monetaryValue != '' || $package->insuredCurrency != '' || $package->signatureType != '') { // Change for COD
      $xml->push('PackageServiceOptions');
      if ($package->monetaryValue != '') {
        $xml->push('InsuredValue');
        $xml->element('CurrencyCode', $package->insuredCurrency);
        $xml->element('MonetaryValue', $package->monetaryValue);
        $xml->pop(); // close InsuredValue
      }
      if ($package->signatureType != '') {
        $xml->push('DeliveryConfirmation');
        $xml->element('DCISType', $package->signatureType);
        $xml->pop(); // end DeliveryConfirmation
      }
      $xml->pop(); // close PackageServiceOptions
    }
    $xml->pop(); // close Package

    $this->core->packagesObject = $xml;

    return true;
  }


  function buildUPSRateXml($requestOption = 'Rate')
  {
    $xml = $this->core->xmlObject;

    $xml->push('RatingServiceSelectionRequest');
    $xml->push('Request');
    $xml->element('RequestAction', 'Rate');
    $xml->element('RequestOption', $requestOption);
    $xml->push('TransactionReference'); // Not required
    $xml->element('CustomerContext', 'RocketShipIt'); // Not required
    //$xml->element('XpciVersion', '1.0'); // Not required
    $xml->pop(); // close TransactionReference, not required
    $xml->pop(); // close Request
    $xml->push('PickupType');
    $xml->element('Code', $this->PickupType); // TODO: insert link to code values
    if ($this->pickupDescription != '') {
      //$xml->element('Description', $this->pickupDescription);
    }
    $xml->pop(); // close PickupType
    if ($this->customerClassification != '') {
      $xml->push('CustomerClassification');
      $xml->element('Code', $this->customerClassification);
      $xml->pop(); //end CustomerClassification
    }
    $xml->push('Shipment');
    //$xml->element('Description', $this->shipmentDescription);
    $xml->push('Shipper');
    $xml->element('ShipperNumber', $this->accountNumber);
    $xml->push('Address');
    $xml->element('AddressLine1', $this->shipAddr1);
    if ($this->shipAddr2 != '') {
      $xml->element('AddressLine2', $this->shipAddr2);
    }
    if ($this->shipAddr3 != '') {
      $xml->element('AddressLine3', $this->shipAddr3);
    }
    if ($this->shipCity != '') {
      $xml->element('City', $this->shipCity);
    }
    $xml->element('StateProvinceCode', $this->shipState);
    $xml->element('PostalCode', $this->shipCode);
    if ($this->shipCountry != '') {
      $xml->element('CountryCode', $this->shipCountry);
    } else {
      $xml->element('CountryCode', 'US');
    }
    $xml->pop(); // close Address
    $xml->pop(); // close Shipper
    $xml->push('ShipTo');
    if ($this->toCompany != '') {
      $xml->element('CompanyName', $this->toCompany);
    }
    $xml->push('Address');
    if ($this->toAddr1 != '') {
      $xml->element('AddressLine1', $this->toAddr1);
    }
    if ($this->toAddr2 != '') {
      $xml->element('AddressLine2', $this->toAddr2);
    }
    if ($this->toAddr3 != '') {
      $xml->element('AddressLine3', $this->toAddr3);
    }
    if ($this->toCity != '') {
      $xml->element('City', $this->toCity);
    }
    if ($this->toState != '') {
      $xml->element('StateProvinceCode', $this->toState);
    }
    $xml->element('PostalCode', $this->toCode);
    if ($this->toCountry != '') {
      $xml->element('CountryCode', $this->toCountry);
    } else {
      $xml->element('CountryCode', 'US');
    }
    if ($this->residentialAddressIndicator != '') {
      $xml->element('ResidentialAddressIndicator', '1');
    }
    $xml->pop(); // close Address
    $xml->pop(); // close ShipTo
    if ($this->fromName != '') {
      $xml->push('ShipFrom');
      $xml->element('CompanyName', $this->fromName);
      //$xml->element('AttentionName', $this->fromAttentionName);
      //$xml->element('PhoneNumber', $this->fromPhoneNumber);
      //$xml->element('FaxNumber', $this->fromFaxNumber);
      $xml->push('Address');
      $xml->element('AddressLine1', $this->fromAddr1);
      $xml->element('AddressLine2', $this->fromAddr2);
      $xml->element('AddressLine3', $this->fromAddr3);
      $xml->element('City', $this->fromCity);
      $xml->element('PostalCode', $this->fromCode);
      if ($this->fromCountry != '') {
        $xml->element('CountryCode', $this->fromCountry);
      } else {
        $xml->element('CountryCode', 'US');
      }
      $xml->pop(); // close Address
      $xml->pop(); // close ShipFrom
    }
    if ($this->service != '') {
      $xml->push('Service');
      $xml->element('Code', $this->service);
      $xml->pop(); // close Service
    }
    if (!isset($this->core->packagesObject)) {
      $xml->push('Package');
      $xml->push('PackagingType');
      $xml->element('Code', $this->packagingType);
      //$xml->element('Description', $this->packageTypeDescription);
      $xml->pop(); // close PacakgeType
      if ($this->length != '' && $this->width != '' && $this->height != '') {
        $xml->push('Dimensions');
        $xml->push('UnitOfMeasurement');
        $xml->element('Code', $this->lengthUnit);
        $xml->pop(); // close UnitOfMeasurement
        $xml->element('Length', $this->length);
        $xml->element('Width', $this->width);
        $xml->element('Height', $this->height);
        $xml->pop(); // close Dimensions
      }
      //$xml->element('Description', $this->packageDescription);
      if (isset($this->weightUnit)) {
        $xml->push('PackageWeight');
        $xml->push('UnitOfMeasurement');
        $xml->element('Code', $this->weightUnit);
        $xml->pop(); // close UnitOfMeasurement
        if ($this->weight != '') {
          $xml->element('Weight', $this->weight);
        } else {
          $xml->element('Weight', '0');
        }
        $xml->pop(); // close PackageWeight
      }
      if ($this->monetaryValue != '' || $this->insuredCurrency != '' || $this->signatureType != '') { // Change for COD
        $xml->push('PackageServiceOptions');
        if ($this->monetaryValue != '') {
          $xml->push('InsuredValue');
          $xml->element('CurrencyCode', $this->insuredCurrency);
          $xml->element('MonetaryValue', $this->monetaryValue);
          $xml->pop(); // close InsuredValue
        }
        if ($this->signatureType != '') {
          $xml->push('DeliveryConfirmation');
          $xml->element('DCISType', $this->signatureType);
          $xml->pop(); // end DeliveryConfirmation
        }
        $xml->pop(); // close PackageServiceOptions
      }
      $xml->pop(); // close Package
    } else {
      $xmlString = $xml->getXml();
      $xmlString .= $this->core->packagesObject->getXml();
      $negotiatedXml = new xmlBuilder(true);
      if ($this->negotiatedRates == '1') {
        $negotiatedXml->push('RateInformation');
        $negotiatedXml->element('NegotiatedRatesIndicator', '1');
        $negotiatedXml->pop(); // close RateInformation
      }
      $xmlString .= $negotiatedXml->getXml();
      $xmlString .= '</Shipment>' . "
";
      $xmlString .= '</RatingServiceSelectionRequest>' . "
";
      return $xmlString;
    }
    if ($this->negotiatedRates == '1') {
      $xml->push('RateInformation');
      $xml->element('NegotiatedRatesIndicator', '1');
      $xml->pop(); // close RateInformation
    }
    //$xml->push('ShipmentServiceOptions');
    //$xml->pop(); // close ShipmentServiceOptions
    $xml->pop(); // close Shipment
    $xml->pop();

    // Convert xml object to a string
    $xmlString = $xml->getXml();
    return $xmlString;
  }


  private function getUSPSRate($allAvailableRates = false)
  {

    if (!$this->isInternational($this->toCountry)) {
      $xmlString = $this->buildUSPSRateXml($allAvailableRates);
    } else {
      if ($allAvailableRates) {
        $xmlString = $this->buildUSPSInternationalRateXml($allAvailableRates);
      } else {
        exit("Please use getAllRates() for international USPS rate quotes");
      }
    }

    $this->core->request('ShippingAPI.dll', $xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  function addPackageToUSPSShipment($package, $isInternational = false)
  {
    if (!isset($this->core->packagesObject)) {
      $this->core->packagesObject = new xmlBuilder(true);
    }

    $xml = $this->core->packagesObject;

    // Create package ID
    $packageId = NULL;
    switch (substr($this->packageCount, -1)) {
      case 1:
        $packageId = $this->packageCount . "ST";
        break;
      case 2:
        $packageId = $this->packageCount . "ND";
        break;
      case 3:
        $packageId = $this->packageCount . "RD";
        break;
      default:
        $packageId = $this->packageCount . "TH";
        break;
    }

    if ($isInternational) {

      $country = (strlen($this->toCountry) == 2)
        ? $this->core->getCountryName($this->toCountry)
        : $this->toCountry;

      // Assign options
      $options = array(
        'packageId'     => $packageId,
        'toCountry'     => $country,
        'weightPounds'  => $package->weightPounds,
        'weightOunces'  => $package->weightOunces,
        'weight'        => $package->weight,
        'packagingType' => $this->packagingType,
        'width'         => $package->width,
        'height'        => $package->height,
        'length'        => $package->length
      );
      $xml = $this->buildUSPSInternationalPackage($xml, $options);
    } else {
      $xml->push('Package', array('ID' => $packageId));
      $xml->element('Service', $this->service);
      if ($this->firstClassMailType != '') {
        $xml->element('FirstClassMailType', $package->firstClassMailType);
      }
      $xml->element('ZipOrigination', $this->shipCode);
      $xml->element('ZipDestination', $this->toCode);

      // Calculate weight in lbs and ounces based on weight parameter
      if ($package->weight != '') {
        $weight = $package->weight;
        list($lbs, $partialLb) = explode('.', "$weight.");
        $tenth = $partialLb / 10;
        $ounces = $tenth * 16;
        $xml->element('Pounds', (string)$lbs);
        $xml->element('Ounces', (string)$ounces);
      } else {
        $xml->element('Pounds', (string)$package->weightPounds);
        $xml->element('Ounces', (string)$package->weightOunces);
      }

      if ($this->packagingType != '') {
        $xml->element('Container', $package->packagingType);
      } else {
        $xml->emptyelement('Container');
      }

      $girth = $this->length + ($this->height * 2) + ($this->width * 2);

      if ($girth > 108) $xml->element('Size', 'Oversize');
      else if ($girth > 84) $xml->element('Size', 'Large');
      else $xml->element('Size', 'Regular');

      $xml->element('Width', $package->width);
      $xml->element('Length', $package->length);
      $xml->element('Height', $package->height);
      $xml->element('Girth', $girth);

      $xml->element('Machinable', 'false');
      $xml->pop(); // Close Package
    }

    $this->core->packagesObject = $xml;
    return true;
  }


  function buildUSPSInternationalPackage($xml, $options)
  {


    $xml->push('Package', array('ID' => $options['packageId']));

    if ($options['weight'] != '') {
      $weight = $options['weight'];
      list($lbs, $partialLb) = explode('.', "$weight.");
      $tenth = $partialLb / 10;
      $ounces = $tenth * 16;
      $xml->element('Pounds', (string)$lbs);
      $xml->element('Ounces', (string)$ounces);
    } else {
      $xml->element('Pounds', $options['weightPounds']);
      $xml->element('Ounces', $options['weightOunces']);
    }

    if ($options['packagingType'] != '') {
      $xml->element('MailType', $options['packagingType']);
    } else {
      $xml->element('MailType', 'Package');
    }

    $xml->emptyelement('ValueOfContents');
    $xml->element('Country', $options['toCountry']);

    if ($options['packagingType'] != '') {
      $xml->element('Container', $options['packagingType']);
    } else {
      $xml->emptyelement('Container');
    }

    $girth = $options['length'] + ($options['length'] * 2) + ($options['width'] * 2);

    if ($girth > 84) $xml->element('Size', 'Large');
    else $xml->element('Size', 'Regular');

    $xml->element('Width', (string)$options['width']);
    $xml->element('Length', (string)$options['length']);
    $xml->element('Height', (string)$options['height']);
    $xml->element('Girth', (string)$girth);

    $xml->pop(); // Close Package

    return $xml;
  }


  function buildUSPSRateXml($allAvailableRates = false)
  {
    $xml = $this->core->xmlObject;

    $xml->push('RateV4Request', array('USERID' => $this->userid));
    if (!isset($this->core->packagesObject)) {
      $xml->push('Package', array('ID' => '1ST'));
      if ($allAvailableRates) {
        $xml->element('Service', 'ALL');
      } else {
        $xml->element('Service', $this->service);
      }
      if ($this->firstClassMailType != '') {
        $xml->element('FirstClassMailType', $this->firstClassMailType);
      }
      $xml->element('ZipOrigination', $this->shipCode);
      $xml->element('ZipDestination', $this->toCode);

      // Calculate weight in lbs and ounces based on weight parameter
      if ($this->weight != '') {
        $weight = $this->weight;
        list($lbs, $partialLb) = explode('.', "$weight.");
        $tenth = $partialLb / 10;
        $ounces = $tenth * 16;
        $xml->element('Pounds', (string)$lbs);
        $xml->element('Ounces', (string)$ounces);
      } else {
        $xml->element('Pounds', $this->weightPounds);
        $xml->element('Ounces', $this->weightOunces);
      }

      if ($this->packagingType != '') {
        $xml->element('Container', $this->packagingType);
      } else {
        $xml->emptyelement('Container');
      }

      $girth = $this->length + ($this->height * 2) + ($this->width * 2);

      if ($girth > 84) $xml->element('Size', 'Large');
      else $xml->element('Size', 'Regular');

      $xml->element('Width', $this->width);
      $xml->element('Length', $this->length);
      $xml->element('Height', $this->height);
      $xml->element('Girth', $girth);

      $xml->element('Machinable', 'false');
      $xml->pop(); // Close Package
      $xmlString = $xml->getXml();
    } else {
      $xmlString = $xml->getXml();
      $xmlString .= $this->core->packagesObject->getXml();
    }
    $xmlString .= '</RateV4Request>' . "
";

    return 'API=RateV4&XML=' . $xmlString;
  }


  function buildUSPSInternationalRateXml($allAvailableRates = false)
  {
    $xmlString = "";

    $country = (strlen($this->toCountry) == 2)
      ? $this->core->getCountryName($this->toCountry)
      : $this->toCountry;

    $xml = $this->core->xmlObject;
    $xml->push('IntlRateV2Request', array('USERID' => $this->userid));

    $xml->element('Revision', '2');

    if (!isset($this->core->packagesObject)) {

      $options = array(
        'packageId'     => '1ST',
        'toCountry'     => $country,
        'weightPounds'  => $this->weightPounds,
        'weightOunces'  => $this->weightOunces,
        'weight'        => $this->weight,
        'packagingType' => $this->packagingType,
        'width'         => $this->width,
        'height'        => $this->height,
        'length'        => $this->length
      );
      $xml = $this->buildUSPSInternationalPackage($xml, $options);
      $xmlString = $xml->getXml();

    } else {
      $xmlString = $xml->getXml();
      $xmlString .= $this->core->packagesObject->getXml();
    }

    $xmlString .= '</IntlRateV2Request>' . "
";
    return 'API=IntlRateV2&XML=' . $xmlString;
  }


  private function getFEDEXRate($allAvailableRates = false)
  {

    $xmlString = $this->buildFEDEXRateXml($allAvailableRates);

    $this->core->request($xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  function addPackageToFEDEXShipment($package)
  {

    if (!isset($this->core->packagesObject)) {
      $this->core->packagesObject = new xmlBuilder(true);
    }

    $xml = $this->core->packagesObject;

    $xml->push('ns:RequestedPackageLineItems');
    $xml->element('ns:SequenceNumber', '1');
    if ($this->insuredValue != '' && $this->insuredCurrency != '') {
      $xml->push('ns:InsuredValue');
      $xml->element('ns:Currency', $this->insuredCurrency);
      $xml->element('ns:Amount', $this->insuredValue);
      $xml->pop(); // end InsuredValue
    }
    $xml->push('ns:Weight');
    $xml->element('ns:Units', $package->weightUnit);
    $xml->element('ns:Value', $package->weight);
    $xml->pop(); // end Weight
    if ($package->length != '') {
      $xml->push('ns:Dimensions');
      if ($package->length != '') {
        $xml->element('ns:Length', $package->length);
      }
      if ($package->width != '') {
        $xml->element('ns:Width', $package->width);
      }
      if ($package->height != '') {
        $xml->element('ns:Height', $package->height);
      }
      $xml->element('ns:Units', $package->lengthUnit);
      $xml->pop(); // end Dimensions
    }
    if ($this->signatureType != '') {
      $xml->push('ns:SpecialServicesRequested');
      $xml->element('ns:SpecialServiceTypes', 'SIGNATURE_OPTION');
      $xml->push('ns:SignatureOptionDetail');
      $xml->element('ns:OptionType', $this->signatureType);
      $xml->pop();
      $xml->pop(); // end ShipmentSpecialServicesRequested
    }
    $xml->pop(); // end RequestedPackageLineItems

    $this->core->packagesObject = $xml;
    $this->packageCount++;
    return true;
  }


  function buildFEDEXRateXml($allAvailableRates = false)
  {
    $xml = $this->core->xmlObject;
    $xml->push('ns:RateRequest', array('xmlns:ns' => 'http://fedex.com/ws/rate/v7', 'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation' => 'http://fedex.com/ws/rate/v7 RateService v7.xsd'));

    $this->core->xmlObject = $xml;
    $this->core->access();
    $xml = $this->core->xmlObject;

    $xml->push('ns:Version');
    $xml->element('ns:ServiceId', 'crs');
    $xml->element('ns:Major', '7');
    $xml->pop(); // end Version
    $xml->element('ns:ReturnTransitAndCommit', 'true');
    $xml->push('ns:RequestedShipment');
    if (!$allAvailableRates) {
      $xml->element('ns:ServiceType', $this->service);
    }
    $xml->element('ns:PackagingType', $this->packagingType);
    $xml->push('ns:Shipper');
    $xml->push('ns:Address');
    $xml->element('ns:StreetLines', $this->shipAddr1);
    $xml->element('ns:City', $this->shipCity);
    $xml->element('ns:StateOrProvinceCode', $this->shipState);
    $xml->element('ns:PostalCode', $this->shipCode);
    $xml->element('ns:CountryCode', $this->shipCountry);
    $xml->pop(); // end Address
    $xml->pop(); // end Shipper
    $xml->push('ns:Recipient');
    $xml->element('ns:AccountNumber', 'ACCOUNT');
    if ($this->toName != '' || $this->toCompany != '') {
      $xml->push('ns:Contact');
      if ($this->toName != '') {
        $xml->element('ns:PersonName', $this->toName);
      }
      if ($this->toCompany != '') {
        $xml->element('ns:CompanyName', $this->toCompany);
      }
      $xml->pop(); // end Contact
    }
    $xml->push('ns:Address');
    $xml->element('ns:PostalCode', $this->toCode);
    $xml->element('ns:CountryCode', $this->toCountry);
    if ($this->residential != '') {
      $xml->element('ns:Residential', $this->residential);
    }
    $xml->pop(); // end Address
    $xml->pop(); // end Recipient
    if ($this->saturdayDelivery == 'YES') {
      $xml->push('ns:SpecialServicesRequested');
      $xml->element('ns:SpecialServiceTypes', 'SATURDAY_DELIVERY');
      $xml->pop(); // end ShipmentSpecialServicesRequested
    }
    $xml->element('ns:RateRequestTypes', 'LIST');
    $xml->element('ns:PackageCount', ($this->packageCount == 0) ? '1' : $this->packageCount);
    $xml->element('ns:PackageDetail', 'INDIVIDUAL_PACKAGES');
    if (!isset($this->core->packagesObject)) {
      $xml->push('ns:RequestedPackageLineItems');
      $xml->element('ns:SequenceNumber', '1');
      if ($this->insuredValue != '' && $this->insuredCurrency != '') {
        $xml->push('ns:InsuredValue');
        $xml->element('ns:Currency', $this->insuredCurrency);
        $xml->element('ns:Amount', $this->insuredValue);
        $xml->pop(); // end InsuredValue
      }
      $xml->push('ns:Weight');
      $xml->element('ns:Units', $this->weightUnit);
      $xml->element('ns:Value', $this->weight);
      $xml->pop(); // end Weight
      if ($this->length != '') {
        $xml->push('ns:Dimensions');
        if ($this->length != '') {
          $xml->element('ns:Length', $this->length);
        }
        if ($this->width != '') {
          $xml->element('ns:Width', $this->width);
        }
        if ($this->height != '') {
          $xml->element('ns:Height', $this->height);
        }
        $xml->element('ns:Units', $this->lengthUnit);
        $xml->pop(); // end Dimensions
      }
      if ($this->signatureType != '') {
        $xml->push('ns:SpecialServicesRequested');
        $xml->element('ns:SpecialServiceTypes', 'SIGNATURE_OPTION');
        $xml->push('ns:SignatureOptionDetail');
        $xml->element('ns:OptionType', $this->signatureType);
        $xml->pop();
        $xml->pop(); // end ShipmentSpecialServicesRequested
      }
      $xml->pop(); // end RequestedPackageLineItems
    } else {
      $xmlString = $xml->getXml();
      $xmlString .= $this->core->packagesObject->getXml();
      $xmlString .= '</ns:RequestedShipment>' . "
";
      $xmlString .= '</ns:RateRequest>' . "
";
      return $xmlString;
    }
    $xml->pop(); // end RequestedShipment
    $xml->pop(); // end RateRequest

    $xmlString = $xml->getXml();
    return $xmlString;
  }


  // In order to allow users to override defaults or specify obsecure UPS
  // data, this function allows you to set any of the variables that this class uses
  function setParameter($param, $value)
  {
    $value = rocketshipit_getParameter($param, $value, $this->carrier);
    $this->{$param} = $value;
  }

  // Checks the country to see if the request is International
  function isInternational($country)
  {
    if ($country == '' || $country == 'US' || $country == $this->core->getCountryName('US')) {
      return false;
    }
    return true;
  }
}


/**
 * Main class for getting time in transit information
 *
 */
class RocketShipTimeInTransit
{
  function __Construct($carrier, $license = '', $username = '', $password = '')
  {
    rocketshipit_validateCarrier($carrier);

    $this->OKparams = rocketshipit_getOKparams($carrier);
    $this->carrier = strtoupper($carrier);
    switch (strtoupper($carrier)) {
      case 'UPS':
        $this->core = new ups($license, $username, $password); // This class depends on ups

        foreach ($this->OKparams as $param) {
          $this->setParameter($param, '');
        }

        if ($license != '') {
          $this->core->license = $license;
        }
        if ($username != '') {
          $this->core->username = $username;
        }
        if ($password != '') {
          $this->core->password = $password;
        }

        break;
      case 'FEDEX':
        $this->core = new fedex();
        foreach ($this->OKparams as $param) {
          $this->setParameter($param, '');
        }
        break;
      case 'USPS':
        $this->core = new usps();

        foreach ($this->OKparams as $param) {
          $this->setParameter($param, '');
        }
        break;
      default:
        exit("Unknown carrier $carrier in RocketShipTimeInTransit.");
    }

  }

  /**
   * Returns a Time in Transit resposne from the carrier.
   */
  function getTimeInTransit()
  {
    switch ($this->carrier) {
      case 'UPS':
        return $this->getUPSTimeInTransit();
      case 'USPS':
        return $this->getUSPSTimeInTransit();
      case 'FEDEX':
        return $this->getFEDEXTimeInTransit();
    }
  }


  function getUPSTimeInTransit()
  {
    $accessXml = $this->core->xmlObject;

    $xml = new xmlBuilder();
    $xml->push('TimeInTransitRequest', array('xml:lang' => 'en-US'));
    $xml->push('Request');
    $xml->push('TransactionReference'); // Not required
    $xml->element('CustomerContext', 'RocketShipIt'); // Not required
    $xml->pop(); // close TransactionReference, not required
    $xml->element('RequestAction', 'TimeInTransit');
    $xml->pop(); // end Request;
    $xml->push('TransitFrom');
    $xml->push('AddressArtifactFormat');
    $xml->element('PoliticalDivision2', $this->shipCity);
    $xml->element('CountryCode', $this->shipCountry);
    $xml->element('PostcodePrimaryLow', $this->shipCode);
    $xml->pop(); // end AddressArtifactFormat
    $xml->pop(); // end TransitFrom
    $xml->push('TransitTo');
    $xml->push('AddressArtifactFormat');
    $xml->element('PoliticalDivision2', $this->toCity);
    $xml->element('CountryCode', $this->toCountry);
    $xml->element('PostcodePrimaryLow', $this->toCode);
    $xml->pop(); // end AddressArtifactFormat
    $xml->pop(); // end TransitTo
    if ($this->weight != '') {
      $xml->push('ShipmentWeight');
      $xml->push('UnitOfMeasurement');
      $xml->element('Code', $this->weightUnit);
      $xml->element('Description', 'Pounds');
      $xml->pop(); //end UnitOfMeasurement
      $xml->element('Weight', $this->weight);
      $xml->pop(); //end ShipmentWeight
    }
    $xml->element('TotalPackagesInShipment', '1');
    // $xml->push('InvoiceLineTotal');
    //     $xml->element('CurrencyCode',$this->insuredCurrency);
    //     $xml->element('MonetaryValue',$this->monetaryValue);
    // $xml->pop(); // end InvoiceLineTotal
    $xml->element('PickupDate', $this->pickupDate);
    //$xml->element('DocumentsOnlyIndicator','');
    if ($this->monetaryValue != '') {
      $xml->push('InvoiceLineTotal');
      $xml->element('CurrencyCode', $this->insuredCurrency);
      $xml->element('MonetaryValue', $this->monetaryValue);
      $xml->pop();
    }
    $xml->pop(); // end TimeInTransitRequest

    // Convert xml object to a string
    $accessXmlString = $accessXml->getXml();
    $requestXmlString = $xml->getXml();

    $xmlString = $accessXmlString . $requestXmlString;

    $this->core->request('TimeInTransit', $xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  function getFEDEXTimeInTransit()
  {
    $xml = $this->core->xmlObject;
    $xml->push('ns:ServiceAvailabilityRequest', array('xmlns:ns' => 'http://fedex.com/ws/packagemovementinformationservice/v4', 'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation' => 'http://fedex.com/ws/packagemovementinformation/v4'));
    $this->core->xmlObject = $xml;
    $this->core->access();
    $xml = $this->core->xmlObject;

    $xml->push('ns:Version');
    $xml->element('ns:ServiceId', 'pmis');
    $xml->element('ns:Major', '4');
    $xml->element('ns:Intermediate', '0');
    $xml->element('ns:Minor', '0');
    $xml->pop(); // end Version
    $xml->push('ns:Origin');
    $xml->element('ns:PostalCode', $this->shipCode);
    $xml->element('ns:CountryCode', $this->shipCountry);
    $xml->pop(); // end Origin
    $xml->push('ns:Destination');
    $xml->element('ns:PostalCode', $this->toCode);
    $xml->element('ns:CountryCode', $this->toCountry);
    $xml->pop(); // end Destination
    $xml->element('ns:ShipDate', $this->pickupDate); // Y-m-d
    $xml->element('ns:Packaging', $this->packagingType);


    $xml->pop(); // end Request

    $xmlString = $xml->getXml();

    $this->core->request($xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  function getUSPSTimeInTransit()
  {
    $xml = $this->core->xmlObject;

    $xml->push('ExpressMailCommitmentRequest', array('USERID' => $this->userid));
    $xml->element('OriginZIP', $this->shipCode);
    $xml->element('DestinationZIP', $this->toCode);
    $xml->element('Date', $this->pickupDate);
    $xml->pop();

    $xmlString = 'API=ExpressMailCommitment&XML=' . $xml->getXml();

    $this->core->request('ShippingAPI.dll', $xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  /**
   * Sets paramaters to be used in {@link RocketShipTimeinTransit()}.
   *
   * Only valid parameters are accepted.
   * @see getOKparams()
   */
  function setParameter($param, $value)
  {
    $value = rocketshipit_getParameter($param, $value, $this->carrier);
    $this->{$param} = $value;
  }

}


/**
 * Main class for producing package objects that are later inserted into a shipment
 * @see RocketShipShipment::addPackageToShipment()
 */
class RocketShipPackage
{

  var $ups;

  function __Construct($carrier, $license = '', $username = '', $password = '')
  {
    rocketshipit_validateCarrier($carrier);

    $carrier = strtoupper($carrier);
    $this->carrier = $carrier;
    $this->OKparams = rocketshipit_getOKparams($carrier);

    // Grab defaults package attributes

    if ($carrier == 'UPS') {
      $this->core = new ups($license, $username, $password); // This class depends on ups

      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }

      if ($license != '') {
        $this->core->license = $license;
      }
      if ($username != '') {
        $this->core->username = $username;
      }
      if ($password != '') {
        $this->core->password = $password;
      }

    }
    if ($carrier == 'FEDEX') {
      $this->core = new fedex(); // This class depends on fedex
      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }

    }

    if ($carrier == 'USPS') {
      $this->core = new usps(); // This class depends on usps
      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }
    }
  }

  function setParameter($param, $value)
  {
    $value = rocketshipit_getParameter($param, $value, $this->carrier);
    $this->{$param} = $value;
  }

}


/**
 * Main Address Validation class for carrier.
 *
 * Valid carriers are: UPS, USPS, STAMPS, and FedEx.
 */
class RocketShipAddressValidate
{

  // Set variable for valid parameters
  var $OKparams;
  var $carrier; // Set variable for carrier

  function __Construct($carrier, $license = '', $username = '', $password = '')
  {
    // Validate carrier name
    rocketshipit_validateCarrier($carrier);

    $this->carrier = $carrier;
    $this->OKparams = rocketshipit_getOKparams($carrier);

    // Set up core class and grab carrier-specific defaults that are unique to the current carrier
    if ($carrier == 'UPS') {
      $this->core = new ups($license, $username, $password); // This class depends on ups

      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }

      if ($license != '') {
        $this->core->license = $license;
      }
      if ($username != '') {
        $this->core->username = $username;
      }
      if ($password != '') {
        $this->core->password = $password;
      }
    }
    if ($carrier == 'FEDEX') {
      $this->core = new fedex(); // This class depends on fedex
    }
    if ($carrier == 'STAMPS') {
      foreach ($this->OKparams as $param) {
        $this->setParameter($param, '');
      }
      $this->core = new stamps(); // This class depends on stamps
    }
  }

  /**
   * Send address data to carrier.
   *
   * This function detects carrier and executes the
   * carrier specific function.
   */
  function validate()
  {
    switch ($this->carrier) {
      case 'UPS':
        return $this->getUPSValidate();
      case 'FEDEX':
        return $this->getFEDEXValidate();
      case 'STAMPS':
        return $this->getSTAMPSValidate();
    }
  }


  // Builds xml for a rate request converts xml to a string, sends the xml to ups,
  // stores the xmlSent and xmlResponse in the ups class incase you want to see it.
  // Finally, this class returns the xml response from UPS as an array.
  private function getUPSValidate()
  {
    // Grab the auth portion of the xml from the ups class
    $accessXml = $this->core->xmlObject;

    // Start a new xml object
    $xml = new xmlBuilder();

    $xml->push('AddressValidationRequest', array('xml:lang' => 'en-US'));
    $xml->push('Request');
    $xml->push('TransactionReference'); // Not required
    $xml->element('CustomerContext', 'RocketShipIt'); // Not required
    //$xml->element('XpciVersion', '1.0'); // Not required
    $xml->pop(); // close TransactionReference, not required
    $xml->element('RequestAction', 'AV');
    $xml->pop(); // Close Request
    $xml->push('Address');
    if ($this->toCity != '') {
      $xml->element('City', $this->toCity);
    }
    if ($this->toState != '') {
      $xml->element('StateProvinceCode', $this->toState);
    }
    if ($this->toCode != '') {
      $xml->element('PostalCode', $this->toCode);
    }
    $xml->pop(); // Close Address
    $xml->pop(); // Close AddressValidationRequest

    // Convert xml object to a string appending the auth xml
    $xmlString = $accessXml->getXml() . $xml->getXml();

    // Submit the cURL call
    $this->core->request('AV', $xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  function validateStreetLevel()
  {
    switch ($this->carrier) {
      case 'UPS':
        $this->core->xmlSent = $this->buildUPSValidateStreetLevelXml();
        $this->core->xmlResponse = $this->core->request('XAV', $this->core->xmlSent);

        // Convert the xmlString to an array
        $xmlParser = new upsxmlParser();
        $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
        $xmlArray = $xmlParser->getData();

        return $xmlArray;
    }
  }


  function buildUPSValidateStreetLevelXml()
  {
    $accessXml = $this->core->xmlObject;

    $xml = new xmlBuilder();

    $xml->push('AddressValidationRequest', array('xml:lang' => 'en-US'));
    $xml->push('Request');
    $xml->push('TransactionReference'); // Not required
    $xml->element('CustomerContext', 'RocketShipIt'); // Not required
    //$xml->emptyelement('ToolVersion');
    $xml->pop(); // close TransactionReference, not required
    $xml->element('RequestAction', 'XAV');
    $xml->element('RequestOption', '3');
    $xml->pop(); // close Request
    $xml->push('AddressKeyFormat');
    $xml->element('ConsigneeName', $this->toName);
    $xml->element('AttentionName', $this->toName);
    $xml->element('PoliticalDivision1', $this->toState);
    $xml->element('PoliticalDivision2', $this->toCity);
    $xml->element('AddressLine', $this->toAddr1);
    $xml->element('BuildingName', $this->toAddr2);
    $xml->element('PostcodePrimaryLow', $this->toCode);
    $xml->element('PostcodeExtendedLow', $this->toExtendedCode);
    $xml->element('CountryCode', $this->toCountry);
    $xml->pop(); // close AddressKeyFormat
    $xml->pop(); // close AddressValidationRequest

    $xmlString = $accessXml->getXml() . $xml->getXml();
    return $xmlString;
  }


  // Not complete waiting on fedex to authenticate account
  private function getFEDEXValidate()
  {
    $xml = $this->core->xmlObject;
    $xml->push('ns:AddressValidationRequest', array('xmlns:ns' => 'http://fedex.com/ws/addressvalidation/v2', 'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema', 'xsi:schemaLocation' => 'http://fedex.com/ws/addressvalidation/v2'));
    $this->core->xmlObject = $xml;
    $this->core->access();
    $xml = $this->core->xmlObject;

    $xml->push('ns:Version');
    $xml->element('ns:ServiceId', 'aval');
    $xml->element('ns:Major', '2');
    $xml->element('ns:Intermediate', '0');
    $xml->element('ns:Minor', '0');
    $xml->pop(); // end Version
    $xml->element('ns:RequestTimestamp', date("c"));
    $xml->push('ns:AddressesToValidate');
    $xml->pop(); // end AddressToValidate

    $xml->pop(); // end AddressValidationRequest

    $xmlString = $xml->getXml();

    $this->core->request($xmlString);

    // Convert the xmlString to an array
    $xmlParser = new upsxmlParser();
    $xmlArray = $xmlParser->xmlparser($this->core->xmlResponse);
    $xmlArray = $xmlParser->getData();
    return $xmlArray;
  }


  // Function that allows parameters to be set
  function setParameter($param, $value)
  {
    $value = rocketshipit_getParameter($param, $value, $this->carrier);
    $this->{$param} = $value;
  }
}


/**
 * Core FedEx Class
 *
 * Used internally to send data, set debug information, change
 * urls, and build xml for FedEx
 */
class fedex
{
  function __Construct()
  {
    // Grab the license, username, password for defaults
    $this->fedexkey = getFEDEXDefault('key');
    $this->password = getFEDEXDefault('password');
    $this->accountNumber = getFEDEXDefault('accountNumber');
    $this->meterNumber = getFEDEXDefault('meterNumber');

    $this->xmlObject = new xmlBuilder(true);
    $this->debugMode = getGenericDefault('debugMode');
    $this->setTestingMode($this->debugMode);
  }

  // Create the auth xml that is used in every request
  function access()
  {
    $xml = $this->xmlObject;
    $xml->push('ns:WebAuthenticationDetail');
    $xml->push('ns:UserCredential');
    $xml->element('ns:Key', $this->fedexkey);
    $xml->element('ns:Password', $this->password);
    $xml->pop(); // end UserCredential
    $xml->pop(); // end WebAuthenticationDetail
    $xml->push('ns:ClientDetail');
    $xml->element('ns:AccountNumber', $this->accountNumber);
    $xml->element('ns:MeterNumber', $this->meterNumber);
    $xml->pop(); // end ClientDetail

    $this->xmlObject = $xml;

    $this->accessRequest = true;
    return $this->xmlObject->getXml(); // returns xmlstring, but probably not used
  }

  function request($xml, $isMultiRequest = false)
  {
    // This function is the only function that actually transmits and recieves data
    // from UPS. All classes use this to send XML to FedEx servers.
    $this->xmlSent = $xml; // Store the xmlSent for debugging
    $output = preg_replace('/[\s+]{2,}/', '', $xml);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->fedexUrl . '/xml');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Request timeout
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $output);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    if ($isMultiRequest) {
      return $ch;
    }
    $curlReturned = curl_exec($ch);
    curl_close($ch);
    //exit ($curlReturned);
    $this->curlReturned = $curlReturned; // Store curl response for debugging

    // Find out if the UPS service is down
    preg_match_all('/HTTP\/1\.\d\s(\d+)/', $curlReturned, $matches);
    foreach ($matches[1] as $key=> $value) {
      if ($value != 100 && $value != 200) {
        throw new RuntimeException("The FedEx service seems to be down with HTTP/1.1 $value");
      } else {
        $response = strstr($curlReturned, '<?'); // Separate the html header and the actual XML because we turned CURLOPT_HEADER to 1
        $this->xmlResponse = $response;
        return $response;
      }
    }
  }

  // This function checks the value of debugMode and changes the FedEx url accordingly.  This is because
  // FedEx has a testing and production server.
  function setTestingMode($bool)
  {
    if ($bool == 1) {
      $this->debugMode = true;
      $this->fedexUrl = 'https://gatewaybeta.fedex.com/xml'; // Don't put a trailing slash here or world will collide.
    } else {
      $this->debugMode = false;
      $this->fedexUrl = 'https://gateway.fedex.com/xml';
    }
    return true;
  }

  // Function to provide vital enviornment and request/response debugging details
  function debug()
  {
    $debugInfo = '<pre>';
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'RocketShipIt Debug Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->debugMode)) {
      $debugInfo .= "debugMode = $this->debugMode";
    }
    $debugInfo .= "

";
    if (isset($this->xmlPrevSent)) {
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= 'XML Prev Sent' . "
";
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= htmlentities($this->xmlPrevSent) . "
";
      $debugInfo .= "

";
    }
    if (isset($this->xmlPrevResponse)) {
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= 'XML Prev Response' . "
";
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= htmlentities($this->xmlPrevResponse) . "
";
      $debugInfo .= "

";
    }
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'XML Sent' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->xmlSent)) {
      $debugInfo .= htmlentities($this->xmlSent) . "
";
    } else {
      $debugInfo .= 'xmlSent was not set' . "
";
    }
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'XML Response' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->xmlResponse)) {
      $debugInfo .= htmlentities($this->xmlResponse) . "
";
    } else {
      $debugInfo .= 'xmlResponse was not set' . "
";
    }
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'PHP Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= phpversion();
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'cURL Return Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->curlReturned)) {
      $debugInfo .= htmlentities($this->curlReturned);
    }
    $debugInfo .= "

";
    $debugInfo .= '</pre>';
    return $debugInfo;
  }

}


/**
 * Core UPS Class
 *
 * Used internally to send data, set debug information, change
 * urls, and build xml
 */
class ups
{

  function __Construct($license = '', $username = '', $password = '')
  {

    // Grab the license, username, password from defaults if they
    // are not set manually.
    if ($license == '') {
      $this->license = getUPSDefault('license');
    } else {
      $this->license = $license;
    }

    if ($username == '') {
      $this->username = getUPSDefault('username');
    } else {
      $this->username = $username;
    }

    if ($password == '') {
      $this->password = getUPSDefault('password');
    } else {
      $this->password = $password;
    }

    $this->debugMode = getGenericDefault('debugMode');
    $this->setTestingMode($this->debugMode);

    // Create a new xmlObject to be used by access and other classes
    // This object will be used all the way through, until the final xmlObject
    // is converted to a string just before sending to UPS
    $this->xmlObject = new xmlBuilder(false);
    $this->access();
  }

  // Build the access XML to be used in EVERY request to UPS
  function access()
  {
    $xml = $this->xmlObject;
    $xml->push('AccessRequest', array('xml:lang' => 'en-US'));
    $xml->element('AccessLicenseNumber', $this->license);
    $xml->element('UserId', $this->username);
    $xml->element('Password', $this->password);
    $xml->pop();

    $this->xmlObject = $xml;

    $this->accessRequest = true; // Old check, probably safe to remove later
    return $this->xmlObject->getXml(); // returns xmlstring, but probably not used
  }

  function request($type, $xml, $isMultiRequest = 0)
  {
    // This function is the only function that actually transmits and recieves data
    // from UPS. All classes use this to send XML to UPS servers.
    if ($this->accessRequest != true) {
      die('access function has not been set');
    } else {
      $this->xmlSent = $xml;
      $output = preg_replace('/[\s+]{2,}/', '', $xml);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->upsUrl . '/ups.app/xml/' . $type);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $output);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      if ($isMultiRequest) {
        return $ch;
      }
      $curlReturned = curl_exec($ch);
      curl_close($ch);
      $this->curlReturned = $curlReturned;
      // exit ($curlReturned);

      // Find out if the UPS service is down
      preg_match_all('/HTTP\/1\.\d\s(\d+)/', $curlReturned, $matches);
      foreach ($matches[1] as $key=> $value) {
        if ($value != 100 && $value != 200) {
          throw new RuntimeException("The UPS service seems to be down with HTTP/1.1 $value");
          return false;
        } else {
          $response = strstr($curlReturned, '<?'); // Separate the html header and the actual XML because we turned CURLOPT_HEADER to 1
          $this->xmlResponse = $response;
          return $response;
        }
      }
    }
  }

  // This function checks the value of debugMode and changes the UPS url accordingly.  This is because
  // UPS has a testing and production server.
  function setTestingMode($bool)
  {
    if ($bool == 1) {
      $this->debugMode = true;
      $this->upsUrl = 'https://wwwcie.ups.com'; // Don't put a trailing slash here or world will collide.
    } else {
      $this->debugMode = false;
      $this->upsUrl = 'https://www.ups.com';
    }
    return true;
  }

  // I am not sure why this is in here or if anything actually uses it.  Maybe in the future?
  function throwError($error)
  {
    if ($this->debugMode) {
      die($error);
    } else {
      return $error;
    }
  }

  function getServiceDescriptionFromCode($code)
  {
    switch ($code) {
      case '01':
        return 'UPS Next Day Air';
      case '02':
        return 'UPS 2nd Day Air';
      case '03':
        return 'UPS Ground';
      case '07':
        return 'UPS Worldwide Express';
      case '08':
        return 'UPS Worldwide Expedited';
      case '11':
        return 'UPS Standard';
      case '12':
        return 'UPS 3 Day Select';
      case '13':
        return 'UPS Next Day Air Saver';
      case '14':
        return 'UPS Next Day Air Early AM';
      case '54':
        return 'UPS Worldwide Express Plus';
      case '59':
        return 'UPS Second Day Air AM';
      case '65':
        return 'UPS Saver';
      case '82':
        return 'UPS Today Standard';
      case '83':
        return 'UPS Today Dedicated';
      case '84':
        return 'UPS Today Intercity';
      case '85':
        return 'UPS Today Express';
      case '86':
        return 'UPS Today Express Saver';
      default:
        return 'Unknown service code';
    }
  }

  function debug()
  {
    $debugInfo = '<pre>';
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'RocketShipIt Debug Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->debugMode)) {
      $debugInfo .= "debugMode = $this->debugMode";
    }
    $debugInfo .= "

";
    if (isset($this->xmlPrevSent)) {
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= 'XML Prev Sent' . "
";
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= htmlentities($this->xmlPrevSent) . "
";
      $debugInfo .= "

";
    }
    if (isset($this->xmlPrevResponse)) {
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= 'XML Prev Response' . "
";
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= htmlentities($this->xmlPrevResponse) . "
";
      $debugInfo .= "

";
    }
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'XML Sent' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->xmlSent)) {
      $debugInfo .= htmlentities($this->xmlSent) . "
";
    } else {
      $debugInfo .= 'xmlSent was not set' . "
";
    }
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'XML Response' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->xmlResponse)) {
      $debugInfo .= htmlentities($this->xmlResponse) . "
";
    } else {
      $debugInfo .= 'xmlResponse was not set' . "
";
    }
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'PHP Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= phpversion();
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'cURL Return Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->curlReturned)) {
      $debugInfo .= htmlentities($this->curlReturned);
    }
    $debugInfo .= "

";
    $debugInfo .= '</pre>';
    return $debugInfo;
  }
}


/**
 * Core USPS Class
 *
 * Used internally to send data, set debug information, change
 * urls, and build xml
 */
class usps
{
  function __Construct()
  {
    // Grab the license, username, password for defaults
    $this->userid = getUSPSDefault('usps');
    $this->setTestingMode(0);

    // Create a new xmlObject to be used by access and other classes
    // This object will be used all the way through, until the final xmlObject
    // is converted to a string just before sending to USPS
    $this->xmlObject = new xmlBuilder(true);
  }

  function request($type, $xml, $isMultiRequest = false)
  {
    // This function is the only function that actually transmits and recieves data
    // from USPS. All classes use this to send XML to USPS servers.
    $this->xmlSent = $xml;
    $output = preg_replace('/[\s+]{2,}/', '', $xml);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->uspsUrl . '/' . $type);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $output);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($isMultiRequest) {
      return $ch;
    }
    $curlReturned = curl_exec($ch);
    curl_close($ch);
    //exit ($curlReturned);
    $this->curlReturned = $curlReturned;

    // Find out if the UPS service is down
    preg_match_all('/HTTP\/1\.\d\s(\d+)/', $curlReturned, $matches);
    foreach ($matches[1] as $key=> $value) {
      if ($value != 100 && $value != 200 && $value != 405) {
        return false;
      } else {
        $response = strstr($curlReturned, '<?'); // Separate the html header and the actual XML because we turned CURLOPT_HEADER to 1
        $this->xmlResponse = $response;
        return $response;
      }
    }
  }

  function setTestingMode($bool)
  {
    if ($bool == 1) {
      $this->uspsUrl = 'http://Production.ShippingAPIs.com';
    } else {
      $this->uspsUrl = 'http://Production.ShippingAPIs.com';
    }
  }

  function debug()
  {
    $debugInfo = '<pre>';
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'RocketShipIt Debug Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->debugMode)) {
      $debugInfo .= "debugMode = $this->debugMode";
    }
    $debugInfo .= "

";
    if (isset($this->xmlPrevSent)) {
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= 'XML Prev Sent' . "
";
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= htmlentities($this->xmlPrevSent) . "
";
      $debugInfo .= "

";
    }
    if (isset($this->xmlPrevResponse)) {
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= 'XML Prev Response' . "
";
      $debugInfo .= '--------------------------------------------------' . "
";
      $debugInfo .= htmlentities($this->xmlPrevResponse) . "
";
      $debugInfo .= "

";
    }
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'XML Sent' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->xmlSent)) {
      $debugInfo .= htmlentities($this->xmlSent) . "
";
    } else {
      $debugInfo .= 'xmlSent was not set' . "
";
    }
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'XML Response' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->xmlResponse)) {
      $debugInfo .= htmlentities($this->xmlResponse) . "
";
    } else {
      $debugInfo .= 'xmlResponse was not set' . "
";
    }
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'PHP Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= phpversion();
    $debugInfo .= "

";
    $debugInfo .= '--------------------------------------------------' . "
";
    $debugInfo .= 'cURL Return Information' . "
";
    $debugInfo .= '--------------------------------------------------' . "
";
    if (isset($this->curlReturned)) {
      $debugInfo .= htmlentities($this->curlReturned);
    }
    $debugInfo .= "

";
    $debugInfo .= '</pre>';
    return $debugInfo;
  }

  function getCountryName($countryCode)
  {
    $countries = array(
      "AF" => "Afghanistan"
    , "AX" => "�land Islands"
    , "AL" => "Albania"
    , "DZ" => "Algeria"
    , "AS" => "American Samoa"
    , "AD" => "Andorra"
    , "AO" => "Angola"
    , "AI" => "Anguilla"
    , "AQ" => "Antarctica"
    , "AG" => "Antigua and Barbuda"
    , "AR" => "Argentina"
    , "AM" => "Armenia"
    , "AW" => "Aruba"
    , "AU" => "Australia"
    , "AT" => "Austria"
    , "AZ" => "Azerbaijan"
    , "BS" => "Bahamas"
    , "BH" => "Bahrain"
    , "BD" => "Bangladesh"
    , "BB" => "Barbados"
    , "BY" => "Belarus"
    , "BE" => "Belgium"
    , "BZ" => "Belize"
    , "BJ" => "Benin"
    , "BM" => "Bermuda"
    , "BT" => "Bhutan"
    , "BO" => "Bolivia, Plurinational State of"
    , "BQ" => "Bonaire, Saint Eustatius and Saba"
    , "BA" => "Bosnia and Herzegovina"
    , "BW" => "Botswana"
    , "BV" => "Bouvet Island"
    , "BR" => "Brazil"
    , "IO" => "British Indian Ocean Territory"
    , "BN" => "Brunei Darussalam"
    , "BG" => "Bulgaria"
    , "BF" => "Burkina Faso"
    , "BI" => "Burundi"
    , "KH" => "Cambodia"
    , "CM" => "Cameroon"
    , "CA" => "Canada"
    , "CV" => "Cape Verde"
    , "KY" => "Cayman Islands"
    , "CF" => "Central African Republic"
    , "TD" => "Chad"
    , "CL" => "Chile"
    , "CN" => "China"
    , "CX" => "Christmas Island"
    , "CC" => "Cocos (Keeling) Islands"
    , "CO" => "Colombia"
    , "KM" => "Comoros"
    , "CG" => "Congo"
    , "CD" => "Congo, the Democratic Republic of the"
    , "CK" => "Cook Islands"
    , "CR" => "Costa Rica"
    , "CI" => "C�te d'Ivoire"
    , "HR" => "Croatia"
    , "CU" => "Cuba"
    , "CW" => "Cura�ao"
    , "CY" => "Cyprus"
    , "CZ" => "Czech Republic"
    , "DK" => "Denmark"
    , "DJ" => "Djibouti"
    , "DM" => "Dominica"
    , "DO" => "Dominican Republic"
    , "EC" => "Ecuador"
    , "EG" => "Egypt"
    , "SV" => "El Salvador"
    , "GQ" => "Equatorial Guinea"
    , "ER" => "Eritrea"
    , "EE" => "Estonia"
    , "ET" => "Ethiopia"
    , "FK" => "Falkland Islands (Malvinas)"
    , "FO" => "Faroe Islands"
    , "FJ" => "Fiji"
    , "FI" => "Finland"
    , "FR" => "France"
    , "GF" => "French Guiana"
    , "PF" => "French Polynesia"
    , "TF" => "French Southern Territories"
    , "GA" => "Gabon"
    , "GM" => "Gambia"
    , "GE" => "Georgia"
    , "DE" => "Germany"
    , "GH" => "Ghana"
    , "GI" => "Gibraltar"
    , "GR" => "Greece"
    , "GL" => "Greenland"
    , "GD" => "Grenada"
    , "GP" => "Guadeloupe"
    , "GU" => "Guam"
    , "GT" => "Guatemala"
    , "GG" => "Guernsey"
    , "GN" => "Guinea"
    , "GW" => "Guinea-Bissau"
    , "GY" => "Guyana"
    , "HT" => "Haiti"
    , "HM" => "Heard Island and McDonald Islands"
    , "VA" => "Holy See (Vatican City State)"
    , "HN" => "Honduras"
    , "HK" => "Hong Kong"
    , "HU" => "Hungary"
    , "IS" => "Iceland"
    , "IN" => "India"
    , "ID" => "Indonesia"
    , "IR" => "Iran, Islamic Republic of"
    , "IQ" => "Iraq"
    , "IE" => "Ireland"
    , "IM" => "Isle of Man"
    , "IL" => "Israel"
    , "IT" => "Italy"
    , "JM" => "Jamaica"
    , "JP" => "Japan"
    , "JE" => "Jersey"
    , "JO" => "Jordan"
    , "KZ" => "Kazakhstan"
    , "KE" => "Kenya"
    , "KI" => "Kiribati"
    , "KP" => "Korea, Democratic People's Republic of"
    , "KR" => "Korea, Republic of"
    , "KW" => "Kuwait"
    , "KG" => "Kyrgyzstan"
    , "LA" => "Lao People's Democratic Republic"
    , "LV" => "Latvia"
    , "LB" => "Lebanon"
    , "LS" => "Lesotho"
    , "LR" => "Liberia"
    , "LY" => "Libyan Arab Jamahiriya"
    , "LI" => "Liechtenstein"
    , "LT" => "Lithuania"
    , "LU" => "Luxembourg"
    , "MO" => "Macao"
    , "MK" => "Macedonia, the former Yugoslav Republic of"
    , "MG" => "Madagascar"
    , "MW" => "Malawi"
    , "MY" => "Malaysia"
    , "MV" => "Maldives"
    , "ML" => "Mali"
    , "MT" => "Malta"
    , "MH" => "Marshall Islands"
    , "MQ" => "Martinique"
    , "MR" => "Mauritania"
    , "MU" => "Mauritius"
    , "YT" => "Mayotte"
    , "MX" => "Mexico"
    , "FM" => "Micronesia, Federated States of"
    , "MD" => "Moldova, Republic of"
    , "MC" => "Monaco"
    , "MN" => "Mongolia"
    , "ME" => "Montenegro"
    , "MS" => "Montserrat"
    , "MA" => "Morocco"
    , "MZ" => "Mozambique"
    , "MM" => "Myanmar"
    , "NA" => "Namibia"
    , "NR" => "Nauru"
    , "NP" => "Nepal"
    , "NL" => "Netherlands"
    , "NC" => "New Caledonia"
    , "NZ" => "New Zealand"
    , "NI" => "Nicaragua"
    , "NE" => "Niger"
    , "NG" => "Nigeria"
    , "NU" => "Niue"
    , "NF" => "Norfolk Island"
    , "MP" => "Northern Mariana Islands"
    , "NO" => "Norway"
    , "OM" => "Oman"
    , "PK" => "Pakistan"
    , "PW" => "Palau"
    , "PS" => "Palestinian Territory, Occupied"
    , "PA" => "Panama"
    , "PG" => "Papua New Guinea"
    , "PY" => "Paraguay"
    , "PE" => "Peru"
    , "PH" => "Philippines"
    , "PN" => "Pitcairn"
    , "PL" => "Poland"
    , "PT" => "Portugal"
    , "PR" => "Puerto Rico"
    , "QA" => "Qatar"
    , "RE" => "R�union"
    , "RO" => "Romania"
    , "RU" => "Russian Federation"
    , "RW" => "Rwanda"
    , "BL" => "Saint Barth�lemy"
    , "SH" => "Saint Helena, Ascension and Tristan da Cunha"
    , "KN" => "Saint Kitts and Nevis"
    , "LC" => "Saint Lucia"
    , "MF" => "Saint Martin (French part)"
    , "PM" => "Saint Pierre and Miquelon"
    , "VC" => "Saint Vincent and the Grenadines"
    , "WS" => "Samoa"
    , "SM" => "San Marino"
    , "ST" => "Sao Tome and Principe"
    , "SA" => "Saudi Arabia"
    , "SN" => "Senegal"
    , "RS" => "Serbia"
    , "SC" => "Seychelles"
    , "SL" => "Sierra Leone"
    , "SG" => "Singapore"
    , "SX" => "Sint Maarten (Dutch part)"
    , "SK" => "Slovakia"
    , "SI" => "Slovenia"
    , "SB" => "Solomon Islands"
    , "SO" => "Somalia"
    , "ZA" => "South Africa"
    , "GS" => "South Georgia and the South Sandwich Islands"
    , "ES" => "Spain"
    , "LK" => "Sri Lanka"
    , "SD" => "Sudan"
    , "SR" => "Suriname"
    , "SJ" => "Svalbard and Jan Mayen"
    , "SZ" => "Swaziland"
    , "SE" => "Sweden"
    , "CH" => "Switzerland"
    , "SY" => "Syrian Arab Republic"
    , "TW" => "Taiwan, Province of China"
    , "TJ" => "Tajikistan"
    , "TZ" => "Tanzania, United Republic of"
    , "TH" => "Thailand"
    , "TL" => "Timor-Leste"
    , "TG" => "Togo"
    , "TK" => "Tokelau"
    , "TO" => "Tonga"
    , "TT" => "Trinidad and Tobago"
    , "TN" => "Tunisia"
    , "TR" => "Turkey"
    , "TM" => "Turkmenistan"
    , "TC" => "Turks and Caicos Islands"
    , "TV" => "Tuvalu"
    , "UG" => "Uganda"
    , "UA" => "Ukraine"
    , "AE" => "United Arab Emirates"
    , "GB" => "United Kingdom"
    , "US" => "United States"
    , "UM" => "United States Minor Outlying Islands"
    , "UY" => "Uruguay"
    , "UZ" => "Uzbekistan"
    , "VU" => "Vanuatu"
    , "VE" => "Venezuela, Bolivarian Republic of"
    , "VN" => "Viet Nam"
    , "VG" => "Virgin Islands, British"
    , "VI" => "Virgin Islands, U.S."
    , "WF" => "Wallis and Futuna"
    , "EH" => "Western Sahara"
    , "YE" => "Yemen"
    , "ZM" => "Zambia"
    , "ZW" => "Zimbabwe"
    );

    if (isset($countries[$countryCode])) {
      return $countries[$countryCode];
    }

    return NULL;
  }
}


/**
 * Queueing class that is responsible for simultaneous requests
 *
 * This class will take a RocketShipIt object such as
 * RocketShipRate, RocketShipShipment, etc. and
 * add them to a queue they will then be executed
 * with curl simultaneously.
 */
class RocketShipQueue
{

  var $queue;
  var $mh;
  var $activeCurlHandles;

  function __construct()
  {
    $this->queue = array();
    $this->mh = curl_multi_init();
    $this->activeCurlHandles = array();
  }

  function append($obj)
  {
    array_push($this->queue, $obj);
    return 1;
  }

  function prepend($obj)
  {
    array_unshift($this->queue, $obj);
    return 1;
  }

  function getCurlHandle($obj)
  {
    $c = get_class($obj);

    switch ($c) {
      case "RocketShipRate":
        if ($obj->carrier == 'FEDEX') {
          $xml = $obj->buildFEDEXRateXml();
          $ch = $obj->core->request($xml, true);
          return $ch;
        } elseif ($obj->carrier == 'UPS') {
          $xml = $obj->buildUPSRateXml();
          $ch = $obj->core->request('Rate', $xml, true);
          return $ch;
        } elseif ($obj->carrier == 'USPS') {
          $xml = $obj->buildUSPSRateXml();
          $ch = $obj->core->request('ShippingAPI.dll', $xml, true);
          return $ch;
        }
      case 'RocketShipShipment':
        return 'it is a shipment';
    }
  }

  function executeCurlMultiRequest()
  {
    $a = array();

    $active = null;
    //execute the handles
    do {
      $mrc = curl_multi_exec($this->mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
      if (curl_multi_select($this->mh) != -1) {
        do {
          $mrc = curl_multi_exec($this->mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
      }
    }
    foreach ($this->activeCurlHandles as $ch) {
      $response = curl_multi_getcontent($ch);
      $xmlResponse = strstr($response, '<?');

      // Convert the xmlString to an array
      $xmlParser = new upsxmlParser();
      $xmlArray = $xmlParser->xmlparser($xmlResponse);
      $xmlArray = $xmlParser->getData();

      array_push($a, $xmlArray);
      curl_multi_remove_handle($this->mh, $ch);
    }
    curl_multi_close($this->mh);
    return $a;
  }

  function execute($max = 0)
  {
    if (sizeof($this->queue) > 0) {
      if ($max == 0) {
        foreach ($this->queue as $obj) {
          $ch = $this->getCurlHandle($obj);
          array_push($this->activeCurlHandles, $ch);
          if ($ch != null) {
            curl_multi_add_handle($this->mh, $ch);
          } else {
            die('Empty curl handler');
          }
        }
        $a = $this->executeCurlMultiRequest();
      } else {
        $a = array_slice($this->queue, 0, $max);
      }
      return $a;
    } else {
      $a = array('error' => 'You must pass a RocketShipIt object into the RocketShipQueue object');
      return $a;
    }
  }

}


// Simon Willison, 16th April 2003
// Based on Lars Marius Garshol's Python XMLWriter class
// See http://www.xml.com/pub/a/2003/04/09/py-xml.html

// modified 2009-07-09 RCE
//  added $subordinateSection parameter; if true, class does not place "xml version" at the start


/**
 * Class used to build xml internally
 */
class xmlBuilder
{

  var $xml;
  var $indent;
  var $stack = array();

  function xmlBuilder($subordinateSection = false)
  {
    //$this->indent = getGenericDefault("debugMode") == 1 ? '   ' : '';   // indent if debugging
    $this->indent = '   '; // indent
    if (!$subordinateSection) {
      $this->xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
    }
  }

  function _indent()
  {
    for ($i = 0, $j = count($this->stack); $i < $j; $i++) {
      $this->xml .= $this->indent;
    }
  }

  function push($element, $attributes = array())
  {
    $this->_indent();
    $this->xml .= '<' . $element;
    foreach ($attributes as $key => $value) {
      $method = new ReflectionFunction('htmlentities');
      $num_params = $method->getNumberOfParameters();
      if ($num_params == 4) {
        $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_NOQUOTES, 'ISO-8859-1', false) . '"';
      } else {
        $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_NOQUOTES, 'ISO-8859-1') . '"';
      }
    }
    $this->xml .= ">\n";
    $this->stack[] = $element;
  }

  function element($element, $content, $attributes = array())
  {
    if ($content != '') {
      $this->_indent();
      $this->xml .= '<' . $element;
      foreach ($attributes as $key => $value) {
        $method = new ReflectionFunction('htmlentities');
        $num_params = $method->getNumberOfParameters();
        if ($num_params == 4) {
          $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_NOQUOTES, 'ISO-8859-1', false) . '"';
        } else {
          $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_NOQUOTES, 'ISO-8859-1') . '"';
        }
      }
      $method = new ReflectionFunction('htmlentities');
      $num_params = $method->getNumberOfParameters();
      if ($num_params == 4) {
        $this->xml .= '>' . htmlentities($content, ENT_NOQUOTES, 'ISO-8859-1', false) . '</' . $element . '>' . "\n";
      } else {
        $this->xml .= '>' . htmlentities($content, ENT_NOQUOTES, 'ISO-8859-1') . '</' . $element . '>' . "\n";
      }
    }
  }

  function emptyelement($element, $attributes = array())
  {
    $this->_indent();
    $this->xml .= '<' . $element;
    foreach ($attributes as $key => $value) {
      $method = new ReflectionFunction('htmlentities');
      $num_params = $method->getNumberOfParameters();
      if ($num_params == 4) {
        $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_NOQUOTES, 'ISO-8859-1', false) . '"';
      } else {
        $this->xml .= ' ' . $key . '="' . htmlentities($value, ENT_NOQUOTES, 'ISO-8859-1') . '"';
      }
    }
    $this->xml .= " />\n";
  }

  function pop()
  {
    $element = array_pop($this->stack);
    $this->_indent();
    $this->xml .= "</$element>\n";
  }

  // Added MS 03-31-2011
  function append($xmlString)
  {
    $this->xml .= "$xmlString";
  }

  function getXml()
  {
    return $this->xml;
  }
}


/**
 * Used internally to parse xml into an array
 */
class upsxmlParser
{

  var $params = array(); //Stores the object representation of XML data
  var $root = NULL;
  var $global_index = -1;
  var $fold = false;

  /* Constructor for the class
    * Takes in XML data as input( do not include the <xml> tag
    */
  function xmlparser($input, $xmlParams = array(XML_OPTION_CASE_FOLDING => 0))
  {
    $xmlp = xml_parser_create();
    foreach ($xmlParams as $opt => $optVal) {
      switch ($opt) {
        case XML_OPTION_CASE_FOLDING:
          $this->fold = $optVal;
          break;
        default:
          break;
      }
      xml_parser_set_option($xmlp, $opt, $optVal);
    }

    if (xml_parse_into_struct($xmlp, $input, $vals, $index)) {
      $this->root = $this->_foldCase($vals[0]['tag']);
      $this->params = $this->xml2ary($vals);
    }
    xml_parser_free($xmlp);
  }

  function _foldCase($arg)
  {
    return ($this->fold ? strtoupper($arg) : $arg);
  }

  /*
     * Credits for the structure of this function
     * http://mysrc.blogspot.com/2007/02/php-xml-to-array-and-backwards.html
     *
     * Adapted by Ropu - 05/23/2007
     *
     */

  function xml2ary($vals)
  {

    $mnary = array();
    $ary =& $mnary;
    foreach ($vals as $r) {
      $t = $r['tag'];
      if ($r['type'] == 'open') {
        if (isset($ary[$t]) && !empty($ary[$t])) {
          if (isset($ary[$t][0])) {
            $ary[$t][] = array();
          } else {
            $ary[$t] = array($ary[$t], array());
          }
          $cv =& $ary[$t][count($ary[$t]) - 1];
        } else {
          $cv =& $ary[$t];
        }
        $cv = array();
        if (isset($r['attributes'])) {
          foreach ($r['attributes'] as $k=> $v) {
            $cv[$k] = $v;
          }
        }

        $cv['_p'] =& $ary;
        $ary =& $cv;

      } else if ($r['type'] == 'complete') {
        if (isset($ary[$t]) && !empty($ary[$t])) { // same as open
          if (isset($ary[$t][0])) {
            $ary[$t][] = array();
          } else {
            $ary[$t] = array($ary[$t], array());
          }
          $cv =& $ary[$t][count($ary[$t]) - 1];
        } else {
          $cv =& $ary[$t];
        }
        if (isset($r['attributes'])) {
          foreach ($r['attributes'] as $k=> $v) {
            $cv[$k] = $v;
          }
        }
        $cv['VALUE'] = (isset($r['value']) ? $r['value'] : '');

      } elseif ($r['type'] == 'close') {
        $ary =& $ary['_p'];
      }
    }

    $this->_del_p($mnary);
    return $mnary;
  }

  // _Internal: Remove recursion in result array
  function _del_p(&$ary)
  {
    foreach ($ary as $k=> $v) {
      if ($k === '_p') {
        unset($ary[$k]);
      } else if (is_array($ary[$k])) {
        $this->_del_p($ary[$k]);
      }
    }
  }

  /* Returns the root of the XML data */
  function GetRoot()
  {
    return $this->root;
  }

  /* Returns the array representing the XML data */
  function GetData()
  {
    return $this->params;
  }
}

// Takes a multi-dimensional array and makes a nested bulleted list
function printArrayTree($array)
{
  print "\n<ul>\n";
  foreach ($array as $key => $value) {
    if (is_array($value)) {
      print "<li>$key</li>\n";
      printArrayTree($value);
    } else {
      //print "<li>$value</li>\n";
    }
  }
  print "</ul>\n";
}


// Error if cURL is not present
if (!extension_loaded('curl')) {
  exit('The required php extension, cURL, was not found.  Please install the cURL module to continue using RocketShipIt.');
}
