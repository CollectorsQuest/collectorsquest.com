<?php

/**
 * @see cqMarkdownException
 */
require_once dirname(__FILE__) .'/cqMarkdownException.class.php';

/**
 * @see Markdown_Parser
 */
require_once dirname(__FILE__).'/../vendor/Markdown/Markdown.php';

class cqMarkdown extends Markdown_Parser
{
  public function convert($text)
  {
    // parse, convert and return
    return $this->transform($text);
  }

  public function convertFile($file)
  {
    // check whether file is readable
    if (!is_readable($file))
    {
      throw new cqMarkdownException("Unable to read file '$file'");
    }

    $text = file_get_contents($file);

    return $this->convert($text);
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
      $parser = new $class;
    }

    return $parser;
  }
}
