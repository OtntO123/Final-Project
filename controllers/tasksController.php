<?php
/**
 * Created by PhpStorm.
 * User: kwilliams
 * Date: 11/27/17
 * Time: 5:32 PM
**/


//each page extends controller and the index.php?page=tasks causes the controller to be called
class tasksController extends http\controller
{

    //each method in the controller is named an action.
    //to call the show function the url is index.php?page=task&action=show
	public static function show()
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

    //to call the show function the url is index.php?page=task&action=list_task

    public static function all()
    {
        $records = todos::findAll();
	//print_r($records); 
	//print_r($_REQUEST);
	//foreach($records
	
        
	if(!isset($_SESSION['userID'])) {
		echo "No login";
		exit();
	}
        $userID = $_SESSION['userID'];

        $records = todos::findTasksbyID($userID);
	if($records) {
		self::getTemplate('all_tasks', $records);
	} else {
        	self::create();
	}

    }
    //to call the show function the url is called with a post to: index.php?page=task&action=create
    //this is a function to create new tasks

    //you should check the notes on the project posted in moodle for how to use active record here

	public static function create()
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
	public static function edit()
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
	public static function store()
	{
		
		$bool = todos::Createtask();
		if($bool) header("Location: index.php?page=tasks&action=show");
		

	}

	public static function save()
	{
		$bool = todos::Edittask();
		if($bool) header("Location: index.php?page=tasks&action=show");
	}

    //this is the delete function.  You actually return the edit form and then there should be 2 forms on that.
    //One form is the todo and the other is just for the delete button
	public static function delete() {
		$id = http\request::getSessionUserID();
		todos::SQLDelete($id);
		session_destroy();
		header("Location: index.php?page=tasks&action=show");
	}

}
