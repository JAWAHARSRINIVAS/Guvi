<?php

require("./connection.php");
require '../vendor/autoload.php';
session_start();

?>

<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    $redis = new Predis\Client();
    if(!$redis->exists('email'))
    {
        session_destroy();
        echo "end session";
        exit();
    }
    else{
        if(isset($_POST['name']) && $_POST['email'] &&   $_POST['password'] && $_POST['age'] && $_POST['dob'] && $_POST['contact'] )
        {
            // MYSQL
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];


            $stmt = $conn->prepare( " INSERT INTO `user` (name , email , password ) VALUES ( ? , ? , ?) " );
            $stmt->bind_param("sss",$name , $email , $password);


            // MONGO DB
            

            $client = new MongoDB\Client;

            $db = $client->guvi;

            // if (!$db->listCollections(['filter' => ['name' => 'user']])->toArray()) {
            //     $db->createCollection('user');
            // } 

            $collection = $db->user;

            $age = $_POST['age'];
            $dob = $_POST['dob'];
            $contact = $_POST['contact'];

            $data = $collection->insertOne(
                [
                    'email' => $email,
                    'age'   => $age,
                    'dob'   => $dob,
                    'contact'=> $contact
                ]
            );

            if($stmt->execute() && $data->getInsertedCount() == 1 ){
                echo "success";
            }
            else{
                echo "Error : ".$stmt->error;
            }

            $stmt->close();


        }
        else   echo "  no detail";
    }
?>
