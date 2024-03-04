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

$data = json_decode(file_get_contents('php://input'));

function sendemail_rdv($nom, $email, $heure, $jour)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "smtp.gmail.com";
    $mail->Username = "hugowario42@gmail.com";
    $mail->Password = "";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->setFrom("hugowario42@gmail.com", $nom);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Annulation rendez-vous";
    $email_template = '
                                        <h2>Vous avez bien annulé(e) votre rendez-vous chez nous le ' . $jour . ' à  ' . $heure . ' ! </h2>
                                    ';
    $mail->Body = $email_template;
    $mail->send();

}
function sendann($nom, $email, $heure, $jour)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "smtp.gmail.com";
    $mail->Username = "hugowario42@gmail.com";
    $mail->Password = "";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->setFrom("hugowario42@gmail.com", $nom);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Annulation rendez-vous";
    $email_template = '
                                        <h2>Votre RDV du ' . $jour . ' à  ' . $heure . ' à été annulé ! </h2>
                                    ';
    $mail->Body = $email_template;
    $mail->send();

}
$heure = substr($data->start, 11, 5);
$jour = substr($data->start, 0, 10);
if ($_SESSION['access']==1){
    $select=$pdo->prepare("SELECT * FROM CLIENTS c JOIN RDV r ON c.email_clients = r.email WHERE id=:id");
    $select->execute([
       'id'=>$data->id
    ]);
    $select=$select->fetch();
    sendann($select['nom'], $select['email'], $heure, $jour);

}else{
    sendemail_rdv($_SESSION['nom'], $_SESSION['email'], $heure, $jour);

}
$del=$pdo->prepare("DELETE FROM RDV WHERE id=:id");
$del->execute([
    'id'=>$data->id
]);
echo json_encode($data);

?>
