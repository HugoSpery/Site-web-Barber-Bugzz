<?php session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../build/PHPMailer-master/src/Exception.php';
require '../build/PHPMailer-master/src/PHPMailer.php';
require '../build/PHPMailer-master/src/SMTP.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

header('Access-Control-Allow-HEADERS: *');
header('Content-Type: application/json; charset=utf-8');

include "../build/index.php";
global $pdo;
if (!isset($_SESSION['email'])) {
    throw new Exception("Bad Request");
}
function sendann($email, $heure, $jour)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "smtp.gmail.com";
    $mail->Username = "hugowario42@gmail.com";
    $mail->Password = "";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->setFrom("hugowario42@gmail.com");
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Annulation rendez-vous";
    $email_template = '
                                        <h2>Votre RDV du ' . $jour . ' à  ' . $heure . ' à été annulé ! </h2>
                                        <h2>Pour voir la raison ou reprendre RDV allez sur le site ! </h2>

                                    ';
    $mail->Body = $email_template;
    $mail->send();

}
$data = json_decode(file_get_contents('php://input'));

$select = $pdo->prepare("SELECT * FROM RDV WHERE start LIKE :jour AND start>=:datedeb AND fin <=:datefin");
$select->execute([
    'jour'=>$data->dateStr.'%',
    'datedeb'=>$data->datedeb,
    'datefin'=>$data->datefin
]);
while ($val=$select->fetch()){
    $heure = substr($val["start"], 11, 5);
    $jour = substr($val["start"], 0, 10);
    sendann($val['email'], $heure, $jour);
    $del=$pdo->prepare("DELETE FROM RDV WHERE id=:id");
    $del->execute([
        'id'=> $val['id']
    ]);
}

$insert= $pdo->prepare("INSERT INTO RDV VALUES(DEFAULT,:email,:title,:start,:fin)");
$insert->execute([
   'email'=>$_SESSION['email'],
   'title' => $data->title,
   'start' =>$data->datedeb,
   'fin' =>$data->datefin
]);
echo json_encode($select);

?>
