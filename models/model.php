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
	
	public function Go() {	//Call function to Compile and Run SQL code, echo operation state
		$this->conn = \httprequest\Database::connect();
		if($this->conn == NULL){	//Do remains after connect
			$this->Result["isOK"] = FALSE;
			return $this->Result;
		}

		$this->setScodeAndExe();
		return $this->Result;
	}

	private function setScodeAndExe() {
		//Execute Select or Delete SQL Command without check input validation
		if($this->check_isset("selectID")) {
			$this->selectAllWhen("id", "selectID");
			return NULL;
		}
		if($this->check_isset("selectUser")) {
			if($this->className == "todos") {
				return NULL;
			}
			$this->selectAllWhen("username", "selectUser");
			return NULL;
		}
		if($this->check_isset("deleteID")) {
			$this->Delete();
			return NULL;
		}
/////////////////////////////////////////////////////////////////////

		//Start Data validation before Execute Insert or Update SQL Command  
		$this->validate();
		if(!($this->validated)){
			$this->Result["isOK"] = FALSE;
			return $this->Result;
		}

		$this->sethashpassword();
		$this->getkeysinAllobject();
		if($this->check_isset("id")) {			
			$this->Update();
			return NULL;
		} else {
			$this->Insert();
			$this->Result["Record"] = $this->id;
			return NULL;
		}
		$this->Result["isOK"] = FALSE;
		$this->Result["Record"] = "Execute Nothing.";
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

		$this->Result["isOK"] = $this->launchcode->execute();
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
			$this->Result["Record"] = $this->launchcode->fetchAll();
			$this->Result["isOK"] = TRUE;
		} else {
			$this->Result["isOK"] = FALSE;
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
