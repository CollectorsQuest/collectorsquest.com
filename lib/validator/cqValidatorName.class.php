<?php

class cqValidatorName extends sfValidatorRegex
{
  const REGEX_URL_FORMAT = '~^
      (
          [\d\-\s]*                     # numbers and dashes/spaces
        |
          (CIMG|DSC_|DSCF|DSCN|DSC
          |DUW|IMG|JD|MGP|S700
          |PICT|vlcsnap|KIF|IMAG).*     # have prefix
        |
          .{0,3}                        # have length 3
      )
    $~ix';

  /**
   * Available options:
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorRegex
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', new sfCallable(array($this, 'generateRegex')));
    $this->setOption('must_match', false);

    $this->setMessage('invalid', 'Wrong name.');
  }

  /**
   * Generates the current validator's regular expression.
   *
   * @return string
   */
  public function generateRegex()
  {
    return self::REGEX_URL_FORMAT;
  }
}
