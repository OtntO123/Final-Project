<?php namespace controllers;

final class homepage extends controller {

	public function show() {
		$templateData[] = \httprequest\request::getCookie("Username");
		session_start();
		$templateData["!issetSessionUserID"] = \httprequest\request::ExcalmationUserIDSession();
		$templateData["issetSessionUserID"] = \httprequest\request::UserIDSession();
		$templateData["UserID"] = \httprequest\request::getSessionUserID();
		$this->template = 'homepage';
		$this->data = $templateData;
		//self::getTemplate('homepage', $templateData);
	}

}
