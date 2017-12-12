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

	public function __construct($id = "", $owneremail = "", $ownerid = "", $createddate = "", $duedate = "", $message = "", $isdone = "") {
		$this->id = $id;
		$this->owneremail = $owneremail;
		$this->ownerid = $ownerid;
		$this->createddate = $createddate;
		$this->duedate = $duedate;
		$this->message = $message;
		$this->isdone = $isdone;
		$this->className = 
		$validated = FALSE;
	}

	protected function validate() {
		$statement = "";

		if($this->ownerid) == "") {
			$statement .= "There should be a Ownerid";
			if(strlen($this->ownerid) > 10) {
				$statement .= "Ownerid can't be more than 10 number.<br>";
				break;
			}
		}

		if($this->owneremail != "") {
			if (!filter_var($_POST["owneremail"], FILTER_VALIDATE_EMAIL)) {
				$statement .= "Invalid email format.<br>";
				break;
			}
		}

		$this->validated = 1;
		return $statement;

	}

}

?>
