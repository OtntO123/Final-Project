<?php namespace controllers;

final class accounts extends controller {

	public function show()
	{		
            header("Location: index.php");
	}

	private function setAccountVariableTable($ValueArray) {
		$inputlabel = array ("Username", "Password", "First Name", "Last Name", "Gender", "Birthday", "Phone Number", "Email Address");
		$inputtype = array ("text", "password", "text", "text", "text", "date", "number", "email");
		$inputname = array ("username", "password", "fname", "lname", "gender", "birthday", "phone", "email");
		$inputstr = $inputlabel;

		foreach ($inputname as $key => $val) 
			$inputstr[$key] .= " <input type = \"$inputtype[$key]\" value = \"$ValueArray[$val]\" name = \"$inputname[$key]\"><br> ";
		$inputstr[4] = "Gender <select name='gender'>
				<option value= $ValueArray[gender] >$ValueArray[gender]</option>
				<option value='Male'>Male</option>
				<option value='Female'>Female</option>
				<option value='Other'>Other</option> </select><br>";
		$this->data = $inputstr;
	}

	//Show create account page
	public function register() {

		$ValueArray = $this->getobjectForController();

		$this->setAccountVariableTable($ValueArray);

		$this->template = 'register';
	}

	//Show edit account 
	public function edit() {
		$id = \httprequest\request::getSessionUserID();
		$ValueArray = $this->getobjectForController();


		session_start();
		$this->model->setVariable("selectID", $_SESSION["UserID"]);
		$Result = $this->model->go();
		$Result["Record"][0]["password"] = "";

		foreach($ValueArray as $key => $val) {
			$ValueArray[$key] = $Result["Record"][0][$key];
		}

		$this->setAccountVariableTable($ValueArray);

		$this->template = 'edit_account';
	}

	//Create an account
	public function store() {
		$this->setAllPOSTvariableToModel();
		$Result = $this->model->Go();

		if($Result["isOK"]) {
			session_start();
			$_SESSION["UserID"] = $Result["Record"];
			header("Location: index.php");
		} else {
			echo "Setting Error<br>" . $Result["Record"];
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

	public function delete() {
		session_start();
		$id = \httprequest\request::getSessionUserID();
		$this->fastdelete($id);
		setcookie("username", "", time() + (86400 * 30), "/");
		$this->logout();
	}

	public function login()
	{
		$this->model->setVariable("selectUser", $_POST["username"]);
		$this->setPOSTVariableToModel("password");
		$Result = $this->model->CheckUsernameAndPasswordPair();

		if($Result["isOK"]) {
			session_start();
			$_SESSION["UserID"] = $Result["Record"];
			setcookie("username", $_POST["username"], time() + (86400 * 30), "/");
			header("Location: index.php");
		} else {
			echo "Wrong Pair<br>" . $Result["Record"];
		}
	}

	public function logout() {

		session_start();
		unset($_SESSION["UserID"]);
		header('Location: index.php');

	}
}
