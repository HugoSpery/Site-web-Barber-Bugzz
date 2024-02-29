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
    $mail->Password = "rzvv ekxr sorq ljsk";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->setFrom("hugowario42@gmail.com", $nom);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Prise de rendez-vous";
    $email_template = '
                                        <h2>Vous avez bien pris rendez-vous chez nous ! </h2>
                                        <h4>Le rdv est à ' . $heure . ' le ' . $jour . '</h4>

                                    ';
    $mail->Body = $email_template;
    $mail->send();

}

$heure = substr($data->datedeb, 11, 5);
$jour = substr($data->datedeb, 0, 10);
sendemail_rdv($_SESSION['nom'], $_SESSION['email'], $heure, $jour);

$insert = $pdo->prepare("INSERT INTO RDV VALUES (DEFAULT,:email,:title,:date,:fin)");
            $insert->execute([
                "email" => $_SESSION['email'],
                "title" => "Rdv de : " . $_SESSION['nom'],
                "date" => $data->datedeb,
                "fin" => $data->datefin
            ]);

echo json_encode($data);

?>



