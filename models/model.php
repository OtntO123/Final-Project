<?php namespace models;

abstract class model{
	public $selectID;
	public $selectUser;
	public $deleteID;
	private $className;
	private $Allobject;
	private $Scode;
	private $conn;
	private $launchcode;
	protected $Result;
	
	public function Go() {	//Call function to Compile and Run SQL code, echo operation state
		$this->validate();
		if(!($this->validated)){
			$this->Result["isOK"] = FALSE;
			return $Result;
		}

		$conn = Database::connect();
		if($conn == NULL){	//Do remains after connect
			$this->Result["isOK"] = FALSE;
			return $Result;
		}

		//getAllObject();	//get all variable in child class
		$this->setClassName();

		$this->setScodeAndExe();
		return $Result;
	}

	private function setScodeAndExe() {
		if(check_isset("selectID")) {
			$this->selectAllWhen("id", "selectID");
			break;
		}
		if(check_isset("selectUser")) {
			$this->selectAllWhen("username", "selectUser");
			break;
		}
		if(check_isset("deleteID")) {
			$this->Delete();
			break;
		}

		$this->sethashpassword();
		if(check_isset("id")) {			
			$this->Update();
			break;
		} else {
			$this->Insert();
			$this->Result["Record"] = $this->id;
		}
	}

	private function check_isset($var) {
		if(!is_null($this->$var)) {
			return true;
		} else {
			return false;
		}
	}

	private function PrepareBindExe($parameters) {
		$this->launchcode = $this->conn->prepare($this->Scode);				

		foreach ($parameters as $key => $value) {
			$this->launchcode->bindParam(":$key", $this->$key);
		}

		$this->Result["isOK"] = $this->launchcode->execute();
	}

	private function selectAllWhen($where, $Parameter) {
		$Parameter = (array) $Parameter;
		$this->Scode = "SELECT * FROM " . $this->className . " WHERE " . $where . " = :" . $Parameter[0];
		$this->PrepareBindExe($Parameter);
		$this->setFetchData();
	}

	private function setFetchData() {
		if ($this->launchcode->rowCount() > 0) {
			$this->launchcode->setFetchMode(\PDO::FETCH_CLASS, $this->classname);
			$this->Result["Record"] = $this->launchcode->fetchAll();
			$this->Result["isOK"] = TRUE;
		} else {
			$this->Result["isOK"] = FALSE;
		}
	}

	private function getAllObject() {
		$this->emptyAllobject();
		return $this->Allobject;
	}

	private function emptyAllobject() {
		if(empty($Allobject)) {
			$this->setAllObject();
		}
	}

	private function setAllObject() {
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

	private function setClassName() {
		$this->className = get_called_class();
	}

	private function getkeysinAllobject() {		
		return  array_keys($this->Allobject);		
	}


	private function Insert() {	//Generate Insert Code with variable in child class
		$parameters = $this->getkeysinAllobject();
		unset($parameters['id']);
		$str = $this->getStringOfkeys($parameters);
		$this->Scode = "INSERT INTO " . get_called_class() . " (";
		$this->Scode .= $str["key"] . ") ";	//implode array to string
		$this->Scode .= "VALUES (" . $str[":key"] . ");";
		$this->PrepareBindExe($parameters);
		$this->id = $this->conn->lastInsertId();
	}

	private function getStringOfkeys($parameters) {
		$str["keys"] = implodearraywithcomma($parameters);
		$str[":keys"] = implode(', :', $parameters);
		return $str; 
	}

	private function implodearraywithcomma($parameters) {
		return implode(', ', $parameters);
	}

	private function Update() {	//Generate Update Code with variable in child class

		$parameters = $this->getkeysinAllobject();
		$this->Scode = "UPDATE " . get_called_class() . " SET (";
		$this->Scode .= $this->getUpdateScode($parameters);
		$this->Scode .= ") WHERE id = :id";
		$this->PrepareBindExe($parameters);
	}

	private function getUpdateScode($parameters) {
		unset($parameters["id"]);
		foreach ($parameters as $key => $val) {
			$parameters[$key] .= " = :" . $val;		
		}
		return implodearraywithcomma($parameters);
	}

	private function Delete($content) {	//Generate Delete Code with variable in child class
		$parameters = array("deleteID");
		$this->Scode = "DELETE FROM " .  get_called_class() . "WHERE id = :deleteID";
		$this->PrepareBindExe($parameters);
	}

	private function sethashpassword() {
		$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
		$this->password = password_hash($this->password, PASSWORD_BCRYPT, $options);
	}
}
