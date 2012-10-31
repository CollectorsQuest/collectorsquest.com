<?php

/**
 * An edit/create form for the backend Organization module
 */
class BackendOrganizationForm extends OrganizationForm
{
  public function configure()
  {
    parent::configure();

    $this->setupFounderIdField();
    $this->setupUrlField();
    $this->setupDescriptionField();
    $this->setupLogoField();
    $this->setupProfileImageField();

    $this->mergePostValidator(new BackendOrganizationValidatorSchema());
  }

  protected function setupFounderIdField()
  {
    $this->widgetSchema['founder_id'] = new BackendWidgetFormModelTypeAhead(array(
        'field' => CollectorPeer::DISPLAY_NAME,
        'submit_on_enter' => false, // enter on typeahead does not submit the form
    ));

    $this->validatorSchema['founder_id'] = new cqValidatorCollectorByName();

  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (!$this->isNew())
    {
      $this->setDefault(
        'founder_id',
        $this->getObject()->getFounder()->getDisplayName()
      );
    }
  }

  protected function setupUrlField()
  {
    $this->validatorSchema['url']->setOption('trim', true);
  }

  protected function setupDescriptionField()
  {
    $this->widgetSchema['description'] = new sfWidgetFormTextareaTinyMCE();
  }

  protected function setupLogoField()
  {
    $logo = $this->getObject()->getMultimediaByRole(OrganizationPeer::MULTIMEDIA_ROLE_LOGO);
    $this->widgetSchema['logo'] = new sfWidgetFormInputFileEditable(array(
        'file_src' => $logo
                      ? $logo->getRelativePath('thumbnail')
                      : '',
        'is_image' => true,
        'with_delete' => false,

    ));
    $this->validatorSchema['logo'] = new cqValidatorFile(array(
        'mime_types' => 'cq_supported_images',
        'required' => false,
    ));
  }

  protected function setupProfileImageField()
  {
    $profile_image = $this->getObject()->getMultimediaByRole(OrganizationPeer::MULTIMEDIA_ROLE_PROFILE);
    $this->widgetSchema['profile_image'] = new sfWidgetFormInputFileEditable(array(
        'file_src' => $profile_image
                      ? $profile_image->getRelativePath('thumbnail')
                      : '',
        'is_image' => true,
        'with_delete' => false,

    ));
    $this->validatorSchema['profile_image'] = new cqValidatorFile(array(
        'mime_types' => 'cq_supported_images',
        'required' => false,
    ));
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    // handle logo as custom multimedia
    if ($logo = $this->getValue('logo'))
    {
      // if a logo was already set, delete it
      $old_logo = $this->getObject()->getMultimediaByRole(OrganizationPeer::MULTIMEDIA_ROLE_LOGO);
      if ($old_logo)
      {
        $old_logo->delete();
      }

      // add the new logo
      $m = $this->getObject()->addMultimedia($logo, array(
          'role' => OrganizationPeer::MULTIMEDIA_ROLE_LOGO,
      ));
      // and create the logo thumbnail
      $m->makeThumb(150, 150);
    }

    // handle profile image as custom multimedia
    if ($profile_image = $this->getValue('profile_image'))
    {
      // if a profile image was already set, delete it
      $old_profile_image = $this->getObject()->getMultimediaByRole(OrganizationPeer::MULTIMEDIA_ROLE_PROFILE);
      if ($old_profile_image)
      {
        $old_profile_image->delete();
      }

      // add the new profile image
      $m = $this->getObject()->addMultimedia($profile_image, array(
          'role' => OrganizationPeer::MULTIMEDIA_ROLE_PROFILE,
      ));
      // and create the profile thumbnail
      $m->makeThumb(150, 150);
    }
  }

  protected function unsetFields()
  {
    parent::unsetFields();

    unset($this['slug']);
    unset($this['updated_at']);
    unset($this['created_at']);
    unset($this['organization_membership_list']);
  }

}
