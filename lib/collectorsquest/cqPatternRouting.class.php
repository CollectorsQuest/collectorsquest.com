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
    // Checking if we need to decrypt the URL
    if (0 === stripos($url, '/ex/v1;'))
    {
      $url = $this->decryptUrl($url);
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
      $relative = parent::generate($name, $params, false);

      $relative = str_replace(array('&encrypt=1', '?encrypt=1'), '', $relative);
      $encrypted = $this->encryptUrl($relative);

      $url = str_replace(array('&encrypt=1', '?encrypt=1'), '', $url);
      $url = str_replace($relative, $encrypted, $url);
    }

    return !empty($url) ? $url : '/';
  }

  public function encryptUrl($url)
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

    return '/ex/'. $hash;
  }

  public function decryptUrl($url)
  {
    $config = sfConfig::get('app_ice_libs_routing', array('secret' => 'yWJ2wUvHwZDnub7MtZLh2Zknd8TFXQGa'));

    // Saving a copy
    $_url = $url;

    list($version, $string, $iv) = explode(';', substr($url, 4));

    // Decrypt to the original URL
    $data = unserialize(mcrypt_decrypt(
      MCRYPT_TWOFISH,
      $config['secret'],
      gzuncompress(base64_decode(strtr($string, '-_', '+/'))),
      MCRYPT_MODE_CBC,
      base64_decode(strtr($iv, '-_', '+/'))
    ));

    // Valid requests are within one day of generating the URL
    if (is_array($data) && $data['version'] == $version && $data['time'] > time() - 86400)
    {
      $url = (string) $data['url'];
      @list($url, $query_string) = explode('?', $url);

      // Merge with the extra query string from the encoded URL
      $query_string = trim(implode('&', array($query_string, $_SERVER['QUERY_STRING'])), '&');

      parse_str($query_string, $_GET);
      $this->setDefaultParameters($_GET);

      foreach (array_keys($_SERVER) as $name)
      {
        $_SERVER[$name] = str_replace($_url, $url, $_SERVER[$name]);
      }
      $_SERVER['QUERY_STRING'] = $query_string;
    }

    return $url;
  }
}
