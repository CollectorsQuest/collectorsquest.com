<?php

/**
 * CollectorCollection Form, to be used as a base for all collection
 * forms in the frontend app
 */
class CollectorCollectionForm extends BaseCollectorCollectionForm
{

  public function configure()
  {
    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');

    $this->setupTagsField();
    $this->unsetFields();

    $this->widgetSchema->setNameFormat('collection[%s]');
  }

  protected function setupTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getTags();
    if (sfContext::hasInstance())
    {
      $request = sfContext::getInstance()->getRequest();
      if (( $values = $request->getParameter($this->getName()) ))
      {
        if (isset($values['tags']))
        {
          $tags = $values['tags'];
        }
      }
    }

    $this->widgetSchema['tags'] = new cqWidgetFormMultipleInputText();

    $this->widgetSchema['tags']->setDefault($tags);
    $this->widgetSchema['tags']->setAttribute('name', 'collection[tags][]');
    $this->widgetSchema['tags']->setAttribute('class', 'tag');

    $this->validatorSchema['tags'] = new sfValidatorPass();
  }

  protected function unsetFields()
  {
    unset($this['id']);
    unset($this['graph_id']);
    unset($this['collection_category_id']);
    unset($this['num_items']);
    unset($this['num_ratings']);
    unset($this['num_views']);
    unset($this['num_comments']);
    unset($this['score']);
    unset($this['is_public']);
    unset($this['is_featured']);
    unset($this['rating_on']);
    unset($this['comments_on']);
    unset($this['eblob']);
    unset($this['created_at']);
    unset($this['updated_at']);
  }

}
