<?php namespace models;

abstract class model{
	public $className;
	public $selectID;
	public $deleteID;
	public $selectUser;
	protected $Allobject;
	private $Scode;
	protected 
	
	public function Go() {	//Call function to Compile and Run SQL code, echo operation state
		$Result = array();
		$statement = $this->validated();
		if(!($this->validated)){
			$Result["statement"] = $statement;
			$Result["isOK"] = FALSE;
			return $Result;
		}

		$conn = Database::connect();
		if($conn == NULL){	//Do remains after connect
			$Result["isOK"] = FALSE;
			return $Result;
		}

		//getAllObject();	//get all variable in child class
		setClassName();

		if($this->id = '') {
			$this->update();
		} else {
			$this->insert();
		}

		$launchcode = $conn->prepare($this->Scode); 
		$Result = $launchcode->execute();
		$Result = ($Result = 1) ? " Successful " : " Error ";
		return "SQL Code : </br>" . $Scode . "<hr>" . $action . " Operation " . $Result . "<hr>";
	}

	private function setScode() {
		if(isselectID()) {
			$this->FindID()
			break;
		}
		if(isselectUser()) {
			$this->FindUserforPassword()
			break;
		}
		if(isselectID()) {
			$this->FindID()
			break;
		}
		if(isselectID()) {
			$this->FindID()
			break;
		}
	}

	private function FindID() {
	$this->Scode = "SELECT * FROM " . $this->className . " WHERE id=\"" . $this->id . "\"";

	}

	private function FindUserforPassword() {

	}

	private function isselectID() {
		if(is_null($this->selectID)) {
			return true;
		} else {
			return false;
		}
	}

	private function isselectUser() {
		if(is_null($this->selectUser))
			return true;
		} else {
			return false;
		}
	}

	private function isupdate() {
		if(is_null($this->id))
			return true;
		} else {
			return false;
		}
	}

	protected function executeScode() {
		
	}

	public function getAllObject() {
		$this->emptyAllobject();
		return $this->Allobject
	}

	private function emptyAllobject() {
		if(empty($Allobject)) {
			setAllObject();
		}
	}

	private function setAllObject() {
		$Allobject = get_object_vars($this);
		unset($Allobject["validated"]);
		unset($Allobject["className"]);
		unset($Allobject["Allobject"]);
		unset($Allobject["Scode"]);
		unset($Allobject["selectID"]);
		unset($Allobject["deleteID"];
		unset($Allobject["selectUser"];
		$this->Allobject = $Allobject;
	}

	private function setClassName() {
		$this->className = get_called_class();
	}
	



	private function Insert($content) {	//Generate Insert Code with variable in child class
	//unset($content['id']);
	$insertInto = "INSERT INTO " . get_called_class() . " (";
	$Keystring = implode(',', array_keys($content)) . ") ";	//implode array to string
	$valuestring = implode("','", $content);
	$Scode = $insertInto . $Keystring . "VALUES ('" . $valuestring . "');";
	return $Scode;
	}

	private function Update($content) {	//Generate Update Code with variable in child class
	$where = " WHERE id = " . $content['id'];
	unset($content['id']);
	$update = "UPDATE " . get_called_class() . "s SET ";
	foreach ($content as $key => $value)	//find variable with value to update
		$update .= ($value !== Null) ? " $key = \"$value\", " : "";
	$update = substr($update, 0, -2);
	$Scode = $update . $where;		//cut its last string of ","
	return $Scode;
	}

	private function Delete($content) {	//Generate Delete Code with variable in child class
	$where = " WHERE";
	foreach ($content as $key => $value)	//find variable with value to designate deleting line
		$where .= ($value !== Null) ? " $key = \"$value\" AND" : "";
	$where = substr($where, 0, -4);		//cut its last string of "and"
	$Scode = "DELETE FROM " .  get_called_class() . "s" . $where . ";";
	return $Scode;
	}

	public function addhashpassword($password) {
		$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
		$hashedpwd = password_hash($password, PASSWORD_BCRYPT, $options);
		$this->password = $hashedpwd;
	}
}
