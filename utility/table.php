<?php	namespace utility;

class table {

	static public function tablecontect($tabl, $tablename = NULL) {	//display result within table function
		$str = "<div><table style='width:100%'><caption>" . $tablename . "</caption>";
		foreach($tabl as $i => $k) {	
			$str .= "<tr>";
			if ($k == $tabl[0]) {	//first line of type name
				foreach($k as $m => $n) {
					if($m != "id")
						$str .= "<th>$m</th>";
					
				}
				$str .= "</tr><tr>";
			}
			foreach($k as $j => $o) {	//split data
				if($j != "id") {
					if($o == "1") {
						$str .= "<td>Have Done</td>";
					} else if($o == "0") {
						$str .= "<td>Have Not Done</td>";
					} else {
						$str .= "<td>$o</td>";
					}
				}
			}
				$str .= "</tr>";
		}
		$str .= "</table></div>";
		return $str;	//return result to $html
	}

	static public function TableEdit($tabl, $tablename = NULL) {	//display result within table function
		$str = "<div><table style='width:100%'><caption>" . $tablename . "</caption>";
		$Maximum = 0;
		foreach($tabl as $i => $k) {	
			$str .= "<tr>";
			if ($i == 0) {	//first line of type name
				foreach($k as $m => $n) {
					if($m == "id")  {
						$str .= "<th>Opitions</th>";
					} else {
						$str .= "<th>$m</th>";
					}
				}
				$str .= "</tr><tr>";
			}

			foreach($k as $j => $o) {	//split data
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
		$str .= "<input type='hidden' name='Maximum' value='$Maximum'>";
		return $str;	//return result to $html
	}

	static private function getvalueNameSet($a, $b) {
		$valueNameSet = $a . "|" . $b;
		return $valueNameSet;
	}


	static public function EditTask($value, $label, $sequence) {
		$valueNameSet = $label . "|" . $sequence . "|" . $value;
		$str = "<select name='$valueNameSet'>
			<option value=''></option>
			<option value='Save'>Save</option>
			<option value='Delete'>Delete</option>
			</select><br>";
	
		$valueNameSet2 = self::getvalueNameSet($label, $sequence);
		$str .= "<input type='hidden' value='$value' name='$valueNameSet2'>";

		return $str;
	}


	static public function EditOwnerEmail($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$str = "<input type=text name='$valueNameSet' size=15  value='$value'>";
		return $str;
	}

	static public function EditDueDate($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$thisdate = substr($value, 0, -9);
		$str = "<input type=date name='$valueNameSet' value='$thisdate'>";
		return $str;
	}

	static public function EditCreatedDate($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$thisdate = substr($value, 0, -9);
		$str = "$thisdate<input type='hidden' name='$valueNameSet' value='$thisdate'>";
		return $str;
	}


	static public function EditMessage($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$str = "<input type=text name='$valueNameSet' size=12  value='$value' style='width:auto>'";
		return $str;
	}

	static public function EditIsDone($value, $label, $sequence) {
		$valueNameSet = self::getvalueNameSet($label, $sequence);
		$checked = "";
		if($value)
			$checked = "checked";
		$str = "<input type=checkbox name='$valueNameSet' $checked>";
		return $str;
	}
}


/*	print_r($tabl);
	
	echo '<form method="POST" action="result.php">
<table>
    <tr>
        <td><b>Day of Week</b></td>
        <td><b>Week 1 Hours</b></td>
        <td><b>Week 2 Hours</b></td>
    </tr>
    <tr>
        <td>Monday</td>
        <td><input type="text" name="Monday" size="3"  value="" onkeypress="return inputLimiter(event,"Numbers")"> <input type="checkbox" tabindex="-1" name="Stime1">Sick?<input type="checkbox" tabindex="-1" name="Vac1">Vacation?</td>
        <td><input type="text" name="Monday2" size="3" maxlength="4" value="" onkeypress="return inputLimiter(event,"Numbers")"> <input type="checkbox" tabindex="-1" name="Stime2">Sick?<input type="checkbox" tabindex="-1" name="Vac2">Vacation?</td>
    </tr>
</table>
<input type="submit" value="submit">
</form>
</html>';
*/
