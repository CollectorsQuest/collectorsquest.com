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
 * Filename: CollectorGuideSignupForm.class.php
 *
 * Signup form for collectors essential guide page
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 6/22/12
 * Id: $Id$
 */
class CollectorGuideSignupForm extends CollectorSignupStep1Form
{
  public function setup()
  {
    parent::setup();

    unset($this->widgetSchema['display_name']);
  }

  /**
   * Overwrite getCSRFToken so that it will be compatible with CollectorSignupStep1Form
   *
   * @param     string $secret The secret string to use (null to use the current secret)
   * @return    string A token string
   *
   * @see       sfForm::getCSRFToken
   */
  public function getCSRFToken($secret = null)
  {
    if (null === $secret)
    {
      $secret = $this->localCSRFSecret ? $this->localCSRFSecret : self::$CSRFSecret;
    }

    return md5($secret.session_id().'CollectorSignupStep1Form');
  }
}
