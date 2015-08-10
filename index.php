<?php
error_reporting(E_ALL^E_NOTICE);
//error_reporting(0);
session_start();

$debug = false;

require_once('set_environment.php');
require_once(INCLUDES_DIR . '/config.php');
require_once(INCLUDES_DIR . '/functions.php');  //You can use functions.php for custom functions. (Not required unless you want to use custom functions)

require_once(CLASSES_DIR . '/routes.class.php');
require_once(CLASSES_DIR . '/core.class.php');
require_once(CLASSES_DIR . '/custom.class.php'); //You can use custom.class.php for custom classes. (Not required unless you want to use custom classes)

class DB extends Database_Base {}

$PageTitle = '';
$MetaDescription = '';
$MetaKeywords = '';

$pathfile = '';
$routes = new Routes();
$routes->init();
$use404 = false;
if(isset($routes->error)){
	if($debug){ echo $routes->error; }
	else {
		$use404 = true;
		$PageTitle = DEFAULT_SITE_NAME . ' - Error 404 - Page Not Found';
	}
}

if(!$use404){
	$url = $routes->url;
	$pathfile = $routes->pathfile;
	$params = $routes->params;

	if($url==''||$url=='/'){ $pathfile = 'main'; }
	if(isset($_GET['DynamicURL'])){ $pathfile = trim($_GET['DynamicURL']); }

	//Initiate View
	class View extends View_Base {}

	//Initiate Model
	class Model extends Model_Base {}
	$model = new Model($url, $pathfile, $params);
	$model->init();
	if(isset($model->error) && $debug) { echo $model->error; }

	//Initiate Controller
	class Controller extends Controller_Base {}
	$controller = new Controller($url, $pathfile, $params);
	$controller->init();
	if(isset($controller->error)){
		$use404 = true;
		$PageTitle = DEFAULT_SITE_NAME . ' - Error 404 - Page Not Found';
	}
}

define('PAGE_FILE', $pathfile);



//============================== CUSTOM APPLICATION CODE ==============================
//Place custom application code here
//============================== CUSTOM APPLICATION CODE ==============================

//============================== CUSTOM META DATA/TITLES BY PAGE NAME ==============================
switch(PAGE_FILE){
	case 'main':
		$PageTitle = DEFAULT_SITE_NAME . ' - Main Page';
		$MetaDescription = 'Main Description';
		$MetaKeywords = 'Main Keywords';
		break;
	case 'about':
		$PageTitle = DEFAULT_SITE_NAME . ' - About';
		$MetaDescription = 'About Description';
		$MetaKeywords = 'About Keywords';
		break;
	default:
		$PageTitle = DEFAULT_SITE_NAME;
		$MetaDescription = '';
		$MetaKeywords = '';
		break;
}
//============================== CUSTOM META DATA/TITLES PER PAGE ==============================

define('PAGE_TITLE',$PageTitle);
define('META_DESCRIPTION',$MetaDescription);
define('META_KEYWORDS',$MetaKeywords);
?>

<?php
require_once(INCLUDES_DIR . '/header.php');

if($use404){ include('error_404.php'); }
else{
	if(isset($model->response)){
		require(MODELS_DIR.'/'.$model->response.'.php');
	}

	if(!isset($controller->error)){
		require(CONTROLLERS_DIR.'/'.$controller->response.'.php');
		$class = str_replace(' ','', (ucfirst(str_replace('-',' ', $controller->response)))) . '_Controller';
		$classInit = new $class;
		if(isset($viewData)){ $classInit->main($viewData); }
		else { $classInit->main(); }
	}
	else if($debug) { echo $controller->error; }
}

require_once(INCLUDES_DIR . '/footer.php');
?>
