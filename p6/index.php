<?php
require_once './php/db_connect.php';
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
        h1.text-center{
            color: darksalmon;
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
                xmlhttp.open("GET", "gethint.php?q=" + str, true);
                xmlhttp.send();
            }
        }
</script>


    <div class="container">
        <div class="page-header">
              <h1>MilesStone3</h1>
          </div>


<!-----------------------SETUP TABLE "POLL" ----------------------------->
<?php //check table existance;
if($db->query('select 1 from `POLL` LIMIT 1'))
{
    //echo '<div class="alert alert-success">Table POLL exists.</div>' . PHP_EOL;
}
else{   //if not exist,
    // Create table with three columns: name, gender and count
    $createStmt = 'CREATE TABLE `POLL` (' . PHP_EOL
            . '  `id` int(11) NOT NULL AUTO_INCREMENT,' . PHP_EOL
            . '  `name` varchar(50) NOT NULL,' . PHP_EOL
            . '  `gender` varchar(10) NOT NULL,' . PHP_EOL
            . '  `popularity` varchar(20) NOT NULL,' . PHP_EOL
            . '  PRIMARY KEY (`id`)' . PHP_EOL
            . ') ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

    if($db->query($createStmt)) {
        echo '        <div class="alert alert-success">Table creation successful.</div>' . PHP_EOL;
    } else {
        echo '        <div class="alert alert-danger">Table creation failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
    exit(); // Prevents the rest of the file from running
    }
}
?>


<!-----------add User input data to Table "POLL" witout duplicate------------
-------------and allow user to input only part of the form such -------------
-------------girl's name but not boy's name--------------------------------->
<?php
    $bnameErr = "";
    $gnameErr = "";
    //filter and reorganize user input data.
    function unifyData($data, &$nameErr){
        if(!preg_match("/^[a-zA-Z]*$/",$data))
        {
            $nameErr = "Only letters allowed!";
            $data = NULL;
        }
        else{
            $data = ucfirst(strtolower($data));
        }
        return $data;
    }
    //trigger if and only if clicked 'Vote'.
    if(!empty($_POST)){

        if(!$_POST['Vote!']){
            echo "please fill out the form";
        }else{

            $boyName = unifyData($_POST['boyname'], $bnameErr);
            $girlName = unifyData($_POST['girlname'], $gnameErr);

            if($boyName)
            {
                $sqlget = 'SELECT * FROM `POLL` WHERE `name` = \''.$boyName.'\' AND `gender` = \'M\';';
                $sqldata = $db->query($sqlget) or die('error getting data!');
                if($sqldata->num_rows > 0)
                {
                    while($row = $sqldata->fetch_assoc())
                    {
                        $id = $row["id"];
                        $count = $row["popularity"] +1;
                    }
                    $sqlUpdate = 'UPDATE `POLL` SET `popularity`=\''.$count.'\' WHERE `id` = \''.$id.'\';';
                    $db->query($sqlUpdate);
                }
                else
                {
                    $insertStmt2 = 'INSERT INTO `POLL` (`id`,`name`,`gender`,`popularity`)' . PHP_EOL.'             VALUES (NULL, \''.$boyName.'\', \'M\', \'1\');';
                    if($db->query($insertStmt2)){
                        // echo '        <div class="alert alert-success">Values inserted successfully.</div>' . PHP_EOL;
                    }
                    else
                    {
                        echo '        <div class="alert alert-danger">Value insertion failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
                        exit();
                    }
                }
            }else{}
            if($girlName){
                $sqlget = 'SELECT * FROM `POLL` WHERE `name` = \''.$girlName.'\' AND `gender` = \'F\';';
                $sqldata = $db->query($sqlget) or die('error getting data!');
                if($sqldata->num_rows > 0)
                {
                    while($row = $sqldata->fetch_assoc())
                    {
                        $id = $row["id"];
                        $count = $row["popularity"] +1;
                    }
                    $sqlUpdate = 'UPDATE `POLL` SET `popularity`=\''.$count.'\' WHERE `id` = \''.$id.'\';';
                    $db->query($sqlUpdate);
                }
                else
                {
                    $insertStmt2 = 'INSERT INTO `POLL` (`id`,`name`,`gender`,`popularity`)' . PHP_EOL.'VALUES(NULL, \''.$girlName.'\', \'F\',\'1\');';
                    if($db->query($insertStmt2)){
                        // echo '        <div class="alert alert-success">Values inserted successfully.</div>' . PHP_EOL;
                    } else {
                        echo '        <div class="alert alert-danger">Value insertion failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
                        exit();
                    }
                }
            }
        }
      }
?>


<!-----------------------User Input Form Setup------------------------------>
    <h1 class="text-center"><b>Vote your favorite baby names!</b></h1>
    <form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
     <p class="col-sm-10 col-sm-offset-4">Suggestions: <span id="txtHint"></span></p>
     <div class="form-group">
        <label class="col-sm-2 col-sm-offset-2 control-label">Boy Name:</label>
        <div class="col-sm-5">
            <input class="form-control" type="text" name="boyname" value="" onkeyup="showHint(this.value)" placeholder="Bob">
        </div>
        <span class="form-inline, error"> <?php echo $bnameErr;?></span>
     </div>

     <div class="form-group">
        <label class="col-sm-2 col-sm-offset-2 control-label">Girl Name:</label>
        <div class="col-sm-5">
            <input class="form-control" type="text" name="girlname" value="" onkeyup="showHint(this.value)" placeholder="Angela">
        </div>
        <span class="form-inline, error"> <?php echo $gnameErr;?></span>
     </div>

     <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
              <button type="submit" class="btn btn-warning" name="Vote!" value = "Vote!">VOTE</button>
            </div>
     </div>
    </form>


<!-----Display the most popular baby name by data in 'POLL'---------------->
    <div id="step-four" class="well">
        <h3>Ranking Table <small>Displaying the most popular baby name</small></h3>
          <div class="table-responsive">
<?php
// Get the rows from the table
    $boy_des_query = 'SELECT * FROM `POLL` WHERE `gender` = \'M\' ORDER BY `popularity` DESC;';
    $boy_result = $db->query($boy_des_query) or die('error getting data!');
    $girl_des_query = 'SELECT * FROM `POLL` WHERE `gender` = \'F\' ORDER BY `popularity` DESC;';
    $girl_result = $db->query($girl_des_query) or die('error getting data!');

    echo '<table class="table table-striped">';
    echo '<thead><tr><th class="success">Rank(#)</th><th class="info">Male name</th><th class="danger">Female name</th></tr></thead>';
    echo '<tbody>';
    if($boy_result->num_rows > 0 || $girl_result->num_rows > 0)
    {

        for($rank = 1; $rank<11; $rank++)
        {
                echo '<tr><td>'.$rank.'</td>';

            if($boy = $boy_result->fetch_assoc())
            {
                echo '<td>'.$boy["name"].'</td>';
            }
            else{
                echo '<td></td>';
            }
            if($girl = $girl_result->fetch_assoc())
            {
                echo '<td>'.$girl["name"].'</td>';
            }
            else{
                echo '<td></td>';
            }
        }
    } else {
        echo '<div class="alert alert-success">No Results</div>' . PHP_EOL;
    }
    echo '</tbody>';
    echo '</table>';

?>
            </div>
          </div>
    </div>
    <br><br>


<!---------------------Display Table "POLL"----------------------------->
    <div class="container">
      <div id="step-three" class="well">
        <h3>POLL Table <small>Displaying Poll from input</small></h3>
          <div class="table-responsive">
<?php
// Get the rows from the table
    $sqlget = 'SELECT * FROM `POLL`;';
    $sqldata = $db->query($sqlget) or die('error getting data!');

    echo '<table class="table">';
    echo '<thead><tr><th>id</th><th>Name</th><th>Gender</th><th>Popularity</th></tr></thead>';

    if($sqldata->num_rows > 0) {
    //echo '<div class="alert alert-success">' . PHP_EOL;
            echo '<tbody>';
        while($row = $sqldata->fetch_assoc()) {
            echo '<tr><td>';
            echo $row["id"];
            echo '</td><td>';
            echo $row["name"];
            echo '</td><td>';
            echo $row["gender"];
            echo '</td><td>';
            echo $row["popularity"];
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
  </body>
</html>
