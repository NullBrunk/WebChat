<?php
require 'db-config.php';
?>


<!DOCTYPE html>
<html>
    <head>    
        <meta charset="utf-8" />
        <title>Sign-up page</title>
    </head>
    <link rel="stylesheet" href="style.css">

        <h1>Create an account </h1>
        <body>
            <fieldset>
                <form method="post">

                    <br>
                    <input class="inpute" placeholder="Username" type="text" name=username ></input> <br><br>
                    <input class="inpute" placeholder="Password" type="password" name=password ></input> <br><br>
                    <input class="inpute" placeholder="Retype password" type="password" name=retype ></input> <br><br>
                    <input class="inputbutton" type="submit" value="Sign-Up">
                </form>
            </fieldset>


            <form method="post"><br>
                <input type="image" src="home.svg" name="bonsoir">
                    <input type="hidden" name="home">
            </form>

<?php

if(isset($_POST['home'])){
        echo "
        <script>
        window.location.href = 'index.php';
        </script>";
        die;

}

if( !empty($_POST['username']) and !empty($_POST['password']) and !empty($_POST['retype']) ) // if username and password are set
{

    if (!($_POST["password"] == $_POST['retype']))
    {
        echo("<p>Password does not match</p>");
        die;
    }

    $username = $_POST['username']; 
    $password = $_POST['password'];

    if (strlen($username) > 65 ){ // i used VARCHAR(65) in my table so 
        echo('<p>Username is too big</p>');
        die; 
    }

    if (strlen($password) > 65 ){  // same
        echo('Password is too big');
        die; 
    }

}

else{ // username or password is not set
    echo "<p>Can't create an account without username/password ! </p>";
    die;
}

if (str_contains($username, "/*") or str_contains($username, "'") or str_contains($username, '"') or str_contains($username, "--") or str_contains($username, "#") or str_contains($username, " ") ){
    echo("<p>Illegal character used in the username ! ( #, /*  --,  " .'",  '. "' or the space character ) </p>");
    die;
}

if (str_contains($username, "/*")  or str_contains($password, "'") or str_contains($password, '"') or str_contains($password, "--") or str_contains($password, "#") or str_contains($password, " ") ){
    echo("<p>Illegal character used in the password !( #, /*,   --,  " .   '",   '. "' or the space character ) </p>");
    die;
}

try{

    // Trying to insert data into the database 
    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS);

	$req = $PDO -> prepare("INSERT INTO users(username, password) VALUES(:username, :password);");
	$req -> execute(array(
		"username" => $username,
		"password" => $password
	));
}

catch(PDOException)
{
	echo "<p>Error in account creation ! </p>";
    die;
}
?>

<script>
alert('Done, you will be redirected to the login page');
window.location.href = "login.php"
</script>


