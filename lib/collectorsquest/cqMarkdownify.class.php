<?php

/**
 * @see cqMarkdownException
 */
require_once dirname(__FILE__) .'/cqMarkdownException.class.php';

/**
 * @see Markdownify
 */
require_once sfConfig::get('sf_lib_dir').'/vendor/Markdown/Markdownify.php';

class cqMarkdownify extends Markdownify
{
  public function convert($text)
  {
    // parse, convert and return
    return $this->parseString($text);
  }

  public function convertFile($file)
  {
    // check whether file is readable
    if (!is_readable($file))
    {
      throw new cqMarkdownException("Unable to read file '$file'");
    }

    $text = file_get_contents($file);

    return $this->parseString($text);
  }

  public static function doConvert($text)
  {
    $parser = self::getParserInstance();

    return $parser->convert($text);
  }

  public static function doConvertFile($file)
  {
    $parser = self::getParserInstance();

    return $parser->convertFile($file);
  }

  public static function getParserInstance()
  {
    static $parser;
    static $class = __CLASS__;

    // get parser instance
    if (!($parser instanceof $class)) 
    {
      $parser = new $class(MDFY_LINKS_EACH_PARAGRAPH, MDFY_BODYWIDTH, false);
    }

    return $parser;
  }
}
