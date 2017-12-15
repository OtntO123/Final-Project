<?php
//session_start();
$time_start = microtime(true);
function Benchmark($time_start){
	echo '<br><b>Total Execution Time:</b> ' . (microtime(true) - $time_start) * 1000000 . ' ms<br>';
}

if(1) {
	echo "METHOD ";
	print_r($_SERVER['REQUEST_METHOD']);
	echo "<BR> POST ";
	print_r($_POST);
	echo "<BR> GET ";
	print_r($_GET);
	echo "<BR> SESSION ";
	print_r($_SESSION);
//	echo "<BR> COOKIES ";
//	print_r($_COOKIE);
	echo "<BR>";
	//turn on debugging messages
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
}

//print_r($_SESSION);
//Autoloader class to load all the different directories

include_once "autoload.php";

//put your database credentials here
include_once "DatabaseConfig.php";
//$obj = new models\todos();
//$obj -> deleteID =3;
//$obj -> selectID = 3;
//$obj -> selectUser = "11111a";
/*
//$obj -> id = 6;
$obj -> username = "1131d1a";
$obj -> password = "ii11515151";
$obj -> fname = "iiiiii";
$obj -> lname = "iiiiii";
$obj -> gender = "Male";
$obj -> birthday = "2017-02-10 91:99:90";
$obj -> phone = 4124411444;
$obj -> email = "eq@eqwed.c";
*/
$obj = new models\todos();
$obj -> selectID = 5;
$Result = $obj -> Go();
var_dump( $Result["isOK"]);
print_r($Result["Record"]);



Benchmark($time_start);
/*
	public $id;
	public $username;
	public $password;
	public $fname;
	public $lname;
	public $gender;
	public $birthday;
	public $phone;
	public $email;


*/
//this starts the program as a static.  Start tracing the program from here following the classes and methods being called
//$response = http\processRequest::createResponse();
//read the notes below


//To make the final project, you need to add routes, controllers, and the html templates in the "pages" folder.
//One of the big challenges will be to get the tasks to show up for the user that created them.

//Basic Steps:

//1. Add a route for each request you need to process (GET / POST).  See the template provided in routes.php.
//2. Add a controller and/or add methods to the controller, which match the actions that you want the program to perform.
//3. Add HTML view templates to the page folder.  Look at how the template is called and you can pass data to the template

//Suggested order of work:

//1.  Get findall working and displaying a table for the todos class's todos_list method;
//2.  Get findOne working to find one to-do and make that work for the todos controller show method.  Remember you have to pass the ID.
//3.  Get the Insert working
//4.  get the delete working
//5.  update working
//6.  once you have this all working for todos start working on accounts
//7.  once accounts works for login / logout / show user profile / edit profile
//8.  go back and add a useriD field to your todos table and update program accordingly i.e. the model
//9.  add a method to your to-do model that retrieves by userID instead of ID.  USe the findONe as an example
//10.  on your To-do-List method of the todos controller update it so that it takes the USER id out of the session and uses that to retrieve the todos


//to get credit for using this as MVC you must rewrite what I give and improve it.
//  A good way to improve it is namespaces and making the scope of properties and functions to be correctly private, public, or protected
//there are notes throughout the code on improvements.  YOu can also correctly apply abstract and final
//you can also look for lines that can be removed by just doing it in the return
//it shouldn't be too hard to namespace and autoload
//namespaces are really needed because your collection and controller classes for todos and accounts are called the same thing.


//IMPORTANT:  YOUR ACTIVE RECORD collection CLASSES  (task/account) WILL CONFLICT WITH THE CONTROLER CLASS HERE.
//You can use namespaces or rename the controller classes, which will change your url parameter for page

//routes are used to match the http request with the controller and method name that are called for that request / route.
//In this program the page parameter matches the controller and the action parameter matches the method on the conroller.
//Examples:

//  index.php?page=todos&action=show specifies the todos collection class and the show method/function


//  GET requests to show the  form for new todos should go to index.php?page=todos&action=create and show a form for a new todoItem
//  POST requests to create todos should go to index.php?page=todos&action=store and would be inserted into the database
//  GET requests to show the update form todos should go to index.php?page=todos&action=edit&id=(todoItem ID) and would show an update form
//  POST requests to update todos should go to index.php?page=todos&action=store&id=(todoItem ID) and would be update in the database
//  For delete you should put a delete button within a new form below the edit form that has a method post and action of index.php?page=todos&action=delete&id=(todoItem ID)

//You can use the accounts controller for handling user login, user registration, showing the profile, editing the profile, and logout
//Login should hash the password and compare it to the saved password hash
//registration should hash the password and insert the hashed password with the user record.  You should not store clear passwords in a database

//for the todos list page you need to make a table that has links to each item.
//  Like:  index.php?page=todos&action=show&id=1  this would show the to-do item with a link to the edit form.
//  you could put your delete on the to_do item view or the edit form, the above still applies.




?>
