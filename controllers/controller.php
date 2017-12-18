<?php namespace controllers;

//Main controller
//this is the controller class that you use to connect models with views and business logic
abstract class controller {

	//get Model of MVC 
	protected $model;

	//Model Name
	protected $modelNM;

	//htmlpage template name
	protected $template;

	//Htmlpage data
	protected $data;

	public function __construct(\models\model $model) {
		$this->model = $model;
		$this->modelNM = substr(get_class($this), 7);
	}

	//Set specific array type to controller for all object in model
	protected function getobjectForController() {
		$ValueArray = $this->model->getAllobject();
		$ValueArray = array_fill_keys($ValueArray, '');
		return $ValueArray;
	}

	//this gets the HTML template for the application and accepts the model.  The model array can be used in the template
	public function display() {
		if(isset($this->template)) {
			\httprequest\request::includepage($this->template, $this->data);
		}
	}

	//set  $_POST variable to model of sql table
	protected function setPOSTVariableToModel($variable){
		if(isset($_POST[$variable]))
			$this->model->setVariable($variable, $_POST[$variable]);
	}

	//set all  $_POST variable of model to model of sql table
	protected function setAllPOSTvariableToModel(){
		$Allobject = $this->model->getAllobject();
		foreach($Allobject as $key => $value) {
			$this->setPOSTVariableToModel($value);
		}
	}

	//Delete id in sql table
	protected function fastdelete($id) {
		$this->model->setVariable("deleteID", $id);
		$this->model->go();
	}

}
