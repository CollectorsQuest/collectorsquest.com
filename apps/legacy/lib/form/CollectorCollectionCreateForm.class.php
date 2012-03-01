<?php

class CollectorCollectionCreateForm extends LegacyCollectorCollectionForm
{

  protected function unsetFields()
  {
    parent::unsetFields();

    unset($this['graph_id']);
    unset($this['collector_id']);
    unset($this['id']);
    unset($this['slug']);
  }

}
