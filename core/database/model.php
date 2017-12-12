<?php namespace database;

use http\controller;

abstract class model{
	public function GoFunction($action){	//Call function to Compile and Run SQL code, echo operation state
		$conn = Database::connect();
		if($conn){	//Do remains after connect
			$content = get_object_vars($this);	//get all variable in child class
			$Scode = $this->$action($content);
			$launchcode = $conn->prepare($Scode); 
			$Result = $launchcode->execute();
			$Result = ($Result = 1) ? " Successful " : " Error ";
			echo "SQL Code : </br>" . $Scode . "<hr>" . $action . " Operation " . $Result . "<hr>";
		}		
	}

	private function Insert($content) {	//Generate Insert Code with variable in child class
	//unset($content['id']);
	$insertInto = "INSERT INTO " . get_called_class() . "s (";
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
