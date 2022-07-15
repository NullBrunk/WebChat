<?php
session_start();


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

// JSP
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
    $request = $PDO -> query('SELECT * FROM forum');


    echo("<strong>");

    $b = 0;
    $c = [];

    foreach($request as $re)
    {    
        array_push($c, $re);
    }
    $calc = (sizeof($c)-1) - 20;
    for($i=sizeof($c)-1; $i>$calc; $i--)
    {    
            echo("<pre>");
            for($a=0; $a<2; $a++)
            {

                try{
                    echo htmlspecialchars($c[$i][$a]);
                }
                catch(Exception $e)
                {
                    echo("");
                }
                if ($a == 0 AND $c[$i][$a])
                    echo " > </strong>";
            }
              
            echo "</pre>";
            echo("<strong>");
            $req = $PDO -> prepare("SELECT * FROM `forum` WHERE id=:id AND author=:username;");
            $req -> execute(array(
                "id" => $c[$i][2],
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
                    <input type="hidden" name="delete" value=' . $c[$i][2] . '>
                    <strong></form>');
            
                }
            }


        $b++;
    }


    
    echo('<br>');
    
}

?>

<!DOCTYPE html>
<html>
    <head>    
        <meta charset="UTF-8"/>
        <title>User page</title>
    </head>
    <link rel="stylesheet" href="style.css">

    <body>


<fieldset>
    <legend><strong>Forum :</strong></legend><br>
    <form method="post"><br>
    <input type="text" class="inpute" placeholder="Envoyer un message" name="texte">
    <input type="submit" class="inputbutton" value="POST"> 
    <input type="submit" class="inputbutton" value="RELOAD" name="RELOAD">
    <br><br>
</form>
<?php
if(!empty($_POST['RELOAD'])){
    displaytext();
}
else{
    if(!empty($_POST['texte'])){

        $_POST['texte'] = str_replace("<", " ", $_POST['texte']);
        $_POST['texte'] = str_replace(">", " ", $_POST['texte']);
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
            <input type="submit" class="inputbutton" value="CHANGE">
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
                echo("<p>Illegal character used in the password ! ( #  --  " .'"  '. "'  space character )</p>");
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
