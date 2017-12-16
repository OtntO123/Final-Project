<?php	namespace views;

class view
{
	private $model;

	private $controller;

	public function __construct(\models\model $model, \controllers\controller $controller) {
		$this->model = $model;
		$this->controller = $controller;
	}

	public function output() {
		$this->controller->display();
	}
}
