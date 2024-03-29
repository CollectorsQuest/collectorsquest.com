<?php
/**
 * Copyright 2012 Collectors' Quest, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/**
 * Filename: cqValidatorNumber.class.php
 *
 * Put some description here
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 5/28/12
 * Id: $Id$
 */

class cqValidatorNumber extends sfValidatorNumber
{

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('multiple', false);
  }

  protected function doClean($value)
  {
    if ($this->getOption('multiple'))
    {
      $returnValue = array();
      foreach (explode(',', $value) as $item)
      {
        $returnValue[] = parent::doClean($item);
      }

      return implode(',', $returnValue);
    }
    else
    {
      return parent::doClean($value);
    }
  }

}
