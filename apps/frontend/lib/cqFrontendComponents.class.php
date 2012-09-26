<?php

/**
 * This is for correct IDE code suggests
 *
 * @method cqFrontendUser getUser()
 * @method cqWebRequest getRequest()
 * @method cqWebResponse getResponse()
 */
class cqFrontendComponents extends sfComponents
{
  /**
   * @param bool $strict
   * @return Collector
   */
  protected function getCollector($strict = false)
  {
    return $this->getUser()->getCollector($strict);
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
