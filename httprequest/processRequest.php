<?php	namespace httprequest;

//From URL, get value of page, form method and action. By searching for them in a same Route from Routers, then get page, accounts or tasks controller and controller's action of that Route.
class processRequest
{
	private $request_method;

	private $page;

	private $action;

	private $routes;

	private $StartRoute;

	private $controller_name;

	//this determines the method to call for the controller
	private $controller_method;

	
	public function __construct() {
		//this function calculate the response to a get or post request into $request_method
		$this->setRequestMethod();

		//this function get value of page from url into $page
		$this->setPage();

		//this function get value of action from url into $action
		$this->setAction();

		//this function get all predefined routes into $routes
		$this->setroutes();

		//this function get a route which has this page and action from all predefined Routes into $routes
		$this->setStartRoute();

		//this function check whether there is a kind of this route. If no, exit
		$this->checkroute();

		//this function set controller name and controller's action
		$this->setController_NameAndMethod();

		//this function use MVC pattern to executive action and generate web pages
		$this->PrepareUsingMVCto_CreateResponse();
	}



	private function setRequestMethod() {
		$this->request_method = request::getRequestMethod();
	}

	private function setPage() {
		$this->page = request::getPage();
	}

	private function setAction() {
		$this->action = request::getAction();
	}

	private function setroutes() {
		$this->routes = routes::getRoutes();
	}

	private function setStartRoute() {
		$ROUTs = $this->routes;
		$PAGs = $this->page;
		$REQST = $this->request_method;
		$ACTN = $this->action;
		//find a required route in predefined Routes
		foreach ($ROUTs as $route) {
			if ($route->page == $PAGs && $route->http_method == $REQST && $route->action == $ACTN)
				$this->StartRoute = $route;
		}	
	}

	private function checkroute() {
		if (is_null($this->StartRoute)) {
			//If there is not such route, include a page, and show page not found and exit.
			request::includepage('notfound');
			exit;
		}
	}

	private function setController_NameAndMethod() {
		//Get controller name and method from an route
		$this->controller_name = $this->StartRoute->controller;
		$this->controller_method = $this->StartRoute->method;		
	}

	private function PrepareUsingMVCto_CreateResponse() {
		//instantiate an controller's child class whose name is equal to table name
		$modelname  = "\\models\\" . $this->controller_name;
		$controllername  = "\\controllers\\" . $this->controller_name;
		$viewname = "\\views\\view";
		$controllermethod = $this->controller_method;

		//instantiate Model
		$model = new $modelname();

		//instantiate controller
		$controller = new $controllername($model);

		//instantiate view
		$view = new $viewname($model, $controller);

		//execute an acion from controller
		$controller->$controllermethod();

		//display results and page
		$view->output();
	}
}
