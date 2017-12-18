<?php

//To Debug, show variable in $_POST $_GET
if(0)	include_once "Debug.php";

//Autoloader class to load all the different directories
include_once "autoload.php";

//Load Username Password of Mysql Database
include_once "DatabaseConfig.php";

/////////////Programme Start here
//From URL, get value of page, form method and action. By searching for them in a same Route from Routers, then get page, accounts or tasks controller and controller's action of that Route.
new httprequest\processRequest();

?>
