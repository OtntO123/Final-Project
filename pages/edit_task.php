<?php if(get_class() == null) {header('Location: /~kz233/final/index.php');} ?>
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

	<form action="index.php" method="post" enctype="multipart/form-data">
	<?php echo $data["Record"]?>
		<p>
		<input type="submit" value="Save Setting" name="submit">
		<input type="hidden" name="page" value="tasks">
		<input type="hidden" name="action" value="edit">
		</p>
	</form>
</body>
</html>
