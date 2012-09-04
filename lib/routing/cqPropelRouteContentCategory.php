<?php

/**
 * Special propel route for content categories
 */
class cqPropelRouteContentCategory extends cqPropelRoute
{

  /**
   * Generates a URL from the given parameters.
   *
   * Will automatically add a "slugpath" parameter for a content category
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

    $params['slugpath'] = $content_category->getSlugPath();

    // forward swashes are escaped, so we unescape them
    return str_replace('%2F', '/', parent::generate($params, $context, $absolute));
  }

  /**
   * We want to use make 'slugpath' and 'slug' the real variables, so we manually
   * add 'slug'
   *
   * @return    array
   */
  public function getRealVariables()
  {
    $vars = parent::getRealVariables();

    // make propel use "slug" to filter ContentCategoryQuery
    return array_merge(array('slug'), $vars);
  }


  /**
   * Convert the 'slugpath' param to 'slug'
   *
   * @param     array $parameters
   * @return    array
   */
  protected function getObjectForParameters($parameters)
  {
    // We only need the last part of the slugpath parameter, as it holds the
    // slug of the category we are targeting
    $slugpath = explode('/', $parameters['slugpath']);
    unset($parameters['slugpath']);
    $parameters['slug'] = array_pop($slugpath);

    return parent::getObjectForParameters($parameters);
  }

  /**
   * Convert a Propel object to array, inserting our custom values
   *
   * In this case, we want to generate a slugpath for the Category and remove the
   * slug parameter so that it won't be added as ?slug=whatever at the end of
   * the route
   *
   * @param       ContentCategory $object
   * @return      array
   */
  protected function doConvertObjectToArray($object)
  {
    $parameters = parent::doConvertObjectToArray($object);
    unset($parameters['slug']);

    return $parameters;
  }

}
