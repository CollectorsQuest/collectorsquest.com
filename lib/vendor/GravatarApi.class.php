<?php

/**
 * This API Enables auto caching gravatar images
 * It is inspired by these two scripts :
 * - http://fucoder.com/code/gravatar-cache/
 * - http://svn.hiddenloop.com/public/plugins/mephisto_gravatar_cache/
 *
 * TODO:
 *  - automatically remove cached gravatar through a cron OR with phptask
 *  - add unit tests
 *
 * @package  Symfony Plugin
 * @author   Mickael Kurmann
 * @author   Xavier Lacot <xavier@lacot.org>
 * @license  MIT
 */
class GravatarApi
{
  // default cached gravatar img
  protected $default_image;

  // possible to put : 3 days, 1 week, and whatever you want according to php strtotime function
  protected $expire_ago;

  protected $image_size, $rating, $cache_dir;
  protected $cache_dir_name;

  protected $base_url = "http://www.gravatar.com";
  // gravatar ratings are only : G | PG | R | X
  protected $base_ratings = array('G', 'PG', 'R', 'X');

  public function __construct($image_size = null, $rating = null)
  {
    if (SYMFONY_VERSION >= 1.1)
    {
      $this->cache_dir = sfConfig::get('sf_upload_dir') . DIRECTORY_SEPARATOR .
                         sfConfig::get('app_gravatar_cache_dir_name', 'g_cache') . DIRECTORY_SEPARATOR;
      $this->cache_dir_name = str_replace(sfConfig::get('sf_upload_dir'), '', $this->cache_dir);
    }
    else
    {
      $this->cache_dir_name = DIRECTORY_SEPARATOR . sfConfig::get('sf_upload_dir_name') .
                              DIRECTORY_SEPARATOR . sfConfig::get('app_gravatar_cache_dir_name', 'g_cache') . DIRECTORY_SEPARATOR;
      $this->cache_dir = sfConfig::get('sf_web_dir') . $this->cache_dir_name;
    }

    $this->default_image = sfConfig::get('app_gravatar_default_image', 'gravatar_default.png');
    $this->expire_ago = sfConfig::get('app_gravatar_cache_expiration', '3 days');

    if (is_null($image_size) || $image_size > 80 || $image_size < 1) {
      $this->image_size = sfConfig::get('app_gravatar_default_size', 80);
    } else {
      $this->image_size = $image_size;
    }

    if (is_null($rating) || !in_array($rating, $this->base_ratings)) {
      $this->rating = sfConfig::get('app_gravatar_default_rating', 'G');
    } else {
      $this->rating = $rating;
    }
  }

  /**
   * Constructs path to gravatar (with size, rating, md5 email and
   * a default image to redirect to (if not found))
   *
   * @author Mickael Kurmann
   *
   * @param  string  $md5_email
   * @return string
   */
  protected function buildGravatarPath($md5_email)
  {
    return $this->base_url . '/avatar.php?gravatar_id=' . $md5_email .
           '&size=' . $this->image_size .
           '&rating=' . $this->rating .
           '&default=http://www.default.com';
  }

  /**
   * Check if a gravatar is available on gravatar.com
   *
   * @author Mickael Kurmann
   *
   * @param  string  $md5_email
   * @return boolean
   */
  protected function hasGravatar($md5_email)
  {
    // TODO: try to cache!
    $ch = curl_init($this->buildGravatarPath($md5_email));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    //--- Start buffering : HIDE CURL EXEC RETURN ...
    ob_start();
    curl_exec($ch);
    ob_end_clean();
    //--- End buffering and clean output

    $session_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 200 == page with no error, else 301 == redirect (no gravatar) or 404... or whatever
    if ($session_code == 200) {
      return true;
    }

    return false;
  }

  /**
   * check for a cache hit - if found check if file is within expiry time
   *
   * @author Mickael Kurmann
   *
   * @param  string  $file_path
   * @return boolean
   */
  protected function isCacheValid($file_path)
  {
    if (file_exists($file_path))
    {
      if (filectime($file_path) < strtotime("+" . $this->expire_ago))
      {
        // file exists and cache is valid
        return true;
      }
      else
      {
        // file exists but cache has expired
        unlink($file_path);
      }
    }

    // no file
    return false;
  }

  /**
   * Get the gravatar to the cache, if email has a gravatar
   * and it does not already exist (or has expired)
   *
   * @param  string  $email
   * @return string
   */
  public function get($email)
  {
    $md5_email = md5($email);
    $file = $this->cache_dir . $md5_email . '.png';

    // the cache is valid, return the cached image
    $to_return = $md5_email;

    // check the cache
    if (!$this->isCacheValid($file))
    {
      // no image in cache
      if ($this->hasGravatar($md5_email))
      {
        $path = $this->buildGravatarPath($md5_email);
      }
      else
      {
        // no gravatar --> get the default one
        $path = sfConfig::get('sf_web_dir') .'/images/legacy/'. $this->default_image;
      }

      $gravatar_img = file_get_contents($path, 'rb');

      // image on gravatar.com --> save it in cache
      file_put_contents($file, $gravatar_img);
    }

    return '/'.sfConfig::get('sf_upload_dir_name'). '/'. $this->cache_dir_name . $to_return;
  }
}
