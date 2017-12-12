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
		$wr = $record->validation();
		$record->id = $_SESSION["UserID"];
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
			//$_SESSION["Temprecord"] = NULL;
		}
		
	
		$record->GoFunction("Update");	//Run Insert() in modol class and echo success or not
		setcookie("Username", $_POST["username"], time() + (86400 * 30), "/");
		return 1;	//return display html table code from ShowData

	}

	final static public function Edittask(){	//Use ActiveRecord to Generate and Run SQL code
		$record = new static::$modelNM();	//instantiate new object
		$wr = $record->validation();
		$record->id = $_SESSION["UserID"];
		$record->owneremail = $_POST["owneremail"];
		$record->ownerid = $_POST["ownerid"];
		$record->createddate = $_POST["createddate"];
		$record->duedate = $_POST["duedate"];
		$record->message = $_POST["message"];
		$record->isdone = $_POST["isdone"];


		if($wr != "") {
			echo $wr;
			return NULL;
		}		
	
		$record->GoFunction("Update");	//Run Insert() in modol class and echo success or not
		return 1;	//return display html table code from ShowData

	}






	final static public function CreateUser(){	//Use ActiveRecord to Generate and Run SQL code		
		
		$record = new static::$modelNM();	//instantiate new object
		$wr = $record->validation();
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
			//$_SESSION["Temprecord"] = NULL;
		}
		
	
		$record->GoFunction("Insert");	//Run Insert() in modol class and echo success or not
		setcookie("Username", $_POST["username"], time() + (86400 * 30), "/");
		return 1;	//return display html table code from ShowData
	}


	final static public function Createtask(){	//Use ActiveRecord to Generate and Run SQL code
		$record = new static::$modelNM();	//instantiate new object
		$wr = $record->validation();
		$record->id = $_SESSION["UserID"];
		$record->owneremail = $_POST["owneremail"];
		$record->ownerid = $_POST["ownerid"];
		$record->createddate = $_POST["createddate"];
		$record->duedate = $_POST["duedate"];
		$record->message = $_POST["message"];
		$record->isdone = $_POST["isdone"];
		
		if($wr != "") {
			echo $wr;
			return NULL;
		} else {
			//$_SESSION["Temprecord"] = NULL;
		}
		
	
		$record->GoFunction("Insert");	//Run Insert() in modol class and echo success or not
		return 1;	//return display html table code from ShowData		
	}


	final static public function Login(){

		$Scode = "SELECT id, password FROM accounts WHERE username = \"" . $_POST["username"] . "\"";
	
		$Result = self::executeScode($Scode);
		$BoolGate = FALSE;
		if($Result != null) {
			$passwordhashingcode = $Result[0]->password;
			$BoolGate = password_verify($_POST["password"], $passwordhashingcode);
		}

		if ($BoolGate) {
			echo "Right Pair";
			$_SESSION["UserID"] = $Result[0]->id;
			setcookie("username", $_POST["username"], time() + (86400 * 30), "/");
			header("Location: index.php");
		} else {
			echo "Wrong Pair";
		}

	}

	final static public function ShowData($id = ""){	//makeup select * from database 
		$id = ($id !== "") ? "= " . $id : "";
		$Scode = 'SELECT * FROM ' . get_called_class() . " WHERE id " . $id;
		$Result = self::executeScode($Scode);
		return $Result;	//return display html table code
	}

	final static public function ShowDataID_5(){	//call ShowData to select * from database where id = 5
		$Result = self::ShowData("5");
		return $Result;
	}

	final static public function SQLDelete($id){	//Use ActiveRecord to Generate and Run SQL code
		$record = new static::$modelNM();	//instantiate new object
		$record->id = $id;
		$record->GoFunction("Delete");	//Run Delete() in modol class and echo success or not
		//return self::showData();	//return display html table code from ShowData
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



	final static public function Session_Destroy(){
		session_destroy();
	}

}
