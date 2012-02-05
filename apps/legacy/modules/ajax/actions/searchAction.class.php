<?php

class searchAction extends cqAjaxAction
{
  public function execute($request)
  {
    $section = $request->getParameter('section');
    $name = $request->getParameter('name');

    $template = str_replace(' ', '', ucwords(str_replace('-', ' ', $section) .' '. $name));
    $method = 'execute'.$template;

    if ($section == 'partial')
    {
      return $this->renderPartial('search/'. $name);
    } 
    else if ($section == 'component')
    {
      return $this->renderComponent('search', $name);
    }

    return $this->$method($request, $template);
  }
}
