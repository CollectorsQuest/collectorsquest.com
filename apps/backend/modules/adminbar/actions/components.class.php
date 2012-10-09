<?php

class adminbarComponents extends cqFrontendComponents
{
   public function executeRateMenuItem()
  {
    $this->label = sfToolkit::pregtr($this->class, array('/([A-Z]+)([A-Z][a-z])/' => '\\1 \\2',
                                                         '/([a-z\d])([A-Z])/'     => '\\1 \\2'));
  }

  public function executeRateObject()
  {
    // TO DO This component should work with any objects
    // For now it works only with Collectible


  }
}
