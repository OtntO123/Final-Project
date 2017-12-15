<?php

namespace http;
//this is the controller class that you use to connect models with views and business logic
class controller
{
	protected $model;

	public function __construct($model){
		$this->model = $model;
	}

//this gets the HTML template for the application and accepts the model.  The model array can be used in the template
	protected function getTemplate($template, $data = NULL)
	{
		$template = 'pages/' . $template . '.php';
//in your template you should use $data to access your array
		include $template;
	}
}
