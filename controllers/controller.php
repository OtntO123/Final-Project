<?php namespace controllers;

abstract class controller {

//this is the controller class that you use to connect models with views and business logic
	protected $model;

	protected $modelNM;

	protected $template;

	protected $data;

	public function __construct(\models\model $model) {
		$this->model = $model;
		$this->modelNM = substr(get_class($this), 7);
	}

	protected function getobjectForController() {
		$ValueArray = $this->model->getAllobject();
		$ValueArray = array_fill_keys($ValueArray, '');
		return $ValueArray;
	}

//this gets the HTML template for the application and accepts the model.  The model array can be used in the template
	public function display() {
		if(isset($this->template)) {
			$data = $this->data;
			$htmlpage = 'pages/' . $this->template . '.php';
	//in your template you should use $data to access your array
			include $htmlpage;
		}
	}

	protected function setPOSTVariableToModel($variable){
		if(isset($_POST[$variable]))
			$this->model->setVariable($variable, $_POST[$variable]);
	}

	protected function setAllPOSTvariableToModel(){
		$Allobject = $this->model->getAllobject();
		foreach($Allobject as $key => $value) {
			$this->setPOSTVariableToModel($value);
		}
	}

	protected function fastdelete($id) {
		$this->model->setVariable("deleteID", $id);
		$this->model->go();
	}

}
