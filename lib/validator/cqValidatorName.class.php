<?php

class cqValidatorName extends sfValidatorRegex
{
  const REGEX_URL_FORMAT = '~^
      (
          [\d\-\s]*                     # numbers and dashes/spaces
        |
          (CIMG|DSC_|DSCF|DSCN|DSC
          |DUW|JD|MGP|S700
          |PICT|vlcsnap|KIF|IMG).*     # have prefix
        |
          (IMAG|IMAGE)[\d\-\s]*                # have prefix and only dashes/spaces
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

    $this->setMessage('invalid', 'Wrong format.');
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
