<?php

/**
 * CollectionCategory filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BackendCollectionCategoryFormFilter extends BaseCollectionCategoryFormFilter
{
  public function configure()
  {
    $this->widgetSchema['parent'] = new bsWidgetFormInputTypeAhead(array(
      'source' => $this->getOption('url_parent', sfContext::getInstance()->getController()->genUrl('collectionCategories/parent')),
    ));

    $this->validatorSchema['parent'] = new sfValidatorPropelChoice(array('required' => false, 'model' => 'CollectionCategory', 'column' => 'id'));
  }
}
