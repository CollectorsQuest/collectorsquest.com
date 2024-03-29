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
 * Filename: config.php
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 4/15/12
 * Id: $Id$
 */

if (in_array('cqMagnifyAuth', sfConfig::get('sf_enabled_modules', array())))
{
  $this->dispatcher->connect('routing.load_configuration', array('cqMagnifyRouting', 'listenToRoutingLoadConfigurationEvent'));
}
