<?php

/**
 * Convert Markdown text to HTML
 *
 * Right now, this is simply a bridge to cqMarkdown class static functions.
 * I have some interesting ideas for the future.
 *
 * @param   string  $text
 * @return  string
 * @see     cqMarkdown::doConvert()
 */
function convert_markdown_text($text)
{
  return cqMarkdown::doConvert($text);
}

/**
 * Convert Markdown file content to HTML
 *
 * Right now, this is simply a bridge to cqMarkdown class static functions.
 * I have some interesting ideas for the future.
 *
 * @param   string  $file
 * @return  string
 * @see     cqMarkdown::doConvertFile()
 */
function convert_markdown_file($file)
{
  return cqMarkdown::doConvertFile($file);
}

/**
 * Converts Markdown text to HTML and prints returned data
 *
 * @param   string  $text
 * @return  void
 * @see     convert_markdown_text()
 * @see     cqMarkdown::doConvert()
 */
function include_markdown_text($text)
{
  echo convert_markdown_text($text);
}

/**
 * Converts Markdown file content to HTML and prints returned data
 *
 * @param   string  $file
 * @return  string
 * @see     convert_markdown_file()
 * @see     cqMarkdown::doConvertFile()
 */
function include_markdown_file($file)
{
  echo convert_markdown_file($file);
}
