<?php	namespace views;

//View of MVC to display data and pages
class view
{
	private $model;

	private $controller;

	//Get instantiated Model and controller object
	public function __construct(\models\model $model, \controllers\controller $controller) {
		$this->model = $model;
		$this->controller = $controller;
	}

	//Display pages
	public function output() {
		$this->controller->display();
	}
}
