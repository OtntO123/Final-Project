<?php	namespace models;

final class accounts extends model
{
	protected $id;
	protected $username;
	protected $password;
	protected $fname;
	protected $lname;
	protected $gender;
	protected $birthday;
	protected $phone;
	protected $email;

	//$validated is gate for input validation
	protected $validated;

	//set an array of all variabls in accounts to Allobject
	protected function setAllObject() {
		$Allobject = get_object_vars($this);
		unset($Allobject["validated"]);
		unset($Allobject["Allobject"]);
		unset($Allobject["Result"]);
		$this->Allobject = $Allobject;
	}

	//Check whether username and password is a pair
	public function CheckUsernameAndPasswordPair() {
		$password_login = $this->password;
		$this->Go();
		if($this->Result["isOK"]) {
			$this->testPassword($password_login);
		} else {
			$this->setResultIsOK(FALSE);
			$this->setResultRecord("We have not this Username.");
		}
		return $this->Result;
	}

	//Using password_verify to varify
	private function testPassword($password_login) {
		$ispair = password_verify($password_login, $this->Result["Record"][0]["password"]);
		if($ispair) {
			$this->setResultIsOK(TRUE);
			$this->setResultRecord($this->Result["Record"][0]["id"]);
		} else {
			$this->setResultIsOK(FALSE);
			$this->setResultRecord("User found but password is incorrect.");
		}
	}

	//set $password into hashed password
	protected function sethashpassword() {
		$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
		$this->password = password_hash($this->password, PASSWORD_BCRYPT, $options);
	}

	//Validation function for all accounts' variable's value
	protected function validate() {
		$this->validated = 1;
		$this->checkusername("username");
		$this->checkpassword();
		$this->checkfname();
		$this->checklname();
		$this->checkgender();
		$this->checkbirthday();
		$this->checkphone();
		$this->checkemail();
	}

	//Used by validate function, for accounts' variable of password input check
	private function checkpassword() {
		$variable = "password";
		if(!$this->checkStrelenshorterthan($this->$variable, 20) or !$this->checkStrelenlongerthan($this->$variable, 6)) {
			$this->setAddResultRecord("Error: Password should not be more than 20 and less than 6 number.<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for accounts' variable of firstname input check
	private function checkfname() {
		$variable = "fname";
		if(!preg_match("/[a-z]/i", $this->$variable)) {
			$this->setAddResultRecord("Firstname at least contain 1 letter.<br>");
			$this->validated = 0;
		}
		if(!$this->checkname($this->$variable)) {
			$this->setAddResultRecord("Firstname should be alphabetic and not more than 20 letters.<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for accounts' variable of lastname input check
	private function checklname() {
		$variable = "lname";
		if(!preg_match("/[a-z]/i", $this->$variable)) {
			$this->setAddResultRecord("Lastname at least contain 1 letter.<br>");
			$this->validated = 0;
		}
		if(!$this->checkname($this->$variable)) {
			$this->setAddResultRecord("Lastname should be alphabetic and not more than 20 letters.<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for accounts' variable of gender input check
	private function checkgender() {
		if($this->gender != "Male" && $this->gender != "Female" && $this->gender != "Other"){
			$this->setAddResultRecord("Invalid Gender format.<br>");
			$this->validated = 0;
		} 
	}

	//Used by validate function, for accounts' variable of birthday input check
	private function checkbirthday() {
		if($this->CheckDate($this->birthday)) {
			$this->setAddResultRecord("Invalid birthday date format.<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for accounts' variable of phone number input check
	private function checkphone() {
		$variable = "phone";
		$max = 20;
		$min = 6;
		if(!$this->checkIsnumber($this->$variable) or !$this->checkStrelenshorterthan($this->$variable, $max) or !$this->checkStrelenlongerthan($this->$variable, $min)) {
			$this->setAddResultRecord("Error: Phone number should not be more than $max and less than $min digits.<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for accounts' variable of email input check
	private function checkemail() {
		if($this->email != "") {
			if (!filter_var($this->email, FILTER_VALIDATE_EMAIL) or strlen($this->email) > 30) {
				$this->setAddResultRecord("Invalid email format.<br>");
				$this->validated = 0;
			}
		}
	}

	//Used by validate function, check username
	private function checkname($variable) {		
		$digit = 20;
		if($this->checkAlphabetic($variable) && $this->checkStrelenshorterthan($variable, $digit)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//Used by validate function, check an string
	private function checkStrelenlongerthan($variable, $digit) {
		if(strlen($variable) >= $digit) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//Used by validate function, check an string
	private function checkAlphabetic($variable) {
		if(ctype_alpha($variable)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
