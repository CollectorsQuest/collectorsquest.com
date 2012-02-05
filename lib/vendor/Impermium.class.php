<?php

/**
 * Copyright 2011 Collectors' Quest, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

if (!function_exists('curl_init')) {
  throw new Exception('Impermium needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Impermium needs the JSON PHP extension.');
}

class Impermium
{
  const VERSION = '1.1';

  /** @var string */
  private $_api_version = '3.1';

  /** @var array */
  private $_entrypoint = array(
    'prod' => 'http://api.impermium.com',
    'test' => 'http://api-test.impermium.com'
  );

  /** @var null|string */
  private $_api_key = null;

  /**
   * Default options for curl.
   *
   * @var array
   */
  public static $CURL_OPTS = array(
    CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
    CURLOPT_USERAGENT      => 'php-api-client-v1.1',
    CURLOPT_POST           => 1,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60,
  );

  /**
   * @param  string  $api_key
   * @param  string  $api_version
   */
  public function __construct($api_key = null, $api_version = '3.1')
  {
    $this->_api_key = $api_key;
    $this->_api_version = $api_version;
  }

  /**
   * Set the API Key.
   *
   * @param  null|string  $api_key  The API Key
   * @return Impermium
   */
  public function setApiKey($api_key)
  {
    $this->_api_key = (string) $api_key;
  }

  /**
   * Get the API Key.
   *
   * @return null|string
   */
  public function getApiKey()
  {
    return $this->_api_key;
  }

  /**
   * Invoke the API.
   *
   * @param  string   $path    The path (required)
   * @param  array    $params  The query/post data
   * @param  boolean  $test    If we should use the testing API entrypoint
   *
   * @return mixed The decoded response object
   * @throws ImpermiumApiException
   */
  public function api($path, $params = array(), $test = false)
  {
    $url = $this->_entrypoint[$test === true ? 'test' : 'prod'] .'/'. $path .'/'. $this->_api_version .'/'. $this->_api_key;
    $result = json_decode($this->makeRequest($url, $params), true);

    // results are returned, errors are thrown
    if (is_array($result) && isset($result['error']))
    {
      throw new ImpermiumApiException($result);
    }

    return $result;
  }

  /**
   * Makes an HTTP request. This method can be overriden by subclasses if
   * developers want to do fancier things or use something other than curl to
   * make the request.
   *
   * @param  string  $url the URL to make the request to
   * @param  array   $params the parameters to use for the POST body
   * @param  CurlHandler $ch optional initialized curl handle
   *
   * @return string the response text
   */
  protected function makeRequest($url, $params, $ch = null)
  {
    if (!$ch) {
      $ch = curl_init();
    }

    $opts = self::$CURL_OPTS;
    $opts[CURLOPT_POSTFIELDS] = str_replace('\\/', '/', json_encode($params));
    $opts[CURLOPT_URL] = $url;

    // disable the 'Expect: 100-continue' behaviour. This causes CURL to wait
    // for 2 seconds if the server does not support this header.
    if (isset($opts[CURLOPT_HTTPHEADER]))
    {
      $existing_headers = $opts[CURLOPT_HTTPHEADER];
      $existing_headers[] = 'Expect:';
      $opts[CURLOPT_HTTPHEADER] = $existing_headers;
    }
    else
    {
      $opts[CURLOPT_HTTPHEADER] = array('Expect:');
    }

    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);

    if ($result === false)
    {
      $e = new ImpermiumApiException(array(
        'error_code' => curl_errno($ch),
        'error' => array(
          'message' => curl_error($ch),
          'type' => 'CurlException',
        ),
      ));
      curl_close($ch);

      throw $e;
    }

    curl_close($ch);

    return $result;
  }

}

class ImpermiumApiException extends Exception
{
  /**
   * Make a new API Exception with the given result.
   *
   * @param  array  $result the result from the API server
   */
  public function __construct($result)
  {
    $this->result = $result;
    $code = isset($result['error_code']) ? $result['error_code'] : 0;

    // Rest server style
    if (isset($result['error_msg']))
    {
      $msg = $result['error_msg'];
    }
    else
    {
      $msg = 'Unknown Error. Check getResult()';
    }

    parent::__construct($msg, $code);
  }

}
