<!DOCTYPE html>
<html lang='it'>

<head>
    <meta charset="utf-8">
    <meta name="author" content="Paolo Franco, Antonio Di Palma">
    <title>Corsi</title>
    <link rel="stylesheet" href="corsi.css">
    <link rel="icon" href="./images/icona.jpg">

</head>

<body>
    <?php //-> mettere la possibilità all'admin di aggiungere nuovi corsi 
    include 'header.php';
    ?>
    <div class="classe-container">
        <?php
        require 'connect db.php';

        if (isset($_COOKIE['tipoUtente']) && $_COOKIE['tipoUtente'] == "admin") {
        ?>
            <h3 id="newCorso">Per inserire un nuovo corso <a href="nuovoCorso.php">premi qui</a>.</h3>
        <?php
        }
        $db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());
        $res = pg_query("select * from corsi order by id");
        pg_prepare("iscriviti corso", "insert into iscritti(utente,corso) values($1,$2)");
        pg_prepare("get iscritti", "select count(*) as n from iscritti where corso=$1");
        pg_prepare("get corsi", "select * from iscritti where utente=$1");
        $item = [];
        do {
            $curr = pg_fetch_assoc($res);
            $item[] = $curr;
        } while ($curr != false);
        if (count($item) != 0)
            unset($item[count($item) - 1]);

        echo "<section class=\"corsi\">
                    <h1>I nostri corsi</h1>
                    <div class=\"container\">";
        //se ci sono corsi nel db
        if ($res != false) {
            //se è un utente registrato
            if (isset($_COOKIE['tipoUtente'])) {
                $corso = "";
                extract($_GET);

                if ($corso != "") {
                    $res = pg_execute("get corsi", array($_COOKIE['CF']));

                    $found = false;
                    do {
                        $curr = pg_fetch_assoc($res);
                        if (($curr != false) && ($curr['corso'] == $corso)) {
                            $found = true;
                            break;
                        }
                    } while ($curr != false);

                    if ($found) {
                        echo "<script>alert(\"Sei già iscritto al corso\")</script>";
                    } else {
                        $res = @pg_execute("iscriviti corso", array($_COOKIE['CF'], strval($corso)));
                        if ($res == false) {
                            echo "<script>alert(\"Iscrizione non riuscita\")</script>";
                        } else {
                            echo "<script>alert(\"Iscrizione riuscita\")</script>";
                        }
                    }
                }

                foreach ($item as $e) {
                    echo "<div class=\"box\">
                                <img src=" . $e['image'] . ">
                                <div class=\"info\"> 
                                    <h3>" . $e['nome'] . "</h3>
                                    <p>" . $e['descrizione'] . "</p>";


                    //se è un utente normale
                    if ($_COOKIE['tipoUtente'] == "user") {
                        echo "<h4 class=\"subsc\"><a href=\"corsi.php?corso=" . $e['id'] . "\">Iscriviti</a></h4>";
                        //se è l'admin
                    } else {
                        $res_n = pg_execute("get iscritti", array($e['id']));
                        $num = pg_fetch_assoc($res_n);
                        echo "<h4 class=\"num\"> Iscritti: " . $num['n'] . " su " . $e['posti_tot'] . "</h4>";
                    }

                    echo "</div>
                            </div>";
                }

                //se non è un utente registrato
            } else {
                $i = 0;
                foreach ($item as $e) {
                    if ($i == 6 || $e == false)
                        break;

                    echo "<div class=\"box\">
                                <img src=" . $e['image'] . ">
                                <div class=\"info\"> 
                                    <h3>" . $e['nome'] . "</h3>
                                    <p>" . $e['descrizione'] . "</p>
                                </div>
                            </div>";
                    $i += 1;
                }
                echo "<div class=\"subsc-to-visualize\">
                    <h3><a href=\"login.php\">Esegui l'accesso</a> per visualizzare tutti i corsi.</h3>
                </div>";
            }
            echo "</section>";  
            //se non ci sono corsi nel db
        } else {
            echo "<h2 id=\"notFound\">Nessun corso nel database</h2>";
        }
        ?>
    </div>
    <?php include 'footer.html'; ?>
</body>

</html>