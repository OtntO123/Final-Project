<?php if(get_class() == null) {header('Location: /~kz233/mvc-mvc2/index.php');} ?>
<!doctype html>

<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Task system</title>
	<meta name='description' content='Sql Active Record'>
	<meta name='author' content='Kan'>
	<link rel='stylesheet' href='css/styles.css?v=1.0'>
</head>
<body>
	<form action="index.php" method="post" enctype="multipart/form-data">
		<h1 style="color:LightGreen;">HI THERE!: </h1>

		<p>Please Login:</p>

		<p>
		Username:
		<input type="text" value="
<?php  echo (isset($_COOKIE['Username'])) ? $_COOKIE['Username'] : '';  ?>
					" name="Username">

		Password:
		<input type="password" value="" name="Password">
		</p>

		<p>Create account:</p>

		<p>
<?php
foreach ($inputstr as $key => $str)
	echo $str . "<br>";
?>
		</p>

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
		<input type="reset">
	</form>
</body>
</html>
