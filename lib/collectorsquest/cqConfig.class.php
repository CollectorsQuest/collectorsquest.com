<?php

class cqConfig extends sfConfig
{
  public static function getCredentials($service, $key = null)
  {
    $service = (string) $service;
    $credentials = self::get('app_credentials_'. $service);

    return (null !== $key) ?
      $credentials[(string) $key] :
      $credentials;
  }
}
