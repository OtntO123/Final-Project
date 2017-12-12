<?php
/*
				  create($http_method, $action, $page, $controller, $method)	
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
	$this->routes[] = routes::create('POST','delete','tasks','tasksController','delete');
*/
//this is the controller for the index page.

//You are going to need to create / use a accounts controller that deals with login and registration you should not submit the post for the to the index controller
//POST index.php?page=accounts?action=create for adding a user
//POST index.php?page=accounts?action=login for logging a  user in and get the userID out of the session
//POST index.php?page=accounts?action=logout  this would destroy the session and return the user to the homepage
//GET  index.php?page=accounts?action=show  this would be to show the user profile and you get the userID out of session


class homepageController extends http\controller
{

    public static function show()
    {
//this is the show method that is called to show the sites name in a template any array passed in will be accepted by the template function as a model
//You could get fancy with the homepage and check for the userID in the session and hide/show the login / registration links when no session
//If there is a session then you should show the user profile link
//the template is an HTML page with PHP inserted in it.  just put an if/else statement to check for the session and show correct links


//template data contains what will show up in the $data variable in the homepage template
//the name of the template 'homepage' becomes 'homepage.php' in the pages directory
	//$sessionID = http\request::getSession("UserID");

	if(isset($_GET["submit"]) == "UnLog") {
		$_SESSION["UserID"] = NULL;
		header('Location: index.php');
	}

	$templateData[] = http\request::getCookie("Username");
	$templateData["!issetSessionUserID"] = http\request::ExcalmationUserIDSession();
	$templateData["issetSessionUserID"] = http\request::UserIDSession();
	$templateData["UserID"] = http\request::getSessionUserID();
        self::getTemplate('homepage', $templateData);
    }

    public static function create()
    {


//I just put a $_POST here but this is where you would put the code to add a record
        print_r($_POST);
    }



}
