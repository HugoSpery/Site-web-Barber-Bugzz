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
$mess=1;
$sel=$pdo->prepare("SELECT * FROM RDV WHERE id=:id");
$sel->execute([
    'id'=>$data->id
]);
$sel=$sel->fetch();
if ($_SESSION['email']!=$sel['email']){
    $mess=0;
}

echo json_encode($mess);

?>