<?php namespace controllers;

final class todos extends controller {
  //each method in the controller is named an action.
    //to call the show function the url is index.php?page=task&action=show
	private static function show()
	{
		$id = http\request::getSessionUserID();
        	$Record = todos::ShowData($id);
		$ishave_task = !empty($Record);
		$isshow = http\request::BoolToStyle_yesORnone(!$ishave_task);
		$data["istask"] = $isshow;

		$noisshow = http\request::BoolToStyle_yesORnone($ishave_task);
		$data["!istask"] = $noisshow;

		echo utility\table::tablecontect($Record, "My Task");
        	self::getTemplate('show_task', $data);
	}

	private static function create()
	{
        	$inputlabel = array ("Owneremail", "Ownerid", "Createddate", "Duedate", "Message", "Isdone");
		$inputtype = array ("email", "number", "date", "date", "text", "text", "number");
		$inputname = array ("owneremail", "ownerid", "createddate", "duedate", "message", "isdone");
		$inputstr = $inputlabel;
		$rec = new todo();

		foreach ($inputname as $key => $val) {
			$recval = $rec->$val;
			$inputstr[$key] .= " <input type = \"$inputtype[$key]\" value = \"$recval\" name = \"$inputname[$key]\"> ";
		}

		$data["outputlabel"] = $inputstr;

		self::getTemplate('create_task', $data);

	}

    //this is the function to view edit record form
	private static function edit()
	{
        	$inputlabel = array ("Owneremail", "Ownerid", "Createddate", "Duedate", "Message", "Isdone");
		$inputtype = array ("email", "number", "date", "date", "text", "text", "number");
		$inputname = array ("owneremail", "ownerid", "createddate", "duedate", "message", "isdone");
		$inputstr = $inputlabel;
		$rec = todos::ShowData($_SESSION["UserID"]);
		unset($rec[0]->id);
		print_r($rec[0]);
		foreach ($inputname as $key => $val) {
			$recval = $rec[0]->$val;
			$inputstr[$key] .= "<input type = \"$inputtype[$key]\" value = \"$recval\" name = \"$inputname[$key]\">";
			if($val == "createddate" or $val =="duedate") {
				$inputstr[$key] = substr($inputstr[$key], 0, -1) . " readonly >";
			}
		}

		$data["outputlabel"] = $inputstr;

		self::getTemplate('edit_task', $data);

	}

    //this would be for the post for sending the task edit form
	private static function store()
	{
		
		$bool = todos::Createtask();
		if($bool) header("Location: index.php?page=tasks&action=show");
		

	}

	private static function save()
	{
		$bool = todos::Edittask();
		if($bool) header("Location: index.php?page=tasks&action=show");
	}

    //this is the delete function.  You actually return the edit form and then there should be 2 forms on that.
    //One form is the todo and the other is just for the delete button
	private static function delete() {
		$id = http\request::getSessionUserID();
		todos::SQLDelete($id);
		session_destroy();
		header("Location: index.php?page=tasks&action=show");
	}
}
