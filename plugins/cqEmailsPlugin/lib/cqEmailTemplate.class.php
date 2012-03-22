<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cqEmailTemplate
 *
 * @package     ???
 * @subpackage  ???
 * @author      Ivan Plamenov Tanev aka Crafty_Shadow @ WEBWORLD.BG <vankata.t@gmail.com>
 */
class cqEmailTemplate
{
  protected
    $name,
    $path,
    $options;

  public static function guessPathFromName($name)
  {
    $parts = preg_split('#(/|:)#', $name, -1, PREG_SPLIT_NO_EMPTY);
    $parts = array_map(array('sfInflector', 'underscore'), $parts);

    return implode('/', $parts) . '.html.twig';
  }

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

  public function render($params = array())
  {
    $params = array_merge(
      cqEmailsConfig::getOptionForName($this->name, 'params', array()),
      isset($options['params']) ? $options['params'] : array(),
      $params);

    $missing_required = array_diff(
      cqEmailsConfig::getOptionForName($this->name, 'required_params', array()),
      array_keys($params));

    if (!empty($missing_required))
    {
      throw new InvalidArgumentException(sprintf(
        'cqEmailsPlugin: The template %s has missing mandatory parameters [%s]',
        $name,
        implode(', ', $missing_required)
      ));
    }

    return cqEmailsConfig::getTwigEnvironment()->render($this->path, $params);
  }

}
