<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$me = $_SESSION['username'];

// TEST IF THE USER HAVE RIGHT PRIVS
// ------------------------------------------------------


if(!isset($_SESSION['username'])) // if the user is not connected at all
{
    echo "
    <script>
    window.location.href = 'login.php';
    </script>";
    die;
}
if(isset($_SESSION['admin']))
{
    if($_SESSION['admin'] != 1){ // if the admin session cookie is not equal to true 

        echo "
        <script>
        window.location.href = 'login.php';
        </script>";
        die;
    }
}
else{ // if the admin session cookie is not set
    echo " 
    <script>
    window.location.href = 'login.php';
    </script>";
    die;
}
// jsp
function tall(){
    require 'db-config.php';
    
    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
    $request = $PDO -> query('SELECT * FROM forum');

    $a=0;
    foreach($request as $re)
    {
        $a++;
    }

    return $a;
}

function displaytext(){

    require 'db-config.php';
    
    if(!empty($_POST['delete']))
    {
        deletemsg($_POST['delete'], $_SESSION['username']);
    }

    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
    $request = $PDO -> query('SELECT * FROM forum ORDER BY `id` DESC LIMIT 15;');


    echo("<strong>");

    foreach($request as $c)
    {    
            echo("<pre>");
            // Capitalize


            for($a=0; $a<2; $a++)
            {
                if ($a==0){
                    if (strtolower($c[$a]) == strtolower($_SESSION['username'])){
                        echo('<strong style="color: #cd7f7f;">');
                    }

                    $c[$a] = strtolower(htmlspecialchars($c[$a]));
                    str_split($c[$a]);
                    $c[$a][0] = strtoupper($c[$a][0]);
  
                }

                if($a==1){
                    echo('</strong>');
                }

                try{
                    $ping = str_contains(strtolower($c[$a]), strtolower("@".$_SESSION['username']));

                    if(!$ping){
                        $ping = str_contains(strtolower($c[$a]), "@everyone");
                        if($ping)
                            echo '<mark style="color: black; background-color: #bd8a08; border-radius: 5px;"> ' . htmlspecialchars($c[$a]) .' ';
                        else
                        echo htmlspecialchars($c[$a]);
                    }
                        else{
                            echo '<mark style="color: black; background-color: #bd8a08; border-radius: 5px;"> ' . htmlspecialchars($c[$a]) .' ';
                    }
                }
                catch(Exception $e)
                {
                    echo("");
                }
                if ($a == 0 AND $c[$a])
                    echo " </strong></mark><strong>> </strong>";
            }
              
            echo "</pre>";
            echo("<strong>");
            $req = $PDO -> prepare("SELECT * FROM `forum` WHERE id=:id AND author=:username;");
            $req -> execute(array(
                "id" => $c[2],
                "username" => $_SESSION['username']
            ));

 

                    echo('</strong>

                    <style>
                    form{display:inline;}
                    form{display:inline-block;}
                    </style>

                    <form method="post">
                    <input type="image" src="trash.svg" name="DELETE">
                    <input type="hidden" name="delete" value=' . $c[2] . '>
                    <strong></form>');



    }


    
    echo('<br>');
    
}

// -----------------------------------------------------------


function leak(){
    // leak the database 

    require 'db-config.php';
    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
    $request = $PDO -> query('SELECT * FROM users');


    echo("<pre>");

    foreach($request as $re)
    {    

        $i=0;
        $len = sizeof($re)/2;
        for($i=1; $i<$len; $i++)
        {
            echo $re[$i];
            if ($i == 1 or $i == 2)
                echo " : ";

        }  
        echo("<br>");
    }
    echo "</pre>";
}

// FUNCTION TO REMOVE AN ENTRY FROM THE DABATABASE

function remove($username, $me){

    require 'db-config.php';

    try{

        $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
	    $sch = $PDO -> prepare("SELECT * FROM `users` WHERE username=:username AND isadmin != 1 AND username !=:me;");
        
        $sch -> execute(array(
	    	"username" => $username,
            "me" => $me
	    ));


        if ($_POST['remove'] == $me)
        {    
            return "You can't ban yourself !";
        }

        foreach($sch as $r)
        {
            $a = $r ;
        }

        if(!(gettype($r) == "array")){
            return "Username does not exist / you try to ban an admin.";
        }
        else{
            $req = $PDO -> prepare("DELETE FROM `users` WHERE username=:username ;");
        
            $req -> execute(array(
                "username" => $username,
            ));
        }

    }

    catch(PDOException){
        return "Error !";
    
    }

    return "Account $username banned";
}

/*
// FUNCTION TO BAN AN ADMIN
// -------------------------------

function delete($username, $me){

    require 'db-config.php';

    try{

        $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
	    $sch = $PDO -> prepare("SELECT * FROM `users` WHERE username=:username AND isadmin != 1 AND username !=:me;");
        
        $sch -> execute(array(
	    	"username" => $username,
            "me" => $me
	    ));


        if ($_POST['remove'] == $me)
        {    
            return "You can't ban yourself !";
        }

        foreach($sch as $r)
        {
            $a = $r ;
        }

        if(!(gettype($r) == "array")){
            return "Username does not exist / you try to ban an admin.";
        }
        else{
            $req = $PDO -> prepare("DELETE FROM `users` WHERE username=:username ;");
        
            $req -> execute(array(
                "username" => $username,
            ));
        }

    }

    catch(PDOException){
        return "Error !";
    
    }

    return "Account $username removed";
}
*/

// FUNCTION TO ADD AN ADMIN USER
// -----------------------------

function add($username)
{
    require 'db-config.php';

    
    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
    $aaa = $PDO -> prepare("SELECT * FROM `users` WHERE username=:username AND isadmin = 0;");
    
    $aaa -> execute(array(
        "username" => $username,
    ));


    
    foreach($aaa as $r)
    {
        $a = $r ;
    }

    if(!(gettype($r) == "array")){
        return "The user don't exist or is already an admin";
    }
    else{
        $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);

        $req = $PDO -> prepare("UPDATE `users` SET isadmin=1 WHERE username=:username;");
        
        $req -> execute(array(
            "username" => $username
        ));
    }

    return "The user $username is now an admin";

}

function deletemsg($id){
    require 'db-config.php';

    try{

        $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
	    $sch = $PDO -> prepare("DELETE FROM `forum` WHERE id=:id;");
        
        $sch -> execute(array(
	    	"id" => $id
	    ));
    }

    catch(PDOException){
        return "Error !";
    }

    echo "<br><p>Le message a été supprimé<br></p>";
    return true;
}

?>

<!DOCTYPE html>
<html>
    <head>    
        <meta charset="UTF-8"/>
        <title>Admin panel</title>
    </head>

    <link rel="stylesheet" href="style.css">

<body>

<?php

    echo "<h1> Hello administrator " . htmlspecialchars($_SESSION['username']) . "</h1> ";


?>

<br>

<fieldset>
    <legend><strong>Forum :</strong></legend><br>


    <form method="post"><br>
            <input class="inpute" type="text" placeholder="Envoyer un message" name="texte" autofocus>
            <input class="inputbutton" type="submit" value="POST"> 
            <input type="submit" class="inputbutton" value="RELOAD" name="RELOAD">
        </form>

        <?php

        if(!empty($_POST['RELOAD'])){
            displaytext();
        }
        else{
            if(!empty($_POST['texte'])){
                include 'db-config.php';

                $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);

                $req = $PDO -> prepare("INSERT INTO `forum`(text,author) VALUES (:text, :author);");
                
                $req -> execute(array(
                    "text" => $_POST['texte'],
                    "author" => $_SESSION['username']
                ));
            }

            displaytext();
        }
        ?>

</fieldset>


<br><br>

    <fieldset>
    <legend><strong>Content of the database : </strong></legend>
    <br>
    <?php 
    leak(); 
    ?>

</fieldset>

<br>
<br>
<fieldset>
    <legend><strong>Admin section : </strong></legend><br>
    <form method="post">
    <p>
        <input type="text" class="inpute" placeholder="Username of the user to ban" name ="rm"></input>
        <input type="submit" class="inputbutton" value="BAN"></input><br>
    </p>

    </form>


    <form method="post">
        <p>
            <input type="text" class="inpute" placeholder="Username to give privs" name ="add"></input>
            <input type="submit" class="inputbutton" value="ADD"></input>
        </p>

    </form>
    

<?php

if(isset($_POST['rm']))
{
    $a = remove($_POST['rm'], $me);
    echo("<p>" . $a . "</p>");
}


if(isset($_POST['add']))
{
    $b = add($_POST['add']);
    echo("<p>" . $b . "</p>");
}



?>

</fieldset>


    <br><form method="post">
        <input type="image" src="home.svg" name="bonsoir">
        <input type="hidden" name="home">
    </form>

<?php
if(isset($_POST['home'])){
    session_destroy();
    echo "
    <script>
    window.location.href = 'index.php';
    </script>";
}
?>


    </body>
</html>
