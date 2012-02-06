<?php

/**
 * Defensio-PHP 2.0
 * PHP wrapper library for Defensio API 2.0
 * Requires PHP 5.x or better
 * PHP version 5
 *
 * @author Camilo Lopez (Websense Inc.)
 * @link http://defensio.com
 * @version 2.0
 */

class Defensio
{
  // Misc
  const API_HOST = 'api.defensio.com';
  const USER_AGENT = 'Defensio-PHP 2.0';
  const CLIENT_ID = 'Defensio-PHP | 2.0 | Websense Inc. | info@defensio.com';
  const FORMAT = 'xml';

  public $rest_client;

  private $api_key;
  private $client_id;
  private $defensio_paths;

  public function __construct($api_key, $client_id = self::CLIENT_ID)
  {
    $this->api_key = $api_key;
    $this->rest_client = new Defensio_REST_Client(self::API_HOST);
    $this->client_id = $client_id;
    $this->defensio_paths = Array(
      'key_get' => "/2.0/users/$this->api_key." . self::FORMAT,
      'document_post' => "/2.0/users/$this->api_key/documents." . self::FORMAT,
      'document_put_get' => "/2.0/users/$this->api_key/documents/{{signature}}." . self::FORMAT,
      'basic_stats_get' => "/2.0/users/$this->api_key/basic-stats." . self::FORMAT,
      'extended_stats_get' => "/2.0/users/$this->api_key/extended-stats." . self::FORMAT,
      'profanity_filter_post' => "/2.0/users/$this->api_key/profanity-filter." . self::FORMAT);
  }

  /**
   * Returns the API key used to instantiate the current object.
   */
  public function getApiKey()
  {
    return $this->api_key;
  }

  /**
   * Get information about the API key
   * @see http://defensio.com/api
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  public function getUser()
  {
    $result = $this->rest_client->get($this->defensio_paths['key_get']);
    return self::parseResult($result, FALSE, array(200, 404));
  }

  /**
   * Create and analyze a new document
   * @param array $params The parameters to be sent to Defensio.
   * @see http://defensio.com/api
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  public function postDocument($params)
  {
    $result = $this->rest_client->post($this->defensio_paths['document_post'],
      array_merge($params, array('client' => $this->client_id)));
    return self::parseResult($result);
  }

  /**
   * Get the status of an existing document
   * @param string $signature The signature of the document to retrieve
   * @see http://defensio.com/api
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  public function getDocument($signature)
  {
    $path = str_replace('{{signature}}', $signature, $this->defensio_paths['document_put_get']);
    $result = $this->rest_client->get($path);
    return self::parseResult($result, TRUE, array(200, 404));
  }

  /**
   * Modify the properties of an existing document
   * @param string $signature The parameters to be sent to Defensio.
   * @param array $params The parameters to be sent to Defensio.
   * @see http://defensio.com/api
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  public function putDocument($signature, $params)
  {
    $result = $this->rest_client->put(
      str_replace('{{signature}}', $signature, $this->defensio_paths['document_put_get']), $params);
    return self::parseResult($result);
  }

  /**
   * Get basic statistics for the current user
   * @see http://defensio.com/api
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  public function getBasicStats()
  {
    $result = $this->rest_client->get($this->defensio_paths['basic_stats_get']);
    return self::parseResult($result, FALSE, array(200, 404));
  }

  /**
   * Get more exhaustive statistics for the current user
   * @see http://defensio.com/api
   * @param array $params The parameters to be sent to Defensio. Keys can either be Strings or Symbols
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  public function getExtendedStats($params)
  {
    $from = $params['from'];
    $to = $params['to'];
    $query_string = "from=$from&to=$to";
    $result = $this->rest_client->get($this->defensio_paths['extended_stats_get'] . "?$query_string");
    return self::parseResult($result, FALSE, array(200, 404));
  }

  /**
   * Filter a set of values based on a pre-defined dictionary
   * @see http://defensio.com/api
   *
   * @param array $data The fields to be filtered
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  public function postProfanityFilter($data)
  {
    $result = $this->rest_client->post($this->defensio_paths['profanity_filter_post'], $data);
    return self::parseResult($result, FALSE, array(200));
  }

  /**
   * Takes the data POSTed by Defensio during the callback following an async request and returns an array
   * @param string $data XML data received by Defensio. If not specified, php://input will be used
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  public static function handlePostDocumentAsyncCallback($data = NULL)
  {
    if (is_null($data)) $data = file_get_contents('php://input');
    if (!$data) throw new DefensioEmptyCallbackData();
    return self::parseResult(array(200, $data, array()));
  }

  /**
   * Takes a raw result from rest_client and parses it
   * @param array $result The result of the HTTP call through rest_client
   * @param boolean $throw_on_failure Throws a DefensioFail exception if there was an error during the request.
   * @param array $expected_http_statuses List of expected HTTP statuses. Other statuses will trhow DefensioUnexpectedHTTPStatus
   * @return array Array containing two values: the HTTP status & a SimpleXML object with the values returned by Defensio
   */
  private static function parseResult($result, $throw_on_failure = TRUE, $expected_http_statuses = array(200))
  {
    $http_status = $result[0];
    $result_object = simplexml_load_string($result[1]);

    if ($http_status == 401 && $throw_on_failure)
      throw new DefensioInvalidKey("Invalid API key");

    if (!in_array($http_status, $expected_http_statuses) && $throw_on_failure) {
      $ex = new DefensioUnexpectedHTTPStatus("Unexpected HTTP status code: $http_status");
      $ex->http_status = $http_status;
      throw $ex;
    }

    if (!isset($result_object) ||
        (isset($result_object) && !in_array($result_object->status, array('success', 'pending'))) &&
            $throw_on_failure
    ) {

      $msg = "Unknown reason";
      if (is_object($result_object) && !empty($result_object->message))
        $msg = $result_object->message;

      $ex = new DefensioFail("Defensio request failed ($http_status): '$msg'");
      $ex->defensio_response = $result_object;
      $ex->http_status = $http_status;
      throw $ex;
    }

    return array($http_status, $result_object);
  }
}


class Defensio_REST_Client
{
  public $host;
  public $http_version;
  public $use_sockets;

  public function __construct($host, $use_sockets = TRUE, $http_version = '1.0')
  {
    $this->host = $host;
    $this->http_version = $http_version;
    $this->use_sockets = $use_sockets;
  }

  /**
   * @param string $path The path in relation to $host
   * @param Array $data  an array of data ('key' => 'value') that will be posted into the server
   * @return Array an array with two elements ( status, data, header )
   */
  public function post($path, $data = Array())
  {
    return $this->do_request('POST', $path, $data);
  }

  /**
   * @param string $path The path in relation to $host
   * @param Array $data  an array of data ('key' => 'value') that will be put into the server
   * @return Array an array with three elements ( status, data, header )
   */
  public function put($path, $data = Array())
  {
    return $this->do_request('PUT', $path, $data);
  }

  /**
   * @param string $path The path in relation to $host /
   * @return Array an array with three elements ( status, data, header )
   */
  public function delete($path)
  {
    return $this->do_request('DELETE', $path);
  }

  /**
   * @param string $path The path in relation to $host
   * @return Array an array with three elements ( status, data, header )
   */
  public function get($path)
  {
    return $this->do_request('GET', $path);
  }

  /**
   * Do actual HTTP requests in here, absctract from the rest of phpDefensio so we can easily change
   * the library or technique used as of now it uses a simple sockets implementation, and might throw
   * an exception if no socket can be open
   *
   * @param $verb
   * @param $path
   * @param array $data
   * @param int $timeout
   *
   * @return array
   */
  private function do_request($verb, $path, $data = Array(), $timeout = 10)
  {
    return $this->use_sockets ? $this->do_request_with_sockets($verb, $path, $data, $timeout) : $this->do_request_with_curl($verb, $path, $data, $timeout);
  }

  private function do_request_with_curl($verb, $path, $data)
  {
    if ($verb == 'DELETE')
      throw new Exception('DELETE not implemeted yet');

    $url = $this->host . $path;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, TRUE);
    // Output:
    // HTTP status, body, headers
    $out = array(NULL, '', array());

    if ($verb == 'POST' || $verb == 'PUT') {

      if ($verb == 'PUT')
        $data = array_merge(array('_method' => 'put'), $data);

      curl_setopt($curl, CURLOPT_POST, TRUE);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }

    if ($response = curl_exec($curl)) {
      $out[0] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      $header_lenght = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
      $result = array();
      $result[2] = substr($response, 0, $header_lenght);
      $result[1] = substr($response, $header_lenght);
      $out[1] = $result[1];

      foreach (explode("\r\n", $result[0]) as $v) {
        $header = explode(": ", $v);
        $out[2][$header[0]] = $header[1];
      }
    }

    curl_close($curl);
    return $out;
  }

  private function do_request_with_sockets($verb, $path, $data = Array(), $conn_timeout)
  {
    $str_data = http_build_query($data);
    $url = parse_url('http://' . $this->host . $path);


    if (!isset($url['port']) || empty($url['port']))
      $url['port'] = 80;

    $sock = fsockopen($url['host'], $url['port'], $errno, $errstr, $conn_timeout);

    if ($sock === FALSE) {
      $msg = 'Impossible to open socket to ' . $url['host'] . ':' . $url['port'];

      if ($errno == 110)
        $ex = new DefensioConnectionTimeout($msg);
      else
        $ex = new DefensioConnectionError($msg);

      $ex->error_code = $errno;
      $ex->error_string = $errstr;

      throw $ex;
    }


    $target = $url['path'];

    if (!empty($url['query']))
      $target .= "?$url[query]";

    fputs($sock, "$verb $target HTTP/$this->http_version\r\n");
    fputs($sock, "Host: " . $url['host'] . "\r\n");

    if ($verb == 'POST' || $verb == 'PUT') {
      fputs($sock, "Content-type: application/x-www-form-urlencoded\r\n");
      fputs($sock, "Content-length: " . strlen($str_data) . "\r\n");
    }

    fputs($sock, "Accept: text/xml\r\n");
    fputs($sock, "Connection: close\r\n\r\n");

    if ($verb == 'POST' || $verb == 'PUT')
      fputs($sock, $str_data);

    $result = '';

    stream_set_timeout($sock, 3);
    $info = stream_get_meta_data($sock);

    while (!feof($sock) && !$info['timed_out']) {
      $result .= fgets($sock, 128);
      $info = stream_get_meta_data($sock);
    }

    fclose($sock);

    if ($info['timed_out'])
      throw new DefensioConnectionTimeout();

    $result = explode("\r\n\r\n", $result, 2);
    $header = isset($result[0]) ? explode("\r\n", $result[0]) : '';
    $content = isset($result[1]) ? $result[1] : '';
    $status = isset($header[0]) ? explode(' ', $header[0]) : NULL;
    $status = $status[1];

    return Array($status, $content, $header);
  }

}

class DefensioError extends Exception
{
  public $http_status;
}

class DefensioFail extends DefensioError
{
  public $defensio_response;
}

class DefensioUnexpectedHTTPStatus extends DefensioError
{
}

class DefensioInvalidKey extends DefensioError
{
}

class DefensioEmptyCallbackData extends DefensioError
{
}

class DefensioConnectionError extends DefensioError
{
  public $error_code;
  public $error_string;
}

class DefensioConnectionTimeout extends DefensioConnectionError
{
}
