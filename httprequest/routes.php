<?php	namespace httprequest;

//Use Factory mode.

class routes
{
	private $routes;

	public function __construct() {
	//create($http_method, $action, $page, $controller, $method)		
	$this->routes[] = routes::create('GET','show','homepage','homepage','show');
	$this->routes[] = routes::create('POST','create','homepage','homepage','create');
	$this->routes[] = routes::create('GET','all','accounts','accounts','all');
	$this->routes[] = routes::create('GET','show','accounts','accounts','show');
	$this->routes[] = routes::create('GET','edit','accounts','accounts','edit');
	$this->routes[] = routes::create('GET','register','accounts','accounts','register');
	$this->routes[] = routes::create('POST','login','accounts','accounts','login');
	$this->routes[] = routes::create('POST','save','accounts','accounts','save');
	$this->routes[] = routes::create('POST','delete','accounts','accounts','delete');
	$this->routes[] = routes::create('POST','register','accounts','accounts','store');
	$this->routes[] = routes::create('GET','show','tasks','todos','show');
	$this->routes[] = routes::create('GET','all','tasks','todos','all');
	$this->routes[] = routes::create('GET','edit','tasks','todos','edit');
	$this->routes[] = routes::create('GET','create','tasks','todos','create');
	$this->routes[] = routes::create('POST','edit','tasks','todos','save');
	$this->routes[] = routes::create('POST','create','tasks','todos','store');
	$this->routes[] = routes::create('POST','delete','tasks','todos','delete');
	}
	
	public static function getRoutes()
	{
		$routs = new routes();
		return $routs->routes;
	}

	public static function create($http_method, $action, $page, $controller, $method) {
		return new route($http_method, $action, $page, $controller, $method);
	}
}

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
