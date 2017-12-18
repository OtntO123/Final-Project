<?php namespace controllers;

//Homepage controller, set as default when start this website.
final class homepage extends controller {

	public function show() {
		$templateData[] = "";
		session_start();

		//check whether there is UserID in session and set which form displayed
		$templateData["!issetSessionUserID"] = \httprequest\request::ExcalmationUserIDSession();
		$templateData["issetSessionUserID"] = \httprequest\request::UserIDSession();
		
		//Show your USERID
		$templateData["UserID"] = \httprequest\request::getSessionUserID();
		$this->template = 'homepage';
		$this->data = $templateData;
	}
}
