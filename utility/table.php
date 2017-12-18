<?php	namespace utility;

//Receive SQL returned array to generate html5 table code
class table {

	//Receive SQL returned array to generate html5 table code
	static public function tablecontect($tabl, $tablename = NULL) {
		$str = "<div><table style='width:100%'><caption>" . $tablename . "</caption>";
		//Split data into column
		foreach($tabl as $i => $k) {	
			$str .= "<tr>";

			//Set Labels of table in first line
			if ($k == $tabl[0]) {
				foreach($k as $m => $n) {
					if($m != "id")
						$str .= "<th>$m</th>";
					
				}
				$str .= "</tr><tr>";
			}

			//Set remaining data
			foreach($k as $j => $o) {
				if($j != "id") {
					//Translate boolean of isdone into language
					if($j == "isdone") {
						if($o == "1") {
							$str .= "<td>Have Done</td>";
						} else if($o == "0") {
							$str .= "<td>Have Not Done</td>";
						} 

					//Cut the end of 9 character of date, which is time
					} else if($j == "createddate" or $j == "duedate") {
						$str .= "<td>" . substr($o, 0, -9) . "</td>";

					} else {
							$str .= "<td>$o</td>";
					}

				}
			}
				$str .= "</tr>";
		}
		$str .= "</table></div>";
		//return html5 table code
		return $str;
	}

	//Receive SQL returned array to generate tasks-editing table code
	static public function TableEdit($tabl, $tablename = NULL) {

		$str = "<div><table style='width:100%'><caption>" . $tablename . "</caption>";
		$Maximum = 0;
		foreach($tabl as $i => $k) {	
			$str .= "<tr>";
			//Set Labels of table in first line
			if ($i == 0) {
				foreach($k as $m => $n) {
					//change label name id into opition
					if($m == "id")  {
						$str .= "<th>Opitions</th>";
					} else {
						$str .= "<th>$m</th>";
					}
				}
				$str .= "</tr><tr>";
			}

			//Set remaining data
			foreach($k as $j => $o) {
				//Set id, owneremail, createddate, duedate, message, isdone editing table seperately
				switch ($j) {
					case "id":
						$str .= "<td>";
						$str .= self::EditTask($o, $j, $i);
						$str .= "</td>";
						break;
					case "owneremail":
						$str .= "<td>";
						$str .= self::EditOwnerEmail($o, $j, $i);
						$str .= "</td>";
						break;
					case "createddate":
						$str .= "<td>";
						$str .= self::EditCreatedDate($o, $j, $i);
						$str .= "</td>";
						break;
					case "duedate":
						$str .= "<td>";
						$str .= self::EditDueDate($o, $j, $i);
						$str .= "</td>";
						break;
					case "message":
						$str .= "<td>";
						$str .= self::EditMessage($o, $j, $i);
						$str .= "</td>";
						break;
					case "isdone":
						$str .= "<td>";
						$str .= self::EditIsDone($o, $j, $i);
						$str .= "</td>";
						break;
				
					default:
						$str .= "<td>$o</td>";
				}
			}
			$str .= "</tr>";
			$Maximum++;
		}
		$str .= "</table></div>";
		//post out the maximum of task array
		$str .= "<input type='hidden' name='Maximum' value='$Maximum'>";
		return $str;	//return result to $html
	}

	//set specific variable name for each input field
	static private function getvalueNameSet($a, $b) {
		$valueNameSet = $a . "|" . $b;
		return $valueNameSet;
	}

	//set edit input field
	static public function EditTask($value, $label, $sequence) {
		$valueNameSet = $label . "|" . $sequence . "|" . $value;
		$str = "<select name='$valueNameSet'>
			<option value=''></option>
			<option value='Save'>Save</option>
			<option value='Delete'>Delete</option>
			</select><br>";

		//post out the id in sql in hidden
		$valueNameSet2 = self::getvalueNameSet($label, $sequence);
		$str .= "<input type='hidden' value='$value' name='$valueNameSet2'>";

		return $str;
	}

	//set OwnerEmail input field
	static public function EditOwnerEmail($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$str = "<input type=text name='$valueNameSet' size=15  value='$value'>";
		return $str;
	}

	//set DueDate input field
	static public function EditDueDate($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$thisdate = substr($value, 0, -9);
		$str = "<input type=date name='$valueNameSet' value='$thisdate'>";
		return $str;
	}

	//set CreatedDate input field
	static public function EditCreatedDate($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$thisdate = substr($value, 0, -9);

		//post out the createddate in sql in hidden
		$str = "$thisdate<input type='hidden' name='$valueNameSet' value='$thisdate'>";
		return $str;
	}

	//set Message input field
	static public function EditMessage($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$str = "<input type=text name='$valueNameSet' size=12  value='$value' style='width:auto>'";
		return $str;
	}

	//set IsDone input field
	static public function EditIsDone($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$checked = "";

		//set checkbox value
		if($value)
			$checked = "checked";
		$str = "<input type=checkbox name='$valueNameSet' $checked>";
		return $str;
	}
}
