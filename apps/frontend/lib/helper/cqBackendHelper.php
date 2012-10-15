<?php

function link_to_backend($name, $routeName, $params = array(), $options = array())
{
  return link_to($name, url_for_backend($routeName, $params), $options);
}

function url_for_backend($name, $parameters = array())
{
  /** @var $application backendConfiguration */
  $application = sfProjectConfiguration::getActive();

  return $application->generateBackendUrl($name, $parameters);
}

/**
 * @depricated
 *
 * @param $name
 * @param $parameters
 *
 * @return mixed
 */
function url_to_backend($name, $parameters = array())
{
  return url_for_backend($name, $parameters);
}
