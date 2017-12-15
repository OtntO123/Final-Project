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

	protected $validated;

	public function __construct($id = NULL, $owneremail = NULL, $ownerid = NULL, $createddate = NULL, $duedate = NULL, $message = NULL, $isdone = NULL) {
		$this->id = $id;
		$this->owneremail = $owneremail;
		$this->ownerid = $ownerid;
		$this->createddate = $createddate;
		$this->duedate = $duedate;
		$this->message = $message;
		$this->isdone = $isdone;
		$this->className = "todos";
		$this->setAllObject();
	}

	protected function validate() {
		$this->validated = 1;
		$this->checkcreateddate();
		$this->checkduedate();
		$this->checkcreateddate();
		$this->checkowneremail();
		//$this->checkid($this->ownerid);
		$this->checkmessage();
	}

	private function checkcreateddate() {
		if($this->createddate == "") {
			$this->setAddResultRecord("There should be a createddate.<br>");
			$this->validated = 0;
		}
		if($this->CheckDate($this->createddate)) {
			$this->setAddResultRecord("Invalid date format.<br>");
			$this->validated = 0;
		}
	}

	private function checkduedate() {
		if($this->duedate == "") {
			$this->setAddResultRecord("There should be a duedate.<br>");
			$this->validated = 0;
		}
		if($this->CheckDate($this->duedate)) {
			$this->setAddResultRecord("Invalid date format.<br>");
			$this->validated = 0;
		}
	}


	private function checkowneremail() {
		if($this->owneremail != "") {
			if (!filter_var($this->owneremail, FILTER_VALIDATE_EMAIL) or strlen($this->owneremail) > 30) {
				$this->setAddResultRecord("Invalid email format.<br>");
				$this->validated = 0;
			}
		}
	}

	private function checkisdone() {
		if(!is_bool($this->isdone)) {
			$this->setAddResultRecord("Invalid isdone format.<br>");
			$this->validated = 0;
		}
	}

	private function checkid($key) {
		if(!Is_Numeric($key) or strlen($key) > 5) {
			$this->setAddResultRecord("Error: Have too much id in database or It is not number.<br>");
			$this->validated = 0;
		}
	}

	private function checkmessage() {
		if(strlen($this->message) > 30) {
			$this->setAddResultRecord("Warning!!Message should be less than 30 characters!<br>");
			$this->validated = 0;
		}
	}


}

