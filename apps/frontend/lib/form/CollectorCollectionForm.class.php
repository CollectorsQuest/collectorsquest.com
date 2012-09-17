<?php

/**
 * CollectorCollection Form, to be used as a base for all collection
 * forms in the frontend app
 */
class CollectorCollectionForm extends BaseCollectorCollectionForm
{

  public function configure()
  {
    $this->setupNameField();
    $this->setupDescriptionField();
    $this->setupTagsField();
    $this->unsetFields();

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
    $this->widgetSchema->setNameFormat('collection[%s]');
  }

  protected function setupNameField()
  {
    $this->widgetSchema['name']->setAttribute('required', 'required');

    $this->validatorSchema['name'] = new cqValidatorName(
      array('required' => true),
      array('invalid' => 'You need to use more descriptive name for your collection
                          (is it the camera auto generated name?)')
    );
  }

  protected function setupDescriptionField()
  {
    $this->widgetSchema['description']->setAttribute('required', 'required');
  }

  protected function setupTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getTags();
    if (sfContext::hasInstance())
    {
      $request = cqContext::getInstance()->getRequest();
      if (( $values = $request->getParameter($this->getName()) ))
      {
        if (isset($values['tags']))
        {
          $tags = $values['tags'];
        }
      }
    }

    $this->widgetSchema['tags'] = new cqWidgetFormInputTags(array(
      'label' => 'Tags',
      'autocompleteURL' => '@ajax_typeahead?section=tags&page=edit',
    ), array(
      'required' => 'required',
    ));
    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words
               or phrases, separated by commas'
    );

    $this->widgetSchema['tags']->setDefault($tags);
    $this->validatorSchema['tags'] = new cqValidatorTags(array(), array(
        'required' => 'Please enter tags for your collection.',
    ));
  }

  protected function setupThumbnailField()
  {
    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile(
      array('label' => 'Photo')
    );
    $this->validatorSchema['thumbnail'] = new cqValidatorFile(array(
      'mime_types' => 'cq_supported_images', 'required' => false
    ));

    /**
     * We need to make the Thumbnail field required if
     * the Collection does not have a Thumbnail yet
     */
    if (!$this->getObject()->hasThumbnail())
    {
      $this->widgetSchema['thumbnail']->setAttribute('required', 'required');
      $this->validatorSchema['thumbnail']->setOption('required', true);
    }

    $this->getWidgetSchema()->setHelp(
      'thumbnail', 'Choose a photo which represents your entire collection.'
    );
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
