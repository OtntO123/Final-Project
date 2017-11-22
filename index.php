<?PHP
/*Note:
Public		-Visible in all
Protected	-Visible in a class and its parent & child class
Private		-Visible only in a class
Static		-Usable out of class

This		-Refer to the Calling object
Self		-Like This but only For static
Parent		-Call sth from its Parent class
Static		-Call sth from its Child class
*/
if(1){
ini_set('display_errors', 'On');	//Debug
error_reporting(E_ALL | E_STRICT);
}

class Manage {	
    public static function autoload($class) {
        //you can put any file name or directory here
        include $class . '.php';
    }
}
spl_autoload_register(array('Manage', 'autoload'));	//autoload classes

define("username", "kz233");
define("password", "luy642EA2");
define("databasesoftware", "mysql");
define("hostwebsite", "sql.njit.edu");



static $html = "";
$html .= "<html lang='en'>";
	$html .= "<head>";
	$html .= "<meta charset='utf-8'>
		<title>Sql Active Record</title>
		<meta name='description' content='Sql Active Record'>
		<meta name='author' content='Kan'>
		<link rel='stylesheet' href='css/styles.css?v=1.0'>";
	$html .= "</head>";

	$html .= "<body>";
	$html .= form::upld();
	if(isset($_POST["submit"])) {
		$html .= $_POST["databasename"]::$_POST["collection"]();
	}

	$html .= "</body>";
$html .= "</html>";
echo $html;


abstract class collections{
//	protected static $a;

	static public function executeScode($Scode){
		$conn = Database::connect();
		if($conn){			
			$launchcode = $conn->prepare($Scode);
			$launchcode->execute();
			$DataTitle = static::$modelNM;
			$launchcode->setFetchMode(PDO::FETCH_CLASS, $DataTitle);
			$Result = $launchcode->fetchAll();
			return $Result;
		}
	}

	static public function ShowData($id = ""){
		$id = ($id !== "") ? "= " . $id : "";
		$Scode = 'SELECT * FROM ' . get_called_class() . " WHERE id " . $id;
		$Result = self::executeScode($Scode);
		return table::tablecontect($Result, $Scode);
	}

	static public function ShowDataID_5(){
		$Result = self::ShowData("5");
		return $Result;
	}

	static public function SQLDelete(){
		$record = new static::$modelNM();
		$record->fname = "Dalven";
		$record->lname = "Kelwen";
		$record->GoFunction("Delete");
		return self::showData();
	}

	static public function SQLUpdate(){
		$record = new static::$modelNM();
		$record->id = 7;
		$record->email = 'kel@njit.edu';
		$record->fname = "Dalven";
		$record->lname = "Kelwen";
		$record->phone = "44144414";
		$record->birthday = "1994-01-01";
		$record->gender = "male";
		$record->password = "31s";
		$record->GoFunction("Update");
		return self::showData();		
	}

	static public function SQLInsert(){
		$record = new static::$modelNM();
		$record->id = 7;	//Will be UNSET()
		$record->email = 'kel@njit.edu';
		$record->fname = "Dalven";
		$record->lname = "Kelwen";
		$record->phone = "44144414";
		$record->birthday = "1994-01-01";
		$record->gender = "male";
		$record->password = "31s";
		$record->GoFunction("Insert");
		return self::showData();
	}
}

class accounts extends collections{
	protected static $modelNM = "account";
}

class todos extends collections{

	protected static $modelNM = "todo";
}


abstract class model{
	public function GoFunction($action){
		$conn = Database::connect();
		if($conn){
			$content = get_object_vars($this);
			$Scode = $this->$action($content);
			$launchcode = $conn->prepare($Scode); 
			$Result = $launchcode->execute();
			$Result = ($Result = 1) ? " Successful " : " Error ";
			echo "</br>SQL Code : " . $Scode . "</br>" . $action . " Operation " . $Result;
		}		
	}

	private function Insert($content) {
	unset($content['id']);
	$insertInto = "INSERT INTO " . get_called_class() . "s (";
	$Keystring = implode(',', array_keys($content)) . ") ";
	$valuestring = implode("','", $content);
	$Scode = $insertInto . $Keystring . "VALUES ('" . $valuestring . "');";
	return $Scode;
	}

	private function Update($content) {
	$where = " WHERE id = " . $content['id'];
	unset($content['id']);
	$update = "UPDATE " . get_called_class() . "s SET ";
	foreach ($content as $key => $value)
		$update .= ($value !== Null) ? " $key = \"$value\", " : "";
	$update = substr($update, 0, -2);
	$Scode = $update . $where;
	return $Scode;
	}

	private function Delete($content) {
	$where = " WHERE";
	foreach ($content as $key => $value)
		$where .= ($value !== Null) ? " $key = \"$value\" AND" : "";
	$where = substr($where, 0, -4);
	$Scode = "DELETE FROM " .  get_called_class() . "s" . $where . ";";
	return $Scode;
	}

	//private function Find() {}
}

class account extends model{
	public $id;
	public $email;
	public $fname;
	public $lname;
	public $phone;
	public $birthday;
	public $gender;
	public $password;
}

class todo extends model{
	public $id;
	public $owneremail;
	public $ownerid;
	public $createddate;
	public $duedate;
	public $message;
	public $isdone;
}




class form{
	static public function upld() {	//Select SQL form
	$msg = '<form action="index.php" method="post" enctype="multipart/form-data">';
	$msg .= '<h1 style="color:LightGreen;">Select SQL Code: </h1>';

	$msg .= '<select name="databasename">';
	$msg .= '<option value="accounts">accounts</option>';
	$msg .= '<option value="todos">todos</option>';
	$msg .= '</select>';

	$msg .= '<select name="collection">';
	$Allfunctions = get_class_methods("collections");
	unset($Allfunctions[0]);
	foreach ($Allfunctions as $functionname)
	$msg .="<option value=$functionname>$functionname</option>";
	$msg .= '</select>';

	$msg .= '<input type="submit" value="Run" name="submit">';
	$msg .= '</form>';
	return $msg;
	}
}







class Database {

	protected static $conn;

	static public function connect() {
		if(!self::$conn){
			new Database();
		}
		return self::$conn;
	}


	public function __construct() {	//Set PDO object and Test connectivity
		try {
			self::$conn = new PDO(databasesoftware . ':host=' . hostwebsite .';dbname=' . username, username, password);
			self::$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			echo 'Connection Successful To Database.';
		}
		catch (PDOException $e) {
			echo "Connection Error To Database: " . $e->getMessage();
		}
	}
	


}

class table {
	static public function tablecontect($tabl, $tablename) {	//display result within table function
		$str = "<div><table style='width:100%'><caption>" . $tablename . "</caption>";
		foreach($tabl as $i => $k) {	
			$str .= "<tr>";
			if ($k == $tabl[0]) {	//first line of type name
				foreach($k as $m => $n) {
					if (!is_numeric($m)) {
						$str .= "<th>$m</th>";
					}
				}
				$str .= "</tr><tr>";
			}
			foreach($k as $j => $o) {	//split data
				if (!is_numeric($j)) {
					$str .= "<td>$o</td>";
				}
			}
				$str .= "</tr>";
		}
		$str .= "</table></div>";
		return $str;	//answer question and display result
	}
}
