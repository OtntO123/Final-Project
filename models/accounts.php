<?php	namespace models;

final class accounts extends model
{
	public $id;
	public $username;
	public $password;
	public $fname;
	public $lname;
	public $gender;
	public $birthday;
	public $phone;
	public $email;

	private $validated;

	protected static $modelName = 'account';

	public function findUserforPassword() {
		$statement = $this->validated();

		$Scode = "SELECT * FROM " . $this->className . " WHERE id=\"" . $this->id . "\"";


	}

	public static function getTablename()
	{

		$tableName = 'accounts';
		return $tableName;
	}


    //to find a users tasks you need to create a method here.  Use $this->id to get the usersID For the query
	public static function findTasks()
	{

		//I am temporarily putting a findall here but you should add a method to todos that takes the USER ID and returns their tasks.
		$records = todos::findAll();
		print_r($records);
		return $records;
	}
    //add a method to compare the passwords this is where bcrypt should be done and it should return TRUE / FALSE for login



	public function setPassword($password) {

		$password = password_hash($password, PASSWORD_DEFAULT);


		return $password;

	}

	public function checkPassword($LoginPassword) {

		return password_verify($LoginPassword, $this->password);


	}


	public function validation() {
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



}


?>
