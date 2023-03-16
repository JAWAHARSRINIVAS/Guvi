<?php

require("./connection.php");
?>

<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    if( isset($_POST['email'] ) &&   isset($_POST['password']) )
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare( " SELECT * FROM `user` where email = ? " );
        $stmt->bind_param("s",$email);
        if($stmt->execute())
        {
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                if($row['password'] != $password)
                {
                    echo "Wrong password";
                }
                else{
                echo "Record found successfully.";
                }
            }
            else{
                echo "Account not found";
            }
        }
        else{
            echo "Error : ".$stmt->error;
        }

        $stmt->close();
    }
    else   echo "  no detail";
?>
