<?php

/**
 * @author Yanko Simeonoff
 * @since $Date: 2011-12-05 18:42:06 +0200 (Mon, 05 Dec 2011) $
 * @version $Id: ManageCollectiblesForm.class.php 2472 2011-12-05 16:42:06Z kangov $
 */
class ManageCollectiblesForm extends sfForm
{
  public function configure()
  {
    $i = 0;

    /* @var $collectible Collectible */
    foreach ($this->getDefault('collectibles') as $item)
    {
      $this->embedForm($i++, new CollectibleEditForm($item));
    }

    $this->getWidgetSchema()->setNameFormat('collectibles[%s]');
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    foreach ($taintedValues as $key => $value)
    {
      if (!empty($value['for_sale']['is_ready']))
      {
        $this->validatorSchema[$key]['for_sale']['price']->setOption('required', true);
        $this->validatorSchema[$key]['description']->setOption('required', true);
        $this->validatorSchema[$key]['tags']->setOption('required', true);
      }
    }

    return parent::bind($taintedValues, $taintedFiles);
  }

}
