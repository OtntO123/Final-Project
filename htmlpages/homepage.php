<!doctype html>

<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Sql Active Record</title>
	<meta name='description' content='Sql Active Record'>
	<meta name='author' content='Kan'>
	<link rel='stylesheet' href='css/styles.css?v=1.0'>
</head>
<body>



	<form action="index.php" method="post" enctype="multipart/form-data">
		<h1 style="color:LightGreen;">Select SQL Code: </h1>
		<p>Username:
		<input type="text" value=
<?php  echo (isset($_COOKIE["Username"])) ? $_COOKIE["Username"] : 'Please Enter Username Here';  ?>
					name="Username">
		Password:
		<input type="text" value=
<?php  echo (isset($_COOKIE["Password"])) ? $_COOKIE["Password"] : 'Please Enter Password Here';  ?>
					name="Password"></p>

		<p>Email Address:
		<input type="text" value="Fish@njit.edu" name="email">
		First Name:
		<input type="text" value="Fish" name="fname">
		Last Name:
		<input type="text" value="Jelly" name="lname"></br>
		Gender:
		<input type="text" value="Male" name="gender">
		Phone Number:
		<input type="text" value="0000000000" name="phone"></br>
		Password:
		<input type="text" value="1111" name="password">
		Birthday:
		<input type="text" value="2000-01-01" name="birthday"></p>


		<select name="databasename">
		<option value="accounts">accounts</option>
		<option value="todos">todos</option>
		</select>

		<select name="collection">
<?php
	echo $formstring;
?>
		</select>

		<input type="submit" value="Run" name="submit">
	</form>
<?php
	echo $tablestring;
?>
</body>
</html>
