<?php

require 'lib/model/om/BaseMultimediaPeer.php';

class MultimediaPeer extends BaseMultimediaPeer
{
  static private $_valid_content_types = array(
    'image/jpg' => 'jpg',
    'image/jpeg' => 'jpg',
    'image/pjpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'video/x-flv' => 'flv'
  );

  static public function getValidContentTypes()
  {
    return self::$_valid_content_types;
  }

  static public function has($model, $type, $primary = null)
  {
    $c = new Criteria();
    $c->add(MultimediaPeer::MODEL, get_class($model));
    $c->add(MultimediaPeer::MODEL_ID, $model->getId());
    $c->add(MultimediaPeer::TYPE, 'image');

    if (is_bool($primary))
    {
      $c->add(MultimediaPeer::IS_PRIMARY, $primary);
    }

    return 0 < MultimediaPeer::doCount($c);
  }

  static public function get($model, $type, $primary = false)
  {
    $c = new Criteria();
    $c->add(MultimediaPeer::MODEL, get_class($model));
    $c->add(MultimediaPeer::MODEL_ID, $model->getId());
    $c->add(MultimediaPeer::TYPE, 'image');

    if (is_bool($primary))
    {
      $c->add(MultimediaPeer::IS_PRIMARY, $primary);
    }

    return ($primary) ? MultimediaPeer::doSelectOne($c) : MultimediaPeer::doSelect($c);
  }

  /**
   * Create a Multimedia object for a certain model given a url
   *
   * @param  object  $model    The model class we are creating multimedia for
   * @param  string  $url      The URL to fetch the multimedia from
   * @param  array   $options  Options to send to the cqStatic::getBrowser() method
   *
   * @return Multimedia
   */
  public static function createMultimediaFromUrl($model, $url, $options = array())
  {
    // Make sure we have an object to work with
    if (!is_object($model))
    {
      return false;
    }

    try
    {
      $b = cqStatic::getBrowser('utf-8', 30, $options);
      $b->get($url);
    }
    catch (Exception $e)
    {
      return false;
    }

    if (!$b->responseIsError() && in_array($b->getResponseHeader('Content-Type'), array_keys(self::$_valid_content_types)))
    {
      $file = tempnam(sfConfig::get('sf_cache_dir'), 'tmp_multimedia_');
      file_put_contents($file, $b->getResponseText());

      $multimedia = self::createMultimediaFromFile($model, $file, self::$_valid_content_types[$b->getResponseHeader('Content-Type')]);
      if ($multimedia instanceof Multimedia)
      {
        $multimedia->setSource($url);
        $multimedia->save();
      }

      // Delete the temporary file
      @unlink($file);

      return $multimedia;
    }

    return false;
  }

  /**
   * Createa a multimedia record from a physical file
   *
   * @param  object  $model
   * @param  string  $file       The path to the file on the filesystem
   * @param  enum    $extension  ['jpg', 'png', 'gif']
   *
   * @return Multimedia
   */
  public static function createMultimediaFromFile($model, $file, $extension = null)
  {
    // Sometimes we can pass a sfValidatedFile if dealing with the backend application
    if ($file instanceof sfValidatedFile)
    {
      $file = $file->getTempName();
    }

    if (!is_readable($file) || filesize($file) == 0) return false;
    if (!is_object($model))  return false;

    $md5 = md5_file($file);

    $c = new Criteria();
    $c->add(MultimediaPeer::MODEL, get_class($model));
    $c->add(MultimediaPeer::MODEL_ID, $model->getId());
    $c->add(MultimediaPeer::MD5, $md5);

    // Checking for the md5 hash of the file so that we can avoid duplicates
    if (MultimediaPeer::doCount($c) > 0)
    {
      return false;
    }

    $extension = (is_null($extension)) ? @end(explode(".", $file)) : $extension;
    switch($extension)
    {
      case 'jpg':
      case 'png':
      case 'gif':
      default:
        $type = 'image';
        break;
      case 'flv':
        $type = 'video';
        break;
    }

    $multimedia = new Multimedia($type);

    if (is_object($model) && !$model->isNew())
    {
      $c = new Criteria;
      $c->setDistinct();
      $c->add(MultimediaPeer::MODEL, get_class($model));
      $c->add(MultimediaPeer::MODEL_ID, $model->getId());
      $c->add(MultimediaPeer::TYPE, $type);
      $has_multimedia = MultimediaPeer::doCount($c);

      if ($has_multimedia == 0)
      {
        $multimedia->setIsPrimary(true);
      }
    }
    else
    {
      return false;
    }

    // Set the model class
    $multimedia->setModel($model);
    $multimedia->setMd5($md5);
    $multimedia->setOrientation(self::getImageOrientation($file));

    $multimedia->createDirectory();
    if (copy($file, $multimedia->getAbsolutePath('original')))
    {
      // Save the multimedia object
      $multimedia->save();

      /**
       * Add this multimedia to the queue for extracting the colors
       */
      cqStatic::loadZendFramework();

      try
      {
        if ($queue = cqJobQueue::create('multimedia_colors'))
        {
          $queue->send((string) $multimedia->getId());
        }
      }
      catch (Exception $e)
      {
        // $colors = MultimediaPeer::getImageColors($multimedia->getAbsolutePath('original'));
        // $multimedia->setColors($colors);
      }

      return $multimedia;
    }

    return false;
  }

  /**
   * Create a thumbnail for the multimedia
   *
   * @param  string  $image
   * @param  string  $size
   * @param  string  $method
   *
   * @return string The file name of the thumb or false on failure
   */
  public static function makeThumb($image, $size, $method = 'shave')
  {
    if (!class_exists('Imagick'))
    {
      return false;
    }

    if (is_file($image) && is_readable($image))
    {
      $thumb = tempnam(sys_get_temp_dir(), 'multimedia_');
      list($width, $height) = explode('x', $size);

      try
      {
        $image = new Imagick($image);
        $image->flattenImages();
        $image->setImagePage(0, 0, 0, 0);

        switch($method)
        {
          case 'bestfit':
            $image->thumbnailImage($width, $height, true);
            break;
          case 'crop':
            $image->cropThumbnailImage($width, $height);
            break;
          case 'shave':
          default:
            (($image->getImageWidth() / $image->getImageHeight()) > ($width / $height)) ?
              $image->resizeImage(0, $height, Imagick::FILTER_CATROM, 1) :
              $image->resizeImage($width, 0, Imagick::FILTER_CATROM, 1);

            $x = $y = 0;
            if ($image->getImageWidth() > $width)
            {
              $x = (int) ceil(($image->getImageWidth() - $width) / 2);
            }

            $image->cropImage($width, $height, $x, $y);
            break;
        }

        $image->setCompression(Imagick::COMPRESSION_JPEG);
        $image->setCompressionQuality(80);

        // Add watermark to the thumbnail
        if ($width > 300 && $watermark = new Imagick(sfConfig::get('sf_web_dir').'/images/watermark.png'))
        {
          self::addWatermark($image, $watermark, 10);
        }

        $image->writeImage($thumb);
        $image->destroy();

        return $thumb;
      }
      catch (ImagickException $e) { }
    }

    return false;
  }

  /**
   * Calculate new image dimensions to new constraints
   *
   * @param  integer  $w
   * @param  integer  $h
   * @param  integer  $mw
   * @param  integer  $mh
   *
   * @return array
   */
  public static function scaleImageSize($w, $h, $mw, $mh)
  {
    foreach(array('w','h') as $v)
    {
      $m = "m{$v}";

      if(${$v} > ${$m} && ${$m}) { $o = ($v == 'w') ? 'h' : 'w';
      $r = ${$m} / ${$v}; ${$v} = ${$m}; ${$o} = ceil(${$o} * $r); }
    }

    // Return the results
    return array(0 => $w, 1 => $h, 'width' => $w, 'height' => $h);
  }

  public static function getImageColors($file, $limit = 12)
  {
    if (!is_readable($file))
    {
      return array();
    }

    $colors = array();

    if (class_exists('Imagick'))
    {
      try
      {
        $image = new Imagick($file);
        $background = $image->getImageBackgroundColor()->getColor();

        $image->medianFilterImage(5);

        $width = $image->getImageWidth();
        $height = $image->getImageHeight();
        if ($width > $height)
        {
          $x = ($width - ($width/1.5)) / 2;
          $y = ($height - ($height/2)) / 2;

          $width = $width / 1.5;
          $height = $height / 2;
        }
        else
        {
          $x = ($width - ($width/2)) / 2;
          $y = ($height - ($height/1.5)) / 2;

          $width = $width / 2;
          $height = $height / 1.5;
        }

        $image->cropImage($width, $height, $x, $y);

        $colormap = new Imagick(sfConfig::get('sf_web_dir').'/images/colortable.gif');
        $image->mapImage($colormap, true);

        $pixels = $image->getImageHistogram();
        foreach ($pixels as $pixel)
        {
          $color = $pixel->getColor();
          if ($color != $background)
          {
            $colors[$pixel->getColorCount()] = sprintf('#%02X%02X%02X', $color['r'], $color['g'], $color['b']);
          }
        }

        $colormap->destroy();
        $image->destroy();
      }
      catch(ImagickException $e)
      {
        ;
      }
    }

    krsort($colors, SORT_NUMERIC);
    return array_slice($colors, 0, $limit);
  }

  public static function getImageOrientation($file)
  {
    list($width, $height) = @getimagesize($file);
    return ($width > $height) ? 'landscape' : 'portrait';
  }

  public static function getImageProportion($file)
  {
    list($width, $height) = @getimagesize($file);
    return ($height > 0) ? $width / $height : 1;
  }

  /**
   * Draw a watermark over an image (the watermark position is
   * selected automatically) and returns true. If the watermark
   * is bigger than the image, this method returns false.
   *
   * @see http://blog.pracucci.com/2008/08/30/watermarks-with-php-and-imagick/
   *
   * @param IMagick $image
   * @param IMagick $watermark
   * @param int $padding
   *
   * @return bool
   */
  private static function addWatermark($image, $watermark, $padding = 0)
  {
    // Check if the watermark is bigger than the image
    $image_width       = $image->getImageWidth();
    $image_height      = $image->getImageHeight();
    $watermark_width   = $watermark->getImageWidth();
    $watermark_height  = $watermark->getImageHeight();

    if ($image_width < $watermark_width + $padding || $image_height < $watermark_height + $padding)
    {
      return false;
    }

    // Calculate each position
    $positions   = array();
    $positions[] = array($image_width - $watermark_width - $padding, $image_height - $watermark_height - $padding);
    $positions[] = array(0 + $padding, $image_height - $watermark_height - $padding);

    // Initialization
    $min = null;
    $min_colors = 0;

    // Calculate the number of colors inside each region and retrieve the minimum
    foreach($positions as $position)
    {
      $colors = $image->getImageRegion($watermark_width, $watermark_height, $position[0], $position[1])->getImageColors();

      if ($min === null || $colors <= $min_colors)
      {
        $min = $position;
        $min_colors = $colors;
      }
    }

    // Draw the watermark
    $image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $min[0], $min[1]);

    return true;
  }
}
