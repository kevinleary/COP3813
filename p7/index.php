<?php
session_start();
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
    <title>Image Sharing Site</title>
    
   <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
   <link href="css/signin.css" rel="stylesheet">
    
  </head>
  <body>
  
   
<?php 
    if(isset($_SESSION["login"]))
    {
        if (isset($_SESSION["login"]) && $_SESSION["login"] == 1)
        {
            
            require_once 'wall.php';
        }
    }
    else
    {
        require_once 'login.php';
    }
						
?>
   
    </body>