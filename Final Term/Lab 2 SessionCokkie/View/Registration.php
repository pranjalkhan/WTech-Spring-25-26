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
                    <td><p style = 'color: red '> * required Field </p></td><br>
                </tr>
                <tr>
                    <td> <label for ="name"> Name: </label></td>
                    <td> <input type ="text" id = "name" name = "name"> <?php echo $name ?></td>
                    <td> <p style = 'color: red'>*</p> </td>
                </tr>
                
                <tr>
                    <td> <label for ="email"> E-mail: </label></td>
                    <td> <input type = "email" id = "email" name = "email"><?php echo $email ?></td>
                    <td> <p style = 'color: red'>*</p> </td>
                </tr>
                <tr>
                    <td> <label for ="website"> Website: </label></td>
                    <td> <input type = "text" id = "website" name = "website"><?php echo $website ?></td>
                </tr>
                <tr>
                    <td> <label for ="comment"> Comment: </label></td>
                    <td> <textarea id = "comment" name = "comment" rows = "5" cols = "40"><?php echo $comment?></textarea></td>
                </tr>
                <tr>
                    <td><label for ="gender"> Gender: </label></td>
                    <td><input type = "radio" id = "female" name = "gender" value = "female">   
                    <label for="female">Female</label>
                        <input type = "radio" id = "male" name = "gender" value = "male"> 
                        <label for="male">Male</label>
                        <input type = "radio" id = "other" name = "gender" value = "other">    
                        <label for="other">Other</label>    
                    </td>
                    <td> <p style = 'color: red'>*</p> </td>
                </tr>
                <tr>
                    <td><input type = "submit" id = "submit" name = "submit" ></td>
                </tr>
            </table>
        </form>
    </body>
</html>