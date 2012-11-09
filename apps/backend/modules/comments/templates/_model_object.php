<?php

/* @var $Comment Comment */ $Comment;

$model_object = $Comment->getModelObject();
if ($model_object)
{
  $routing_params = cqFrontWebController::generateRouteParamsForModelObject($model_object);
  $route_name = $routing_params['sf_route'];
  unset($routing_params['sf_route']);

  echo link_to(
    sprintf('%s (%s)', $model_object, get_class($model_object)),
    cqContext::getInstance()->getConfiguration()->generateFrontendUrl($route_name, $routing_params)
  );
}
else
{
  echo 'Comment target deleted';
}
