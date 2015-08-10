<?php

date_default_timezone_set('America/New_York');

/*==================== REQUIRED SETTINGS ====================*/
define("CODE_BASE", $_SERVER['DOCUMENT_ROOT']);
define("INCLUDES_DIR", CODE_BASE . '/inc');
define("CLASSES_DIR", CODE_BASE . '/classes');
define("CONTROLLERS_DIR", CODE_BASE . '/controllers');
define("VIEWS_DIR", CODE_BASE . '/views');
define("MODELS_DIR", CODE_BASE . '/models');

define("DOMAIN", $_SERVER['SERVER_NAME']);
define("URI", $_SERVER['REQUEST_URI']);
define("PROTOCOL", "http://");
define("REMOTE_IP_ADDRESS", $_SERVER['REMOTE_ADDR']);
define("HTTP_USER_AGENT", $_SERVER['HTTP_USER_AGENT']);
define("HTTP_REFERER", isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');

define("DEFAULT_SITE_NAME", "MVC"); //Set per application
/*==================== REQUIRED SETTINGS ====================*/



//============================== CUSTOM APPLICATION SETTINGS ==============================

//============================== CUSTOM APPLICATION SETTINGS ==============================

?>
