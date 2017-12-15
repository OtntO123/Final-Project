<?php	namespace httprequest;

//by using the use here you don't have to put http on each class in that namespace

class processRequest
{

	//this is the main function of the program to calculate the response to a get or post request
	public static function createResponse()
	{
		$requested_route = self::getRequestedRoute();
		self::prepareToCreate($requested_route);
	}	
	
	//this function matches the request to the correct controller
	private static function getRequestedRoute()
	{

	//this is a helper function that needs to be improved because it does too much.  I will look for this in grading
		$request_method = request::getRequestMethod();
		$page = request::getPage();
		$action = request::getAction();
echo $request_method . " " . $page . " " . $action . "<br>";
		return self::sendroutefortest($request_method, $page, $action);
        //these are helpful for figuring out the action and method being requested
        //echo 'Action: ' . $action . '</br>';
        //echo 'Page: ' . $page . '</br>';
        //echo 'Request Method: ' . $request_method . '</br>'
	}

	private static function sendroutefortest($request_method, $page, $action) {
        //this gets the routes objects, you need to add routes to add pages and follow the template of the route specified
		$routes = \routes::getRoutes();
		return self::testingroute($routes, $request_method, $page, $action);
	}

	private static function testingroute($routes, $request_method, $page, $action){
		$foundRoute = NULL;
        //this figures out which route matches the page being requested in the URL and returns it so that the controller and method can be called
		$foundRoute = self::checkroute($routes, $request_method, $page, $action);
		return self::findpage($foundRoute);
	}

	private static function checkroute($routes, $request_method, $page, $action) {
		foreach ($routes as $route) {
			if ($route->page == $page && $route->http_method == $request_method && $route->action == $action) {
			return $route;
			}
		}	
	}

	private static function findpage($foundRoute) {
		if (is_null($foundRoute)) {
			controller::getTemplate('notfound');
			exit;
		} else {
			return $foundRoute;
		}
	}

	private static function prepareToCreate($requested_route) {
		//this print r shows the requested route
		//print_r($requested_route);
		//This is an important function to look at, it determines which controller to use
		$controller_name = $requested_route->controller;
		//this determines the method to call for the controller
		$controller_method = $requested_route->method;
		self::UsingMVCtoStartCreateResponse($controller_name, $controller_method);
	}
	
	private static function UsingMVCtoStartCreateResponse($controller_name, $controller_method) {
		//these echo helps figure out the controller name and method
		// echo $controller_name . '</br>';
		// echo $controller_method . '</br>';

		$model = new models\$tablename();

		$controller = new controllers\$tablename($model);

		$view = new views\view($controller, $model);

		$controller->$controller_method();

		$view->output();
	}
}
