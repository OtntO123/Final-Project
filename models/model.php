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

	public function __construct() {
		$this->setmodelNM();
		$this->setAllObject();
	}

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

	public function setVariable($variable, $value) {
		$all_changable_object = $this->getAllobject();
		$all_changable_object[] = "selectID";
		$all_changable_object[] = "selectUser";
		$all_changable_object[] = "deleteID";
		$all_changable_object[] = "selectownerID";
		if(in_array($variable, $all_changable_object))
			$this->$variable = $value;
	}

	protected function setmodelNM() {
		$this->modelNM = substr(get_class($this), 7);
	}
	
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
		$this->setResultRecord("");
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
		$this->Scode = "SELECT * FROM " . $this->modelNM . " WHERE " . $where . " = :" . $Parameter[0];
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

	private function getkeysinAllobject() {
		unset($this->Allobject['id']);
		$this->Allobject = array_keys($this->Allobject);
	}

	private function Insert() {	//Generate Insert Code with variable in child class
		$str = $this->getStringOfkeys();
		$this->Scode = "INSERT INTO " . $this->modelNM . " (";
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
		$this->Scode = "UPDATE " . $this->modelNM . " SET ";
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
		$this->Scode = "DELETE FROM " .  $this->modelNM . " WHERE id = :deleteID";
		$this->PrepareBindExe($parameters);
	}

	protected function sethashpassword() {}

	protected function CheckDate($Date) {
		if (\DateTime::createFromFormat('Y-m-d', $Date) == FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
