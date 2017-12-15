<?php namespace models;

abstract class model
{
	public $selectID;
	public $selectUser;
	public $deleteID;
	protected $className;
	private $Allobject;
	private $Scode;
	private $conn;
	private $launchcode;
	protected $Result;
	
	public function getAllobject() {
		return array_keys($this->Allobject);
	}

	protected function setAddResultRecord($record) {
		$this->Result["Record"] .= $record;
	}

	protected function setResultRecord($record) {
		$this->Result["Record"] = $record;
	}

	protected function setResultIsOK($isOK) {
		$this->Result["isOK"] = $isOK;
	}

	protected function checkusername() {
		if(!preg_match("/[a-z]/i", $this->username)) {
			$this->setAddResultRecord("Username at least contain 1 letter.<br>");
			$this->validated = 0;
			return FALSE;
		}
		if(!$this->checkStrelenshorterthan($this->username, 20)) {
			$this->setAddResultRecord("Username should be alphabetic and less than 20 letters.<br>");
			$this->validated = 0;
			return FALSE;
		}
		return TRUE;
	}

	protected function checkStrelenshorterthan($variable, $digit) {
		if(strlen($variable) <= $digit) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function checkIsnumber($variable) {
		if(Is_Numeric($variable)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateID($variable) {
		$digit = 3;
		if(!$this->checkIsnumber($this->$variable) or !$this->checkStrelenshorterthan($this->$variable, $digit)) {
			$this->setAddResultRecord("Error ID is founded.<br>");
			$this->validated = 0;
			return FALSE;
		}
		return TRUE;
	}



	public function Go() {	//Call function to Compile and Run SQL code, echo operation state
		$this->conn = \httprequest\Database::connect();
		if($this->conn == NULL){	//Do remains after connect
			$this->setResultIsOK(FALSE);
			return $this->Result;
		}

		$this->setScodeAndExe();
		return $this->Result;
	}

	private function setScodeAndExe() {
		//Execute Select or Delete SQL Command without check input validation
		$SQLtype = "selectID";
		if($this->check_isset($SQLtype)) {
			if($this->validateID($SQLtype)) {
				$this->selectAllWhen("id", $SQLtype);
				return NULL;
			}
		}

		$SQLtype = "selectUser";
		if($this->check_isset($SQLtype)) {
			if($this->className == "todos") {
				$this->setResultRecord("Error: TODOs have no USERNAME table.<br>");
				$this->setResultIsOK(FALSE);
				return NULL;
			}
			if($this->checkusername()) {
				$this->selectAllWhen("username", $SQLtype);
				return NULL;
			}
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



	private function check_isset($var) {
		if(!is_null($this->$var)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function PrepareBindExe($parameters) {
		$this->launchcode = $this->conn->prepare($this->Scode);
		foreach ($parameters as $key => $value) {
			$this->launchcode->bindParam(":$value", $this->$value);
		}

		$this->setResultIsOK($this->launchcode->execute());
	}

	protected function selectAllWhen($where, $Parameter) {
		$Parameter = (array) $Parameter;
		$this->Scode = "SELECT * FROM " . $this->className . " WHERE " . $where . " = :" . $Parameter[0];
		$this->PrepareBindExe($Parameter);
		$this->setFetchData();
	}

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

	protected function setAllObject() {
		$Allobject = get_object_vars($this);
		unset($Allobject["validated"]);
		unset($Allobject["className"]);
		unset($Allobject["Allobject"]);
		unset($Allobject["selectID"]);
		unset($Allobject["selectUser"]);
		unset($Allobject["deleteID"]);
		unset($Allobject["Scode"]);
		unset($Allobject["conn"]);
		unset($Allobject["launchcode"]);
		unset($Allobject["Result"]);
		$this->Allobject = $Allobject;
	}

	private function getkeysinAllobject() {
		unset($this->Allobject['id']);
		$this->Allobject = array_keys($this->Allobject);
	}

	private function Insert() {	//Generate Insert Code with variable in child class
		$str = $this->getStringOfkeys();
		$this->Scode = "INSERT INTO " . $this->className . " (";
		$this->Scode .= $str["keys"] . ") ";	//implode array to string
		$this->Scode .= "VALUES (" . $str[":keys"] . ");";
		$this->PrepareBindExe($this->Allobject);
		$this->id = $this->conn->lastInsertId();
	}

	private function getStringOfkeys() {
		$str["keys"] = $this->implodearraywithcomma(', ');
		$str[":keys"] = ":" . $this->implodearraywithcomma(', :');
		return $str; 
	}

	private function implodearraywithcomma($seperator) {
		return implode($seperator, $this->Allobject);
	}

	private function Update() {	//Generate Update Code with variable in child class
		$parameters = $this->Allobject;
		$parameters[] = "id";
		$this->Scode = "UPDATE " . $this->className . " SET ";
		$this->Scode .= $this->getUpdateScode();
		$this->Scode .= " WHERE id = :id";
		$this->PrepareBindExe($parameters);
	}

	private function getUpdateScode() {
		foreach ($this->Allobject as $key => $val) {
			$this->Allobject[$key] .= " = :" . $val;		
		}
		return $this->implodearraywithcomma(', ');
	}

	private function Delete() {	//Generate Delete Code with variable in child class
		$parameters = array("deleteID");
		$this->Scode = "DELETE FROM " .  $this->className . " WHERE id = :deleteID";
		$this->PrepareBindExe($parameters);
	}

	protected function sethashpassword() {}

	protected function CheckDate($Date) {
		if (\DateTime::createFromFormat('Y-m-d G:i:s', $Date) == FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
