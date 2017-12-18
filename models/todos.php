<?php	namespace models;

final class todos extends model
{
	protected $id;
	protected $owneremail;
	protected $ownerid;
	protected $createddate;
	protected $duedate;
	protected $message;
	protected $isdone;

	//$validated is gate for input validation
	protected $validated;

	//set an array of all variabls in todos to Allobject
	protected function setAllObject() {
		$Allobject = get_object_vars($this);
		unset($Allobject["validated"]);
		unset($Allobject["Allobject"]);
		unset($Allobject["Result"]);
		$this->Allobject = $Allobject;
	}

	//Validation function for all todos' variable's value
	protected function validate() {
		$this->validated = 1;
		$this->checkduedate();
		$this->checkcreateddate();
		$this->checkowneremail();
		$this->checkmessage();
		$this->checkid($this->id);
		$this->checkid($this->ownerid);
	}

	//Used by validate function, for todos' variable of createddate input check
	private function checkcreateddate() {
		if($this->createddate != "") {
			if($this->CheckDate($this->createddate)) {
				$this->setAddResultRecord("Invalid date format.<br>");
				$this->validated = 0;
			}
		}
	}

	//Used by validate function, for todos' variable of duedate input check
	private function checkduedate() {
		if($this->CheckDate($this->duedate)) {
			$this->setAddResultRecord("Invalid date format.<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for todos' variable of owneremail input check
	private function checkowneremail() {
		if (!filter_var($this->owneremail, FILTER_VALIDATE_EMAIL) or strlen($this->owneremail) > 30) {
			$this->setAddResultRecord("Invalid email format.<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for todos' variable of isdone input check
	private function checkisdone() {
		if(!is_bool($this->isdone)) {
			$this->setAddResultRecord("Invalid isdone format.<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for todos' variable of message input check
	private function checkmessage() {
		if(strlen($this->message) > 30) {
			$this->setAddResultRecord("Warning!!Message should be less than 30 characters!<br>");
			$this->validated = 0;
		}
	}

	//Used by validate function, for todos' variable of id input check
	private function checkid($key) {
		if(isset($key)) {
			if(!Is_Numeric($key) or strlen($key) > 5) {
				$this->setAddResultRecord("Error: Have too much id in database or It is not number.<br>");
				$this->validated = 0;
			}
		}
	}
}

