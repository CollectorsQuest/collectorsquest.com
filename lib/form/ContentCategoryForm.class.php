<?php

/**
 * ContentCategory form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 *
 * @method     ContentCategory getObject()
 */
class ContentCategoryForm extends BaseContentCategoryForm
{
  public function configure()
  {
    $this->setupCollectionCategoryIdField();
    $this->setupParentIdField();
    $this->setupSlugField();
    $this->setupDescriptionField();
    $this->setupSEOCollectionFields();
    $this->setupSEOMarketFields();

    $this->unsetFields();
  }

  protected function unsetFields()
  {
    unset ($this['tree_left']);
    unset ($this['tree_right']);
    unset ($this['tree_level']);
  }

  protected function setupCollectionCategoryIdField()
  {
    $this->widgetSchema['collection_category_id'] = new cqWidgetFormPropelChoiceByParentId(array(
        'model' => 'CollectionCategory',
        'add_empty' => true,
        'id_to_make_first' => 0,
    ));
  }

  protected function setupDescriptionField()
  {
    $this->widgetSchema['description'] = new sfWidgetFormTextareaTinyMCE();
  }

  protected function setupParentIdField()
  {
    $this->widgetSchema['parent_id'] = new cqWidgetFormPropelChoiceByNestedSet(array(
        'model' => 'ContentCategory',
        'add_empty' => true, 'chozen' => true
    ));
    $this->validatorSchema['parent_id'] = new sfValidatorPropelChoice(array(
        'model' => 'ContentCategory',
        'query_methods' => array(
            // do not allow selecting yourself as parent
            'filterById' => array(
                $this->isNew() ? null : $this->getObject()->getId(),
                Criteria::NOT_EQUAL),
        )
    ));
  }

  protected function setupSlugField()
  {
    $this->validatorSchema['slug']->setOption('required', false);
  }

  protected function setupSEOCollectionFields()
  {
    $this->widgetSchema['seo_collections_title_prefix'] = new sfWidgetFormInputText(
      array('label' => 'Name Prefix')
    );
    $this->validatorSchema['seo_collections_title_prefix'] = new sfValidatorString(
      array('required' => false)
    );

    $this->widgetSchema['seo_collections_title_suffix'] = new sfWidgetFormInputText(
      array('label' => 'Name Suffix')
    );
    $this->validatorSchema['seo_collections_title_suffix'] = new sfValidatorString(
      array('required' => false)
    );

    $this->widgetSchema['seo_collections_description'] = new sfWidgetFormTextarea(
      array('label' => 'Description')
    );
    $this->validatorSchema['seo_collections_description'] = new sfValidatorString(
      array('required' => false)
    );

    $this->widgetSchema['seo_collections_keywords'] = new sfWidgetFormInputText(
      array('label' => 'Keywords')
    );
    $this->validatorSchema['seo_collections_keywords'] = new sfValidatorString(
      array('required' => false)
    );

    $this->widgetSchema['seo_collections_use_singular'] = new sfWidgetFormInputCheckbox(
      array('label' => 'Use the singular name?')
    );
    $this->validatorSchema['seo_collections_use_singular'] = new sfValidatorBoolean(
      array('required' => false)
    );
  }

  protected function setupSEOMarketFields()
  {
    $this->widgetSchema['seo_market_title_prefix'] = new sfWidgetFormInputText(
      array('label' => 'Name Prefix')
    );

    $this->validatorSchema['seo_market_title_prefix'] = new sfValidatorString(
      array('required' => false)
    );

    $this->widgetSchema['seo_market_title_suffix'] = new sfWidgetFormInputText(
      array('label' => 'Name Suffix')
    );

    $this->validatorSchema['seo_market_title_suffix'] = new sfValidatorString(
      array('required' => false)
    );

    $this->widgetSchema['seo_market_description'] = new sfWidgetFormTextarea(
      array('label' => 'Description')
    );
    $this->validatorSchema['seo_market_description'] = new sfValidatorString(
      array('required' => false)
    );

    $this->widgetSchema['seo_market_keywords'] = new sfWidgetFormInputText(
      array('label' => 'Keywords')
    );
    $this->validatorSchema['seo_market_keywords'] = new sfValidatorString(
      array('required' => false)
    );

    $this->widgetSchema['seo_market_use_singular'] = new sfWidgetFormInputCheckbox(
      array('label' => 'Use the singular name?')
    );

    $this->validatorSchema['seo_market_use_singular'] = new sfValidatorBoolean(
      array('required' => false)
    );
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $parent = $this->getObject()->getParent();
    $this->setDefault('parent_id', $parent ? $parent->getId() : null);

    $this->setDefaults(array_merge($this->defaults, array(
      'seo_collections_title_prefix'     => $this->getObject()->getSeoCollectionsTitlePrefix(),
      'seo_market_title_prefix'          => $this->getObject()->getSeoMarketTitlePrefix(),
      'seo_collections_title_suffix'     => $this->getObject()->getSeoCollectionsTitleSuffix(),
      'seo_market_title_suffix'          => $this->getObject()->getSeoMarketTitleSuffix(),
      'seo_collections_description'      => $this->getObject()->getProperty(ContentCategoryPeer::PROPERTY_SEO_COLLECTIONS_DESCRIPTION),
      'seo_market_description'           => $this->getObject()->getProperty(ContentCategoryPeer::PROPERTY_SEO_MARKET_DESCRIPTION),
      'seo_collections_keywords'         => $this->getObject()->getSeoCollectionsKeywords(),
      'seo_market_keywords'              => $this->getObject()->getSeoMarketKeywords(),
      'seo_collections_use_singular'     => (boolean) $this->getObject()->getSeoCollectionsUseSingular(),
      'seo_market_use_singular'          => (boolean) $this->getObject()->getSeoMarketUseSingular(),
    )));
  }

  public function doUpdateObject($values)
  {
    parent::doUpdateObject($values);

    if (isset($values['parent_id']))
    {
      // check if we need to reorder the tree
      $parent = ContentCategoryPeer::retrieveByPK($values['parent_id']);

      if ($this->getObject()->getParent() != $parent)
      {
        if ($this->getObject()->isInTree())
        {
          // if the current object is somewhere in the tree
          // we move it to its new parent
          $this->getObject()->moveToLastChildOf($parent);
        }
        else
        {
          // if not in tree, then this is a new object
          // and we insert it as the last child of its parent
          $this->getObject()->insertAsLastChildOf($parent);
        }
      }
    }

    if (isset($values['seo_collections_title_prefix']))
    {
      $this->getObject()->setSeoCollectionsTitlePrefix(
        (string) $values['seo_collections_title_prefix']
      );
    }
    if (isset($values['seo_market_title_prefix']))
    {
      $this->getObject()->setSeoMarketTitlePrefix(
        (string) $values['seo_market_title_prefix']
      );
    }

    if (isset($values['seo_collections_title_suffix']))
    {
      $this->getObject()->setSeoCollectionsTitleSuffix(
        (string) $values['seo_collections_title_suffix']
      );
    }
    if (isset($values['seo_market_title_suffix']))
    {
      $this->getObject()->setSeoMarketTitleSuffix(
        (string) $values['seo_market_title_suffix']
      );
    }

    if (isset($values['seo_collections_description']))
    {
      $this->getObject()->setSeoCollectionsDescription(
        (string) $values['seo_collections_description']
      );
    }
    if (isset($values['seo_market_description']))
    {
      $this->getObject()->setSeoMarketDescription(
        (string) $values['seo_market_description']
      );
    }

    if (isset($values['seo_collections_keywords']))
    {
      $this->getObject()->setSeoCollectionsKeywords(
        (string) $values['seo_collections_keywords']
      );
    }
    if (isset($values['seo_market_keywords']))
    {
      $this->getObject()->setSeoMarketKeywords(
        (string) $values['seo_market_keywords']
      );
    }

    if (isset($values['seo_collections_use_singular']))
    {
      $this->getObject()->setSeoCollectionsUseSingular(
        (boolean) $values['seo_collections_use_singular']
      );
    }
    if (isset($values['seo_market_use_singular']))
    {
      $this->getObject()->setSeoMarketUseSingular(
        (boolean) $values['seo_market_use_singular']
      );
    }
  }

}
