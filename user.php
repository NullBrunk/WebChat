<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db-config.php';

// VERIFIY THAT THE USER IS CURRENTLY LOGGED
if(!isset($_SESSION['username'])) // if the username session cookie is not set, user is not logged in
{
    echo "
    <script>
    window.location.href = 'login.php';
    </script>";
    die;
}

// DELETE MESSAGE
// ----------------------

function deletemsg($id, $username){

    if(empty($id)){
        return "</strong><br>No id specified<strong>";
    }

    require 'db-config.php';

    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
    $request = $PDO -> prepare("SELECT * FROM `forum` WHERE id=:id AND author=:username ;");
    
    $request -> execute(array(
        "id" => $id,
        "username" => $username
    ));

    foreach($request as $a){
        $r = $a;
    }
    if (isset($r) and !empty($r)){
        if(!(gettype($r) == "array")){
            echo "<br><p>Seul l'admin peut supprimer les messages d'autres utilisateurs !</p>";
            return false;
        }
        else{
            try{

                $sch = $PDO -> prepare("DELETE FROM `forum` WHERE id=:id AND author=:username ;");

                $sch -> execute(array(
                    "id" => $id,
                    "username" => $username
                ));
            }
        
            catch(PDOException){
                return "Error !";
            }    
        }
    }  

    
    return true;
}

// CHANGE THE PASSWORD
// -------------------------

function changepass($pass){
    
    require 'db-config.php';

    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);

    try{
    $req = $PDO -> prepare("UPDATE `users` SET password=:pass WHERE username=:username;");
    
    $req -> execute(array(
        "pass" => $pass,
        "username" => $_SESSION['username']
    ));
    }
    catch(PDOException){
        return "Error";
    }

    $_SESSION['password'] = $pass;
    return "Password changed";
}
/*
<audio autoplay="true">    
   <source src="notif.mp3" type="audio/mpeg">  
</audio>

*/
function displaytext(){

    require 'db-config.php';
    
    if(!empty($_POST['delete']))
    {
        deletemsg($_POST['delete'], $_SESSION['username']);
    }

    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);
    $request = $PDO -> query('SELECT * FROM forum ORDER BY `id` DESC ;');


    echo("<strong>");

    foreach($request as $c)
    {    
            echo('<pre>');
            // Capitalize


            for($a=0; $a<2; $a++)
            {
                if ($a==0){
                    if (strtolower($c[$a]) == strtolower($_SESSION['username'])){
                        echo('<strong style="color: #ff0000;">');
                    }
                    else{
                        echo('<strong style="color: #e56060;">');
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
                            echo '<mark style="color: black; background-color: #ff08e2; border-radius: 5px;"> ' . htmlspecialchars($c[$a]) .' ';
                        else
                        echo htmlspecialchars($c[$a]);
                    }
                        else{
                            echo '<mark style="color: black; background-color: #ff08e2; border-radius: 5px;"> ' . htmlspecialchars($c[$a]) .' ';
                    }
                }
                catch(Exception $e)
                {
                    echo("");
                }
                if ($a == 0 AND $c[$a])
                    echo " </strong></mark><strong>> <strong>";
            }
              
            echo "</pre>";
            echo("<strong>");
            $req = $PDO -> prepare("SELECT * FROM `forum` WHERE id=:id AND author=:username;");
            $req -> execute(array(
                "id" => $c[2],
                "username" => $_SESSION['username']
            ));

 
            foreach($req as $r){
                if(!empty($r)){
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
            }
        

    }


    echo('<br>');
    
}

?>

<!DOCTYPE html>
<html class="imagegrande">
    <head>    
        <meta charset="UTF-8"/>
        <title>User page</title>
    </head>
    <link rel="stylesheet" href="style.css">

    <body>

<?php

    echo "<h1> Hello username " . htmlspecialchars($_SESSION['username']) . "</h1> ";


?>

<fieldset class="centerfieldd">
    <legend><strong>Forum :</strong></legend><br>
    <form method="post"><br>
    <input type="text" class="inpute" placeholder="Envoyer un message" name="texte" autofocus>
    <input type="submit" class="chatbutton" value="POST"> 
    <input type="submit" class="chatbutton" value="RELOAD" name="RELOAD">
    <br><br>
</form>
<?php
if(!empty($_POST['RELOAD'])){
    displaytext();
}
else{
    if(!empty($_POST['texte'])){

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
    <legend><strong>Change your password</strong></legend><br>

        <form method="post">

            <input type="password" class="inpute" name="pass" placeholder="Your actual password"><br><br>
            <input type="password" class="inpute" name="newpassword" placeholder="New password"> <br><br>
            <input type="submit" class="chatttttbutton" value="CHANGE">
        </form>
    </fieldset>

    <br>
<?php
if(isset($_POST['newpassword'])){

    if (strlen($_POST['newpassword']) > 65 ){
        echo("<p>Password is too long</p>");
    }
    
    else{
        if($_POST['pass'] == $_SESSION['password'])
        {

            if (str_contains($_POST["newpassword"], "/*")  or str_contains($_POST["newpassword"], "'") or str_contains($_POST["newpassword"], '"') or str_contains($_POST["newpassword"], "--") or str_contains($_POST["newpassword"], "#") or str_contains($_POST["newpassword"], " ") ){
                echo("<p>Illegal character used in the password ! ( #, /*,  --,  " .'",  '. "',  space character )</p>");
                die;
            }
            

            if(!($_POST['newpassword'] == $_SESSION['password'])){
                $a = changepass($_POST["newpassword"]);
                echo($a);
            }
            else{
                echo("<p>Old and new password are same</p>");
            }
        }
        else{
            echo("<p>Your actual password and the supplied password does not match</p>");
        }
    }
}
?>


<br><form method="post">
        <input type="image" src="home.svg" name="home">
        <input type="hidden" name="home">
    </form>

<?php
if(isset($_POST['home'])){
    session_destroy();
    echo "
    <script>
    window.location.href = 'login.php';
    </script>";
}
?>

</body>
</html>