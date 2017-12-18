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
	<h1>My Tasks</h1>
			
	<?php echo $data["Record"]?>

	<form action="index.php" method="get" enctype="multipart/form-data" style="display:yes">

		<br>
		<input type="submit" value="Create a task" name="submit">
		<input type="hidden" name="page" value="tasks">
		<input type="hidden" name="action" value="create">
		
	</form>

	<form action="index.php" method="get" enctype="multipart/form-data" style="display:<?php echo $data['!istask']?>">
		<input type="hidden" name="page" value="tasks">
		<input type="hidden" name="action" value="edit">
		<input type="submit" value="Edit My Task" name="submit">
	</form>
<br>
	<form action="index.php" method="get" enctype="multipart/form-data" style="display:yes">
		<input type="hidden" name="page" value="accounts">
		<input type="hidden" name="action" value="show">
		<input type="submit" value="Back To My Account" name="submit">
	</form>
</body>
</html>
