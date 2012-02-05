<?php
/**
 * File: Configuration
 * 	Stores your AWS account information. Add your account information, then rename this file to 'config.inc.php'.
 *
 * Version:
 * 	2008.12.02
 * 
 * Copyright:
 * 	2006-2009 LifeNexus Digital, Inc., and contributors.
 * 
 * License:
 * 	Simplified BSD License - http://opensource.org/licenses/bsd-license.php
 * 
 * See Also:
 * 	Tarzan - http://tarzan-aws.com
 */


/**
 * Constant: AWS_KEY
 * 	Amazon Web Services Key. <http://aws-portal.amazon.com/gp/aws/developer/account/index.html?ie=UTF8&action=access-key>
 */
define('AWS_KEY', '0Q4JCH5ZFJ3WBVP7N9R2');

/**
 * Constant: AWS_SECRET_KEY
 * 	Amazon Web Services Secret Key. <http://aws-portal.amazon.com/gp/aws/developer/account/index.html?ie=UTF8&action=access-key>
 */
define('AWS_SECRET_KEY', 'ltkYUWp3Mn43c6EywZngBrx++4jwyLolhz5bcUH3');

/**
 * Constant: AWS_ACCOUNT_ID
 * 	Amazon Account ID without dashes. Used for identification with Amazon EC2. <http://aws-portal.amazon.com/gp/aws/developer/account/index.html?ie=UTF8&action=edit-aws-profile>
 */
define('AWS_ACCOUNT_ID', '464612211554');

/**
 * Constant: AWS_ASSOC_ID
 * 	Amazon Associates ID. Used for crediting referrals via Amazon AAWS. <http://affiliate-program.amazon.com/gp/associates/join/>
 */
define('AWS_ASSOC_ID', 'collectorsquest-20');

/**
 * Constant: AWS_CANONICAL_ID
 * 	Your CanonicalUser ID. Used for setting access control settings in AmazonS3. Must be fetched from the server. Call print_r($s3->get_canonical_user_id()); to view.
 */
define('AWS_CANONICAL_ID', '267e4ccb5333696ea4e51311d09df1053955ffef922cc6699cd12ce5c1374de4');

/**
 * Constant: AWS_CANONICAL_NAME
 * 	Your CanonicalUser DisplayName. Used for setting access control settings in AmazonS3. Must be fetched from the server. Call print_r($s3->get_canonical_user_id()); to view.
 */
define('AWS_CANONICAL_NAME', '');

?>
