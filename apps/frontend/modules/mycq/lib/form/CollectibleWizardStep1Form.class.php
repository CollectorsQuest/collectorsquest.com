<?php

class CollectibleWizardStep1Form extends BaseCollectibleForm
{
  protected $multimedia = null;

  public function configure()
  {
    $this->widgetSchema['files'] = new sfWidgetFormInputFile(
      array('needs_multipart' => false), array('name' => 'files[]', 'multiple' => 'multiple'));
    $this->validatorSchema['files'] = new sfValidatorString(
      array('required' => true));

    $this->useFields(array('name', 'files'));

    if (!$this->getObject()->isNew())
    {
      $this->multimedia = $this->getObject()->getMultimedia();

    }

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function save($con = null)
  {
    $new = $this->getObject()->isNew();
    /* @var $recipient Collectible */
    $recipient = parent::save();
    $values = $this->getValues();

    /* @var $multimedias iceModelMultimedia[] */
    $oldMultimedia = array();
    /* @var $multimedias iceModelMultimedia[] */
    $multimedias = $recipient->getMultimedia();

    foreach ($multimedias as $multimedia)
    {
      $oldMultimedia[$multimedia->getId()] = $multimedia;
    }

    //Refill multimedia to get it from request
    $this->multimedia = null;
    /* @var $donors Collectible[] */
    $donors = $this->getMultimedia();

    $is_primary = true;

    foreach ($donors as $donor)
    {
      if ($donor instanceof Collectible)
      {
        if ($donor->getCollectorId() != $recipient->getCollectorId()
          || $donor->countCollectionCollectibles() > 0)
        {
          continue;
        }

        /* @var $image iceModelMultimedia */
        if ($image = $donor->getPrimaryImage(Propel::CONNECTION_WRITE))
        {
          try
          {
            $image->setIsPrimary($is_primary);
            $image->setModelId($recipient->getId());
            $image->setSource($donor->getId());
            // $image->setCreatedAt(time());
            $image->save();
            if ($is_primary === true)
            {
              $is_primary = false;
            }

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
      }

      if ($donor instanceof iceModelMultimedia)
      {
        if (isset($oldMultimedia[$donor->getId()]))
        {
          $oldMultimedia[$donor->getId()]->setIsPrimary($is_primary);
          $oldMultimedia[$donor->getId()]->setNew(false);
          $oldMultimedia[$donor->getId()]->save();
          if ($is_primary === true)
          {
            $is_primary = false;
          }
          unset($oldMultimedia[$donor->getId()]);
        }
      }
    }

    return $recipient;
  }

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
            /* @var $donor iceModelMultimedia */
            $donor = iceModelMultimediaQuery::create()
              ->filterByModelId($this->getObject()->getId())
              ->filterById((int) $f[1])
              ->findOne();

            if ($donor)
            {
              $mm[] = $donor;
            }
            break;
        }
      }
    }

    return $this->multimedia = $mm;
  }

}
