<?php
require_once './php/db_connect.php';
$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($db->connect_error) die($db->connect_error);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DB Table Test</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <style>
        span.error{
            color: red;
        }
    </style>
 <script>
        function showHint(str) 
        {
            if (str.length == 0) { 
                document.getElementById("txtHint").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() 
                {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                    }
                };
                xmlhttp.open("GET", "php/nameExist.php?q=" + str, true);
                xmlhttp.send();
            }
        }
</script>
  </head>
  <body>
    <div class="container">
      <div class="page-header">
        <h1> An image sharing site </h1>
      </div>
    </div>
<!-------------------SETUP TABLE "users"------------------------------>
    <div class="container"> 
<?php
//check table existance in database.        
if($db->query('select 1 from `users` LIMIT 1'))
{
}
else{//if not exist,
    // Create table with three columns: name, gender and count
    $query = 'CREATE TABLE `users` (' . PHP_EOL
            . '  `id` int(11) NOT NULL AUTO_INCREMENT,' . PHP_EOL
            . '  `username` VARCHAR(32) NOT NULL UNIQUE,' . PHP_EOL
            . '  `password` VARCHAR(32) NOT NULL,' . PHP_EOL
            . '  PRIMARY KEY (`id`)' . PHP_EOL
            . ') ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
    
    if($db->query($query)) {
        //echo '        <div class="alert alert-success">Table creation successful.</div>' . PHP_EOL;
    } else {
        echo '        <div class="alert alert-danger">Table creation failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
    exit(); // Prevents the rest of the file from running
    }
}
?>

</div>
        
    <div class="container">
          
<!------------allow user to register account without duplicated------------->
<?php
        $nameErr = $pwdErr = $pwdErr2 = "";
        function unifyData($data, &$error){
            if(!preg_match("/^[a-zA-Z0-9]*$/",$data))
            {
                $error = "Only letters and numbers allowed!";
                $data = NULL;
            }
            return $data;
        }
        function add_user($db, $un, $pw)
        {
            $query  = "INSERT INTO users VALUES(NULL,'$un', '$pw')";
            if($result = $db->query($query)){
                echo '<div class="alert alert-success" role="alert">Account Created! Back to Login Page...</div>';
                header('Refresh: 1; URL = index.php');
            }
            else{ echo '<div class="alert alert-danger" role="alert">Account creation failed!</div>';}
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
                if (empty($_POST["username"])) {$nameErr = "username is required";}
                else{   $username = unifyData($_POST['username'], $nameErr);}
                
                if (empty($_POST["password"])) {$pwdErr = "password is required";}
                else{$password = unifyData($_POST['password'], $pwdErr);}
                
                if (empty($_POST["re-password"])) {$pwdErr2 = "password is required";}
                else{$repassword = $_POST['re-password'];}
            
                if($password !== $repassword)
                {
                    $pwdErr2 = "Password entered must be identical!";
                }
                else{
                    if($username & $password){
                        $salt1    = "qm&h*";
                        $salt2    = "pg!@";

                        $token    = hash('ripemd128', "$salt1$password$salt2");

                        $sqlcheck = 'SELECT * FROM `users` WHERE `username` = \''.$username.'\';';
                        $sqlcon = $db->query($sqlcheck) or die('error getting data!');
                        if($sqlcon->num_rows > 0)
                        {
                            $nameErr = "account existed!";
                        }
                        else
                        {
                            add_user($db, $username, $token);


                        }
                    }
                }
            
        }
?>                             
                                              
                                                                                  
<!---------------------Register Account Form Setup-------------------------->
        <h1 class="text-center"><b>Account sign Up</b></h1>
        <form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">  
            <label class="col-sm-2 col-sm-offset-2 control-label">User Name:</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" name="username" placeholder="username" onkeyup="showHint(this.value)">
            </div>
            <span id="txtHint"></span>
            <span class="form-inline, error"> <?php echo $nameErr;?></span>
         </div>
       
         <div class="form-group">   
            <label class="col-sm-2 col-sm-offset-2 control-label">Password:</label>
            <div class="col-sm-5">
                <input class="form-control" type="password" name="password" placeholder="Password">
            </div>
            <span class="form-inline, error"> <?php echo $pwdErr;?></span>
         </div>
         <div class="form-group">   
            <label class="col-sm-2 col-sm-offset-2 control-label">Re-Enter Password:</label>
            <div class="col-sm-5">
                <input class="form-control" type="password" name="re-password" placeholder="re-Password">
            </div>
            <span class="form-inline, error"> <?php echo $pwdErr2;?></span>
         </div>
         <div class="form-group">
             <div class="col-sm-offset-4 col-sm-10">
              <button type="submit" class="btn btn-default" name="Submit" value = "Submit">Submit</button>
            </div>
         </div>
        </form>  
    
<!---------------------Display Table "users"----------------------------->
    <div class="container">
      <div id="step-three" class="well">
        <h3>user Table <small>Displaying Poll from input</small></h3>
          <div class="table-responsive">
<?php
// Get the rows from the table
    $sqlget = 'SELECT * FROM `users`;';
    $sqldata = $db->query($sqlget) or die('error getting data!');
    
    echo '<table class="table">';
    echo '<thead><tr><th>username</th><th>password</th></tr></thead>';
    
    if($sqldata->num_rows > 0) {
    //echo '<div class="alert alert-success">' . PHP_EOL;
            echo '<tbody>';
        while($row = $sqldata->fetch_assoc()) {
            echo '<tr><td>';
            echo $row["id"];
            echo '</td><td>';
            echo $row["username"];
            echo '</td><td>';
            echo $row["password"];
            echo '</td></tr>';
        }
            echo '</tbody>';
    } else {
        echo '<div class="alert alert-success">No Results</div>' . PHP_EOL;
    }
    echo '</table>';
    
?>   
            </div>
          </div>          
      </div>
      </div>
<?php $db->close(); ?>
  </body> 
</html>