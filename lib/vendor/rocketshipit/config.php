<?php
//Copyright RocketShipIt LLC All Rights Reserved
// For Support email: support@rocketship.it

// Feel free to modify the following defaults:

// You can find out which timezones are available here:
// http://php.net/manual/en/timezones.php
// date_default_timezone_set('America/Denver');

// If __DIR__ is not defiened (PHP version < 5.3)
if(!defined('__DIR__')) {
    $iPos = strrpos(__FILE__, "/");
    define("__DIR__", substr(__FILE__, 0, $iPos) . "/");
}

/**
* This function is used to set generic defaults.  I.e. They are not carrier-specific.
*
* These defaults will be used across all carriers.  They can be overwritten on the
* shipment/package level.
*/
function getGenericDefault($def) {
    $a = array();

    // 1 for Debug mode; 0 for normal operations
    // This also changes from testing to production mode
    $a['debugMode'] = 1;

    // Your company name
    $a['shipper'] = '';

    // Key shipping contact individual at your company
    $a['shipContact'] = "";

    $a['shipAddr1'] = '';
    $a['shipAddr2'] = '';
    $a['shipCity'] = '';

    // the two-letter State or Province code
    // ex. MT = Montana, ON = Ontario
    $a['shipState'] = '';

    // The Zip or Postal code
    $a['shipCode'] = '';

    // The two-letter country code
    $a['shipCountry'] = '';

    // Phone number in this format: 1234567890
    $a['shipPhone'] = '1231231234';
    $a['toCountry'] = 'US';

    if (array_key_exists($def, $a)) {
        return $a[$def];
    } else {
        return '';
    }
}


/**
* This function is used to set FedEx specfic defaults.
*
* These defaults will be used for FedEx calls only.  They can be overwritten on the
* shipment/package level using the setParameter() function.
*/
function getFEDEXDefault($def) {
    $a = array();

    // Your FedEx developer key
    $a['key'] = '';

    // Your FedEx developer password
    $a['password'] = '';

    // Your FedEx accountNumber
    $a['accountNumber'] = '';

    // Your FedEx meter number
    $a['meterNumber'] = '';

    // Allowed packaging types:
    // FEDEX_10KG_BOX
    // FEDEX_25KG_BOX
    // FEDEX_BOX
    // FEDEX_ENVELOPE
    // FEDEX_PAK
    // FEDEX_TUBE
    // YOUR_PACKAGING
    $a['packagingType'] = '';

    // The two possible weight units are LB and KG
    $a['weightUnit'] = '';

    // The two possible length units are IN and CM
    $a['lengthUnit'] = '';

    // EUROPE_FIRST_INTERNATIONAL_PRIORITY
    // FEDEX_1_DAY_FREIGHT
    // FEDEX_2_DAY
    // FEDEX_2_DAY_FREIGHT
    // FEDEX_3_DAY_FREIGHT
    // FEDEX_EXPRESS_SAVER
    // FEDEX_GROUND
    // FIRST_OVERNIGHT
    // GROUND_HOME_DELIVERY
    // INTERNATIONAL_ECONOMY
    // INTERNATIONAL_ECONOMY_FREIGHT
    // INTERNATIONAL_FIRST
    // INTERNATIONAL_PRIORITY
    // INTERNATIONAL_PRIORITY_FREIGHT
    // PRIORITY_OVERNIGHT
    // SMART_POST
    // STANDARD_OVERNIGHT
    // FEDEX_FREIGHT
    // FEDEX_NATIONAL_FREIGHT
    $a['service'] = '';

    // REGULAR_PICKUP
    // REQUEST_COURIER
    // DROP_BOX
    // BUSINESS_SERVICE_CENTER
    // STATION
    $a['dropoffType'] = '';

    // COLLECT
    // RECIPIENT
    // SENDER
    // THIRD_PARTY
    $a['paymentType'] = '';

    // COMMON2D
    // LABEL_DATA_ONLY
    $a['labelFormatType'] = '';

    // DPL
    // EPL2
    // PDF
    // PNG
    // ZPLII
    $a['imageType'] = '';

    // PAPER_4X6
    // PAPER_4X8
    // PAPER_4X9
    // PAPER_7X4.75
    // PAPER_8.5X11_BOTTOM_HALF_LABEL
    // PAPER_8.5X11_TOP_HALF_LABEL
    // STOCK_4X6
    // STOCK_4X6.75_LEADING_DOC_TAB
    // STOCK_4X6.75_TRAILING_DOC_TAB
    // STOCK_4X8
    // STOCK_4X9_LEADING_DOC_TAB
    // STOCK_4X9_TRAILING_DOC_TAB
    $a['labelStockType'] = '';

    // BILL_OF_LADING
    // COD_RETURN_TRACKING_NUMBER
    // CUSTOMER_AUTHORIZATION_NUMBER
    // CUSTOMER_REFERENCE
    // DEPARTMENT
    // FREE_FORM_REFERENCE
    // GROUND_SHIPMENT_ID
    // GROUND_MPS
    // INVOICE
    // PARTNER_CARRIER_NUMBER
    // PART_NUMBER
    // PURCHASE_ORDER
    // RETURN_MATERIALS_AUTHORIZATION
    // TRACKING_CONTROL_NUMBER
    // TRACKING_NUMBER_OR_DOORTAG
    // SHIPPER_REFERENCE
    // STANDARD_MPS
    $a['trackingIdType'] = '';

    // If default doesn't exist it needs to be set to blank.
    if (array_key_exists($def, $a)) {
        return $a[$def];
    } else {
        return '';
    }
}


/**
* This function is used to set UPS specfic defaults.
*
* These defaults will be used for UPS calls only.  They can be overwritten on the
* shipment/package level using the setParameter() function.
*/
function getUPSDefault($def) {
    $a = array();

    // Your UPS Developer license
    // your UPS XML Access Key TODO: Insert link to get one
    $a['license'] = '';

    // your UPS Developer username
    $a['username'] = '';

    // your ups Developer password
    $a['password'] = '';

    // Make sure addresses are valid before label creation
    // validate, nonvalidate
    $a['verifyAddress'] = 'nonvalidate';

    // The following variables govern the way the system functions
    // Options
    // ZPL - Zebra UPS Thermal Printers
    // EPL - Eltron UPS Thermal Printers
    // GIF - Image based, desktop inkjet printers
    // STARPL
    // SPL
    $a['labelPrintMethodCode'] = '';

    // Used when printing GIF images
    $a['httpUserAgent'] = '';

    // Only valid option for ZPL, EPL, STARPL, and SPL is 4
    // When using inches use whole numbers only
    $a['labelHeight'] = '';

    // Options are 6 or 8 inches
    $a['labelWidth'] = '';

    // Options
    // GIF - A gif image
    $a['labelImageFormat'] = '';

    // The following variables are for your UPS account
    // They typically don't change from shipment to shipment; although,
    // you may set any of them directly.
    // Your UPS Account number
    $a['accountNumber'] = '';

    // Options
    // 01 - Daily Pickup
    // 03 - Customer Counter
    // 06 - One Time Pickup
    // 07 - On Call Air
    // 11 - Suggested Retail Rates
    // 19 - Letter Center
    // 20 - Air Service Center
    $a['PickupType'] = '';

    // LBS or KGS
    $a['weightUnit'] = '';

    // IN, or CM
    $a['lengthUnit'] = '';

    // See the ups manual for a list of all currency types
    $a['insuredCurrency'] = 'USD';

    // two-letter country code
    $a['toCountryCode'] = 'US';

    // The following variables set the defaults for individual shipments
    // you may set them here to save time, or you may set them explicitly
    // each time you use the classes.
    $a['shipmentDescription'] = 'My RocketShipIt Shipment';

    // Options
    // 01 - UPS Next Day Air
    // 02 - UPS Second Day Air
    // 03 - UPS Ground
    // 07 - UPS Worldwide Express
    // 08 - UPS Worldwide Expedited
    // 11 - UPS Standard
    // 12 - UPS Three-Day Select
    // 13 - Next Day Air Saver
    // 14 - UPS Next Day Air Early AM
    // 54 - UPS Worldwide Express Plus
    // 59 - UPS Second Day Air AM
    // 65 - UPS Saver
    $a['service'] = '';

    // Options
    // 01 - UPS Letter
    // 02 - Your Packaging
    // 03 - Tube
    // 04 - PAK
    // 21 - Express Box
    // 24 - 25KG Box
    // 25 - 10KG Box
    // 30 - Pallet
    // 2a - Small Express Box
    // 2b - Medium Express Box
    // 2c - Large Express Box
    $a['packagingType'] = '';

    $a['packageDescription'] = '';

    // Set '0' for commercial '1' for residential
    $a['residentialAddressIndicator'] = '0';

    // Set '0' for retail rates '1' for negotiated
    // You must turn this on with your UPS account rep
    $a['negotiatedRates'] = '0';

    // Options
    // AJ Accounts Receivable Customer Account
    // AT Appropriation Number
    // BM Bill of Lading Number
    // 9V Collect on Delivery (COD) Number
    // ON Dealer Order Number
    // DP Department Number
    // 3Q Food and Drug Administration (FDA) Product Code
    // IK Invoice Number
    // MK Manifest Key Number
    // MJ Model Number
    // PM Part Number
    // PC Production Code
    // PO Purchase Order Number
    // RQ Purchase Request Number
    // RZ Return Authorization Number
    // SA Salesperson Number
    // SE Serial Number
    // ST Store Number
    // TN Transaction Reference Number
    $a['referenceCode'] = '02';

    // Options
    // 2 - UPS Print and Mail (PNM)
    // 3 - UPS Return Service 1-Attempt (RS1)
    // 5 - UPS Return Service 3-Attempt (RS3)
    // 8 - UPS Electronic Return Label (ERL)
    // 9 - UPS Print Return Label (PRL)
    $a['returnCode'] = '';


    // If default doesn't exist it needs to be set to blank.
    if (array_key_exists($def, $a)) {
        return $a[$def];
    } else {
        return '';
    }
}


/**
* This function is used to set USPS specfic defaults.
*
* These defaults will be used for USPS calls only.  They can be overwritten on the
* shipment/package level using the setParameter() function.
*/
function getUSPSDefault($def) {
    $a = array();

    // USPS userID
    // Please note: the USPS test servers only respond to pre-defined requests.
    // For USPS rates you need to call USPS and have them move you to
    // the production servers.
    $a['userid'] = '';

    // USPS service type
    // FIRST CLASS
    // PRIORITY
    // PRIORITY COMMERCIAL
    // EXPRESS
    // EXPRESS COMMERCIAL
    // EXPRESS SH
    // EXPRESS SH COMMERCIAL
    // EXPRESS HFP
    // EXPRESS HFP
    // COMMERCIAL
    // BPM
    // PARCEL
    // MEDIA
    // LIBRARY
    // ALL
    // ONLINE
    $a['service'] = '';

    // Values:
    // REGULAR
    // LARGE (Length + girth over 84in under 109in)
    // OVERSIZE (length + girth over 108 under 131)
    $a['size'] = '';

    // FLAT
    $a['firstClassMailType'] = '';

    // If default doesn't exist or is not set it needs to be set to blank.
    if (array_key_exists($def, $a)) {
        return $a[$def];
    } else {
        return '';
    }
}



?>
