<?php namespace database;

abstract class collections{	//Save functions of SQL Operation by ActiveRecord

	final static protected function executeScode($Scode){	//Execute SQL code and return table display html string
		$conn = Database::connect();
		if($conn){			
			$launchcode = $conn->prepare($Scode);
			$launchcode->execute();
			$DataTitle = static::$modelNM;
			$launchcode->setFetchMode(\PDO::FETCH_CLASS, $DataTitle);
			$Result = $launchcode->fetchAll();
			return $Result;
		}
	}

	final static public function EditProfile(){	//Use ActiveRecord to Generate and Run SQL code
		$record = new static::$modelNM();	//instantiate new object
		$record->id = 11;
		$record->username = $_POST["username"];
		$record->fname = $_POST["fname"];
		$record->lname = $_POST["lname"];
		$record->gender = $_POST["gender"];
		$record->phone = $_POST["phone"];
		$record->birthday = $_POST["birthday"];
		$record->email = $_POST["email"];

		$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
		$record->password = password_hash($_POST["password"], PASSWORD_BCRYPT, $options);

		$record->GoFunction("Update");	//Run Update() in modol class and echo success or not
		return self::showData();	//return display html table code from ShowData	
	}

	final static public function validation() {
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
		
		if($_POST["email"] != "") {
			if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
				$wr .= "Invalid email format.<br>"; 
			}
		}
		return $wr;
	}

	final static public function CreateUser(){	//Use ActiveRecord to Generate and Run SQL code		
		
		$wr = static::validation();
		$record = new static::$modelNM();	//instantiate new object
		$record->username = $_POST["username"];
		$record->fname = $_POST["fname"];
		$record->lname = $_POST["lname"];
		$record->gender = $_POST["gender"];
		$record->phone = $_POST["phone"];
		$record->birthday = $_POST["birthday"];
		$record->email = $_POST["email"];
		$record->addhashpassword($_POST["password"]);


		if($wr != "") {
			echo $wr;
			$_SESSION["Temprecord"] = $record;
			return NULL;
		} else {
			$_SESSION["Temprecord"] = NULL;
		}
		
	
		$record->GoFunction("Insert");	//Run Insert() in modol class and echo success or not
		$_SESSION["Username"] = $_POST["username"];
		$_SESSION["Password"] = $_POST["password"];
		setcookie("Username", $_POST["username"], time() + (86400 * 30), "/");
		return self::showData();	//return display html table code from ShowData
	}

	final static public function Login(){

		$Scode = "SELECT password FROM accounts WHERE username = \"" . $_POST["Username"] . "\"";
	
		$Result = self::executeScode($Scode);
		$BoolGate = FALSE;
		if($Result != null) {
			$passwordhashingcode = $Result[0]->password;
			$BoolGate = password_verify($_POST["Password"], $passwordhashingcode);
		}

		if ($BoolGate) {
			echo "Right Pairs";
			$_SESSION["UserID"] = "Don't Know";
			setcookie("Username", $_POST["Username"], time() + (86400 * 30), "/");
		} else {
			echo "Wrong Pairs";
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
