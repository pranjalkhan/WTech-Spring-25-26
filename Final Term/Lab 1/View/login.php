<!DOCTYPE html>
<html>
    <body>
        <?php
            echo "<h1 style = 'color: red'> Log In Form </h1>";
        ?>
        <form method = "post" action="../Controller/loginvalidation.php">
        <table>
            <tr>
                <td>
                    <p> User Name: </p>
                </td>
                <td>
                    <input type ="text"/>
                </td>
            </tr>
            <tr>
                <td>
                    <p> Password: </p>
                </td>
                <td>
                    <input type ="password"/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type = "submit"/>
                </td>
               
            </tr>
        </table>
 </form>


<?php
echo "<h1 style = 'color: red'> Hello php </h1>";
$text1 = "Hello PHP";
$text2 = "Web Technology";
echo "$text1<br>";
echo $text2; 
echo '<h1>'.$text1. '</h1>'; 
echo "<h1> $text1 </h1>"; 

$var1 = 19.6;
$var2 = 10; 
echo $var1 + $var2;
echo "<br>";
if($var1>0)
    {
        echo "its positve";
    }
    else{
        echo "negative";
    }

echo "<br>";
$car = array("WebTechnology", "C#", "Python");
var_dump($car); 
echo "<br>";

$cars = array("Course"=> "WebTechnology", "Section"=>"R");
echo $cars["Course"];

echo "<br>";

print_r($car);

echo "<br>";
$x = "10";   // string 
$y = 5;      // integer 
$z = $x + $y; // string "10" is converted to integer 10 
 
echo $z; // Output: 15 
echo "<br>";
echo gettype($z); // Output: integer 

?>


    </body>
</html>