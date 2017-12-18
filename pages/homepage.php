<?php if(get_class() == null) {header('Location: /~kz233/mvc-mvc2/index.php');} ?>
<!doctype html>

<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Task system</title>
	<meta name='description' content='Sql Active Record'>
	<meta name='author' content='Kan'>
	<link rel='stylesheet' href='utility/styles.css?v=1.0'>
</head>
<body>
	<form action="index.php?login" method="post" enctype="multipart/form-data" style="display:<?php echo $data['!issetSessionUserID']?>">
		<h1 style="color:LightGreen;">HI THERE</h1>

		<p>Please Login - Username:
		<input type="text" value="" name="username">

		Password:
		<input type="password" value="" name="password">
		
		<input type="submit" value="Login" name="submit">
		<input type="hidden" name="page" value="accounts">
		<input type="hidden" name="action" value="login">
		</p>
	</form>	

	<form action="index.php?action=create" method="get" enctype="multipart/form-data" style="display:<?php echo $data['!issetSessionUserID']?>">
		<input type="hidden" name="page" value="accounts">
		<input type="hidden" name="action" value="register">
		<input type="submit" value="Do not have an account? Create one!" name="submit">
	</form>


	<form action="index.php" method="get" enctype="multipart/form-data" style="display:<?php echo $data['issetSessionUserID']?>">
		<p>Login successful. Your UserID is <?php echo $data['UserID']?>
		<input type="hidden" name="page" value="accounts">
		<input type="hidden" name="action" value="logout">
		<input type="submit" value="UnLog" name="submit">
		</p><hr>
	</form>

	<form action="index.php" method="get" enctype="multipart/form-data" style="display:<?php echo $data['issetSessionUserID']?>">
		<p>
		<input type="hidden" name="page" value="accounts">
		<input type="hidden" name="action" value="edit">
		<input type="submit" value="View And Revise my account!" name="submit">
		</p><hr>
	</form>

	<form action="index.php" method="get" enctype="multipart/form-data" style="display:<?php echo $data['issetSessionUserID']?>">
		<p>
		<input type="hidden" name="page" value="tasks">
		<input type="hidden" name="action" value="show">
		<input type="submit" value="Show my task!" name="submit">
		</p>
	</form>



</body>
</html>
