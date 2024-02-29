<?php session_start();
include "../build/index.php";
global $pdo;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../build/PHPMailer-master/src/Exception.php';
require '../build/PHPMailer-master/src/PHPMailer.php';
require '../build/PHPMailer-master/src/SMTP.php';
if ($_SESSION['access']!=1){
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
          integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script type='importmap'>

    </script>
    <?php
        if (!isset($_SESSION['email'])) {
             header("Location: connexion.php");
        }
    ?>
    <script src='fullcalendar/lang-all.js'></script>
    <script type='module'>
        let currentDate = new Date().toJSON().slice(0, 10);
        document.addEventListener('DOMContentLoaded', function () {
            // fetch("planning-get-event.php")
            //     .then(response => response.json())
            //     .then(response => {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                height: 720,
                validRange: {
                    start: currentDate,
                    end: '2025-01-01'
                },
                selectable: true,
                locale: 'fr',
                allDaySlot: false,
                slotMinTime: '07:00:00',
                slotMaxTime: '20:00:00',
                hiddenDays: [0],
                titleFormat: { // will produce something like "Tuesday, September 18, 2018"
                    month: 'long',
                    year: 'numeric',
                    day: 'numeric',
                    weekday: 'long'
                },
                selectConstraint: {
                    start: '07:01',
                    end: '18:59',
                },
                defaultTimedEventDuration: '00:30:00',
                eventSources: {url: "planning-get-event.php"},
                eventClick: function(info) {

                    // function formatDate(date) {
                    //     var d = new Date(date),
                    //         month = '' + (d.getMonth() + 1),
                    //         day = '' + d.getDate(),
                    //         year = d.getFullYear();
                    //
                    //     if (month.length < 2)
                    //         month = '0' + month;
                    //     if (day.length < 2)
                    //         day = '0' + day;
                    //
                    //     return [year, month, day].join('-');
                    // }

                    // var h=info.event.start.toString().substring(16,24);
                    // var d=formatDate(info.event.start);
                    // var cdate = d+"T"+h+"+01:00";
                    let data=info.event;
                    const annulation = document.querySelector(".annuler-rdv");
                    const ann = document.querySelector(".mess-annuler");
                    annulation.style.display = "block";
                    annulation.style.left = info.jsEvent.pageX + "px";
                    annulation.style.top = info.jsEvent.pageY + "px";
                    document.getElementById("annC-rdv").addEventListener("submit", (e) => {
                        e.preventDefault()
                        fetch("planning-drop-event.php", {
                            method: "POST",
                            body: JSON.stringify(data),
                            header: {
                                "Content-type": "application/json; charset=utf-8"
                            },
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                console.log(data)
                                location.reload();

                            });
                    });

                },
                headerToolbar: {
                    center: 'addEventButton'
                },
                customButtons: {
                    addEventButton: {
                        text: 'ajouter une exception ! ',
                        click: function () {
                            var raison = prompt("Entrer l'évènement");
                            var dateStr = prompt("Entrer une date dans le format YYYY-MM-DD");
                            var debut = prompt("Entrer une date début hh:mm:ss");
                            var fin = prompt("Entrer une date de fin hh:mm:ss");
                            var date = new Date(dateStr + "T" + debut); // will be in local time
                            var datef = new Date(dateStr + "T" + fin);
                            let dates={'dateStr':dateStr, 'title': raison , 'datedeb': date , 'datefin': datef};
                            fetch("planning-set-exception.php",{
                                method : "POST",
                                body : JSON.stringify(dates),
                                header : {
                                    "Content-type" : "application/json; charset=utf-8"
                                },
                            })
                                .then((response)=>response.json())
                                .then((data)=>{
                                    console.log(data)
                                    location.reload();

                                });


                        }
                    }

                }
                    //<?php



                    //?>



        });
        calendar.render();


        // })
        // .catch(error => alert("Erreur : " + error));
        })
        ;


    </script>

</head>
<?php

if (isset($_SESSION['email'])) {
    echo '
        <header>
            <nav id="nav-bar">
                <ul class="nav-ul">
                    <a href="page_accueil.php"><img class="nav-logo" src="photo/logo_barber.png" alt="logo"></a>
                    <a class="nav-a" href="planning.php"><h2 class="nav-rdv">Prendre RDV</h2></a>
                    <a class="nav-a" href="profil.php">' . $_SESSION['email'] . '</a>
                </ul>
            </nav>
        </header>
        ';
} else {
    echo '
        <header>
            <nav id="nav-bar">
                <ul class="nav-ul">
                    <a href="page_accueil.php"><img class="nav-logo" src="photo/logo_barber.png" alt="logo"></a>
                    <a class="nav-a" href="planning.php"><h2 class="nav-rdv">Prendre RDV</h2></a>
                    <a class="nav-a" href="connexion.php">Se connecter</a>
                </ul>
            </nav>
        </header>
        ';

}


?>
<body>

<div id='calendar'></div>

<div class="valider-rdv">
    <p class="mess-valider">Valider votre rdv de </p>
    <form id="confirm-rdv" method="post">
        <input class="btnval" name="valrdv" id="valrdv" type="submit" value="Confirmer">
    </form>
</div>
<div class="annuler-rdv">
    <p class="mess-annuler">Annuler Le Rendez-Vous </p>
    <form id="annC-rdv" method="post">
        <input class="btnval" name="annrdv" id="annrdv" type="submit" value="Confirmer">
    </form>
</div>
<?php

//        $sel = $pdo->prepare("SELECT * FROM RDV WHERE start=:deb");
//        $sel->execute([
//            'deb'=> $_GET['datec']
//        ]);
//        $d=$_GET['datec'].'+01:00';
//        echo $d

?>

<script src="javascript.js"></script>
</body>
</html>
