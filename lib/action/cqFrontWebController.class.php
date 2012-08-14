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

    return $this->genUrl(
      self::generateRouteParamsForModelObject($model_object),
      $absolute
    );
  }

  public static function generateRouteParamsForModelObject(BaseObject $model_object)
  {
    $model_class = get_class($model_object);

    switch ($model_class):
      case 'Collection':
        return array(
            'sf_subject' => $model_object,
            'sf_route' => 'collector_by_slug',
        );

      case 'Collectible':
        return array(
            'sf_subject' => $model_object,
            'sf_route' => 'collectible_by_slug',
        );

      case 'Collector':
        return array(
            'sf_subject' => $model_object,
            'sf_route' => 'collector_by_slug',
        );

      case 'Collector':
        return array(
            'sf_subject' => $model_object,
            'sf_route' => 'collector_by_slug',
        );

      case 'CollectorCollection':
        return array(
            'sf_subject' => $model_object->getCollection(),
            'sf_route' => 'collection_by_slug',
        );

      case 'Comment':
        // for comments, return the route to their target object
        return self::generateRouteParamsForModelObject(
          $model_object->getModelObject()
        );

      default:
        return false;
    endswitch;
  }

}
