<?php
include "../Controller/Registrationvalidation.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Registration Log In Form</title>
    </head>
    <body>
        <form method ="post" action="">
            <table>
                <tr>
                    <td><p style = 'color: red '> * Required Field </p></td><br>
                </tr>
                <tr>
                    <td> <label for ="UserName"> User Name: </label></td>
                    <td> <input type ="text" id = "name" name = "name"> <?php echo $name ?></td>
                    <td> <p style = 'color: red'>*</p> </td>
                </tr>
                <tr>
                    <td> <label for ="password"> Password: </label></td>
                    <td> <input type = "password" id="pass" name ="password"><?php echo $password ?></td>
                </tr>
                <tr>
                    <td><input type = "submit" id = "submit" name = "submit" </td>
                </tr>
            </table>
        </form>
    </body>
</html>