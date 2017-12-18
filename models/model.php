<?php namespace models;

abstract class model
{
	private $selectID;
	private $selectownerID;
	private $selectUser;
	private $deleteID;
	private $modelNM;
	protected $Allobject;
	private $Scode;
	private $conn;
	private $launchcode;
	protected $Result;

	//initialize model name which is table name in SQL
	//initialize AllObject
	public function __construct() {
		$this->setmodelNM();
		$this->setAllObject();
	}

	//Clean all variable in this object
	public function cleanThisObject() {
		$this->setAllObject();
		$Allobj = $this->getAllobject();
		foreach ($Allobj as $key => $val)
			$this->$val = NULL;
		$this->validated = NULL;
		$this->setmodelNM();
		$this->selectID = NULL;
		$this->selectownerID = NULL;
		$this->selectUser = NULL;
		$this->deleteID = NULL;
		$this->Scode = NULL;
		$this->conn = NULL;
		$this->launchcode = NULL;
		$this->Result = NULL;
	}

	//Used by outside to set value of variables in this object
	public function setVariable($variable, $value) {
		$all_changable_object = $this->getAllobject();
		$all_changable_object[] = "selectID";
		$all_changable_object[] = "selectUser";
		$all_changable_object[] = "deleteID";
		$all_changable_object[] = "selectownerID";
		if(in_array($variable, $all_changable_object))
			$this->$variable = $value;
	}

	//Set model name which is table name in SQL
	protected function setmodelNM() {
		$this->modelNM = substr(get_class($this), 7);
	}
	
	//Get from outside model name which is table name in SQL
	public function getAllobject() {
		return array_keys($this->Allobject);
	}
	
	//set Add record in array Result
	protected function setAddResultRecord($record) {
		$this->Result["Record"] .= $record;
	}

	//Set record in array Result
	protected function setResultRecord($record) {
		$this->Result["Record"] = $record;
	}

	//Set isOK in array Result
	protected function setResultIsOK($isOK) {
		$this->Result["isOK"] = $isOK;
	}

	//Check username
	protected function checkusername($variable) {
		if(!preg_match("/[a-z]/i", $this->$variable)) {
			$this->setAddResultRecord("Username at least contain 1 letter.<br>");
			$this->validated = 0;
			return FALSE;
		}
		if(!$this->checkStrelenshorterthan($this->$variable, 20)) {
			$this->setAddResultRecord("Username should be alphabetic and less than 20 letters.<br>");
			$this->validated = 0;
			return FALSE;
		}
		return TRUE;
	}

	//check the length of an string which not shorter than $digit
	protected function checkStrelenshorterthan($variable, $digit) {
		if(strlen($variable) <= $digit) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//check whether is a number
	protected function checkIsnumber($variable) {
		if(Is_Numeric($variable)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//Check ID
	private function validateID($variable) {
		$digit = 3;
		if(!$this->checkIsnumber($this->$variable) or !$this->checkStrelenshorterthan($this->$variable, $digit)) {
			$this->setAddResultRecord("Error ID is founded.<br>");
			$this->validated = 0;
			return FALSE;
		}
		return TRUE;
	}

//////////////////////////////////////////////////////////////
	//Main function to detect and run sql command
	public function Go() {	//Call function to Compile and Run SQL code, echo operation state
		$this->setResultRecord("");
		$this->conn = \httprequest\Database::connect();
		if($this->conn == NULL){	//Do remains after connect
			$this->setResultIsOK(FALSE);
			return $this->Result;
		}

		$this->setScodeAndExe();
		return $this->Result;
	}

	//Used by Main function GO, to detect and run sql command. Put result into $Result["Record"] and $Result["isOK"]
	private function setScodeAndExe() {
		//Execute Select or Delete SQL Command without check input validation
		$SQLtype = "selectID";
		if($this->check_isset($SQLtype)) {
			if($this->validateID($SQLtype)) {
				$this->selectAllWhen("id", $SQLtype);
				return NULL;
			}
				return NULL;
		}

		$SQLtype = "selectownerID";
		if($this->check_isset($SQLtype)) {
			if($this->validateID($SQLtype)) {
				$this->selectAllWhen("ownerid", $SQLtype);
				return NULL;
			}
				return NULL;
		}

		$SQLtype = "selectUser";
		if($this->check_isset($SQLtype)) {
			if($this->checkusername($SQLtype)) {
				$this->selectAllWhen("username", $SQLtype);
				return NULL;
			}
				return NULL;
		}

		$SQLtype = "deleteID";
		if($this->check_isset($SQLtype)) {
			if($this->validateID($SQLtype)) {
				$this->Delete();
				return NULL;
			}
		}

		/////////////////////////////////////////////////////////////////////
		//Start Data validation before Execute Insert or Update SQL Command  
		$this->validate();
		if(!($this->validated)){
			$this->setResultIsOK(FALSE);
			return NULL;
		}

		$this->sethashpassword();
		$this->getkeysinAllobject();
		if($this->check_isset("id")) {			
			$this->Update();
			return NULL;
		} else {
			$this->Insert();
			$this->setResultRecord($this->id);
			return NULL;
		}
		$this->setResultIsOK(FALSE);
		$this->setResultRecord("Execute Nothing.");
	}
///////////////////////////////////////////////////////////////////////////////////////

	//Check whether a variable is set
	private function check_isset($var) {
		if(!is_null($this->$var)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//Prepare SQL command and bind value of variable into it
	private function PrepareBindExe($parameters) {
		$this->launchcode = $this->conn->prepare($this->Scode);
		foreach ($parameters as $key => $value) {
			$this->launchcode->bindParam(":$value", $this->$value);
		}

		$this->setResultIsOK($this->launchcode->execute());
	}

	//Generate SQL command 'Select * When ' and run and return result into $Result["Record"] and $Result["isOK"]
	protected function selectAllWhen($where, $Parameter) {
		$Parameter = (array) $Parameter;
		$this->Scode = "SELECT * FROM " . $this->modelNM . " WHERE " . $where . " = :" . $Parameter[0];
		$this->PrepareBindExe($Parameter);
		$this->setFetchData();
	}

	//Used by SelectAllWhen to fetch data into $Result["Record"] and $Result["isOK"]
	private function setFetchData() {
		if ($this->launchcode->rowCount() > 0) {
			$this->launchcode->setFetchMode(\PDO::FETCH_ASSOC);
			$ResultArray = $this->launchcode->fetchAll();
			$this->setResultRecord($ResultArray);
			$this->setResultIsOK(TRUE);
		} else {
			$this->setResultIsOK(FALSE);
		}
	}

	//get all variablie of SQL table into an array of values
	private function getkeysinAllobject() {
		unset($this->Allobject['id']);
		$this->Allobject = array_keys($this->Allobject);
	}

	//Generate SQL command 'Insert into () Value ()' and run and return result into $Result["Record"] and $Result["isOK"]
	private function Insert() {
		$str = $this->getStringOfkeys();
		$this->Scode = "INSERT INTO " . $this->modelNM . " (";
		$this->Scode .= $str["keys"] . ") ";
		$this->Scode .= "VALUES (" . $str[":keys"] . ");";
		$this->PrepareBindExe($this->Allobject);
		$this->id = $this->conn->lastInsertId();
	}

	//implode array into string
	private function getStringOfkeys() {
		$str["keys"] = $this->implodearraywithcomma(', ');
		$str[":keys"] = ":" . $this->implodearraywithcomma(', :');
		return $str; 
	}

	//implode array into string with comma
	private function implodearraywithcomma($seperator) {
		return implode($seperator, $this->Allobject);
	}

	//Generate SQL command 'UPDATE table SET WHERE id = :id ' and run and return result into $Result["Record"] and $Result["isOK"]
	private function Update() {
		$parameters = $this->Allobject;
		$parameters[] = "id";
		$this->Scode = "UPDATE " . $this->modelNM . " SET ";
		$this->Scode .= $this->getUpdateScode();
		$this->Scode .= " WHERE id = :id";
		$this->PrepareBindExe($parameters);
	}

	//Used by Update to generate SET in SQL UPDATE command
	private function getUpdateScode() {
		foreach ($this->Allobject as $key => $val) {
			$this->Allobject[$key] .= " = :" . $val;		
		}
		return $this->implodearraywithcomma(', ');
	}

	//Generate SQL command 'DELETE FROM table WHERE id = :deleteID' and run and return result into $Result["Record"] and $Result["isOK"]
	private function Delete() {
		$parameters = array("deleteID");
		$this->Scode = "DELETE FROM " .  $this->modelNM . " WHERE id = :deleteID";
		$this->PrepareBindExe($parameters);
	}

	//Set hash password
	protected function sethashpassword() {}

	//Used by validation to check date formate
	protected function CheckDate($Date) {
		if (\DateTime::createFromFormat('Y-m-d', $Date) == FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
