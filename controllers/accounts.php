<?php namespace controllers;

final class accounts extends controller {

	protected function show()
	{		
            header("Location: index.php");
	}

	private function register() {
		$inputlabel = array ("Username", "Password", "First Name", "Last Name", "Gender", "Birthday", "Phone Number", "Email Address");
		$inputtype = array ("text", "password", "text", "text", "text", "date", "number", "email");
		$inputname = array ("username", "password", "fname", "lname", "gender", "birthday", "phone", "email");
		$inputstr = $inputlabel;
		$rec = new account();

		foreach ($inputname as $key => $val) {
			$recval = $rec->$val;
			$inputstr[$key] .= " <input type = \"$inputtype[$key]\" value = \"$recval\" name = \"$inputname[$key]\"> ";
		}
  		self::getTemplate('register', $inputstr);
	}

	private function store() {
		$bool = accounts::CreateUser();
		if ($bool == 1) {
			accounts::Login();
		}
	}

	private function edit() {
		$inputlabel = array ("Username", "Password", "First Name", "Last Name", "Gender", "Birthday", "Phone Number", "Email Address");
		$inputtype = array ("text", "password", "text", "text", "text", "date", "number", "email");
		$inputname = array ("username", "password", "fname", "lname", "gender", "birthday", "phone", "email");
		$inputstr = $inputlabel;
		$rec = accounts::ShowData($_SESSION["UserID"]);
		$rec[0]->password = "";
		//print_r($rec[0]);
		foreach ($inputname as $key => $val) {
			$recval = $rec[0]->$val;
			$inputstr[$key] .= " <input type = \"$inputtype[$key]\" value = \"$recval\" name = \"$inputname[$key]\"> ";
		}
		self::getTemplate('edit_account', $inputstr);
}

	private function save() {
		$bool = accounts::EditProfile();
		if ($bool == 1) {
			accounts::Login();
		}
	}

	private function delete() {
		$id = http\request::getSessionUserID();
		accounts::SQLDelete($id);
		session_destroy();
		header("Location: index.php");
	}

	private function login()
	{
		accounts::Login();
	}

}
