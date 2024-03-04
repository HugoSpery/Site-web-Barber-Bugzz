<?php
    session_start();
    include "../build/index.php";
    global $pdo;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../build/PHPMailer-master/src/Exception.php';
    require '../build/PHPMailer-master/src/PHPMailer.php';
    require '../build/PHPMailer-master/src/SMTP.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<body class="co-fond">

<section class="page_co">
    <div class="inscr-connect">
        <form method="post">
            <h1 class="title-connect">Reset Password</h1>
            <ul class="inscr-ul">
                <li class="inscr-li">
                    <article class="box-prenom">
                        <input class="input-box" type="text" id="loseEmail" name="loseEmail" placeholder="Entrez votre email : " required />
                        <i class="fa-solid fa-user"></i>
                    </article>

                </li>

            </ul>

            <button type="submit" name="NewPass" id="NewPass" class="btn-connect">Envoyer un lien par mail</button>

        </form>


    </div>
</section>

<?php
    function sendemail_pass($email){

        $mail=new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth=true;

        $mail->Host = "smtp.gmail.com";
        $mail->Username="hugowario42@gmail.com";
        $mail->Password="";
        $mail->SMTPSecure="tls";
        $mail->Port=587;
        $mail->setFrom("hugowario42@gmail.com");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject="Reset Password";
        $email_template = '
                    <h2>Vous avez demander à changer votre mot de passe ! </h2>
                    <h4>Changer votre mot de pass en cliquant sur le lien ci-dessous</h4>
                    <br><br>
                    <a class="a-ac" href="http://localhost/BIG_Project/app/password-reset-code.php?email='.$email.'">Click me</a>
                ';
        $mail->Body=$email_template;
        $mail->send();

    };

    if (isset($_POST['NewPass'])){
        //Vérifier qu'il est bien dans la base de données.
        $s=$pdo->prepare("SELECT * FROM CLIENTS WHERE email_clients=:email");
        $s->execute([
                'email'=>$_POST['loseEmail']
        ]);
        $result=$s->rowCount();
        if ($result==0){
            echo "<h1>Aucun compte avec cette email ! </h1>";
        }
        else{
            sendemail_pass($_POST['loseEmail']);

        }


    }

?>

<script src="javascript.js"></script>
</body>
</html>
