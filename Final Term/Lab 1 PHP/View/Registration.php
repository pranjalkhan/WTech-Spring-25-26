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
                    <td> <label for ="email"> Email: </label></td>
                    <td> <input type = "email" id = "email" name = "email"><?php echo $email ?></td>
                </tr>
                <tr>
                    <td> <label for ="website"> Website: </label></td>
                    <td> <input type = "text" id = "website" name = "website"><?php echo $website ?></td>
                </tr>
                <tr>
                    <td> <label for ="comment"> Comment: </label></td>
                    <td> <textarea id = "comment" name = "comment" rows = "5" cols = "40"><?php echo $comment ?></textarea></td>
                </tr>
                <tr>
                    <td><label for ="gender"> Gender: </label></td>
                    <td><input type = "radio" id = "gender" name = "gender" value = "female">   Female
                        <input type = "radio" id = "gender" name = "gender" value = "male">     Male
                        <input type = "radio" id = "gender" name = "gender" value = "other">    Other
                    </td>

                <tr>
                    <td><input type = "submit" id = "submit" name = "submit" </td>
                </tr>
            </table>
        </form>
    </body>
</html>