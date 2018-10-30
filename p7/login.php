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
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Signin Page</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--<script src="../../assets/js/ie-emulation-modes-warning.js"></script>-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        span.error{
            color: red;
        }
        h2{
            color: orange;
        }
        #signup{
            color: whitesmoke;
        }
        .text-warning{
            color: orange;
        }

    </style>
  </head>
  <body>


 <!------------allow user to register account without duplicated------------->
<?php
      if (isset($_SESSION["login"]) && $_SESSION["login"] == 1) {

		echo '<div class="alert alert-success" role="alert">You are already Loged In! Flying to Home Page...</div>';
		header('Refresh: 1; URL = index.php');
	}
    else{
        $error = $nameErr = $pwdErr = "";
        function mysql_entities_fix_string($db, $string)
        {
            return htmlentities(mysql_fix_string($db, $string));
        }

        function mysql_fix_string($db, $string)
        {
            if (get_magic_quotes_gpc()) $string = stripslashes($string);
            return $db->real_escape_string($string);
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
                if (empty($_POST["PHP_AUTH_USER"])) {$nameErr = "username is required";}
                else{   $un_temp = mysql_entities_fix_string($db, $_POST["PHP_AUTH_USER"]);}

                if (empty($_POST["PHP_AUTH_PW"])) {$pwdErr = "password is required";}
                else{   $pw_temp = mysql_entities_fix_string($db, $_POST["PHP_AUTH_PW"]);}

                if($un_temp & $pw_temp){

                    $query = "SELECT * FROM users WHERE username='$un_temp'";
                    $result = $db->query($query) or die($db->error);

                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $result->close();

                        $salt1 = "qm&h*";
                        $salt2 = "pg!@";
                        $token = hash('ripemd128', "$salt1$pw_temp$salt2");

                        if ($token == $row["password"])//if pwd and username matched.
                        {
                            echo '<div class="alert alert-success" role="alert">Login Success! Flying to Home Page...</div>';
                            $_SESSION["login"]= 1;
                            $_SESSION["userID"]= $row["id"];
                            $_SESSION["userName"]= $row["username"];
                            header('Refresh: 1; URL = index.php');

                        }
                        else{ $error = "Invalid username/password combination";}
	               }
	               else{ $error = "Invalid username/password combination";}
                }

        }
    }

?>

<!-- This is my sign in page -->
       <div class="container">
        <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h2 class="form-signin-heading">Please sign in</h2>
        <span class="form-inline, text-center, text-warning" id="error"> <?php echo $error;?></span>
        <label class="sr-only">User Name</label>
        <input type="username" class="form-control"  name="PHP_AUTH_USER" placeholder="User Name" required autofocus>
        <span class="form-inline, error"> <?php echo $nameErr;?></span>
        <label for="inputPassword" class="sr-only">Password:</label>
        <input type="password" id="inputPassword" class="form-control" name="PHP_AUTH_PW" placeholder="Password" required>
        <span class="form-inline, error"> <?php echo $pwdErr;?></span>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-default btn-block" type="submit" name="Login" value = "Login">Sign in</button>
        <h6 class="text-center"><a id ="signup" href="signup.php">Sign up</a></h6>
        </form>
      </div>
<?php  $db->close(); ?>
  </body>
</html>
