<?php

    require '../vendor/autoload.php';

    $client = new MongoDB\Client;

    $db = $client->test;

    $collection = $db->testcollection;
    // $result = $db->dropCollection('test');

    // var_dump($result);

    // $insertOneResult = $collection->insertOne(
    //     ['_id'=>'1', 'name'=> 'Appaji']
    // );

    // printf(" Recode Indserted ");
    // var_dump($insertOneResult->getInsertedId());
    
    $document  = $collection->findOne(
        ['email'=>"nivas@gmail.com"]
    );
    var_dump($document);
    echo " +++++++++++++ ".$document['email'];

?>

