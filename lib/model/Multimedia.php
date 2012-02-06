<?php

require 'lib/model/om/BaseMultimedia.php';

class Multimedia extends BaseMultimedia
{
  public function __construct($type = 'image')
  {
    parent::__construct();
    $this->setType($type);
  }

  public function save(PropelPDO $con = null)
  {
    if ($this->isNew())
    {
      $this->setName($this->getName());
      $this->createDirectory();
    } // end if

    parent::save($con);
  }

  public function getModelObject()
  {
    $object = null;

    $class = $this->getModel().'Peer';
    if (method_exists($class, 'retrieveByPk'))
    {
      $object = call_user_func(array($class, 'retrieveByPk'), $this->getModelId());
    }

    return $object;
  }

  public function getFileSize($which = 'original')
  {
    $unit = array(' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
    $size = @filesize($this->getAbsolutePath($which));
    $size = $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit[$i] : '0 Bytes';

    return $size;
  }

  public function getImageInfo($which = 'original')
  {
    if ($this->getType() != 'image')
    {
      return false;
    }

    list($width, $height, $type) = @getimagesize($this->getAbsolutePath($which));

    return array(
      'width'  => $width,
      'height' => $height,
      'size'   => $this->getFileSize(),
      'type'   => $type
   );
  }

  public function getImageHeight($which = 'original')
  {
    if ($this->getType() != 'image')
    {
      return false;
    }

    list($width, $height, $type) = @getimagesize($this->getAbsolutePath($which));

    return (int) $height;
  }

  public function getImageWidth($which = 'original')
  {
    if ($this->getType() != 'image')
    {
      return false;
    }

    list($width, $height, $type) = @getimagesize($this->getAbsolutePath($which));

    return (int) $width;
  }

  /**
   * Return basic pdf information
   *
   * @return false | array
   */
  public function getPDFInfo()
  {
    if ($this->getType() != 'pdf')
    {
      return array();
    }
    try
    {
      if (false) //($pdf = Zend_Pdf::load($this->getAbsolutePath()))
      {
        return array(
          'title'    => $pdf->properties['Title'],
          'size'     => $this->getFileSize(),
          'author'   => @$pdf->properties['Author'],
          'subject'  => @$pdf->properties['Subject'],
          'keywords' => @$pdf->properties['Keywords']
        );
      }
    }
    catch (Zend_Pdf_Exception $e)
    { ; }

    return array();
  }

  public function setModel($model, $id = null)
  {
    if (is_object($model))
    {
      parent::setModel(get_class($model));
      $this->setModelId($model->getId());
    }
    else
    {
      parent::setModel($model);
      $this->setModelId($id);
    }
  }

  public function setIsPrimary($v)
  {
    if (($v == true) && ($multimedia = MultimediaPeer::get($this, true)))
    {
      $multimedia->setIsPrimary(false);
      $multimedia->save();
    }

    return parent::setIsPrimary($v);
  }

  public function fileExists($which)
  {
    $file = $this->getAbsolutePath($which);
    return (file_exists($file) && is_readable($file));
  }

  public function getAbsolutePath($which = 'original')
  {
    $dir  = sfConfig::get('sf_upload_dir') .'/'. $this->getModel();
    $dir .= '/'. (($this->isNew()) ? strftime('%Y/%m/%d', time()) : $this->getCreatedAt('Y/m/d'));

    return implode('.', array($dir.'/'.$this->getMd5(), $which, $this->getFileExtension()));
  }

  public function getRelativePath($which = 'original')
  {
    $dir  = '/uploads/'. $this->getModel();
    $dir .= '/'. (($this->isNew()) ? strftime('%Y/%m/%d', time()) : $this->getCreatedAt('Y/m/d'));

    return implode('.', array($dir.'/'.$this->getMd5(), $which, $this->getFileExtension() .'?'. $this->getUpdatedAt('U')));
  }

  /**
   *  A proxy method to MultimediaPeer::makeThumb()
   *
   * @see MultimediaPeer::makeThumb()
   */
  public function makeThumb($size, $method = 'shave', $queue = false)
  {
    if ($queue == true)
    {
      cqStatic::loadZendFramework();

      try
      {
        $queue = cqJobQueue::create('multimedia_thumbs');
        $queue->send(implode(', ', array($this->getId(), $size, $method)));
        
        return true;
      }
      catch (Exception $e) { ; }
    }

    $thumb = MultimediaPeer::makeThumb($this->getAbsolutePath('original'), $size, $method);

    if ($thumb && copy($thumb, $this->getAbsolutePath($size)))
    {
      // Delete the temporary file
      unlink($thumb);

      return true;
    }

    return false;
  }

  public function rotate($which, $degrees = 90, $queue = false)
  {
    if ($queue == true)
    {
      cqStatic::loadZendFramework();

      try
      {
        $queue = cqJobQueue::create('multimedia_rotate');
        if ($queue)
        {
          $queue->send(implode(', ', array($this->getId(), $which, $degrees)));
        }
        
        return true;
      }
      catch (Exception $e) { ; }
    }
    
    $src = $this->getAbsolutePath($which);

    if ($src)
    {
      $image = new Imagick($src);
      $image->rotateImage(new ImagickPixel(), $degrees);
      $image->writeImage($src);
      $image->destroy();
    }

    return true;
  }

  public function downloadOriginalFromUrl($url)
  {
    try
    {
      $b = cqStatic::getBrowser();
      $b->get($url);
    }
    catch (Exception $e)
    {
      return false;
    }

    if (!$b->responseIsError())
    {
      @file_put_contents($this->getAbsolutePath('original'), $b->getResponseText());
      return true;
    }

    return false;
  }

  public function getFileExtension()
  {
    return ($this->getType() == 'video') ? 'flv' : 'jpg';
  }

  public function setColors($colors)
  {
    if (is_array($colors))
    {
      $colors = implode(', ', $colors);
    }

    return parent::setColors($colors);
  }

  public function getColors()
  {
    $colors = explode(', ', parent::getColors());
    $colors = array_filter($colors);

    return $colors;
  }

  public function createDirectory()
  {
    $dir = dirname($this->getAbsolutePath());
    if (is_dir($dir) && is_writable($dir))
    {
      return true;
    }

    umask(022);
    return @mkdir($dir, 0755, true);
  }

  public function delete(PropelPDO $con = null)
  {
    $original = $this->getAbsolutePath('original');

    @unlink($original);
    @unlink(str_replace('original', 'thumbnail', $original));
    @unlink(str_replace('original', '420x420', $original));
    @unlink(str_replace('original', '1024x768', $original));

    parent::delete($con);
  }
}
