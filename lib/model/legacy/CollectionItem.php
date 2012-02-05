<?php

/**
 * Subclass for representing a row from the 'collection_item' table.
 *
 *
 *
 * @package lib.model
 */
class CollectionItem extends BaseCollectionItem
{
  public $update_search_index = true;

  public function save(PropelPDO $con = null)
  {
    if ($this->isNew())
    {
      $collection = $this->getCollection();
      $num_items = intval($collection->getNumItems()) + 1;
      $collection->setNumItems($num_items);
      $collection->setCreatedAt(time());
      $collection->save($con);
    }

    parent::save($con);

    //$this->_updateSearchIndex();
  }

  public function __toString()
  {
    return $this->getName();
  }

  public function getName($escaped = false)
  {
    $name = parent::getName();

    return ($escaped)?stripslashes(str_replace(' ', '_', str_replace(array('(', ')', '[', ']'), '', $name))):$name;
  }

  public function getPhoto($absolute = false)
  {
    return ($absolute)?$this->getCollection()->getItemsDir($absolute)."/".parent::getPhoto():parent::getPhoto();
  }

  public function getPhotoOriginal($absolute = true)
  {
    return $this->getCollection()->getOriginalsDir($absolute)."/".parent::getPhoto();
  }

  public function setPhoto($fileName, $regenerate = false, $passthrough = false)
  {
    if (($this->photo !== $fileName || $regenerate) && $passthrough != true)
    {
      $img_original = $this->getCollection()->getOriginalsDir(true).'/'.$fileName;
      if (@is_file($img_original))
      {
        $img_thumb = $this->getCollection()->getThumbsDir(true).'/'.$fileName;
        $img_item = $this->getCollection()->getItemsDir(true).'/'.$fileName;

        // Some garbage collecting
        $keep_original = $regenerate ? true : false;
        $this->deletePhotos($keep_original);

        // Create the Thumbnails
        $thumbnail = new sfThumbnail(150, 150, false, true, 75, 'sfImageMagickAdapter', array('method' => 'shave_bottom'));
        $thumbnail->loadFile($img_original);
        $thumbnail->save($img_thumb, 'image/jpeg');

        $thumbnail = new sfThumbnail(420, null, true, true, 75, 'sfImageMagickAdapter');
        $thumbnail->loadFile($img_original);
        $thumbnail->save($img_item, 'image/jpeg');
      }
    }

    parent::setPhoto($fileName);
  }

  public function getPhotoOrientation()
  {
    $photo = $this->getPhoto(true);
    list($width, $height) = getimagesize($photo);

    return ($width > $height) ? 'landscape' : 'portrait';
  }

  public function getPhotoProportion()
  {
    $photo = $this->getPhoto(true);
    list($width, $height) = @getimagesize($photo);

    return ($height > 0) ? $width / $height : 1;
  }

  public function delete(PropelPDO $con = null)
  {
    $this->deleteTags();
    $this->deletePhotos();

    $collection = $this->getCollection();
    $collection->setNumItems($collection->getNumItems()-1);
    $collection->save();

    parent::delete();
  }

  private function deleteTags()
  {
    return $this->removeAllTags();
  }

  private function deletePhotos($keep_original = false)
  {
    $cwd = getcwd();

    if (!$keep_original)
    {
      // Delete original photo
      $originals = $this->getCollection()->getOriginalsDir(true);
      if (!empty($originals))
      {
        @chdir($originals) && exec("rm -f ". $this->getPhoto());
      }
    }

    // Delete item photo
    $items = $this->getCollection()->getItemsDir(true);
    if (!empty($items)) {
      @chdir($items) && exec("rm -f ". $this->getPhoto());
    }

    // Delete photo thumbnail
    $thumbnails = $this->getCollection()->getThumbsDir(true);
    if (!empty($thumbnails)) {
      @chdir($thumbnails) && exec("rm -f ". $this->getPhoto());
    }

    chdir($cwd);
  }

  public function setCustomFields($fields)
  {
    if (is_array($fields))
    {
      $this->deleteCustomValues();
      $fields = array_filter($fields);
      foreach ($fields as $id => $value)
      {
        if (is_array($value)) $value = array_filter($value);
        if (empty($value)) continue;

        $custom = new CustomValue();
        $custom->setCollection($this->getCollection());
        $custom->setItemId($this->getId());
        $custom->setFieldId($id);

        if ($custom->setValue($value)) {
          $custom->save();
        } else {
          unset($custom);
        }
      }
    }
  }

  public function getCustomValues($c = null, PropelPDO $con = null)
  {
    if ($c === null) {
      $c = new Criteria();
    }
    elseif ($c instanceof Criteria)
    {
      $c = clone $c;
    }
    $c->addAscendingOrderByColumn(CustomValuePeer::FIELD_ID);

    $_custom_values = parent::getCustomValues($c, $con);
    $custom_values = array();
    foreach ($_custom_values as $value)
    {
      $custom_values[$value->getFieldId()] = $value;
    }

    return $custom_values;
  }

  public function deleteCustomValues()
  {
    $c = new Criteria();
    $c->add(CustomValuePeer::COLLECTION_ID, $this->getCollectionId());
    $c->add(CustomValuePeer::ITEM_ID, $this->getId());

    return CustomValuePeer::doDelete($c);
  }

  public function setDescription($v)
  {
    return parent::setDescription(General::noXss($v));
  }

  public function setName($v)
  {
    return parent::setName(General::noXss($v));
  }

  public function getAdditionalMedia()
  {
    $c = new Criteria();
    $c->add(CollectionItemMediaPeer::ITEM_ID, $this->getId());
    return CollectionItemMediaPeer::doSelect($c);
  }

  public function getTagIds()
  {
    $c = new Criteria;
    $c->addSelectColumn(iceModelTaggingPeer::TAG_ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_ID, $this->getId());
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'CollectionItem');

    $stmt = iceModelTaggingPeer::doSelectStmt($c);
    $tag_ids = array();
    while ($tag_id = $stmt->fetchColumn(0)) {
      $tag_ids[] = (int) $tag_id;
    }

    return $tag_ids;
  }

  public function getRelatedCollections($limit = 5, &$rnd_flag = false)
  {
    $collections = CollectionPeer::getRelatedCollections($this, $limit);

    if ($limit != $found = count($collections))
    {
      $limit = $limit - $found;
      $context = sfContext::getInstance();

      if ($context->getUser()->isAuthenticated())
      {
        $collector = $context->getUser()->getCollector();
        $c = new Criteria();
        $c->add(CollectionPeer::ID, $this->getId(), Criteria::NOT_EQUAL);
        $c->add(CollectionPeer::COLLECTOR_ID, $collector->getId(), Criteria::NOT_EQUAL);
        $c->addAscendingOrderByColumn('RAND()');

        $collections = array_merge($collections, CollectionPeer::getRelatedCollections($collector, $limit, $c));
      }
    }

    if (0 == count($collections))
    {
      $c = new Criteria();
      $c->add(CollectionPeer::ID, $this->getCollectionId(), Criteria::NOT_EQUAL);

      $collections = CollectionPeer::getRandomCollections($limit, $c);
      $rnd_flag = true;
    }

    return $collections;
  }

  public function getCollectionTags()
  {
    $tags = $this->getCollection()->getTags();
    arsort($tags);
    return array_keys($tags);
  }

  public function getEbayKeywords()
  {
    $tags = $this->getTags();
    $keywords = (!empty($tags)) ? $tags : TermPeer::getTerms($this);
    if (empty($keywords)) $keywords = $this->getCollectionTags();

    shuffle($keywords);
    return str_replace(' ', '+', implode('+', (array) array_slice($keywords, 0, (count($keywords) < 2) ? count($keywords) : 2)));
  }

  public function getAmazonKeywords()
  {
    $tags = $this->getTags();
    $keywords = (!empty($tags)) ? $tags : TermPeer::getTerms($this);
    if (empty($keywords)) $keywords = $this->getCollectionTags();

    return $keywords;
  }

  public function getTagString()
  {
    return implode(", ", $this->getTags());
  }

  public function isForSale()
  {
    //return 0 < sfPropelFinder::from('CollectionItemForSale')->where('ItemId', $this->getId())->where('IsSold', false)->count();
	// Replace above line by Prakash Panchal 5-APR-2011
	$oCriteria = new Criteria();
	$oCriteria->add(CollectionItemForSalePeer::ITEM_ID,$this->getId());
	$oCriteria->add(CollectionItemForSalePeer::IS_SOLD,true);
	$snCount = CollectionItemForSalePeer::doCount($oCriteria);

	return $snCount;
  }

  public function getForSaleInformation()
  {
    //return sfPropelFinder::from('CollectionItemForSale')->findOneBy('ItemId', $this->getId());
	// Replace above line by Prakash Panchal 5-APR-2011
	$oCriteria =new Criteria();
	$oCriteria->add(CollectionItemForSalePeer::ITEM_ID,$this->getId());
	return CollectionItemForSalePeer::doSelectOne($oCriteria);
  }


  private function _updateSearchIndex()
  {
    if ($this->update_search_index == false)
    {
      $this->update_search_index = true;
      return;
    }

    // Zend Lucene
    $search = new cqSearchIndex(get_class($this));
    $search->updateIndexDocument($this->getId());
  }
  public static function updateItemIsForSale($snCollectionItemId)
  {
  	$omCollectionItem = CollectionItemPeer::retrieveByPK($snCollectionItemId);
	$omCollectionItem->setIsForSale(1);

	try
    {
      $omCollectionItem->save();
    }
    catch (PropelException $e)
    {
      return false;
    }
    return $omCollectionItem;
  }
}

sfPropelBehavior::add('CollectionItem', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'CollectionItem',
  array(
    'PropelActAsSluggableBehavior' => array(
      'columns' => array(
        'from' => CollectionItemPeer::NAME,
        'to' => CollectionItemPeer::SLUG
      ),
      'separator' => '-',
      'permanent' => false
    )
  )
);
