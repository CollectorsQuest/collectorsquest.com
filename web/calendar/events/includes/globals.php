<?php
/*
	Helios Calendar - Professional Event Management System
	Developed By: Chris Carlevato <chris@refreshwebdev.com>

	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]

	License Information is found in docs/license.html


	NOTE: 	If Your Public Helios Calendar is not stored the events directory changes will need to be made
			to some include statements in the Helios admin.
*/
	/*	Database Server Globals
		DATABASE_HOST - Database Host Name. Typically localhost
		DATABASE_NAME - The name of your Helios database
		DATABASE_USER - Username for your Helios database
		DATABASE_PASS - Password for your Helios database user
		HC_TblPrefix - Prefix of your Helios datatables. */

  if (!defined('SF_ENV') && $_SERVER['HTTP_HOST'] == 'www.cqstaging.com') define('SF_ENV', 'staging');
  else if (!defined('SF_ENV') && ($_SERVER['HTTP_HOST'] == 'collectorsquest.dev' || $_SERVER['HTTP_HOST'] == 'www.collectorsquest.dev')) define('SF_ENV', 'dev');
  else if (!defined('SF_ENV')) define('SF_ENV', 'prod');

  if (SF_ENV == 'dev')
  {
    define('DATABASE_NAME', 'collectorsquest_calendar');
	   define('DATABASE_USER', 'root');
	   define('DATABASE_PASS', '');
	   define('DATABASE_HOST', '127.0.0.1');

    $rootURL = "http://www.collectorsquest.dev/calendar";
  }
	 else if (SF_ENV == 'staging')
	 {
    define('DATABASE_HOST', '127.0.0.1');
	   define('DATABASE_NAME', 'collectorsquest_calendar');
	   define('DATABASE_USER', 'collectorsquest');
	   define('DATABASE_PASS', 'KjtGUm9L7bSjH5Zr');

    $rootURL = "http://www.cqstaging.com/calendar";
	 }
	 else
	 {
	 	 define('DATABASE_HOST', '127.0.0.1');
	 	 define("DATABASE_NAME", "collectorsquest_calendar");
	 	 define("DATABASE_USER", "collectorsquest");
	 	 define("DATABASE_PASS", "KjtGUm9L7bSjH5Zr");

    $rootURL = "http://www.collectorsquest.com/calendar";
	 }

	 define("HC_TblPrefix", "hc_");


	//	Helios Location Globals
	define("CalRoot", "$rootURL/events");
	define("CalAdminRoot", "$rootURL/admin");
	define("MobileRoot", "$rootURL/events/wml");


	/*	Helios Name and	Contact Globals
		CalName - Used to identify the website. e.g.: "all you need to access (CalName) event information" (Helios RSS Page)
		AdminName - Used to identify the Helios Administration Console
		CalAdmin & CalAdminEmail - Name & Email of the primary Helios Administrator. Used for emails sent by Helios.	*/

	define("CalName", "Collectors&#8217; Quest Event Calendar");
	define("AdminName", "Helios Calendar Admin");
	define("CalAdmin", "Calendar Administrator");
	define("CalAdminEmail", "calendar@collectorsquest.com");
