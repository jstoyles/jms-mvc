<?php

class Main_Controller
{
	//This template variable will hold the 'view' portion of the MVC for this controller
	public $viewfile = 'main'; //Must be defined with same name as view and model templates

	//param array $params the GET variables posted to index.php
	public function main()
	{
		//Create a new view and pass it our template
		$view = new View(strtolower($this->viewfile));
	}
}
?>
