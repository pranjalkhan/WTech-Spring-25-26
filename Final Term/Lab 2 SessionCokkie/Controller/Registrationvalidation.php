<?php 

session_start();

$name="";
$email="";
$website="";
$comment="";
$gender="";

$validemail = "";
$validwebsite = "";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $name = $_POST["name"];
    $email = $_POST["email"];
    $website = $_POST["website"];
    $comment = $_POST["comment"];

    $name = $_REQUEST["name"];
    $email = $_REQUEST["email"];
    $website = $_REQUEST["website"];
    $comment = $_REQUEST["comment"];

    if(isset($_POST["gender"]))
    {
        $gender = $_POST["gender"];
    }

    if(!empty($name) && strlen($name)>=5)
    {
        echo "Name: ".$name."<br>";
        $_SESSION["name"] = $name;
        setcookie("name",$name,time()+3600);
    }
    else
    {
        echo "Name must be greater than 5 char<br>";
    }

    
    if(!empty($email))
    {
        if(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))
        {
            $validemail = $email;
            echo "Email: ".$validemail."<br>";

            $_SESSION["email"] = $validemail;
            setcookie("email",$validemail,time()+3600);
        }
        else
        {
            echo "Invalid Email Format<br>";
        }
    }
    else
    {
        echo "Email is required<br>";
    }

    
    if(!empty($website))
    {
        if(preg_match("/\b(?:https?:\/\/|www\.)\S+\.\S+\b/i", $website))
        {
            $validwebsite = $website;
            echo "Website: ".$validwebsite."<br>";

            $_SESSION["website"] = $validwebsite;
            setcookie("website",$validwebsite,time()+3600);
        }
        else
        {
            echo "Invalid Website URL<br>";
        }
    }
    else
    {
        echo "Website is required<br>";
    }

    
    if(!empty($gender))
    {
        echo "Gender: ".$gender."<br>";

        $_SESSION["gender"] = $gender;
        setcookie("gender",$gender,time()+3600);
    }
    else
    {
        echo "Gender is required<br>";
    }
    
    if(!empty($comment))
    {
        echo "Comment: ".$comment."<br>";

        $_SESSION["comment"] = $comment;
        setcookie("comment",$comment,time()+3600);
    }
    else
    {
        echo "Comment is required<br>";
    }

}
?>