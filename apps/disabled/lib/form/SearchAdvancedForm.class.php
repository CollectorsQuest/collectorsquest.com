<?php

class SearchAdvancedForm extends sfForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'q'      => new sfWidgetFormInputText(),
      'types'  => new sfWidgetFormSelectCheckbox(
        array('choices' => array(
          'collectibles' => 'Collectibles',
          'collections' => 'Collections',
          'collectors' => 'Collectors',
          'blog' => 'Blog Articles',
          'calendar' => 'Calendar'
      )))
    ));

    $c = new Criteria();
    $c->add(CollectionCategoryPeer::PARENT_ID, 0);
    $c->addAscendingOrderByColumn(CollectionCategoryPeer::NAME);
    $this->widgetSchema['category'] = new sfWidgetFormPropelSelectMany(array('model' => 'CollectionCategory', 'criteria' => $c));

    $this->setValidators(array(
      'q' => new sfValidatorString(array('max_length' => 128)),
    ));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }
}
