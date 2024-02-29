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
                        <article class="box-pass">
                            <input class="input-box" type="password" id="newmdp" name="newmdp" placeholder="Nouveau mot de passe : " required />
                            <i class="fa-solid fa-lock"></i>
                        </article>
                        <article class="box-pass">
                            <input class="input-box" type="password" id="Confnewmdp" name="Confnewmdp" placeholder="Confirmer votre mot de passe : " required />
                            <i class="fa-solid fa-lock"></i>
                        </article>


                    </li>

                </ul>

                <button type="submit" name="Newpass" id="Newpass" class="btn-connect">Changer le mot de passe</button>

            </form>


        </div>
    </section>

    <?php

    if (isset($_POST['Newpass'])){
        extract($_POST);
        if ($newmdp!=$Confnewmdp){
            echo "<h1>Mot de passe différents ! </h1>";
        }else{
            $hpassword=password_hash($newmdp,PASSWORD_BCRYPT);
            $up = $pdo->prepare("UPDATE CLIENTS SET mdp=:mdp WHERE email_clients=:email");
            $up->execute([
                'mdp'=>$hpassword,
                'email'=> $_GET['email']
            ]);
            echo "<h1>Votre mdp a été changé !</h1>";

        }

    }


    ?>


    <script src="javascript.js"></script>
</body>
</html>
