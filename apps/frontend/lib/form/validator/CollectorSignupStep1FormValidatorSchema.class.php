<?php

/**
 * Validator schema for the base signup form
 */
class CollectorSignupStep1FormValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
  }

  protected function doClean($values)
  {
    // if we are using a form that only has a username input,
    // use that as the display name
    if (!isset($values['display_name']))
    {
      $values['display_name'] = $values['username'];
    }

    // Display names should be unique
    while (
      CollectorQuery::create()
        ->filterByDisplayName($values['display_name'])
        ->count()
    )
    {
      // If we have a duplicate, randomize the display name a little;
      // For display names with space in it "Kiril Angov",
      // it will do "Kiril Angov 54", otherwise "KirilAngov54"
      $values['display_name'] .= stripos(trim($values['display_name']), ' ')
        ? ' '. mt_rand(10, 99)
        : mt_rand(10, 99);
    }

    return $values;
  }

}
