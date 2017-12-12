<?php

class routes
{
	protected $routes;

	public function __construct() {
	//create($http_method, $action, $page, $controller, $method)		
	$this->routes[] = routes::create('GET','show','homepage','homepageController','show');
	$this->routes[] = routes::create('POST','create','homepage','homepageController','create');
	$this->routes[] = routes::create('GET','all','accounts','accountsController','all');
	$this->routes[] = routes::create('GET','show','accounts','accountsController','show');
	$this->routes[] = routes::create('GET','edit','accounts','accountsController','edit');
	$this->routes[] = routes::create('GET','register','accounts','accountsController','register');
	$this->routes[] = routes::create('POST','login','accounts','accountsController','login');
	$this->routes[] = routes::create('POST','save','accounts','accountsController','save');
	$this->routes[] = routes::create('POST','delete','accounts','accountsController','delete');
	$this->routes[] = routes::create('POST','register','accounts','accountsController','store');
	$this->routes[] = routes::create('GET','show','tasks','tasksController','show');
	$this->routes[] = routes::create('GET','all','tasks','tasksController','all');
	$this->routes[] = routes::create('GET','edit','tasks','tasksController','edit');
	$this->routes[] = routes::create('GET','create','tasks','tasksController','create');
	$this->routes[] = routes::create('POST','edit','tasks','tasksController','save');
	$this->routes[] = routes::create('POST','create','tasks','tasksController','store');
	$this->routes[] = routes::create('POST','delete','tasks','tasksController','delete');
	}
	
	public static function getRoutes()
	{
		$routs = new routes();
		return $routs->routes;
/*

        //bellow adds routes to your program, routes match the URL and request method with the controller and method.
        //You need to follow this pattern to add new URLS
        //You should improve this function by making functions to create routes in a factory. I will look for this when grading

        //I also use object for the route because it has data and it's easier to access.
        $route = new route();
        //this is the index.php route for GET
        //Specify the request method
        $route->http_method = 'GET';
        //specify the page.  index.php?page=index.  (controller name / method called
        $route->page = 'homepage';
        //specify the action that is in the URL to trigger this route index.php?page=index&action=show
        $route->action = 'show';
        //specify the name of the controller class that will contain the functions that deal with the requests
        $route->controller = 'homepageController';
        //specify the name of the method that is called, the method should be the same as the action
        $route->method = 'show';
        //this adds the route to the routes array.
        $routes[] = $route;

        //this is the index.php route for POST

        //This is an examole of the post for index
        $route = new route();
        $route->http_method = 'POST';
        $route->action = 'create';
        $route->page = 'homepage';
        $route->controller = 'homepageController';
        $route->method = 'create';
        $routes[] = $route;

        //This is an examole of the post for tasks to show a task
        //GET METHOD index.php?page=tasks&action=show
        $route = new route();
        $route->http_method = 'GET';
        $route->action = 'show';
        $route->page = 'tasks';
        $route->controller = 'tasksController';
        $route->method = 'show';
        $routes[] = $route;

        //This is an examole of the post for tasks to list tasks.  See the action matches the method name.
        //you need to add routes for create, edit, and delete
        //GET METHOD index.php?page=tasks&action=all

        $route = new route();
        $route->http_method = 'GET';
        $route->action = 'all';
        $route->page = 'tasks';
        $route->controller = 'tasksController';
        $route->method = 'all';
        $routes[] = $route;
        //GET METHOD index.php?page=accounts&action=all
//https://web.njit.edu/~kwilliam/mvc/index.php?page=accounts&action=all

        $route = new route();
        $route->http_method = 'GET';
        $route->action = 'all';
        $route->page = 'accounts';
        $route->controller = 'accountsController';
        $route->method = 'all';
        $routes[] = $route;
        //GET METHOD index.php?page=accounts&action=show

        $route = new route();
        $route->http_method = 'GET';
        $route->action = 'show';
        $route->page = 'accounts';
        $route->controller = 'accountsController';
        $route->method = 'show';
        $routes[] = $route;

        //This goes in the login form action method
        //GET METHOD index.php?page=accounts&action=login


        $route = new route();
        $route->http_method = 'POST';
        $route->action = 'login';
        $route->page = 'accounts';
        $route->controller = 'accountsController';
        $route->method = 'login';
        $routes[] = $route;

        //YOU WILL NEED TO ADD MORE ROUTES

        $route = new route();
        $route->http_method = 'POST';
        $route->action = 'delete';
        $route->page = 'tasks';
        $route->controller = 'tasksController';
        $route->method = 'delete';
        $routes[] = $route;


        $route = new route();
        $route->http_method = 'POST';
        $route->action = 'delete';
        $route->page = 'accounts';
        $route->controller = 'accountsController';
        $route->method = 'delete';
        $routes[] = $route;

        $route = new route();
        $route->http_method = 'GET';
        $route->action = 'edit';
        $route->page = 'accounts';
        $route->controller = 'accountsController';
        $route->method = 'edit';
        $routes[] = $route;

        $route = new route();
        $route->http_method = 'POST';
        $route->action = 'save';
        $route->page = 'accounts';
        $route->controller = 'accountsController';
        $route->method = 'save';
        $routes[] = $route;
        //this is the route for the reg form
        $route = new route();
        $route->http_method = 'GET';
        $route->action = 'register';
        $route->page = 'accounts';
        $route->controller = 'accountsController';
        $route->method = 'register';
        $routes[] = $route;
        //this handles the reg post to create the user
        $route = new route();
        $route->http_method = 'POST';
        $route->action = 'register';
        $route->page = 'accounts';
        $route->controller = 'accountsController';
        $route->method = 'store';
        $routes[] = $route;
*/
	}

	public static function create($http_method, $action, $page, $controller, $method) {
		return new route($http_method, $action, $page, $controller, $method);
	}
}

//this is the route prototype object  you would make a factory to return this

class route
{
	public $http_method;
	public $page;
	public $action;
	public $method;
	public $controller;

	public function __construct($Rhttp_method, $Raction, $Rpage, $Rcontroller, $Rmethod) {
		$this->http_method = $Rhttp_method;
		$this->action = $Raction;
		$this->page = $Rpage;
		$this->controller = $Rcontroller;
		$this->method = $Rmethod;
	}
}
?>
