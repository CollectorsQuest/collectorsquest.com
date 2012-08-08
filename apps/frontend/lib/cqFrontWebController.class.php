<?php

/**
 * cqWebController
 */
class cqFrontWebController extends sfFrontWebController
{

  /**
   *
   * @param BaseObject $model_object
   * @param type $absolute
   *
   * @return string|false An URL or boolean false if one cannot be generated
   */
  public function genUrlForModelObject(
    BaseObject $model_object = null,
    $absolute = false
  ) {
    if (null === $model_object)
    {
      return false;
    }

    $model_class = get_class($model_object);

    switch ($model_class):
      case 'Collection':
        return $this->_genUrlForModelObjectAndRoute(
          $model_object, 'collector_by_slug', $absolute);

      case 'Collectible':
        return $this->_genUrlForModelObjectAndRoute(
          $model_object, 'collectible_by_slug', $absolute);

      case 'Collector':
        return $this->_genUrlForModelObjectAndRoute(
          $model_object, 'collector_by_slug', $absolute);

      case 'Collector':
        return $this->_genUrlForModelObjectAndRoute(
          $model_object, 'collector_by_slug', $absolute);

      case 'CollectorCollection':
        return $this->_genUrlForModelObjectAndRoute(
          $model_object->getCollection(), 'collection_by_slug', $absolute);

      case 'Comment':
        return $this->genUrlForModelObject(
          $model_object->getModelObject(), $absolute);

      default:
        return false;
    endswitch;
  }

  /**
   * Helper method for genUrlForModelObject()
   *
   * @param     BaseObject $model_object
   * @param     string $sf_route
   * @param     boolean $absolute
   * @return    string
   */
  protected function _genUrlForModelObjectAndRoute(
    BaseObject $model_object,
    $sf_route,
    $absolute
  ) {
    return $this->genUrl(array(
        'sf_route' => $sf_route,
        'sf_subject' => $model_object,
      ), $absolute);
  }

}
