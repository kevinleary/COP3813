<?php
require_once "php/db_connect.php";
require_once "php/functions.php";


if($db->query('select 1 from `WALL` LIMIT 1'))
{
}
else{
     $query = 'CREATE TABLE `WALL` (' . PHP_EOL
            . '  `USER_USERNAME` VARCHAR(15) NOT NULL,' . PHP_EOL
            . '  `STATUS_TEXT` VARCHAR(140) NOT NULL,' . PHP_EOL
            . '  `STATUS_TITLE` VARCHAR(140) NOT NULL,' . PHP_EOL
            . '  `IMAGE_NAME` VARCHAR(50) NOT NULL,' . PHP_EOL
            . '  `TIME_STAMP` VARCHAR(50) NOT NULL,' . PHP_EOL
            . '  `IMAGE_FILTER` VARCHAR(50) NOT NULL,' . PHP_EOL
            . '  PRIMARY KEY (`TIME_STAMP`)' . PHP_EOL
            . ')';

    if($db->query($query)) {
        //echo '        <div class="alert alert-success">Table creation successful.</div>' . PHP_EOL;
    } else {
        echo '        <div class="alert alert-danger">Table creation failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
    exit(); // Prevents the rest of the file from running
    }
}

if(isset($_POST['name']) && isset($_POST['title']) && isset($_POST['text']))
{
    $name = sanitizeString($db, $_POST['name']);
    $title = sanitizeString($db, $_POST['title']);
    $text = sanitizeString($db, $_POST['text']);

    $filterStr = '';
	if(isset($_POST['filter']))
	{
		$filterName = sanitizeString($db, $_POST['filter']);
		//myNostalgia grayscale lomo
		if($filterName == 'myNostalgia') {$filterStr = 'saturate(40%) grayscale(100%) contrast(45%) sepia(100%)';}
		if($filterName == 'grayscale') {$filterStr = 'grayscale(100%)';}
	}


    $time = $_SERVER['REQUEST_TIME'];
	$file_name = $time . '.jpg';

    if ($_FILES)
    {
        $tmp_name = $_FILES['upload']['name'];
        $dstFolder = 'users';
        move_uploaded_file($_FILES['upload']['tmp_name'], $dstFolder . DIRECTORY_SEPARATOR . $file_name);
        //echo "Uploaded image '$file_name'<br /><img src='$dstFolder/$file_name'/>";
    }

    SavePostToDB($db, $name, $title, $text, $time, $file_name, $filterStr);
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

	<title>Image sharing wall</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="css/styles.css">

	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<h4 class="navbar-brand">Wall</h4>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="uploader.php">Upload</a></li>
					<li ><a href="logout.php">Logout</a></li>
				</ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php echo getPostcards($db); ?>
    </div>
</body>



<?php $db->close(); ?>
