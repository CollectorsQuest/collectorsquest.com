<?php 

require_once 'vendor/zend/Zend/Search/Lucene/Analysis/Analyzer.php';
require_once 'vendor/zend/Zend/Search/Lucene.php';
require_once 'vendor/zend/Zend/Search/Lucene/Analysis/Analyzer/Common/TextNum/CaseInsensitive.php';

class cqSearchIndex
{
  private $_class = null;

  public function __construct($class)
  {
    $this->_class = $class;
    Zend_Search_Lucene_Analysis_Analyzer::setDefault($this->_getIndexAnalyzer());
  }

  public function updateIndexDocument($id)
  {
    $index = $this->getLuceneSearchIndex();
    Zend_Search_Lucene_Analysis_Analyzer::setDefault($this->_getIndexAnalyzer());

    // First remove the current record as we cannot do updates
    $this->_deleteIndexDocument($id);

    // Create and add document to index
    $doc = $this->_createIndexDocument($id);

    // Then add it to the search index
    $index->addDocument($doc);
  }

  public function getLuceneSearchIndex()
  {
    $where = sfConfig::get('sf_data_dir') . '/search/' . strtolower($this->_class);
    $index = is_dir($where) ? Zend_Search_Lucene::open($where) : Zend_Search_Lucene::create($where);

    return $index;
  }

  public function rebuildIndex($show_progress = false)
  {
    $index = $this->getLuceneSearchIndex();
    Zend_Search_Lucene_Analysis_Analyzer::setDefault($this->_getIndexAnalyzer());

    if ($this->_class == 'wpPost') {
      $con = Propel::getConnection('blog');
    } else if ($this->_class == 'hcEvent') {
      $con = Propel::getConnection('calendar');
    } else {
      $con = Propel::getConnection();
    }

    $objects = call_user_func(array($this->_class . 'Peer', 'doSelect'), new Criteria, $con);

    $i = 0;
    foreach ($objects as $object)
    {
      $doc = $this->_createIndexDocument($object->getId());
      $index->addDocument($doc);
      if ($show_progress)
      {
        if (++$i % ceil(count($objects) / 100) == 0)
        {
          echo '.';
        }
      }
    }
  }

  public static function getStopWords()
  {
    return self::$_stop_words;
  }

  private function _createIndexDocument($id)
  {
    $doc = new Zend_Search_Lucene_Document();
    switch ($this->_class) {
      case 'Collector':
        $collector = CollectorPeer::retrieveByPK($id);
        $profile = $collector->getProfile();

        $doc->addField(Zend_Search_Lucene_Field::Keyword('collector_id', $collector->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('username', $collector->getUsername()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('collections', implode(', ', $collector->getCollectionsArray())));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('tags', implode(', ', $collector->getTags())));

        if ($profile instanceof CollectorProfile) {
          $doc->addField(Zend_Search_Lucene_Field::Unstored('display_name', $profile->getDisplayName()));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('gender', ($profile->getGender() == 1) ? 'female'
                                                                        : 'male'));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('city', $profile->getCity()));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('zip', $profile->getZip()));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('state', $profile->getState()));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('country', $profile->getCountry()));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('about_me', $this->_filter($profile->getAbout())));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('about_collections', $this->_filter($profile->getCollections())));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('about_interests', $this->_filter($profile->getInterests())));
        }

        break;
      case 'Collection':
        $collection = CollectionPeer::retrieveByPK($id);
        $profile = $collection->getCollectionProfile();

        $doc->addField(Zend_Search_Lucene_Field::Keyword('collection_id', $collection->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('title', $collection->getTitle()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('description', $this->_filter($collection->getDescription())));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('tags', implode(', ', $collection->getTags())));

        if ($profile instanceof CollectionProfile) {
          $doc->addField(Zend_Search_Lucene_Field::Unstored('about_started', $this->_filter($profile->getStarted())));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('about_finished', $this->_filter($profile->getFinished())));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('about_tips', $this->_filter($profile->getTips())));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('favorite_items', $this->_filter($profile->getFavoriteItems())));
          $doc->addField(Zend_Search_Lucene_Field::Unstored('looking_for', $this->_filter($profile->getLookingFor())));
        }

        break;
      case 'CollectionItem':
        $collection_item = CollectionItemPeer::retrieveByPK($id);

        $doc->addField(Zend_Search_Lucene_Field::Keyword('item_id', $collection_item->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Keyword('collection_id', $collection_item->getCollectionId()));
        $doc->addField(Zend_Search_Lucene_Field::Keyword('collector_id', $collection_item->getCollection()->getCollectorId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('name', $collection_item->getName()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('description', $this->_filter($collection_item->getDescription())));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('tags', implode(' ', $collection_item->getTags())));
        break;
      case 'ResourceCategory':
        $resource_category = ResourceCategoryPeer::retrieveByPK($id);

        $doc->addField(Zend_Search_Lucene_Field::Keyword('resource_category_id', $resource_category->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('name', $resource_category->getName()));
        break;
      case 'ResourceEntry':
        $resource_entry = ResourceEntryPeer::retrieveByPK($id);

        $doc->addField(Zend_Search_Lucene_Field::Keyword('resource_entry_id', $resource_entry->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Keyword('resource_category_id', $resource_entry->getCategoryId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('name', $resource_entry->getName()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('description', $this->_filter($resource_entry->getDescription())));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('url', $resource_entry->getUrl()));
        break;
      case 'Store':
        $store = StorePeer::retrieveByPK($id);

        $doc->addField(Zend_Search_Lucene_Field::Keyword('store_id', $store->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('name', $store->getName()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('about', $store->getAbout()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('tags', implode(', ', $store->getTags())));
        break;
      case 'StoreCatalog':
        $catalog = StoreCatalogPeer::retrieveByPK($id);

        $doc->addField(Zend_Search_Lucene_Field::Keyword('store_catalog_id', $catalog->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('name', $catalog->getName()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('tags', implode(', ', $catalog->getTags())));
        break;
      case 'StoreProduct':
        $product = StoreProductPeer::retrieveByPK($id);

        $doc->addField(Zend_Search_Lucene_Field::Keyword('store_product_id', $product->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('name', $product->getName()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('tags', implode(', ', $product->getTags())));
        break;
      case 'wpPost':
        $post = wpPostPeer::retrieveByPK($id);

        if ($post->getPostStatus() != 'publish') continue;
        if (!$post->getId()) continue;

        $doc->addField(Zend_Search_Lucene_Field::Keyword('post_id', $post->getId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('title', $post->getPostTitle()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('content', $post->getPostContent()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('tags', $post->getTags()));
        break;
      case 'hcEvent':
        $event = hcEventPeer::retrieveByPK($id);

        $doc->addField(Zend_Search_Lucene_Field::Keyword('event_id', $event->getPkId()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('title', $event->getTitle()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('description', $event->getDescription()));
        $doc->addField(Zend_Search_Lucene_Field::Unstored('tags', implode(', ', $event->getTags())));
        break;
    }

    return $doc;
  }

  private function _deleteIndexDocument($id)
  {
    $index = $this->getLuceneSearchIndex();
    Zend_Search_Lucene_Analysis_Analyzer::setDefault($this->_getIndexAnalyzer());

    $term = new Zend_Search_Lucene_Index_Term($id, $this->_class . '_id');
    $query = new Zend_Search_Lucene_Search_Query_Term($term);

    $hits = array();
    $hits = $index->find($query);

    foreach ($hits as $hit)
    {
      $index->delete($hit->id);
    }
  }

  private function _filter($text)
  {
    // split into words
    $words = str_word_count(strtolower($text), 1);

    // ignore stop words
    $words = array_diff($words, self::$_stop_words);

    return implode(' ', $words);
  }

  private function _getIndexAnalyzer()
  {
    return new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive();
  }

  private static $_stop_words = array(
    'i', 's', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours',
    'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers',
    'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves',
    'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are',
    'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does',
    'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until',
    'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into',
    'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down',
    'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here',
    'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more',
    'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so',
    'than', 'too', 'very',
  );
}