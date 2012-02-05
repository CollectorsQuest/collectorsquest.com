<?php
//  Copyright (c) 2009 Facebook
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
//

/**
 * AJAX endpoint for XHProf function name typeahead.
 *
 * @author(s)  Kannan Muthukkaruppan
 *             Changhao Jiang
 */

// by default assume that xhprof_html & xhprof_lib directories
// are at the same level.

if (!defined('XHPROF_LIB_ROOT')) {
  define('XHPROF_LIB_ROOT', '/www/libs/plugins/iceLibsPlugin/lib/vendor/xhprof');
}

include_once XHPROF_LIB_ROOT . '/config.php';
include_once XHPROF_LIB_ROOT . '/xhprof.php';
include_once XHPROF_LIB_ROOT . '/xhprof_runs.php';

$xhprof_runs_impl = new XHProfRuns_Default();

include_once $GLOBALS['XHPROF_LIB_ROOT'].'/typeahead.php';
