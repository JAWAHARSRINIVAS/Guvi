<?php

    require("./connection.php");
    require '../vendor/autoload.php';
    


?>

<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    if(  isset($_POST['email'] ) &&   isset($_POST['password']) )
    {
        // MYSQL
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $name = "";
        
        $stmt = $conn->prepare( " SELECT * FROM `user` where email = ? " );
        $stmt->bind_param("s",$email);
        if($stmt->execute())
        {
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                if($row['password'] != $password)
                {
                    $response = [ "msg"=>"Wrong password" ];
                    $json_data = json_encode($response);
                    echo $json_data;
                    exit();
                }
                else{
                    $name = $row['name'];
                }
            }
            else{
                $response = [ "msg"=>"Account not found" ];
                $json_data = json_encode($response);
                echo $json_data;
                exit();
            }
        }
        else{
            $response = [ "msg"=>"Error : ".$stmt->error];
            $json_data = json_encode($data);
            echo $json_data;
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


        $redis = new Predis\Client();
        session_start();
        $redis->set('email',$email);
        $redis->set('name',$name);
        $redis->set('password',$password);
        $redis->set('age',$document['age']);
        $redis->set('dob',$document['dob']);
        $redis->set('contact',$document['contact']);

        $response = array(
            'msg'   => "success",
            "sessionId" => session_id()
        );

       
        $json_data = json_encode($response);
        echo $json_data;

    }
    else   echo "  no detail";
?>
