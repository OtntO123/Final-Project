<?php namespace controllers;

abstract class controller {

//this is the controller class that you use to connect models with views and business logic
	protected $model;

	protected $modelNM;

	protected $template;

	protected $data;

	public function __construct(\models\model $model) {
		$this->model = $model;
		$this->modelNM = substr(get_class($this), 7);
	}



//this gets the HTML template for the application and accepts the model.  The model array can be used in the template
	public function display() {
		$data = $this->data;
		$htmlpage = 'pages/' . $this->template . '.php';
//in your template you should use $data to access your array
		include $htmlpage;
	}


/////////////////////////////////////////////////////////////

	protected function executeScode(){
		return $this->model->Go();
	}

	protected function EditProfile(){	//Use ActiveRecord to Generate and Run SQL code

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

	protected function Edittask(){	//Use ActiveRecord to Generate and Run SQL code
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






	protected function CreateUser(){	//Use ActiveRecord to Generate and Run SQL code		
		
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


	protected function Createtask(){	//Use ActiveRecord to Generate and Run SQL code
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


	protected function Login(){

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

	protected function ShowData($id = ""){	//makeup select * from database 
		$id = ($id !== "") ? "= " . $id : "";
		$Scode = 'SELECT * FROM ' . get_called_class() . " WHERE id " . $id;
		$Result = self::executeScode($Scode);
		return $Result;	//return display html table code
	}

	protected function ShowDataID_5(){	//call ShowData to select * from database where id = 5
		$Result = self::ShowData("5");
		return $Result;
	}

	protected function SQLDelete($id){	//Use ActiveRecord to Generate and Run SQL code
		$record = new static::$modelNM();	//instantiate new object
		$record->id = $id;
		$record->GoFunction("Delete");	//Run Delete() in modol class and echo success or not
		//return self::showData();	//return display html table code from ShowData
	}

	protected function SQLUpdate_11(){	//Use ActiveRecord to Generate and Run SQL code
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



	protected function Session_Destroy(){
		session_destroy();
	}
/////////////////////////////////////////////////////////////

/*
	protected function setAllvariable(){
		foreach($this->model->getAllobject() as $key => $value) {
			if(isset($_POST($value)))
				$this->model->$value = $_POST($value);
		}
	}

	protected function setVariable($variable){
		if(isset($_POST($variable)))
			$this->model->$value = $_POST($variable);
	}

*/
}
