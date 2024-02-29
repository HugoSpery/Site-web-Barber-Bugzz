<?php session_start();
        include "../build/index.php";
        global $pdo;
        define('GOOGLE_ID','140389587208-m8a1jtifi27usmtg7j0uau6dqcr81vtm.apps.googleusercontent.com');
        define('GOOLGE_SECRET','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');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<?php
    if (isset($_SESSION['email'])){
        echo '
            <header>
                <nav id="nav-bar">
                    <ul class="nav-ul">
                        <a href="page_accueil.php"><img class="nav-logo" src="photo/logo_barber.png" alt="logo"></a>
                        <a class="nav-a" href="planningv2.php"><h2 class="nav-rdv">Prendre RDV</h2></a>
                        <a class="nav-a" href="profil.php">'.$_SESSION['email'].'</a>
                    </ul>
                </nav>
            </header>
            ';
    }
    else if ($_SESSION['access']==1){
        echo '
            <header>
                <nav id="nav-bar">
                    <ul class="nav-ul">
                        <a href="page_accueil.php"><img class="nav-logo" src="photo/logo_barber.png" alt="logo"></a>
                        <a class="nav-a" href="planningv2_admin.php"><h2 class="nav-rdv">Prendre RDV</h2></a>
                        <a class="nav-a" href="profil.php">'.$_SESSION['email'].'</a>
                    </ul>
                </nav>
            </header>
            ';
    }
    else{
        echo '
            <header>
                <nav id="nav-bar">
                    <ul class="nav-ul">
                        <a href="page_accueil.php"><img class="nav-logo" src="photo/logo_barber.png" alt="logo"></a>
                        <a class="nav-a" href="planningv2.php"><h2 class="nav-rdv">Prendre RDV</h2></a>
                        <a class="nav-a" href="connexion.php">Se connecter</a>
                    </ul>
                </nav>
            </header>
        ';
    }
?>


<body class="co-fond">

    <section class="page_co">
        <div class="connect">
            <form method="post">
                <h1 class="title-connect">Connectez-Vous !</h1>
                <article class="box-email">
                    <input class="input-box" type="email" id="mail" name="mail" placeholder="Email" required />
                    <i class="fa-solid fa-user"></i>
                </article>
                <article class="box-pass">
                    <input class="input-box" type="password" id="lpass" name="lpass" placeholder="Mot de passe" required />
                    <i class="fa-solid fa-lock"></i>

                </article>
                <p class="go-inscription">Vous n'avez pas de compte ? Créez en un ! </p>

                <a href="password-reset.php" target="_blank" class="go-mdp">Mot de passe oublié ? </a>

                <button type="submit" name="Lsend" id="Lsend" class="btn-connect">Se Connecter</button>


            </form>
            <div class="other-co">
                <p class="other-link">OU</p>
                <a class="btn-google" href="https://accounts.google.com/o/oauth2/v2/auth?scope=email&access_type=online&response_type=code&redirect_uri=<?=urlencode('http://localhost/SITE/connexion.php') ?>&client_id=<?=GOOGLE_ID ?>">
                    <i class="fa-brands fa-google"></i>
                </a>
            </div>
        </div>
    </section>
    <script>
        const btn = document.querySelector(".go-inscription");
        btn.addEventListener('click',()=>{
            let fenetre;
            fenetre=window.open("signin.php","","width=600px,height=600px");
        });</script>

    <?php
        /*
         *
         scope=email&
         access_type=online&
         redirect_uri=<URL_REDIRECTION>&
         response_type=code&
         client_id=<CLIENT_ID>
         */


        if (isset($_POST['Lsend'])){
            extract($_POST);
            $r=$pdo->prepare("SELECT * FROM CLIENTS where email_clients=:email");
            $r->execute([
               'email'=>$mail
            ]);
            $r=$r->fetch();

            if ($r==true){
                $hashpass=$r['mdp'];
                $t=password_verify($lpass,$hashpass);
                if ($t==true){
                    if ($r['verify_status']==1){
                        $_SESSION['status']="Vérifier";
                    }
                    if ($_SESSION['status']!="Vérifier"){
                        echo "votre compte n'a pas été vérifier, vérifier le !";
                    }
                    else {
                        $_SESSION['email'] = $r['email_clients'];
                        $_SESSION['prenom'] = $r['prenom'];
                        $_SESSION['nom'] = $r['nom'];
                        $_SESSION['mdp']= $lpass;
                        $_SESSION['access']=$r['access'];
                        header("Location: profil.php");
                        exit;
                    }
                }else{
                    echo "Mauvais mdp";
                }


            }
            else {
                echo 'aucun compte avec cette email ! ';
            }
        }


        /*


        use GuzzleHttp\Client;

        $client =new Client([
            'timeout'=>2.0
        ]);
        $response = $client->request('GET','https://accounts.google.com/.well-know/openid-configuration');
        dd((string)$response->getBody());
        */
    ?>

    <script src="javascript.js"></script>

</body>
</html>
