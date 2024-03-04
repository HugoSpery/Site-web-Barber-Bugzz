<?php session_start();
    include "../build/index.php";
    global $pdo;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../build/PHPMailer-master/src/Exception.php';
    require '../build/PHPMailer-master/src/PHPMailer.php';
    require '../build/PHPMailer-master/src/SMTP.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<body class="co-fond">

<section class="page_co">
    <div class="inscr-connect">
        <form method="post">
            <h1 class="title-connect">Inscrivez-Vous !</h1>
            <ul class="inscr-ul">
                <li class="inscr-li">
                    <article class="box-prenom">
                        <input class="input-box" type="text" id="prenom" name="prenom" placeholder="Prenom" required />
                        <i class="fa-solid fa-user"></i>
                    </article>
                    <article class="box-nom">
                        <input class="input-box" type="text" id="nom" name="nom" placeholder="Nom" required />
                        <i class="fa-solid fa-user"></i>
                    </article>
                    <article class="box-email">
                        <input class="input-box" type="email" id="Imail" name="Imail" placeholder="Email" required pattern="^[0-9a-zA-Z.-_]{2,}@[a-zA-Z]{2,}\.[a-zA-Z]{1,}"/>
                        <i class="fa-solid fa-user"></i>
                    </article>
                    <article class="box-pass">
                        <input class="input-box" type="password" id="Ipass" name="Ipass" placeholder="mot de passe" required />
                        <i class="fa-solid fa-lock"></i>

                    </article>
                    <article class="box-Cpass">
                        <input class="input-box" type="password" id="ICpass" name="ICpass" placeholder="Confirmez votre mot de passe" required />
                        <i class="fa-solid fa-lock"></i>

                    </article>
                </li>

            </ul>

            <button type="submit" name="Isend" id="Isend" class="btn-connect">S'inscrire</button>

        </form>


    </div>
</section>

    <?php
        function sendemail_verify($nom,$email,$verify_token){
            $mail=new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth=true;

            $mail->Host = "smtp.gmail.com";
            $mail->Username="hugowario42@gmail.com";
            $mail->Password="";
            $mail->SMTPSecure="tls";
            $mail->Port=587;
            $mail->setFrom("hugowario42@gmail.com",$nom);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject="Email Verification";
            $email_template = '
                <h2>Vous avez crée votre compte chez nous ! </h2>
                <h4>Verifier votre email en cliquant sur le lien ci-dessous</h4>
                <br><br>
                <a class="a-ac" href="http://localhost/BIG_Project/app/verify-email.php?token='.$verify_token.'">Click me</a>
            ';
            $mail->Body=$email_template;
            $mail->send();

        };

        if (isset($_POST['Isend'])){
            if ($_POST['Ipass']==$_POST['ICpass']){

                $c=$pdo->prepare("SELECT email_clients FROM CLIENTS WHERE email_clients=:email");
                $c->execute([
                        'email'=>$_POST['Imail']
                ]);

                $result=$c->rowCount();
                if ($result==0){
                    $verify_token=md5(rand());
                    sendemail_verify($_POST['nom'],$_POST['Imail'],$verify_token);
                    $hashpass=password_hash($_POST["Ipass"],PASSWORD_BCRYPT);
                    $insert_c=$pdo->prepare("INSERT INTO CLIENTS VALUES(:email,:nom,:prenom,:mdp,:token,:verify_status,:access)");
                    $insert_c->execute([
                            'email'=>$_POST['Imail'],
                            'nom'=>$_POST['nom'],
                            'prenom'=>$_POST['prenom'],
                            'mdp'=> $hashpass,
                            'token'=>$verify_token,
                            'verify_status'=> 0,
                            'access' => 0

                    ]);
                    echo "<h1>Un émail à été envoyer pour vérifier votre email ! Vous pouvez fermer cette fenêtre ! </h1>";

                }
                else{
                    echo "il y a déja un compte avec cette adresse mail ! ";
                }

            }
            else{
                echo "Les mots de passe sont différents";
            }
        }
    /*

        CREATE TABLE CLIENTS(
        email_clients varchar(50) PRIMARY KEY,
        nom varchar(30) NOT NULL,
        prenom varchar(30) NOT NULL,
        mdp varchar(200) NOT NULL,
        token varchar(100) NOT NULL,
        verify_status numeric(1) NOT NULL,
        access numeric(1) NOT NULL

        );
    */



    ?>


<script src="javascript.js"></script>
</body>
</html>

