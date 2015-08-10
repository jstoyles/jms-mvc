<?php

// Handle URL Routes
class Routes {
	private $routes = array();

	public $url = '';
	public $params = array();
	public $pathfile = '';
	public $error = NULL;

	function init(){


		//==================== Allowed URL Routes =====================
		array_push($this->routes, '/about/');
		//==================== Allowed URL Routes =====================


		$urlArr = explode('?',URI);
		$this->url = $urlArr[0];

		if(!in_array($this->url, $this->routes) && $this->url != '' && $this->url != '/' && !isset($_GET['DynamicURL'])){ $this->error = 'URL Not Found!'; }
		else {
			$paths = explode('/',$urlArr[0]);
			$paramStr = isset($url[1])?explode('&', $urlArr[1]):'';

			$paths = explode('/',$urlArr[0]);
			$paramStr = isset($urlArr[1])?explode('&', $urlArr[1]):'';

			if(isset($urlArr[1])){
				foreach($paramStr as $param){
					$kv = explode('=',$param);
					$this->params[$kv[0]] = $kv[1];
				}
			}

			foreach($paths as $path){
				if(trim($path) != ''){
					$this->pathfile = $path;
				}
			}
		}
	}
}

?>
