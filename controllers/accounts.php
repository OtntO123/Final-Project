<?php namespace controllers;

//Accounts controller for logging, unlogging, creating, editing and deleting an account
final class accounts extends controller {

	//turn to index.php
	public function show()
	{		
            header("Location: index.php");
	}

	//generate edit form of variables for account table, and set each value as $ValueArray
	private function setAccountVariableTable($ValueArray) {
		$inputlabel = array ("Username", "Password", "First Name", "Last Name", "Gender", "Birthday", "Phone Number", "Email Address");
		$inputtype = array ("text", "password", "text", "text", "text", "date", "number", "email");
		$inputname = array ("username", "password", "fname", "lname", "gender", "birthday", "phone", "email");
		$inputstr = $inputlabel;

		foreach ($inputname as $key => $val) 
			$inputstr[$key] .= " <input type = '$inputtype[$key]' value = '$ValueArray[$val]' name = '$inputname[$key]'><br> ";
		$inputstr[4] = "Gender <select name='gender'>
				<option value= $ValueArray[gender] >$ValueArray[gender]</option>
				<option value='Male'>Male</option>
				<option value='Female'>Female</option>
				<option value='Other'>Other</option> </select><br>";
		$this->data['Record'] = $inputstr;
	}

	//Show create account page
	public function register() {

		//get all variables of accounts
		$ValueArray = $this->getobjectForController();

		//put all variables of accounts into table
		$this->setAccountVariableTable($ValueArray);

		$this->template = 'register';
	}

	//Show edit account 
	public function edit() {
		$Allobject =  $this->getobjectForController();
		$ValueArray =array();

		//Check whether there is UserID in session to edit your account
		session_start();
		if(isset($_SESSION["UserID"])) {

			//look for ID in account by SQl
			$this->model->setVariable("selectID", $_SESSION["UserID"]);
			//Run and Get result
			$Result = $this->model->go();

			foreach($Allobject as $key => $val) {
				$ValueArray[$key] = $Result["Record"][0][$key];
			}

			//Prevent from setting hashed password as new password
			$ValueArray["password"] = NULL;

			//set html table code 
			$this->setAccountVariableTable($ValueArray);
			
			//Prevent from editting Username
			unset($this->data['Record'][0]);

			$this->data['username'] = $Result["Record"][0]["username"];

			$this->template = 'edit_account';
		} else {
			header('Location: index.php');
		}
	}

	//Create an account
	public function store() {
		$this->model->setVariable("selectUser", $_POST["username"]);
		$ResultUser = $this->model->Go();
		$haveThisUser  = $ResultUser["isOK"];

		if(!$haveThisUser) {
			$this->model->cleanThisObject();
			$this->setAllPOSTvariableToModel();
			$Result = $this->model->Go();

			if($Result["isOK"]) {
				session_start();
				$_SESSION["UserID"] = $Result["Record"];
				header("Location: index.php");
			} else {
				echo "Setting Error<br>" . $Result["Record"];
			}

		} else {
			echo "This Username have been used, try another.<br>";
		}
	}

	//Save edited account
	public function save() {
		$this->setAllPOSTvariableToModel();
		session_start();
		$id = \httprequest\request::getSessionUserID();
		$this->model->setVariable("id", $id);
		$Result = $this->model->Go();

		if($Result["isOK"]) {
			header("Location: index.php");
		} else {
			echo "Setting Error<br>" . $Result["Record"];
		}
	}

	//Delete an account
	public function delete() {
		session_start();
		$id = \httprequest\request::getSessionUserID();
		$this->fastdelete($id);
		$this->logout();
	}

	//Log in by account
	public function login()
	{
		$this->model->setVariable("selectUser", $_POST["username"]);
		$this->setPOSTVariableToModel("password");
		$Result = $this->model->CheckUsernameAndPasswordPair();

		if($Result["isOK"]) {
			session_start();
			$_SESSION["UserID"] = $Result["Record"];
			header("Location: index.php");
		} else {
			echo "Wrong Pair<br>" . $Result["Record"];
		}
	}

	//Log out and back to homepage
	public function logout() {

		session_start();
		unset($_SESSION["UserID"]);
		header('Location: index.php');

	}
}
