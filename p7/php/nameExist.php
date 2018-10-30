<?php
require_once "db_connect.php";

$q = $_REQUEST["q"];

$hint = "";

// lookup all hints from array if $q is different from "" 
if ($q !== "") {
    //$q = strtolower($q);
    //$len=strlen($q);
    
    $sqlcheck = 'SELECT * FROM `users` WHERE `username` = \''.$q.'\';';
    $sqlcon = $db->query($sqlcheck) or die('error getting data!');
    if($sqlcon->num_rows > 0)
    {
        $hint = "account existed!";
    }
}

// Output "no suggestion" if no hint was found or output correct values 
 echo $hint === "" ? "good!" : $hint;
?> 






