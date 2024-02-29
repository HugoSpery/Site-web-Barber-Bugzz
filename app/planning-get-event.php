<?php
session_start();
if (!isset($_SESSION['email'])){
    throw new Exception("Bad Request");
}
include "../build/index.php";
global $pdo;

$events = [];



$r=$pdo->prepare("SELECT * FROM RDV");
$r->execute();
while ($val=$r->fetch()){
    $event= [
        "id" => $val['id'],
        "class" =>"mine",
        "title"=>$val["title"],
        "start"=>$val["start"],
        "end"=>$val["fin"],
        "extendedProps"=>["email"=>""]

    ];
    if ($val['email']==$_SESSION['email'] || $_SESSION['access']==1){
        $event["class"]= "mine";
        $event["extendedProps"]["email"]= $_SESSION['email'];

    }else if($val['email']=="speryhugo1@gmail.com"){
        $event["class"]= "mine";
        $event["backgroundColor"]="orange";
    }else{
        $event["class"]= "other";
        $event["title"]= "Rdv Client(e)";
        $event["backgroundColor"]="grey";

    }
    $events[]=$event;

}
echo json_encode($events);





?>