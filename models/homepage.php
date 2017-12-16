<?php	namespace models;

final class homepage extends model{
	protected function setAllObject() {
		$Allobject = get_object_vars($this);
		unset($Allobject["Allobject"]);
		unset($Allobject["Result"]);
		$this->Allobject = $Allobject;
	}
}
