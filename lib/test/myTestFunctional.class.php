<?php
/**
* Custom functional tester class
*
* @package      test
*/
class myTestFunctional extends sfTestFunctional
{

  /**
  * Sign in a user
  *
  * @param      string $username
  * @param      string $password
  *
  * @return     myTestFunctional
  */
  public function login($username, $password, $signin_url = '/login', $signin_form_name = 'signin')
  {
    return $this->
      info(sprintf('Signing in user using username "%s" and password "%s"', $username, $password))->
      get($signin_url)->
      setFormField($signin_form_name, 'username', $username)->
      setFormField($signin_form_name, 'password', $password)->
      click('*[type=submit]')->
      with('response')->isRedirected()->
      with('user')->isAuthenticated()
    ;
  }

  /**
  * Sign out a user
  *
  * @return     myTestFunctional
  */
  public function logout($signout_url = '/logout')
  {
    if (!$this->browser->getUser()->isAuthenticated())
    {
      self::$test->fail('The current user is not authenticated');
    }
    return $this->
      info('Signing out current authenticated user')->
      get($signout_url)->
      with('user')->isAuthenticated(false)
    ;
  }


 /**
  * Allows you to load the data for easy filling of forms that is stored in a file or directory, yaml format
  *
  * @param      string $filename
  *
  * @return     myTestFunctional
  */
  public function loadFormData($filename = null)
  {
    if (!is_file($filename) && '/' != substr($filename, 0, 1))
    {
      $filename = sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . $filename;
    }
    if (is_file($filename))
    {
      $data = sfYaml::load($filename);
      $this->loadFormDataFromArray($data);
    }
    else if (is_dir($filename))
    {
      $files = sfFinder::type('file')->name('*.yml')->sort_by_name()->in($filename);
      foreach ($files as $filename)
      {
        $data = sfYaml::load($filename);

        $this->loadFormDataFromArray($data);
      }
    }
    else
    {
      throw new sfInitializationException(sprintf('You must give an array, a directory or a file to myTestFunctional::loadFormData() (%s given).', $filename));
    }

    return $this;
  }


 /**
  * Allows you to set default from data for fast filling as an array of form_name => form data
  *
  * @param      array $data
  *
  * @return     myTestFunctional
  */
  public function loadFormDataFromArray($data = array())
  {
    foreach ($data as $form_name => $form_data)
    {
      if (isset($this->forms_data[$form_name]))
      {
        throw new sfConfigurationException(sprintf('You have already set a form with the name "%s"', $form_name));
      }

      $this->forms_data[$form_name] = $form_data;
    }

    return $this;
  }


  /**
  * Returns the form data used for easy filling of web forms.
  * If no data has been set, the function tries to get it from the fixtures/forms dir
  *
  * @return     array $form_data
  */
  public function getFormsData()
  {
    if (empty($this->forms_data))
    {
      $this->loadFormData();
    }

    return $this->forms_data;
  }


  /**
  * Fills a form by name
  * Can either use values supplied as an array or pre-set fixtures
  *
  * @param      string $form_name
  * @param      array $values
  *
  * @return     myTestFunctional
  */
  public function fillForm($form_name, $values = null)
  {
    $forms_data = $this->getFormsData();

    if (!isset($forms_data[$form_name]) && !is_array($values))
    {
      throw new sfConfigurationException('No data with which to fill the form');
    }

    foreach (sfToolkit::arrayDeepMerge(isset($forms_data[$form_name]) ? $forms_data[$form_name] : array(), is_array($values) ? $values : array()) as $field_name => $field_value)
    {
      $this->setFormField($form_name, $field_name, $field_value);
    }

    return $this;
  }


  /**
  * Sets a form field
  *
  * @param      string $form_name
  * @param      string $field_name
  * @param      array|string $field_value
  *
  * @return     myTestFunctional
  */
  public function setFormField($form_name, $field_name, $field_value)
  {
    $field_name = false === strpos($field_name, '[') ? sprintf('[%s]', $field_name) : $field_name;

    if (is_array($field_value))
    {
      // we are dealing with some sort of embedding or a widget that needs array to represent it's value (sfWidgetFormDate for example)
      foreach ($field_value as $embedded_field_name => $embedded_field_value)
      {
        $this->setFormField($form_name, sprintf('%s[%s]', $field_name, $embedded_field_name), $embedded_field_value);
      }
    }
    else
    {
      $this->browser->setField(sprintf('%s[%s]', $form_name, $field_name), $field_value);
    }

    return $this;
  }


}
