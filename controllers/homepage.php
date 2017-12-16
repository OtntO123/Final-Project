<?php namespace controllers;

final class homepage extends controller {

	public function show() {

		if(isset($_GET["submit"]) == "UnLog") {
			$_SESSION["UserID"] = NULL;
			header('Location: index.php');
		}

		$templateData[] = \httprequest\request::getCookie("Username");
		$templateData["!issetSessionUserID"] = \httprequest\request::ExcalmationUserIDSession();
		$templateData["issetSessionUserID"] = \httprequest\request::UserIDSession();
		$templateData["UserID"] = \httprequest\request::getSessionUserID();
		$this->template = 'homepage';
		$this->data = $templateData;
		//self::getTemplate('homepage', $templateData);
	}
}
