<?php

class Geocoder
{
  const API_KEY = 'ABQIAAAAFWhrOd_LZ8oN4w-vkUWughQAU-WnqYQCO7zppSGvHR51tCrN4xRY0TaQWDhMG_uhoslQtMZplJu3gg';
  const HOST = "maps.google.com";

  /**
   * Get an array of possible geocoding matches for an address or an empty array if none found.
   * Matches are of the type array('q' => , 'address' =>  , 'latitude' => )
   * Return value of null signals an error somewhere along the way.
   */
  public static function getGeocode($addr, $host = self::HOST, $key = self::API_KEY)
  {
    $data = self::geocode($addr, $host, $key);
    if (! ($data && $data['Status']['code']))
    {
      return null;
    }
    $statusCode = $data['Status']['code'];
    if ($statusCode == "602" || !isset($data['Placemark']))
    {
      return array();
    } 
    else if ($statusCode != "200")
    {
      return null;
    }

    $result = array();
    foreach ($data['Placemark'] as $placemark)
    {
      $result[] = self::parsePlacemark($placemark);
    }

    return $result;
  }

  /**
   * Get the Google Maps API JSON output as an assoc. array for the specified address.
   * Return value of null means the data could not be retrieved, false means could not be decoded.
   */
  private static function geocode($addr, $host = self::HOST, $key = self::API_KEY)
  {
    if (! $key)
    {
      throw new Exception("Add your Google Maps API key to the source to use this function without passing it as a parameter.");
    }

    $url = "http://" . self::HOST . "/maps/geo?output=json&oe=utf-8&hl=en&q=" . urlencode($addr) . "&key=" . $key;
    if (!$json = file_get_contents($url))
    {
      return null;
    }

    $data = json_decode($json, true);
    if (!$data)
    {
      return false;
    }

    return $data;
  }

  private static function parsePlacemark($placemark)
  {
    $result = array(
      'country' => null,
      'country_iso3166' => null,
      'state' => null,
      'county' => null,
      'city' => null,
      'zip_postal' => null,
      'longitude' => null,
      'latitude' => null,
      'timezone' => null
    );
    $result['address'] = $placemark['address'];

    if (isset($placemark['AddressDetails']['Country']))
    {
      $country = $placemark['AddressDetails']['Country'];

      $result['country'] = $country['CountryName'];
      $result['country_iso3166'] = $country['CountryNameCode'];

      if (isset($country['AdministrativeArea']))
      {
        $state = $country['AdministrativeArea'];

        $result['state'] = $state['AdministrativeAreaName'];

        if (isset($state['SubAdministrativeArea']))
        {
          $county = $state['SubAdministrativeArea'];

          $result['county'] = $county['SubAdministrativeAreaName'];

          if (isset($county['Locality']))
          {
            $city = $county['Locality'];

            $result['city'] = $city['LocalityName'];
            $result['zip_postal'] = (isset($city['PostalCode'])) ? $city['PostalCode']['PostalCodeNumber'] : null;
          }
        }
        else
        {
          if (isset($state['Locality']))
          {
            $city = $state['Locality'];

            $result['city'] = $city['LocalityName'];
            $result['zip_postal'] = (isset($city['PostalCode'])) ? $city['PostalCode']['PostalCodeNumber'] : null;
          }
          else if (isset($state['PostalCode']))
          {
            $result['zip_postal'] = $state['PostalCode']['PostalCodeNumber'];
          }
        }
      }
    }

    $coordinates = isset($placemark['Point']['coordinates']) ? $placemark['Point']['coordinates'] : array();
    $result['longitude'] = $coordinates[0];
    $result['latitude']  = $coordinates[1];

    if ($result['country'] == 'USA')
    {
      $result['timezone'] = self::getTimeZone($result['zip_postal']);
    }

    return $result;
  }

  private static function getTimeZone($zip = null)
  {
    $timezone = timezone_name_from_abbr('EST');

    if (empty($zip)) 
    {
      return $timezone;
    }

    $url = 'http://www.webservicex.net/uszip.asmx/GetInfoByZIP';

    $url .= '?'. http_build_query(array('USZip' => $zip), null, '&');
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 3);
    $response = curl_exec($curl);

    if ($response)
    {
      try
      {
        $xml = new SimpleXMLElement($response);
        switch ($xml->Table->TIME_ZONE) {
          case 'C':
            $timezone = timezone_name_from_abbr('CST');
            break;
          case 'P':
            $timezone = timezone_name_from_abbr('PST');
            break;
          case 'E':
          default:
            $timezone = timezone_name_from_abbr('EST');
            break;
        }
      } catch (Exception $e) {
        ;
      }
    }

    return $timezone;
  }
}
