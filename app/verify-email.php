<?php
    session_start();
    include "../build/index.php";
    global $pdo;

    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $r=$pdo ->prepare("SELECT * FROM CLIENTS WHERE token=:token");
        $r->execute([
           'token'=>$token
        ]);
        if($r->rowCount()>0){
            $r=$r->fetchAll();
            if ($r['verify_status']==0){
                $clicked_token = $r['token'];
                $up=$pdo->prepare("UPDATE CLIENTS SET verify_status='1' WHERE token=:token");
                $up->execute([
                    'token'=>$token
                ]);

                header("Location: connexion.php");
                exit;
            }
            else{
                echo '<h1>Ce compte est déjà vérifier </h1>';
            }
        }
        else{
            echo "<h1>Ce token n'existe pas</h1>";
        }
    }
?>