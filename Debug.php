<?php

//Display All Variable in $_POST, $_GET and $_SERVER['REQUEST_METHOD']
echo "METHOD ";
print_r($_SERVER['REQUEST_METHOD']);
echo "<BR> POST ";
print_r($_POST);
echo "<BR> GET ";
print_r($_GET);
//echo "<BR> COOKIES ";
//print_r($_COOKIE);
echo "<BR>";

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);

