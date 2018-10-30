<?php
//session_start();
require_once './php/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Wall</title>
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="css/styles.css">
	
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    

  </head>
  <body>

<?php 
      
      if(isset($_SESSION["login"]) && $_SESSION["login"] == 1)
        {
            require_once 'uploader.php';
            
        }else{
          echo 'please login first';
          header('Refresh: 1; URL = login.php');
      }
    
?>  
      
  </body>

</html>