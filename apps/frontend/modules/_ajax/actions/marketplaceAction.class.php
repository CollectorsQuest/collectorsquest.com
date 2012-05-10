<?php

class marketplaceAction extends cqAjaxAction
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
      return $this->renderPartial('marketplace/'. $page);
    }
    else if ($section == 'component')
    {
      return $this->renderComponent('marketplace', $page);
    }

    return $this->$method($request, $template);
  }

}
