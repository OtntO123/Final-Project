<?php	namespace models;

final class todos extends model
{
	public $id;
	public $owneremail;
	public $ownerid;
	public $createddate;
	public $duedate;
	public $message;
	public $isdone;

	protected $validated;

	public function __construct($id = NULL, $owneremail = NULL, $ownerid = NULL, $createddate = NULL, $duedate = NULL, $message = NULL, $isdone = NULL) {
		$this->id = $id;
		$this->owneremail = $owneremail;
		$this->ownerid = $ownerid;
		$this->createddate = $createddate;
		$this->duedate = $duedate;
		$this->message = $message;
		$this->isdone = $isdone;
	}

	protected function validate() {

		$this->validated = 1;
		$this->Result["Record"] = "";

		if($this->createddate == "") {
			$this->Result["Record"] .= "There should be a createddate.<br>";
			$this->validated = 0;
		}

		if($this->duedate == "") {
			$this->Result["Record"] .= "There should be a duedate.<br>";
			$this->validated = 0;
		}
		if($this->CheckDate($this->duedate)) {
			$this->Result["Record"] .= "Invalid date format.<br>";
			$this->validated = 0;
		}

		if($this->createddate == "") {
			$this->Result["Record"] .= "There should be a createddate.<br>";
			$this->validated = 0;
		}
		if($this->CheckDate($this->createddate)) {
			$this->Result["Record"] .= "Invalid date format.<br>";
			$this->validated = 0;
		}


		if($this->owneremail != "") {
			if (!filter_var($this->owneremail, FILTER_VALIDATE_EMAIL)) {
				$this->Result["Record"] .= "Invalid email format.<br>";
				$this->validated = 0;
			}
		}

		if(!is_bool($this->isdone)) {
			$this->Result["Record"] .= "Invalid isdone format.<br>";
			$this->validated = 0;
		}
	}

	private function CheckDate($Date) {
		if (DateTime::createFromFormat('Y-m-d G:i:s', $Date) == FALSE) {
			return TRUE;
		}
	}

}

?>
