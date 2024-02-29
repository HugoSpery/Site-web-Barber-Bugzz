<?php session_start();
include "../build/index.php";
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<?php

        if($_SESSION['access']==1){
            echo '
                <header>
                    <nav id="nav-bar">
                        <ul class="nav-ul">
                            <a href="page_accueil.php"><img class="nav-logo" src="photo/logo_barber.png" alt="logo"></a>
                            <a class="nav-a" href="planningv2_admin.php"><h2 class="nav-rdv">Prendre RDV</h2></a>
                            <a class="nav-a" href="#">' . $_SESSION['email'] . '</a>
                        </ul>
                    </nav>
                </header>
                ';
        }else {


            echo '
                <header>
                    <nav id="nav-bar">
                        <ul class="nav-ul">
                            <a href="page_accueil.php"><img class="nav-logo" src="photo/logo_barber.png" alt="logo"></a>
                            <a class="nav-a" href="planningv2.php"><h2 class="nav-rdv">Prendre RDV</h2></a>
                            <a class="nav-a" href="#">' . $_SESSION['email'] . '</a>
                        </ul>
                    </nav>
                </header>
                ';
        }

?>
<body class="co-fond">

    <section class="page_co">
        <div class="connect">
            <h1 class="title-connect">Vos informations :</h1>
            <?php
                $mdpcache="";
                for ($i=0;$i<strlen($_SESSION['mdp']);$i++){
                    $mdpcache=$mdpcache."*";
                }


                echo '
                    <ul>
                        <li class="profil-li"><p>Nom : '.$_SESSION['nom'].'</p></li>
                        <li class="profil-li"><p>Prenom : '.$_SESSION['prenom'].'</p></li>
                        <li class="profil-li"><p>Email : '.$_SESSION['email'].'</p></li>
                        <li class="profil-li mdpc"><p>Mot de passe : '.$mdpcache.'</p></li>
                        <form method="post">
                            <input class="showmdp smdp" type="submit" name="showmdp" id="showmdp" value="Afficher le mot de passe">
                            <input class="showmdp3 smdp3" type="submit" name="showmdp3" id="showmdp3" value="Cacher le mot de passe">

                        </form>
                    </ul>
                       
                ';
                if (isset($_POST['showmdp'])) {
                    echo '
                    <form method="post">
                        <input class="testmdp" type="password" name="testmdp" id="testmdp" placeholder="Entrez votre mot de passe : " required >
                        <input class="showmdp" type="submit" name="showmdp2" id="showmdp2" value="Valider">

                    </form>
                    ';
                }

                if (isset($_POST['showmdp2'])){
                    if ($_POST['testmdp']==$_SESSION['mdp']){
                    ?>
                     <script type="text/javascript">
                         const mdp=document.querySelector(".mdpc");
                         const btn=document.querySelector(".smdp");

                         const btn2=document.querySelector(".showmdp3");
                         btn.style.display="none";
                         btn2.style.display="block";
                        btn2.style.marginLeft="95px";
                         mdp.textContent="Mot de passe : <?php echo $_SESSION['mdp']; ?>";


                     </script>

                    <?php

                    }
                    else{
                        echo "<p>Mauvais mot de passe</p>";
                    }
                }

            ?>

            <form method="post">

                <button class="dsend" type="submit" name="dsend" id ="dsend" > Se déconnecter </button>

            </form>

        </div>

    </section>
<?php




        if (isset($_POST['dsend'])){
            extract($_POST);
            unset($_SESSION['email']);
            unset($_SESSION['prenom']);
            unset($_SESSION['nom']);


            header("Location: connexion.php");
            exit;
        }

?>



<script src="javascript.js"></script>
</body>
</html>
