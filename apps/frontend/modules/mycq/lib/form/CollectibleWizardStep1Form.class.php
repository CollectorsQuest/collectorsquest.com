<?php

class CollectibleWizardStep1Form extends BaseCollectibleForm
{

  public function configure()
  {
   // $this->setupThumbnailField();

    $this->widgetSchema['files'] = new sfWidgetFormInputFile(
      array('needs_multipart' => false), array('name' => 'files[]', 'multiple' => 'multiple'));
    $this->validatorSchema['files'] = new sfValidatorString(
      array('required' => true));

    $this->useFields(array('name', 'files'));

  //  $this->widgetSchema->setNameFormat('collectible_upload[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function save($con = null)
  {
    $new = $this->getObject()->isNew();
    /* @var $recipient Collectible */
    $recipient = parent::save();
    $values = $this->getValues();

    /* @var $donors Collectible[] */
    $donors = CollectibleQuery::create()
      ->filterById($values['files'])->find();
    $is_primary = true;
    foreach ($values['files'] as $file)
    {
      $f = explode('-', $file);
      switch($f[0])
      {
        case 'upload':
          /* @var $donor Collectible */
          $donor = CollectibleQuery::create()->findOneById((int) $f[1]);
          if (!$donor || $donor->getCollectorId() != $recipient->getCollectorId()
            || $donor->countCollectionCollectibles() > 0)
          {
            continue;
          }

          /* @var $image iceModelMultimedia */
          if ($image = $donor->getPrimaryImage(Propel::CONNECTION_WRITE))
          {
            // Get rid of the old primary Multimedia
            if ($is_primary === true && ($primary = $recipient->getPrimaryImage()))
            {
              $primary->delete();
              $is_primary = false;
            }

            try
            {
              $image->setIsPrimary($is_primary);
              $image->setModelId($recipient->getId());
              $image->setSource($donor->getId());
              // $image->setCreatedAt(time());
              $image->save();
            }
            catch (PropelException $e)
            {
              if (preg_match('/multimedia_U_1/i', $e->getMessage()))
              {
                continue;
              }

              throw $e;
            }

            $recipient->setUpdatedAt(time());
            $recipient->save();

            // Archive the $donor, not needed anymore
            $donor->delete();
          }
          break;
        case 'mm':
          break;
      }





    }

    return $recipient;
  }

  protected $multimedia = null;
  public function getMultimedia()
  {
    if ($this->multimedia !== null)
    {
      return $this->multimedia;
    }
    $values = $this->getTaintedValues();

    $mm = array();
    if (isset($values['files']))
    {
      foreach ($values['files'] as $file)
      {
        $f = explode('-', $file);
        switch($f[0])
        {
          case 'upload':
            /* @var $donor Collectible */
            $donor = CollectibleQuery::create()->findOneById((int) $f[1]);
            if (!$donor || $donor->getCollectorId() != $this->getObject()->getCollectorId()
              || $donor->countCollectionCollectibles() > 0)
            {
              continue;
            }

            $mm[] = $donor;
            break;
          case 'mm':
            break;
        }
      }
    }

    return $this->multimedia = $mm;
  }



  public function validatePhoto($validator, $values)
  {
    if (!$this->getObject()->getPrimaryImage())
    {
      throw new sfValidatorError($validator, 'Photo is required');
     }

    return $values;
  }

}