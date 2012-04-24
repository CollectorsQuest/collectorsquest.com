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
 * Filename: cqWidgetFormCreditCard.class.php
 *
 * Widget represents credit card field with javascript validation of all possible cc types
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @link http://paweldecowski.github.com/jQuery-CreditCardValidator/
 * @since 4/23/12
 * Id: $Id$
 */
class cqWidgetFormCreditCard extends sfWidgetFormInputText
{

  /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {

  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return parent::render($name, $value, $attributes, $errors) . sprintf(<<<JAVASCRIPT
<script type="text/javascript">
$(document).ready(function() {
  console.log('test');
});
</script>
JAVASCRIPT
    , $this->generateId($name));
  }

  public function getJavaScripts()
  {
    return array('/assets/js/jquery/creditCardValidator.js');
  }

}
