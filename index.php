<!DOCTYPE html>
<html>

    <head>    
        <meta charset="UTF-8"/>
        <title>MyForum</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <h1 class="centerh">Forum</h1>
        

        <fieldset class="centerfield">
            <p>
                Welcome on this simple website created with HTML/CSS for the frontend, PHP for the backend some JavaScript and using MySQL as the DBMS.
                <br><br>Enjoy :) ! 
            <p>
        </fieldset>
        <fieldset class="centerfield">

        <p>
            <strong>Pages : </strong>
        </p>

        <!-- Redirect fonction -->
        <script>

            function changepage(url){
                window.location.href=url;
            }

        </script>



        <button class="inputbutton"  onclick="changepage('login.php')" value="Login">Login</button>
        <button class="inputbutton" onclick="changepage('signup.php')" value="Signup">Signup</button>


        </fieldset>


    </body>
</html>
