<?php
/**
 * Custom functional tester class
 *
 * @package     test
 */
class cqTestFunctional extends sfTestFunctional
{

  protected $form_fixtures = array();


  /**
   * Sign in a user
   *
   * @param     string $username
   * @param     string $password
   * @param     string $signin_url
   * @param     string $signin_form_name
   *
   * @return    cqTestFunctional
   */
  public function login($username, $password, $signin_url = '/login', $signin_form_name = null)
  {
    return $this->
      info(sprintf('Signing in user using username "%s" and password "%s"', $username, $password))->
      get($signin_url)->
      setFormField($signin_form_name, 'username', $username)->
      setFormField($signin_form_name, 'password', $password)->
      click('Sign in to Your Account!', array(), array('_with_csrf' => true))->
      with('response')->isRedirected()->
      with('user')->isAuthenticated()
    ;
  }

  /**
   * Sign in a user at next
   *
   * @param     string $username
   * @param     string $password
   * @param     string $signin_url
   * @param     string $signin_form_name
   *
   * @return    cqTestFunctional
   */
  public function loginNext($username, $password, $signin_url = '/login', $signin_form_name = null)
  {
    return $this->
      info(sprintf('Signing in user using username "%s" and password "%s"', $username, $password))->
      get($signin_url)->
      with('response')->
        click('Login', array('login'=>array(
          'username'=>$username,
          'password'=>$password,
        )))->
      with('response')->begin()->
          isRedirected()->
          followRedirect()->
      end()->
      with('user')->
          isAuthenticated()
    ;
  }


  /**
   * Sign out a user
   *
   * @param     string $signout_url
   * @return    cqTestFunctional
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
   * Sign out a user
   *
   * @param     string $signout_url
   * @return    cqTestFunctional
   */
  public function logoutNext($signout_url = '/logout')
  {
    if (!$this->browser->getUser()->isAuthenticated())
    {
      self::$test->fail('The current user is not authenticated');
    }

    return $this->
      info('Signing out current authenticated user')->
      get($signout_url)->
      with('response')->begin()->
        isRedirected()->
        followRedirect()->
      end()->
      with('user')->isAuthenticated(false)
    ;
  }


  /**
   * Allows you to load the data for easy filling of forms that is stored in a file or directory, yaml format
   *
   * @param     string $filename
   *
   * @return    cqTestFunctional
   */
  public function loadFormFixtures($filename = null)
  {
    if (!is_file($filename) && '/' != substr($filename, 0, 1))
    {
      $filename = sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . $filename;
    }
    if (is_file($filename))
    {
      $this->info(sprintf('Loading form fixtures from file %s', $filename));
      $data = sfYaml::load($filename);
      $this->loadFormFixturesFromArray($data);
    }
    else if (is_dir($filename))
    {
      $files = sfFinder::type('file')->name('*.yml')->sort_by_name()->in($filename);
      foreach ($files as $filename)
      {
        $this->info(sprintf('Loading form fixtures from file %s', $filename));
        $data = sfYaml::load($filename);
        $this->loadFormFixturesFromArray($data);
      }
    }

    return $this;
  }


  /**
   * Allows you to set default from data for fast filling as an array of form_name => form data
   *
   * @param     array $data
   *
   * @return    cqTestFunctional
   */
  public function loadFormFixturesFromArray($data = array())
  {
    foreach ($data as $form_name => $form_data)
    {
      if (isset($this->form_fixtures[$form_name]))
      {
        throw new sfConfigurationException(sprintf('You have already set a form with the name "%s"', $form_name));
      }

      $this->info(sprintf('Making the form fixture "%s" available to the tester', $form_name));
      $this->form_fixtures[$form_name] = $form_data;
    }

    return $this;
  }


  /**
   * Returns the form data used for easy filling of web forms.
   * If no data has been set, the function tries to get it from the fixtures/forms dir
   *
   * @return    array $form_data
   */
  public function getFormFixtures()
  {
    return $this->form_fixtures;
  }


  /**
   * Fills a form by name
   * Can either use values supplied as an array or pre-set fixtures
   *
   * @param     string $form_name
   * @param     array  $values
   * @param     string $fixture_name
   *
   * @return    cqTestFunctional
   */
  public function fillForm($form_name, $values = null, $fixture_name = null)
  {
    $form_fixtures = $this->getFormFixtures();

    $fixture_name = $fixture_name ?: $form_name;

    if (!isset($form_fixtures[$fixture_name]) && !is_array($values))
    {
      throw new InvalidArgumentException("No data available with which to fill the {$form_name} form");
    }

    foreach (sfToolkit::arrayDeepMerge(isset($form_fixtures[$fixture_name]) ? $form_fixtures[$fixture_name] : array(), is_array($values) ? $values : array()) as $field_name => $field_value)
    {
      $this->setFormField($form_name, $field_name, $field_value);
    }

    return $this;
  }

  /**
   * Getter for a specific form fixture
   *
   * @param     string $fixture_name
   * @param     string $field_name
   * @param     mixed $default
   *
   * @return    array
   */
  public function getFormFixture($fixture_name, $field_name = null, $default = null)
  {
    $form_fixtures = $this->getFormFixtures();

    $result = $default;

    if (isset($form_fixtures[$fixture_name]))
    {
      $result = $form_fixtures[$fixture_name];

      if (isset($field_name) && isset($result[$field_name]))
      {
        $result = $result[$field_name];
      }
    }

    return $result;
  }

  /**
   * Sets a form field
   *
   * @param     string $form_name
   * @param     string $field_name
   * @param     array|string $field_value

   * @return    cqTestFunctional
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
