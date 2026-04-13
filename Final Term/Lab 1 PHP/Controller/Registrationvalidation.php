<?php 

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
        echo "User Name: ".$name."<br>";
    }
    else
    {
        echo "UserName must be greater than 5 char<br>";
    }

    
    if(!empty($email))
    {
        if(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))
        {
            $validemail = $email;
            echo "Email: ".$validemail."<br>";
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
    }
    else
    {
        echo "Gender is required<br>";
    }
    
    if(!empty($comment))
    {
        echo "Comment: ".$comment."<br>";
    }
    else
    {
        echo "Comment is required<br>";
    }
    
}

?>