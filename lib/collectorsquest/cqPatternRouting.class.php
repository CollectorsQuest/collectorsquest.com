<?php

class cqPatternRouting extends sfPatternRouting
{
  /**
   * @see sfPatternRouting
   *
   * @throws sfStopException
   * @param  string $url
   *
   * @return mixed
   */
  public function findRoute($url)
  {
    // Saving a copy
    $_url = $url;

    // Checking for URL encryption
    if (0 === stripos($url, '/ex/v1;'))
    {
      $config = sfConfig::get('app_ice_libs_routing', array('secret' => 'yWJ2wUvHwZDnub7MtZLh2Zknd8TFXQGa'));

      list($version, $string, $iv) = explode(';', substr($url, 4));

      // Decrypt to the original URL
      $data = unserialize(mcrypt_decrypt(
        MCRYPT_TWOFISH,
        $config['secret'],
        gzuncompress(base64_decode(strtr($string, '-_', '+/'))),
        MCRYPT_MODE_CBC,
        base64_decode(strtr($iv, '-_', '+/'))
      ));

      // Valid requests are within an hour of generating the URL
      if (is_array($data) && $data['version'] == $version && $data['time'] > time() - 3600)
      {
        $url = (string) $data['url'];
        @list($path, $query_string) = explode('?', $url);

        parse_str($query_string, $_GET);
        $this->setDefaultParameters($_GET);

        foreach (array_keys($_SERVER) as $name)
        {
          $_SERVER[$name] = str_replace($_url, $url, $_SERVER[$name]);
        }
        $_SERVER['QUERY_STRING'] = $query_string;
      }
    }

    return parent::findRoute($url);
  }

  /**
   * @see sfPatternRouting
   *
   * @param  string  $name
   * @param  array   $params
   * @param  bool    $absolute
   *
   * @return mixed
   */
  public function generate($name, $params = array(), $absolute = false)
  {
    $url = parent::generate($name, $params, $absolute);

    // Check if we need to encrypt the route
    if (stripos($url, '&encrypt=1') || stripos($url, '?encrypt=1'))
    {
      $config = sfConfig::get('app_ice_libs_routing', array('secret' => 'yWJ2wUvHwZDnub7MtZLh2Zknd8TFXQGa'));
      $time = time();

      // Encryption Algorithm
      $alg = MCRYPT_TWOFISH;

      // Create the initialization vector for increased security.
      $iv = mcrypt_create_iv(mcrypt_get_iv_size($alg, MCRYPT_MODE_ECB), MCRYPT_RAND);

      $string = serialize(array(
        'version' => 'v1', 'url' => $url, 'time' => (int) $time
      ));

      $string = mcrypt_encrypt($alg, $config['secret'], $string, MCRYPT_MODE_CBC, $iv);
      $string = strtr(base64_encode(gzcompress($string, 9)), '+/', '-_');
      $hash = sprintf("v1;%s;%s", $string, strtr(base64_encode($iv), '+/', '-_'));

      $url = '/ex/'. $hash;
    }

    return !empty($url) ? $url : '/';
  }
}
