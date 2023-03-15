<?php

require("./connection.php");
?>

<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    if(isset($_POST['name']) && $_POST['email'] &&   $_POST['password'] && $_POST['age'] && $_POST['dob'] && $_POST['contact'] )
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $age = $_POST['age'];
        $dob = $_POST['dob'];
        $contact = $_POST['contact'];

        $stmt = $conn->prepare( " INSERT INTO `user` (name , email , password ) VALUES ( ? , ? , ?) " );
        $stmt->bind_param("sss",$name , $email , $password);

        if($stmt->execute()){
            echo "New record created successfully.";
        }
        else{
            echo "Error : "+$stmt->error;
        }

        $stmt->close();
    }
    else   echo "  no detail";
?>
