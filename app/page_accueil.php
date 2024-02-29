<?php
    session_start();
    include "../build/index.php";
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../build/PHPMailer-master/src/Exception.php';
    require '../build/PHPMailer-master/src/PHPMailer.php';
    require '../build/PHPMailer-master/src/SMTP.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Barber Bugzz</title>
</head>
<?php
    if ($_SESSION['access']==1){
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
    }else if (isset($_SESSION['email'])){
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
    else{
        echo '
                <header>
                    <nav id="nav-bar">
                        <ul class="nav-ul">
                            <img class="nav-logo" src="photo/logo_barber.png" alt="logo">
                            <a class="nav-a" href="planningv2.php"><h2 class="nav-rdv">Prendre RDV</h2></a>
                            <a class="nav-a" href="connexion.php">Se connecter</a>
                        </ul>
                    </nav>
                </header>
            ';
    }

?>
<body>
    <span id="ancre"></span>
    <h1 class="name">Barber Bugzz</h1>

    <section class="message">
        <h1 class="sec-mes first">Hommes ou Femmes</h1>
        <h1 class="sec-mes second">Venez Comme Vous Êtes</h1>
        <h1 class="sec-mes third">All Welcome</h1>
    </section>
    <section class="prez">
        <div class="prez-1">
            <img class="prez-1-img" src="photo/photo1.jpg" alt="image">
            <article class="prez-art">
                <h1 class="prez-1-title">À Propos de Nous</h1>
                <p class="prez-1-desc1">Le Barber Bugzz vous accueille le lundi au samedi pour vous
                    offrir un instant de détente dans une ambiance conviviale.</p>
                <p class="prez-1-desc2">Homme, femme, enfant… toute la famille peut trouver sa place dans
                    notre salon et repartir satisfait de sa coupe de cheveux!</p>
                <p class="prez-1-desc3">Pour un entretien de votre coupe, une coiffure évènementielle, une coloration,
                    faites appel à notre équipe d'expérience qui saura vous aider à rencontrer votre personnalité grâce
                    à une coupe personnalisée.
                </p>
            </article>

        </div>
        <div class="prez-2">
            <article class="prez-art">
                <h1 class="prez-2-title">Nos Informations</h1>

                <p class="prez-2-desc1">226 Rue de la Gare</p>
                <p class="prez-2-desc2">Bellegarde-En-Forez, 42210</p>
                <p class="prez-2-desc3">06 24 27 93 11</p>
                <p class="prez-2-desc0">Lundi à Samedi</p>



            </article>
            <img class="prez-2-img" src="photo/photo2.jpeg" alt="image">


        </div>
    </section>
    <section class="container-form">
        <form class="form-accueil" method="post">
            <h1 class="form-title">Tu veux nous Contacter ? </h1>

            <div class="box-accueil-input">
                <input class="input-accueil" type="text" name="nom" id="nom" placeholder="Votre nom : " required>
                <input class="input-accueil" type="text" name="prenom" id="prenom" placeholder="Votre prenom :" required>
                <input class="input-accueil" type="email" name="email" id="email" placeholder="Votre email : " required>
            </div>
            <textarea class="input-accueil-message" name="com" id="com" cols="30" rows="5" placeholder="Votre message :" required></textarea>
            <input class="input-accueil-sub" name="post-accueil" id="post-accueil" type="submit" value="Envoyer">
        </form>
    </section>
    <div class="container-ancre">
        <a onclick="topFunction()" class="ancre"><i class="fa-solid fa-arrow-up"></i></a>

    </div>


    <?php
    if (isset($_POST['post-accueil'])){
        function sendemail($nom,$email,$message){
            $mail=new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth=true;

            $mail->Host = "smtp.gmail.com";
            $mail->Username=$email;
            $mail->Password="rzvv ekxr sorq ljsk";
            $mail->SMTPSecure="tls";
            $mail->Port=587;
            $mail->setFrom($email,$nom);
            $mail->addAddress("hugowario42@gmail.com");
            $mail->isHTML(true);
            $mail->Subject="Message info Barber Bugzz";
            $email_template = "$message";
            $mail->Body=$email_template;
            $mail->send();

        };
        sendemail($_POST['nom'],$_POST['email'],$_POST['com']);
    }

    ?>
    <script src="javascript.js"></script>
</body>
<footer>
    2024 © Bugzz Corporation - Mentions légales
</footer>
</html>

