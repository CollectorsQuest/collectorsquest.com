<?php

/**
 * Checks the application configuration to determine which modules/actions are supposed
 * to be secure and ensures they are using https.  This filter will also redirect https
 * requests to non-secured pages back to http if the strict option is set in the configuration
 * file.
 *
 * @author Casey Cambra <casey@tigregroup.com>
 * @version 1.1
 */
class cqSslRedirectFilter extends sfFilter
{

  /**
   * Executes the filter.  This filter will determine if a
   * request should be http or https and will redirect as such
   *
   * @param sfFilterChain $filterChain the current symfony filter chain
   * @return void
   */
  public function execute($filterChain)
  {
    // Only run once per request
    if ($this->isFirstCall())
    {
      /* @var $request cqWebRequest */
      $request = $this->getContext()->getRequest();

      // only filter is the request is get or head
      if (($request->isMethod('get') || $request->isMethod('head')) && !$request->isXmlHttpRequest())
      {
        $controller = $this->getContext()->getController();
        $stackEntry = $controller->getActionStack()->getLastEntry();
        $module = $stackEntry->getModuleName();
        $action = $stackEntry->getActionName();

        // get the module settings
        $moduleSettings = sfConfig::get('app_ssl_redirect_secure', false);

        // see if strict settings are on (non secure modules must be http)
        $strict = sfConfig::get('app_ssl_redirect_strict', true);

        // if there are settings for this module
        if ($moduleSettings && array_key_exists($module, (array) $moduleSettings))
        {
          // There are actions defined, check if this actions is secure
          if (isset($moduleSettings[$module]['actions']))
          {
            // this is a secure action
            if (
              !$request->isSecure() &&
              is_array($moduleSettings[$module]['actions']) &&
              in_array($action, $moduleSettings[$module]['actions'])
            )
            {
              // we need to redirect to a secure url
              $this->redirectSecure($request);
            }
            // else: the request should be secure, and is. No more to be done.
            // module was defined, but no actions were
          }
          else if (!$request->isSecure())
          {
            // every action in this module is secure, redirect
            $this->redirectSecure($request);
          }
        }
        else if ($request->isSecure() && $strict)
        {
          // redirect back to http, strict is set
          $this->redirectUnsecure($request);
        }
      }
    }

    // no redirect necessary, continue the filter chain
    $filterChain->execute();
  }

  /**
   * redirects an http request to https
   *
   * @param sfWebRequest $request
   * @return boolean
   */
  protected function redirectSecure($request)
  {
    // Replace http w/ https
    $url = str_replace('http://', 'https://', $request->getUri());

    return $this->getContext()->getController()->redirect($url, 0, 301);
  }

  /**
   * redirects an https request to http
   *
   * @param sfWebRequest $request
   * @return boolean
   */
  protected function redirectUnsecure($request)
  {
    // Replace https w/ http
    $url = str_replace('https://', 'http://', $request->getUri());

    return $this->getContext()->getController()->redirect($url, 0, 301);
  }
}
