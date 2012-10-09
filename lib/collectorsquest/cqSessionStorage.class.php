<?php


/**
 * cqSessionStorage based on sfSessionStorage session handler
 */
class cqSessionStorage extends sfSessionStorage
{
  /**
   * Class constructor.
   *
   * @param  array $options
   * @return cqSessionStorage
   */
  public function __construct($options = array())
  {
    /**
     * Sessions should not be used in "cli" mode
     */
    if (php_sapi_name() == 'cli')
    {
      return;
    }
    /**
     * We do not want to create bogus session data for crowlers
     */
    else if (class_exists('cqStatic') && false !== cqStatic::isCrawler())
    {
      return;
    }
    else
    {
      if (($b = @get_browser(null, false)) && ($b->cookies === '0'))
      {
        return;
      }
    }

    $start = microtime(true);

    if (isset($options['session_cookie_domain']))
    {
      $parts = explode('.', $options['session_cookie_domain']);
      $context = $parts[1];
    }
    else
    {
      $context = 'global';
    }

    try
    {
      parent::__construct($options);
    }
    catch (Exception $e)
    {
      // Stop the session so that we can fallback to files
      try
      {
        session_destroy();
      }
      catch (Exception $e)
      {
        ;
      }

      ini_set('session.save_handler', 'files');
      ini_set('session.save_path', '/www/tmp');

      parent::__construct($options);
    }

    // Write the unique user string to the session
    if (class_exists('cqStatic') && !$this->read('unique'))
    {
      $this->write('unique', cqStatic::getUserUniqueString());
    }

    if (class_exists('cqStats') && class_exists('cqFunctions'))
    {
      cqStats::timing(cqFunctions::gethostname() .'.'. $context .'.sessions', microtime(true) - $start);
    }
  }

  /**
   * Initialize the Session
   *
   * @param array $options associative array of options
   * @return bool|void
   */
  public function initialize($options = null)
  {
    $session_id = isset($_POST['_session_id']) ? $_POST['_session_id'] : @$_GET['_session_id'];

    if (!empty($session_id))
    {
      ini_set('session.use_cookies', 0);

      is_array($options) ?
        $options['session_id'] = $session_id :
        $options = array('session_id' => $session_id);
    }

    return parent::initialize($options);
  }
}
