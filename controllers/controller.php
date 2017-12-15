<?php namespace controllers

class controller {

//this is the controller class that you use to connect models with views and business logic
	protected $model;

	public function __construct(\models\model $model) {
		$this->model = $model;
	}


//this gets the HTML template for the application and accepts the model.  The model array can be used in the template
	protected function display($template, $data = NULL);
		$template = 'pages/' . $template . '.php';
//in your template you should use $data to access your array
		include $template;
	}




	protected function setAllvariable(){
		foreach($this->model->getAllobject() as $key => $value) {
			if(isset($_POST($value)))
				$this->model->$value = $_POST($value);
		}
	}

	protected function setvariable($variable){
		if(isset($_POST($variable)))
			$this->model->$value = $_POST($variable);
	}
}
