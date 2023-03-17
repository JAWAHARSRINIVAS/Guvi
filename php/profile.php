<?php

require("./connection.php");
require '../vendor/autoload.php';


?>

<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    $redis = new Predis\Client();
    if(!$redis->exists('email'))
    {
        session_destroy();
        echo "session ended";
        exit();
    }
    if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['age']) && isset($_POST['dob']) && isset($_POST['contact']) )
    {
        // MYSQL
        $name = $_POST['name'];
        $email = $_POST['email'];


        $stmt = $conn->prepare( " UPDATE `user` SET name = ?  WHERE email = ? " );
        $stmt->bind_param("ss",$name  , $email);
        

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
        
        $update = ['$set' => [
            "email" => $email, 
            "age" => $age,
            "dob" =>$dob,
            "contact" => $contact
            ]]; 
        $UpdateResult = $collection->UpdateOne(
                ["email" => $email],
                $update
            
        );

        if($stmt->execute() && $UpdateResult->getModifiedCount() == 1 ){
            $data = array(
                'msg' => "Update success"
            );
            
            $redis = new Predis\Client();
            $redis->set('email',$email);
            $redis->set('name',$name);
            $redis->set('age',$age);
            $redis->set('dob',$dob);
            $redis->set('contact',$contact);
    
            $json_data = json_encode($data);
            echo $json_data;
        }
        else{
            echo "Error : ".$stmt->error;
        }

        $stmt->close();


    }else if(isset($_POST['sessionId']))
        {
            $sessionId = $_POST['sessionId'];
            if($sessionId == 'empty'){
                $redis = new Predis\Client();
                $redis->flushall();
                echo "session ended";
            }
            else{
                $redis = new Predis\Client();
                $email = $redis->get('email');
                
        
                $response = array(
                    'msg'   => "success",
                    'name'  => $redis->get('name'),
                    'email' => $redis->get('email') ,
                    'age'   => $redis->get('age'),
                    'dob'   => $redis->get('dob'),
                    'contact'=> $redis->get('contact'),
                );
        
               
                $json_data = json_encode($response);
                echo $json_data;
            }
    }else{
        session_destroy();
        $redis = new Predis\Client();
        $redis->flushall();
    } 
    
?>
