<?php

class About_Controller
{
	public $viewfile = 'about'; //Must be defined with same name as view and model templates

	public function main()
	{
		//Create a new view and pass it our template
		$view = new View(strtolower($this->viewfile));

		//Assign Page Variable
		$view->assign('var1', 'About page variable.');
	}
}
?>
