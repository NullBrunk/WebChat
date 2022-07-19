<?php
session_start();

/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/

function search($username, $password, $isadmin){

    if (str_contains($username, "/*") or str_contains($username, "'") or str_contains($username, '"') or str_contains($username, "--") or str_contains($username, "#") or str_contains($username, " ") ){
        echo("<p>Illegal character used</p>");
        die;
    }
    
    if (str_contains($username, "/*") or str_contains($password, "'") or str_contains($password, '"') or str_contains($password, "--") or str_contains($password, "#") or str_contains($password, " ") ){
        echo("<p>Illegal character used</p>");
        die;
    }


    require 'db-config.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASS);

    $rq = $pdo -> prepare("SELECT * FROM users WHERE username=:username AND password=:password AND isadmin=:isadmin");

    $rq -> execute(
        [
            'username' => $username,
            'password' => $password,
            'isadmin' => $isadmin
        ]
    );
    $req = $rq->fetch();

    return $req;
}

?>

<!DOCTYPE html>
<html>
    <head>    
        <meta charset="utf-8" />
        <title>Login page</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body class="bodyclass">

    <h1 class="centerh">Forum</h1>
        <fieldset class="centerfield_login">
            <form method="post">
                <br>
                <input placeholder="Username" class="inpute" type="text" name=username ></input> <br><br>
                <input placeholder="Password" class="inpute" type="password" name=password ></input> <br><br>
                <br>
                <input type="submit" value="Login" class="inputbutton">
                <input type="submit" value="Signup" name="signup" class="inputbutton"> 
        
            </form>
    </fieldset>     
    <br>
    <form method="post"><br>
        <input type="image" src="home.svg" name="bonsoir">
        <input type="hidden" name="home">
    </form>

<?php 

if(isset($_POST['signup'])){
    echo "
    <script>
    window.location.href = 'signup.php';
    </script>";
}
?>

<?php
if(isset($_POST['home'])){
        echo "
        <script>
        window.location.href = 'index.php';
        </script>";
    }
?>
    
<!-- 
    JavaScript fonction to redirect
-->

        <script>
        
            function redirect(url){
                window.location.href = url;
            }
        
        </script>

    </body>
</html> 



<?php




if( !empty($_POST['username']) and !empty($_POST['password']) )
{
    $username = $_POST['username'];
    $password = $_POST['password'];
}
else{
    echo '<p>Please enter a username and a password</p>';
    die;
}

$rq = search($username, $password, "0");

if (empty($rq) ){
    // can be admin
    $rq = search($username, $password, "1");
    if (!empty($rq)){

        if (!($rq["password"] == $password))
        {
            echo("<p>Wrong username or password / Account does not exist.</p>");
        }
        else{
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;    
            $_SESSION['admin'] = 1;
            echo '<script>redirect("panel.php")</script>';
        }

    }
    else{
        echo("<p>Wrong username or password / Account does not exist.</p>");
    }
}

else{
    if (!($rq["password"] == $password))
    {
        echo("<p>Wrong username or password / Account does not exist.</p>");
    }

    else{
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        echo '<script>redirect("user.php")</script>';
    }
}
?>