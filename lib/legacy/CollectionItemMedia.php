<?php

/**
 * Subclass for representing a row from the 'collection_item_media' table.
 *
 *
 *
 * @package lib.model
 */
class CollectionItemMedia extends BaseCollectionItemMedia
{
  public function getCollection()
  {
    $item = CollectionItemPeer::retrieveByPK($this->getItemId());
    if ($item instanceof CollectionItem) {
      return $item->getCollection();
    }

    return null;
  }

  public function getFilename($absolute = false)
  {
    return ($absolute)?$this->getCollection()->getItemDir($absolute)."/".parent::getFilename():parent::getFilename();
  }

  public function setFilename($fileName)
  {
    if ($this->filename !== $fileName)
    {
      switch ($this->getType()) {
      case 'image':
        $img_original = $this->getCollection()->getOriginalsDir(true).'/'.$fileName;
        if (@is_file($img_original))
        {
          $img_thumb = $this->getCollection()->getThumbsDir(true).'/'.$fileName;
          $img_item  = $this->getCollection()->getItemsDir(true).'/'.$fileName;

          // Create the Thumbnails
          $thumbnail = new sfThumbnail(150, 150, false, true, 75, 'sfImageMagickAdapter', array('method' => 'shave_bottom'));
          $thumbnail->loadFile($img_original);
          $thumbnail->save($img_thumb, 'image/jpeg');

          $thumbnail = new sfThumbnail(420, 420, true, true, 75, 'sfImageMagickAdapter');
          $thumbnail->loadFile($img_original);
          $thumbnail->save($img_item, 'image/jpeg');

          parent::setFilename($fileName);
        } else {
          return false;
        }
        break;
      case 'video':
        parent::setFilename($fileName);
        break;
      }
		}

		return true;
  }
  
  public function setName($v)
  {
    return parent::setName(General::noXss($v));
  }
}
