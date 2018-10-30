<?php
   session_start();
   unset($_SESSION["login"]);
   unset($_SESSION["userID"]);
   unset($_SESSION["userName"]);
   unset($_SESSION["password"]);
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
  </head>


   <h1 class="text-center">You have Logged Out</h1>

 <?php
   header('Refresh: 1; URL = index.php');
?>
