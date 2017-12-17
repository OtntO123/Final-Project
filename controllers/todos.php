<?php namespace controllers;

final class todos extends controller {
  //each method in the controller is named an action.
    //to call the show function the url is index.php?page=task&action=show
	public function show()
	{
		$Result = $this->showTasks();

		if($Result["isOK"]) {			
        		$data["Record"] = \utility\table::tablecontect($Result["Record"]);
		} else {
			$data["Record"] = "You have no task.<hr>";
		}

		$ishave_task = $Result["isOK"];

		$isshow = \httprequest\request::BoolToStyle_yesORnone(!$ishave_task);
		$data["istask"] = $isshow;

		$noisshow = \httprequest\request::BoolToStyle_yesORnone($ishave_task);
		$data["!istask"] = $noisshow;

		$this->data = $data;
		$this->template = 'show_task';

	}

	private function showTasks() {
		session_start();
		$id = \httprequest\request::getSessionUserID();
		$this->model->setVariable("selectownerID", $id);
		$Result = $this->model->Go();
		return $Result;
	}

	private function setTaskVariableTable($ValueArray) {
		$inputlabel = array ("Owneremail", "Duedate", "Message");
		$inputtype = array ("email", "date", "text");
		$inputname = array ("owneremail", "duedate", "message");
		$inputstr = $inputlabel;

		foreach ($inputname as $key => $val) 
			$inputstr[$key] .= " <input type = \"$inputtype[$key]\" value = \"$ValueArray[$val]\" name = \"$inputname[$key]\"><br> ";
		$inputstr[] = "Isdone <select name='isdone'>
				<option value= $ValueArray[isdone] >$ValueArray[isdone]</option>
				<option value='1'>Have Done</option>
				<option value='0'>Have Not Done</option></select>";
		$this->data = $inputstr;
	}

	//Show Task create table
	public function create()
	{
		session_start();
		$ValueArray = $this->getobjectForController();

		$this->setTaskVariableTable($ValueArray);

		$this->template = 'create_task';

	}

	//Create A Task
	public function store()
	{
		session_start();
		$id = \httprequest\request::getSessionUserID();

		date_default_timezone_set('America/New_York');
		$date = date('Y-m-d');

		$this->setPOSTVariableToModel("owneremail");
		$this->model->setVariable("ownerid", $id);
		$this->model->setVariable("createddate", $date);
		$this->setPOSTVariableToModel("duedate");
		$this->setPOSTVariableToModel("message");
		$this->setPOSTVariableToModel("isdone");

		$Result = $this->model->Go();

		if($Result["isOK"]) {
			header("Location: index.php?page=tasks&action=show");
		} else {
			echo "Setting Error<br>" . $Result["Record"];
		}
	}

	//Show Task edit table
	public function edit()
	{
		$Result = $this->showTasks();
		$this->data["Record"] = \utility\table::TableEdit($Result["Record"]);
		$this->template = 'edit_task';
	}

	//UPDATE OR DELETE an editted Task
	public function save()
	{
		session_start();
		$USER_ID = \httprequest\request::getSessionUserID();

		$Maximum = $_POST["Maximum"];

//[id|14|36] => Save [id|14] => 36 [owneremail|14] => rwe@r.v [duedate|14] => 2017-12-13 [message|14] => bytybtb [isdone|14] => on 
		$MultiResults = array("Record" =>"", "isOK" => "TRUE");
		for($i = 0; $i < $Maximum ; $i++) {
			$SQLid =  $_POST["id|" . $i];
			$SQLmethod = $_POST["id|" . $i . "|" . $SQLid];
			if($SQLmethod != ""){
				if($SQLmethod == "Save") {
					$owneremail = $_POST["owneremail|" . $i];
					$duedate = $_POST["duedate|" . $i];
					$message = $_POST["message|" . $i];
					$createddate = $_POST["createddate|" . $i];
					$isdone = isset($_POST["isdone|" . $i]);
echo $SQLid . " " . $SQLmethod . " " . $USER_ID . " " . $owneremail . " " . $createddate . " " . $duedate . " " . $message . " " . $isdone;

					$this->model->setVariable("id", $SQLid);

					$this->model->setVariable("owneremail", $owneremail);

					$this->model->setVariable("ownerid", $USER_ID);

					$this->model->setVariable("createddate", $createddate);
					
					$this->model->setVariable("duedate", $duedate);

					$this->model->setVariable("message", $message);

					if($isdone){
						$this->model->setVariable("isdone", TRUE);
					} else {
						$this->model->setVariable("isdone", FALSE);
					}

					$OneResult = $this->model->Go();

					if(!$OneResult["isOK"])
						$MultiResults["isOK"] = FALSE;
					$Order = $i + 1;
					$MultiResults["Record"] .= "There is Error in No." . $Order . " . " . $OneResult["Record"];

				} else if($SQLmethod == "Delete") {
					$this->fastdelete($SQLid);
				}
			}
			$this->model->cleanThisObject();
		}


		if($MultiResults["isOK"]) {
			header("Location: index.php?page=tasks&action=show");
		} else {
			echo "Setting Error<br>" . $MultiResults["Record"];
		}
	}

    //this is the delete function.  You actually return the edit form and then there should be 2 forms on that.
    //One form is the todo and the other is just for the delete button

}
