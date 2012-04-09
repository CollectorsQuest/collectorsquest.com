<?php

/**
 * cqEmailTemplate is a convenience wrapper for a Twig template that can
 * sniff the proper path to the template based on its name and will
 * make any default params available to the twig template.
 *
 * The template will not be handled to twig for rendering if there are any
 * missing params that have been defined as mandatory in the template's config,
 * instead an InvalidArgumentException is thrown
 */
class cqEmailTemplate
{
  protected
    $name,
    $path,
    $options;

  /**
   * Try to guess the path to a twig template based on the email template's name,
   * for example:
   *
   * Collector/sendWelcomeEmail is collector/send_welcome_email.html.twig
   *
   * @param     string $name
   * @return    string
   */
  public static function guessPathFromName($name)
  {
    $parts = preg_split('#(/|:)#', $name, -1, PREG_SPLIT_NO_EMPTY);
    $parts = array_map(array('sfInflector', 'underscore'), $parts);

    return implode('/', $parts) . '.html.twig';
  }

  /**
   * @param     string $name
   * @param     array $options
   *
   * @throws    InvalidArgumentException if the twig template file does not exist
   */
  public function __construct($name, $options = array())
  {
    $this->name = $name;
    $this->options = $options;
    if (isset($options['template_path']) && $options['template_path'])
    {
      $path = $options['template_path'];
    }
    else
    {
      $path = self::guessPathFromName($name);
    }

    $full_path = cqEmailsConfig::getTemplatesDir() . '/' . $path;
    if (!is_file($full_path))
    {
      throw new InvalidArgumentException(sprintf(
        'cqEmailsPlugin: The template %s does not exist',
        $path
      ));
    }

    $this->path = $path;
  }

  /**
   * Try to render the template. If there are mandatory params, first a check
   * will be performed. If not all mandatory params are present, an exception is thrown
   *
   * @param     array $params
   * @return    string
   *
   * @throws    InvalidArgumentException on missing mandatory params
   */
  public function render($params = array())
  {
    $params = array_merge(
      cqEmailsConfig::getOptionForName($this->name, 'params', array()),
      isset($this->options['params']) ? $this->options['params'] : array(),
      $params);

    $missing_required = array_diff(
      cqEmailsConfig::getOptionForName($this->name, 'required_params', array()),
      array_keys($params));

    if (!empty($missing_required))
    {
      throw new InvalidArgumentException(sprintf(
        'cqEmailsPlugin: The template %s has missing mandatory parameters [%s]',
        $this->name,
        implode(', ', $missing_required)
      ));
    }

    return cqEmailsConfig::getTwigEnvironment()->render($this->path, $params);
  }

}
