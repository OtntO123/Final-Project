<?php	namespace httprequest;

class request
{
	//include html page template and data to display pages
	static public function includepage($page, $data = NULL) {
		$htmlpage = 'pages/' . $page . '.php';
		include $htmlpage;
	}

	//getCookie
	static public function getCookie($var) {
		$val = (isset($_COOKIE[$var])) ? $_COOKIE[$var] : '';
		return $val;
	}	
	
	//Translate bool to yes or none
	static public function BoolToStyle_yesORnone($bool) {
		if($bool) {
			return "yes";
		} else {
			return "none";
		}
	}

	//examine $_SESSION["UserID"] then Translate it into none or yes
	static public function UserIDSession() {
		$bool = "none";
		if(isset($_SESSION["UserID"])) {
			if($_SESSION["UserID"] != "") {
				$bool = "yes";
			}
		}
		return $bool;
	}

	//examine $_SESSION["UserID"] then Translate it into yes or none
	static public function ExcalmationUserIDSession() {
		$bool = "yes";
		if(isset($_SESSION["UserID"])) {
			if($_SESSION["UserID"] != "") {
				$bool = "none";
			}
		}
		return $bool;
	}

	//Get variable in $_SESSION
	static public function getSession($var) {
		$val = (isset($_SESSION[$var])) ? $_SESSION[$var] : '';
		return $val;
	}

	//Get session USERID
	static public function getSessionUserID() {
		$val = self::getSession("UserID");
		return $val;
	}

	//this gets the request method to make it easier to use
	static public function getRequestMethod() {
		$request_method = $_SERVER['REQUEST_METHOD'];
		return $request_method;
	}

	//this gets determines the page
	static public function getPage() {
	//this sets the default page for the app to index
		$page = 'homepage';

	//this checks if page is set
		if (!empty($_REQUEST['page'])) {
			$page = $_REQUEST['page'];
		}
		return $page;
	}

	//this gets the action out of the URL
	static public function getAction()
	{
		//this is a litte code to help the homepage handle post requests if needed
		$action = 'show';
		if(isset($_REQUEST["action"])) {
			$action = $_REQUEST["action"];
		}
        	return $action;
	}

}
