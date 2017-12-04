<?PHP
/*Note:
Public		-Visible in all
Protected	-Visible in a class and its parent & child class
Private		-Visible only in a class
Static		-Usable out of class

This		-Refer to the Calling object
Self		-Like This but only For static
Parent		-Call variable from its Parent class
Static		-Call variable from its Child class
*/


//$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
//$apassword = password_hash("a", PASSWORD_BCRYPT, $options);
//echo $apassword;
//echo "<br>";

session_start();
if(1){
ini_set('display_errors', 'On');	//Debug
error_reporting(E_ALL | E_STRICT);
}

if(0){
setcookie("Username", "mjlee@njit.edu", time() + (86400 * 30), "/");
setcookie("Password", "1234", time() + (86400 * 30), "/");
}


function autoload($class) {
	$nm = explode('\\', $class);
	$namespc = end($nm);
	require_once "$namespc.php";
}
spl_autoload_register('autoload');	//autoload classes

define("username", "kz233");
define("password", "luy642EA2");
define("databasesoftware", "mysql");
define("hostwebsite", "sql.njit.edu");

new htmlpage();	//instantiate main page

class htmlpage{	//Weaver main page

	public function __construct(){	//Write main page
		//echo $_SESSION["Username"];
		if (isset($_SESSION["Username"])) 
		{
			echo $_SESSION["Username"] . " " . $_SESSION["Password"];
		}		
		$formstring = $this->htmlform();
		$tablestring = $this->autoshowtable();
		$requestfromserver = "";
		include "htmlpages/homepage.php";
	}


	public function htmlform() {	//Select SQL form
		$formstring = "";
		$Allcollectionfunctions = get_class_methods("collections");
		unset($Allcollectionfunctions[0]);
		foreach ($Allcollectionfunctions as $functionname)
			$formstring .="<option value=$functionname>$functionname</option>";
		return $formstring;
	//two select tools. List all collection's function except execute()	
	}
	
	public function autoshowtable() {//Run e.g. account::ShowData and return table of main page
		$tablestring = "";
		if(isset($_POST["submit"])) {
			$tablestring = $_POST["databasename"]::$_POST["collection"]();
		}
		return $tablestring;
	}

}





abstract class collections{	//Save functions of SQL Operation by ActiveRecord

	final static public function executeScode($Scode){	//Execute SQL code and return table display html string
		$conn = db\Database::connect();
		if($conn){			
			$launchcode = $conn->prepare($Scode);
			$launchcode->execute();
			$DataTitle = static::$modelNM;
			$launchcode->setFetchMode(PDO::FETCH_CLASS, $DataTitle);
			$Result = $launchcode->fetchAll();
			return $Result;
		}
	}

	final static public function CreateUser(){	//Use ActiveRecord to Generate and Run SQL code		
		$wr = "";
		
		if(strlen($_POST["password"]) < 6) {
			$wr .= "Password should at least be more than 6 number.<br>";
		}

		if(!preg_match("/[a-z]/i", $_POST["username"])) {
			$wr .= "Username at least contain 1 letter.<br>";
		}

		if(!preg_match("/[a-z]/i", $_POST["fname"]) && !preg_match("/[a-z]/i", $_POST["lname"])) {
			$wr .= "First or Last Name at least contain 1 letter.<br>";
		}

		if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$wr .= "Invalid email format.<br>"; 
		}
		
		if($wr != "") {
			echo $wr;
			return 0;
		}

		$record = new static::$modelNM();	//instantiate new object
		$record->username = $_POST["username"];
		$record->email = $_POST["email"];
		$record->fname = $_POST["fname"];
		$record->lname = $_POST["lname"];
		$record->phone = $_POST["phone"];
		$record->birthday = $_POST["birthday"];
		$record->gender = $_POST["gender"];

		$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
];
		$record->password = password_hash($_POST["password"], PASSWORD_BCRYPT, $options);

		$record->GoFunction("Insert");	//Run Insert() in modol class and echo success or not
		setcookie("Username", $_POST["email"], time() + (86400 * 30), "/");
		setcookie("Password", $_POST["password"], time() + (86400 * 30), "/");		
		return self::showData();	//return display html table code from ShowData
		
	}

	final static public function passwordpair(){
		$Scode = "SELECT password FROM accounts WHERE username = \"" . $_POST["Username"] . "\"";
		$Result = self::executeScode($Scode);
		$passwordhashingcode = $Result[0]->password;
		$BoolGate = password_verify($_POST["Password"], $passwordhashingcode);
		
		if ($BoolGate) {
			echo "Right Pairs";
		} else {
			echo "Wrong Pairs";
		//return $Scode;
			$_SESSION["Username"] = $_POST["Username"];
			$_SESSION["Password"] = $_POST["Password"];
			setcookie("Username", $_POST["email"], time() + (86400 * 30), "/");
			setcookie("Password", $_POST["password"], time() + (86400 * 30), "/");
		}

	}

	final static public function ShowData($id = ""){	//makeup select * from database 
		$id = ($id !== "") ? "= " . $id : "";
		$Scode = 'SELECT * FROM ' . get_called_class() . " WHERE id " . $id;
		$Result = self::executeScode($Scode);
		return tb\table::tablecontect($Result, $Scode);	//return display html table code
	}

	final static public function ShowDataID_5(){	//call ShowData to select * from database where id = 5
		$Result = self::ShowData("5");
		return $Result;
	}

	final static public function SQLDelete(){	//Use ActiveRecord to Generate and Run SQL code
		$record = new static::$modelNM();	//instantiate new object
		$record->fname = "Dalven";
		$record->lname = "Kelwen";
		$record->GoFunction("Delete");	//Run Delete() in modol class and echo success or not
		return self::showData();	//return display html table code from ShowData
	}

	final static public function SQLUpdate_11(){	//Use ActiveRecord to Generate and Run SQL code
		$record = new static::$modelNM();	//instantiate new object
		$record->id = 11;
		$record->email = 'kzzz@njit.edu';
		$record->fname = "Kan";
		$record->lname = "Zhang";
		$record->phone = "44144414";
		$record->birthday = "1800-01-01";
		$record->gender = "male";
		$record->password = "31s";
		$record->GoFunction("Update");	//Run Update() in modol class and echo success or not
		return self::showData();	//return display html table code from ShowData	
	}

	final static public function SQLInsert(){	//Use ActiveRecord to Generate and Run SQL code
		$record = new static::$modelNM();	//instantiate new object
		$record->id = 7;	//Will be UNSET() in object
		$record->email = 'kel@njit.edu';
		$record->fname = "Dalven";
		$record->lname = "Kelwen";
		$record->phone = "44144414";
		$record->birthday = "1994-01-01";
		$record->gender = "male";
		$record->password = "31s";
		$record->GoFunction("Insert");	//Run Insert() in modol class and echo success or not
		return self::showData();	//return display html table code from ShowData
	}

	final static public function Session_Destroy(){
		session_destroy();
	}

}

class accounts extends collections{
	protected static $modelNM = "account";
}

class todos extends collections{

	protected static $modelNM = "todo";
}


abstract class model{
	final public function GoFunction($action){	//Call function to Compile and Run SQL code, echo operation state
		$conn = db\Database::connect();
		if($conn){	//Do remains after connect
			$content = get_object_vars($this);	//get all variable in child class
			$Scode = $this->$action($content);
			$launchcode = $conn->prepare($Scode); 
			$Result = $launchcode->execute();
			$Result = ($Result = 1) ? " Successful " : " Error ";
			echo "SQL Code : </br>" . $Scode . "<hr>" . $action . " Operation " . $Result . "<hr>";
		}		
	}

	final private function Insert($content) {	//Generate Insert Code with variable in child class
	unset($content['id']);
	$insertInto = "INSERT INTO " . get_called_class() . "s (";
	$Keystring = implode(',', array_keys($content)) . ") ";	//implode array to string
	$valuestring = implode("','", $content);
	$Scode = $insertInto . $Keystring . "VALUES ('" . $valuestring . "');";
	return $Scode;
	}

	final private function Update($content) {	//Generate Update Code with variable in child class
	$where = " WHERE id = " . $content['id'];
	unset($content['id']);
	$update = "UPDATE " . get_called_class() . "s SET ";
	foreach ($content as $key => $value)	//find variable with value to update
		$update .= ($value !== Null) ? " $key = \"$value\", " : "";
	$update = substr($update, 0, -2);
	$Scode = $update . $where;		//cut its last string of ","
	return $Scode;
	}

	final private function Delete($content) {	//Generate Delete Code with variable in child class
	$where = " WHERE";
	foreach ($content as $key => $value)	//find variable with value to designate deleting line
		$where .= ($value !== Null) ? " $key = \"$value\" AND" : "";
	$where = substr($where, 0, -4);		//cut its last string of "and"
	$Scode = "DELETE FROM " .  get_called_class() . "s" . $where . ";";
	return $Scode;
	}

	//private function Find() {}
}

class account extends model{	//Variables of table accounts 
	public $id;
	public $username;
	public $password;
	public $fname;
	public $lname;
	public $gender;
	public $birthday;
	public $phone;
	public $email;
}

class todo extends model{	//Variables of table todos
	public $id;
	public $owneremail;
	public $ownerid;
	public $createddate;
	public $duedate;
	public $message;
	public $isdone;
}
