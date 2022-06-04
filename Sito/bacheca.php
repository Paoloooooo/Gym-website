<!DOCTYPE html>
<html lang='it'>

<head>
    <meta charset="utf-8">
    <meta name="author" content="Franco Paolo,Antonio Di Palma">
    <title>Bacheca</title>
    <link rel="stylesheet" href="bacheca.css">
    <link rel="icon" href="./images/icona.jpg">
</head>

<body>
    <?php
    include 'header.php';
    require 'connect db.php';
    $page = 1;
    $num = 10;
    extract($_GET);
    $message = $titolo = "";
    extract($_POST);
    if ($message != "") {
        $db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());
        pg_prepare("nuovo messaggio", "insert into bacheca(data,titolo,testo) values($1,$2,$3)");
        $res = pg_execute("nuovo messaggio", array(strval(date('Y-m-d H:i:s')), $titolo, $message));
        if ($res == false) {
            echo "<script>alert(\"Inserimento non riuscito\")</script>";
        } else {
            echo "<script>alert(\"Inserimento riuscito\")</script>";
        }
    }
    ?>

    <div class="container">
        <div class="page-title">
            <h1>News</h1>
        </div>
        <?php
        if (isset($_COOKIE['tipoUtente']) && $_COOKIE['tipoUtente'] == "admin") {
        ?>
            <h2 id="insert">Inserisci una news:</h2>
            <div class="form-container">
                <form method="POST" id="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="input-field1">
                        <input required type="text" name="titolo" id="titolo" placeholder="Titolo: MAX 60 caratteri" value="<?php echo $titolo; ?>">
                    </div>
                    <div class="input-field2">
                        <textarea name="message" id="message" cols="30" rows="5" placeholder="Contenuto: MAX 1000 caratteri" value="<?php echo $message; ?>"></textarea>
                    </div>
                    <div class="submit-field">
                        <input type="submit" id="subm" value="Carica"></input>
                    </div>
                </form>
            </div>
            <br>
            <script type="text/javascript">
                document.getElementById("form").addEventListener('submit', function(e) {
                    e.preventDefault();
                    text = document.getElementById("message").value;

                    if (text.length > 1000) {
                        alert("Hai superato il limite di caratteri, hai usato " + text.length + " caratteri su un massimo di 1000");
                        return false;
                    }

                    console.log("Inviati");
                    document.forms.form.submit()
                });
            </script>
        <?php
        }
        ?>

        <form method="GET" id="scelta" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="number" id="page" name="page" value="<?php echo $page; ?>" hidden>
            <div class="numb-picker">
                Numero di post da visualizzare: <input type="number" id="num" name="num" value="<?php echo $num; ?>">
                <input type="submit">
            </div>
            <br>
        </form>

        <div class="news-section">
            <div class="section-content">
                <div class="posts">
                    <div class="post">
                        <?php
                        $db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());
                        $query = pg_query("select * from bacheca order by data desc");
                        do {
                            $curr = pg_fetch_assoc($query);
                            $item[] = $curr;
                        } while ($curr != false);
                        $start = ($num * ($page - 1)) % count($item);
                        //$page=intval($start/$num)+1;

                        for ($i = $start; $i < $num + $start; $i++) {
                            if (!isset($item[$i]) || $item[$i] == false)
                                break;
                            extract($item[$i]);
                            echo "<div class=\"article\">
                                                <h4 class=\"title\">$titolo</h4>
                                                <p>$testo</p>
                                                <div class=\"posted-date\">
                                                    <p>$data</p>
                                                </div>
                                    </div><hr>";
                        };
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            document.getElementById("scelta").addEventListener('submit', function(e) {
                e.preventDefault();
                num = document.getElementById("num").value;

                if (num < 1) {
                    alert("Il numero di notizie mostrate deve essere positivo");
                    //console.log(text.length)
                    return false;
                }

                //console.log("Inviati");
                document.forms.scelta.submit()
            });
        </script>

        <div class="pulsanti">
            <?php
            if ($page > 1) {
                echo "<input class=\"prev\"type=\"submit\" value=\"&#8592 Prev\" onclick=\"prev()\">";
            }
            if (count($item) - $start - 1 > $num) {
                echo "<input class=\"next\"type=\"submit\" value=\"Next &#8594\" onclick=\"next()\">";
            }
            ?>
        </div>
    </div>

    <script type="text/javascript">
        function next() {
            num = document.getElementById("num").value;
            page = parseInt(document.getElementById("page").value) + 1;
            window.location.assign("bacheca.php?page=" + page + "&num=" + num);
        }

        function prev() {
            num = document.getElementById("num").value;
            page = parseInt(document.getElementById("page").value) - 1;
            window.location.assign("bacheca.php?page=" + page + "&num=" + num);
        }
    </script>
    <?php include 'footer.html'; ?>
</body>

</html>