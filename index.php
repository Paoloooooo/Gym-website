<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home page - Fitness</title>
    <meta charset="utf=8" />
    <meta name="Author" content="Antonio Di Palma, Paolo Franco" />
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="icon" href="./images/icona.jpg">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="header">
        <div class="subscribe">
            <h1>train with the best</h1>
            <?php
            if (isset($_COOKIE['tipoUtente'])) {
            ?>
                <button class="btn" onclick="goToProf()">Profilo</button>
            <?php
            } else {
            ?>
                <button class="btn" onclick="goToSub()">Iscriviti ora</button>
            <?php
            }
            ?>
        </div>
    </div>

    <script type="text/javascript">
        function goToProf() {
            window.location.assign("profilo.php");
        }

        function goToSub() {
            window.location.assign("login.php");
        }
    </script>

    <section class="about">
        <div class="row">
            <div class="image">
                <img src="./images/about.jpg">
            </div>
            <div class="content">
                <h1>Chi siamo</h1>
                <p>Fitness è una palestra adatta a tutti. Vieni a scoprire le nostre strutture e tutti i corsi forniti, adatti ai bisogni di chiunque. Vieni a conoscere il nostro staff cordiale e sempre disponibile per qualsiasi necessità</p>
                <p>Ci trovi in via "si proprio quella", aperti tutti i giorni dalle 8:00 fino alle 22:00, festivi inclusi. </p>
            </div>
        </div>
    </section>

    <section class="corsi">
        <h1>corsi</h1>
        <div class="container">

            <?php
            include 'connect db.php';
            $db = pg_connect($connection_string);
            $res = pg_query("select * from corsi");

            $item = [];
            do {
                $curr = pg_fetch_assoc($res);
                $item[] = $curr;
            } while ($curr != false);
            if (count($item) != 0)
                unset($item[count($item) - 1]);

            if ($res == false || count($item) == 0) {
                echo "<h2 style=\"text-align:center\">Nessun corso disponibile.</h2>";
            } else {
                $len = count($item);
                if ($len <= 6) {
                    foreach ($item as $valid) {
                        echo " <div class=\"box\">
                                        <img src=" . $valid['image'] . ">
                                        <div class=\"info\">
                                            <h3>" . $valid['nome'] . "</h3>
                                            <p>" . $valid['descrizione'] . "</p>
                                        </div>
                                    </div>";
                    }
                } else {
                    $used = [];
                    for ($i = 0; $i < 6; $i++) {
                        $curr = rand() % $len;
                        if (!in_array($curr, $used)) {
                            $used[] = $curr;
                            $valid = $item[$curr];
                            $image = base64_encode($valid['image']);
                            echo " <div class=\"box\">
                                            <img src=" . $valid['image'] . ">
                                            <div class=\"info\">
                                                <h3>" . $valid['nome'] . "</h3>
                                                <p>" . $valid['descrizione'] . "</p>
                                            </div>
                                        </div>";
                        } else {
                            $i--;
                        }
                    }
                }
            }
            ?>

        </div>
    </section>
    <?php include "footer.html"; ?>
</body>

</html>