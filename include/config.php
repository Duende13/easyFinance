<?php
define('HOSTNAME', 'localhost');
define('DATABASE', 'easyfinance');
define('USERNAME', 'root');
define('PASSWORD', 'root');

define('NEED_LOGIN', true);

define('EASY_FINANCE_VERSION', '0.1');
define('JQUERY_VERSION', '1.4.2');
define('JQUERY_UI_VERSION', '1.8rc3');

define('COUNTRY_CODE', 'es_ES');
define('TIME_ZONE', 'Europe/Brussels');

define('ASSUJETTI_A_LA_TVA', true);
define('DEFAULT_TVA', '21,00');
define('FACTURES_ENTRANTES', false);
define('CONTRATS', false);
define('STATISTIQUES', false);

error_reporting(E_ALL);

$db = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE) or trigger_error(mysqli_error($db),E_USER_ERROR);

mysqli_query($db,"SET NAMES 'UTF8' ");

?>
