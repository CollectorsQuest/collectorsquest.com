<?php

/**
 * Special propel route for content categories
 */
class cqPropelRouteContentCategory extends cqPropelRoute
{

  /**
   * Convert the 'path' param to 'slug'
   *
   * @param     array $parameters
   * @return    array
   */
  protected function getObjectForParameters($parameters)
  {
    // We only need the last part of the path parameter, as it holds the
    // slug of the category we are targeting
    $path = explode('/', $parameters['path']);
    unset($parameters['path']);
    $parameters['slug'] = array_pop($path);

    return parent::getObjectForParameters($parameters);
  }


  public function getRealVariables()
  {
    $vars = parent::getRealVariables();

    // we need to remove "path" from the real variables, otherwise propel
    // will attempt to use it as a column to filter on.
    // this array_search unset is safe because we know that "path" always exists,
    // as it is an required param (if it didn't, array_search would return false,
    // and unset the element at index 0)
    unset( $vars[array_search('path', $vars)] );

    // instead make propel use "slug" to filter ContentCategoryQuery
    return array_merge(array('slug'), $vars);
  }

  /**
   * Generates a URL from the given parameters.
   *
   * Will automatically add a "path" parameter for a content category
   *
   * @param  mixed   $params    The parameter values
   * @param  array   $context   The context
   * @param  Boolean $absolute  Whether to generate an absolute URL
   *
   * @return string The generated URL
   */
  public function generate($params, $context = array(), $absolute = false)
  {
    /* @var $content_category ContentCategory */
    $content_category = $params['sf_subject'];

    $ancestors = ContentCategoryQuery::create()
      ->ancestorsOfObjectIncluded($content_category)
      ->notRoot()
      ->orderByTreeLevel()
      ->select('Slug')
      ->find()->getArrayCopy();

    $params['path'] = implode('/', $ancestors);

    // forward swashes are escaped, so we unescape them
    return str_replace('%2F', '/', parent::generate($params, $context, $absolute));
  }

  /**
   * Convert a Propel object to array, inserting our custom values
   *
   * In this case, we want to generate a path for the Category and remove the slug
   * parameter so that it won't be added as ?slug=whatever at the end of the route
   *
   * @param       ContentCategory $object
   * @return      array
   */
  protected function doConvertObjectToArray($object)
  {
    $parameters = parent::doConvertObjectToArray($object);
    unset($parameters['slug']);

    $ancestors = ContentCategoryQuery::create()
      ->ancestorsOfObjectIncluded($object)
      ->notRoot()
      ->orderByTreeLevel()
      ->select('Slug')
      ->find()->getArrayCopy();

    $parameters['path'] = implode('/', $ancestors);

    return $parameters;
  }
}
