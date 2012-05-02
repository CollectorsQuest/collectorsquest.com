<?php

class collectionsAction extends cqAjaxAction
{
  /**
   * @param  sfRequest|sfWebREquest $request
   * @return mixed|string
   */
  public function execute($request)
  {
    $section = $request->getParameter('section');
    $page = $request->getParameter('page');

    $template = str_replace(' ', '', ucwords(str_replace('-', ' ', $section) .' '. $page));
    $method = 'execute'.$template;

    if ($section == 'partial')
    {
      return $this->renderPartial('collections/'. $page);
    }
    else if ($section == 'component')
    {
      return $this->renderComponent('collections', $page);
    }

    return $this->$method($request, $template);
  }

}
