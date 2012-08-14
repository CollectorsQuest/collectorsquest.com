<?php

/**
 * cqWebController
 */
class cqFrontWebController extends sfFrontWebController
{

  /**
   *
   * @param BaseObject $model_object
   * @param boolean $absolute
   *
   * @return string|boolean An URL or boolean false if one cannot be generated
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
    switch (get_class($model_object))
    {
      case 'Collection':
      case 'CollectorCollection':
        /** @var $model_object Collection|CollectorCollection */

        return array(
          'sf_subject' => $model_object,
          'sf_route' => 'collection_by_slug',
        );

      case 'Collectible':
      case 'CollectionCollectible':
        /** @var $model_object Collectible|CollectionCollectible */

        return array(
          'sf_subject' => $model_object,
          'sf_route' => 'collectible_by_slug',
        );

      case 'Collector':
        /** @var $model_object Collector */

        return array(
          'sf_subject' => $model_object,
          'sf_route' => 'collector_by_slug',
        );

      case 'CollectorCollection':
        /** @var $model_object CollectorCollection */
        return array(
          'sf_subject' => $model_object->getCollection(),
          'sf_route' => 'collection_by_slug',
        );

      case 'Comment':
        /** @var $model_object Comment */

        // for comments, return the route to their target object
        return self::generateRouteParamsForModelObject(
          $model_object->getModelObject()
        );

      default:
        return false;
    }
  }

}
