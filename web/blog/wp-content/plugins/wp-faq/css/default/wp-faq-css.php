<?php

if (!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }

$root = __FILE__;
for ($i = 0; $i < 4; $i++) $root = dirname($root);
require_once($root . DS . 'wp-config.php');
include_once(ABSPATH . 'wp-admin' . DS . 'admin-functions.php');

?>

<?php header("Content-Type: text/css; charset=UTF-8"); ?>
<?php if (get_option('wpfaqcustomcss') == "Y") : ?>
	<?php echo stripslashes(get_option('wpfaqcustomcsscode')); ?>
<?php endif; ?>