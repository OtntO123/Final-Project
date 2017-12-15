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

	protected $validated;

	public function __construct($id = NULL, $username = NULL, $password = NULL, $fname = NULL, $lname = NULL, $gender = NULL, $birthday = NULL, $phone = NULL, $email = NULL) {
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
		$this->fname = $fname;
		$this->lname = $lname;
		$this->gender = $gender;
		$this->birthday = $birthday;
		$this->phone = $phone;
		$this->email = $email;
		$this->className = "accounts";
		$this->setAllObject();
	}

	public function CheckUsernameAndPasswordPair() {
		$password_login = $this->password;
		$this->selectAllWhen("username", "selectUser");
		if($this->Result["isOK"]) {
			$this->testPassword($password_login);
		} else {
			$this->Result["isOK"] = FALSE;
			$this->Result["Record"] = "We have not this Username.";
		}
		return $Result;
	}

	private function testPassword($password_login) {
		$ispair = password_verify($password_login, $this->password);
		if($ispair) {
			$this->Result["isOK"] = TRUE;
			$this->Result["Record"] = $this->id;
		} else {
			$this->Result["isOK"] = FALSE;
			$this->Result["Record"] = "User found but password is incorrect.";
		}
	}

	protected function sethashpassword() {
		$options = ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
		$this->password = password_hash($this->password, PASSWORD_BCRYPT, $options);
	}

	protected function validate() {
		$this->validated = 1;
		$this->Result["Record"] = "";
		//$this->checkid($this->id);
		$this->checkusername();
		$this->checkpassword();
		$this->checkfname();
		$this->checklname();
		$this->checkgender();
		$this->checkbirthday();
		$this->checkphone();
		$this->checkemail();
	}

	private function checkid() {
		$variable = "id";
		$digit = 3;
		if(!$this->checkIsnumber($this->$variable) or !$this->checkStrelenshorterthan($this->$variable, $digit)) {
			$this->Result["Record"] .= "Error: Have too much id in database or id is not number.<br>";
			$this->validated = 0;
		}
	}

	private function checkusername() {
		if(!preg_match("/[a-z]/i", $this->username)) {
			$this->Result["Record"] .= "Username at least contain 1 letter.<br>";
			$this->validated = 0;
		}
		if(!$this->checkStrelenshorterthan($this->username, 20)) {
			$this->Result["Record"] .= "Username should be alphabetic and less than 20 letters.<br>";
			$this->validated = 0;
		}
	}

	private function checkpassword() {
		$variable = "password";
		if(!$this->checkStrelenshorterthan($this->$variable, 20) or !$this->checkStrelenlongerthan($this->$variable, 6)) {
			$this->Result["Record"] .= "Error: Password should not be more than 20 and less than 6 number.<br>";
			$this->validated = 0;
		}
	}

	private function checkfname() {
		$variable = "fname";
		if(!preg_match("/[a-z]/i", $this->$variable)) {
			$this->Result["Record"] .= "Firstname at least contain 1 letter.<br>";
			$this->validated = 0;
		}
		if(!$this->checkname($this->$variable)) {
			$this->Result["Record"] .= "Firstname should be alphabetic and not more than 20 letters.<br>";
			$this->validated = 0;
		}
	}

	private function checklname() {
		$variable = "lname";
		if(!preg_match("/[a-z]/i", $this->$variable)) {
			$this->Result["Record"] .= "Lastname at least contain 1 letter.<br>";
			$this->validated = 0;
		}
		if(!$this->checkname($this->$variable)) {
			$this->Result["Record"] .= "Lastname should be alphabetic and not more than 20 letters.<br>";
			$this->validated = 0;
		}
	}

	private function checkgender() {
		if($this->gender != "Male" && $this->gender != "Female" && $this->gender != "Other"){
			$this->Result["Record"] .= "Invalid Gender format.<br>";
			$this->validated = 0;
		} 
	}

	private function checkbirthday() {
		if($this->CheckDate($this->birthday)) {
			$this->Result["Record"] .= "Invalid birthday date format.<br>";
			$this->validated = 0;
		}
	}

	private function checkphone() {
		$variable = "phone";
		if(!$this->checkIsnumber($this->$variable) or !$this->checkStrelenshorterthan($this->$variable, 20) or !$this->checkStrelenshorterthan($this->$variable, 10)) {
			$this->Result["Record"] .= "Error: Phone number should not be more than 20 and less than 10 digits.<br>";
			$this->validated = 0;
		}
	}

	private function checkemail() {
		if($this->email != "") {
			if (!filter_var($this->email, FILTER_VALIDATE_EMAIL) or strlen($this->email) > 30) {
				$this->Result["Record"] .= "Invalid email format.<br>";
				$this->validated = 0;
			}
		}
	}

	private function checkIsnumber($variable) {
		if(Is_Numeric($variable)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function checkStrelenshorterthan($variable, $digit) {
		if(strlen($variable) <= $digit) {
			return TRUE;
		} else {
			return FALSE;
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
?>
