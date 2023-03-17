<?php

require("./connection.php");
require '../vendor/autoload.php';


?>

<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    
    if(isset($_POST['name']) && isset($_POST['email']) &&   isset($_POST['password']) && isset($_POST['age']) && isset($_POST['dob']) && isset($_POST['contact']) )
    {
        // MYSQL
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];


        $stmt = $conn->prepare( " UPDATE `user` SET name = ? , password = ?  WHERE email = ? " );
        $stmt->bind_param("sss",$name  , $password , $email);
        

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
                'name'  => $name,
                'email' => $email ,
                'age'   => $age,
                'dob'   => $dob,
                'password'=> $password,
                'contact'=> $contact
            );
    
    
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
                echo "session ended";
            }
            else{
                $redis = new Predis\Client();
                $email = $redis->get('user');

                $name = "";
        
                $stmt = $conn->prepare( " SELECT * FROM `user` where email = ? " );
                $stmt->bind_param("s",$email);
                if($stmt->execute())
                {
                    $result = $stmt->get_result();
                    if($result->num_rows == 1){
                        $row = $result->fetch_assoc();
                        $name = $row['name'];
                    }
                    
                }
                else{
                    exit();
                }
                
                $stmt->close();
        
        
        
                // MONGO DB
                
                $client = new MongoDB\Client;
        
                $db = $client->guvi;
        
                // if (!$db->listCollections(['filter' => ['name' => 'user']])->toArray()) {
                //     $db->createCollection('user');
                // } 
        
                $collection = $db->user;
                $document =$collection->findOne(
                    ['email'=>$email]
                );
        
                
        
                $response = array(
                    'msg'   => "success",
                    'name'  => $name,
                    'email' => $email ,
                    'age'   => $document['age'],
                    'dob'   => $document['dob'],
                    'contact'=> $document['contact'],
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
