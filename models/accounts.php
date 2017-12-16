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

	protected $validated;

	protected function setAllObject() {
		$Allobject = get_object_vars($this);
		unset($Allobject["validated"]);
		unset($Allobject["Allobject"]);
		unset($Allobject["Result"]);
		$this->Allobject = $Allobject;
	}

	public function CheckUsernameAndPasswordPair() {
		$password_login = $this->password;
		$this->selectAllWhen("username", "selectUser");
		if($this->Result["isOK"]) {
			$this->testPassword($password_login);
		} else {
			$this->setResultIsOK(FALSE);
			$this->setResultRecord("We have not this Username.");
		}
		return $Result;
	}

	private function testPassword($password_login) {
		$ispair = password_verify($password_login, $this->password);
		if($ispair) {
			$this->setResultIsOK(TRUE);
			$this->setResultRecord($this->id);
		} else {
			$this->setResultIsOK(FALSE);
			$this->setResultRecord("User found but password is incorrect.");
		}
	}

	protected function sethashpassword() {
		$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
		$this->password = password_hash($this->password, PASSWORD_BCRYPT, $options);
	}

	protected function validate() {
		$this->validated = 1;
		$this->checkusername();
		$this->checkpassword();
		$this->checkfname();
		$this->checklname();
		$this->checkgender();
		$this->checkbirthday();
		$this->checkphone();
		$this->checkemail();
	}





	private function checkpassword() {
		$variable = "password";
		if(!$this->checkStrelenshorterthan($this->$variable, 20) or !$this->checkStrelenlongerthan($this->$variable, 6)) {
			$this->setAddResultRecord("Error: Password should not be more than 20 and less than 6 number.<br>");
			$this->validated = 0;
		}
	}

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

	private function checkgender() {
		if($this->gender != "Male" && $this->gender != "Female" && $this->gender != "Other"){
			$this->setAddResultRecord("Invalid Gender format.<br>");
			$this->validated = 0;
		} 
	}

	private function checkbirthday() {
		if($this->CheckDate($this->birthday)) {
			$this->setAddResultRecord("Invalid birthday date format.<br>");
			$this->validated = 0;
		}
	}

	private function checkphone() {
		$variable = "phone";
		if(!$this->checkIsnumber($this->$variable) or !$this->checkStrelenshorterthan($this->$variable, 20) or !$this->checkStrelenshorterthan($this->$variable, 10)) {
			$this->setAddResultRecord("Error: Phone number should not be more than 20 and less than 10 digits.<br>");
			$this->validated = 0;
		}
	}

	private function checkemail() {
		if($this->email != "") {
			if (!filter_var($this->email, FILTER_VALIDATE_EMAIL) or strlen($this->email) > 30) {
				$this->setAddResultRecord("Invalid email format.<br>");
				$this->validated = 0;
			}
		}
	}

	private function checkname($variable) {		
		$digit = 20;
		if($this->checkAlphabetic($variable) && $this->checkStrelenshorterthan($variable, $digit)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function checkStrelenlongerthan($variable, $digit) {
		if(strlen($variable) >= $digit) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function checkAlphabetic($variable) {
		if(ctype_alpha($variable)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
