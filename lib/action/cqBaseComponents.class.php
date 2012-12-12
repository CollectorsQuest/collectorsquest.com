<?php

/**
 * This is for correct IDE code suggests
 *
 * @method cqBaseUser getUser()
 * @method cqWebRequest getRequest()
 * @method cqWebResponse getResponse()
 */
class cqBaseComponents extends sfComponents
{

  protected function setComponentVar($name, $value, $action, $module = null)
  {
    $namespace = sprintf(
      'cq/components/%s/%s',
      $module ?: $this->getModuleName(),
      $action ?: $this->getActionName()
    );

    $this->getUser()->setFlash($name, $value, $persist = false, $namespace);
  }

  public function getVar($name, $default = null)
  {
    if (!$this->getVarHolder()->has($name))
    {
      $namespace = sprintf(
        'cq/components/%s/%s', $this->getModuleName(), $this->getActionName()
      );

      return $this->$name = $this->getUser()->getFlashAndDelete($name, $default, $namespace);
    }

    return parent::getVar($name);
  }

}
