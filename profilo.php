<!DOCTYPE html>
<html lang='it'>

<head>
    <title>Profilo</title>
    <meta charset="utf-8">
    <meta name="author" content="Paolo Franco, Antonio Di Palma">
    <link rel="stylesheet" href="profilo.css">
    <link rel="icon" href="./images/icona.jpg">

</head>

<body>
    <?php
    include "header.php";

    $logout = "";
    extract($_POST);
    if ($logout != "") {
        setcookie("tipoUtente", "", 0);
        setcookie("nomeUtente", "", 0);
        setcookie("CF", "", 0);

        session_destroy();
        header("location: index.php");
    }

    include "connect db.php";
    if (isset($_COOKIE["CF"])) {
        $db = pg_connect($connection_string);
        pg_prepare("cerca user utenti", "select * from utenti where CF=$1;");
        pg_prepare("cerca user admin", "select * from amministratori where CF=$1;");
        $res_1 = pg_execute("cerca user utenti", [$_COOKIE["CF"]]);
        $res_1 = pg_fetch_assoc($res_1);
        $res_2 = pg_execute("cerca user admin", [$_COOKIE["CF"]]);
        $res_2 = pg_fetch_assoc($res_2);
    }
    if (!isset($_COOKIE["CF"]) || ($res_1 == false && $res_2 == false)) { ?>
        <h3 class="register">Per accedere al tuo profilo <a href="register.php">iscriviti</a>.</h3>
    <?php } else {
        if (isset($res_1["cf"])) {
            $utente = $res_1;
        } elseif (isset($res_2["cf"])) {
            $utente = $res_2;
        }
    ?>

        <div class="container">
            <div class="propic"><i class="fas fa-running"></i></div>
            <div class="info-container">
                <div class="info" id="name">
                    <h3>Nome: </h3>
                    <h4><i><?php echo $utente["nome"]; ?></i></h4>
                </div>
                <div class="info">
                    <h3>Cognome: </h3>
                    <h4><i><?php echo $utente["cognome"]; ?></i></h4>
                </div>
                <div class="info">
                    <h3>Codice fiscale: </h3>
                    <h4><i><?php echo $utente["cf"]; ?></i></h4>
                </div>
                <?php if ($_COOKIE["tipoUtente"] != "admin") { ?>
                    <div class="info">
                        <h3>Data di nascita: </h3>
                        <h4><i><?php echo $utente["nascita"]; ?></i></h4>
                    </div>
                    <div class="info">
                        <h3>Sesso: </h3>
                        <h4><i><?php echo $utente["sesso"]; ?></i></h4>
                    </div>
                    <div class="info">
                        <h3>Indirizzo: </h3>
                        <h4><i><?php echo $utente["residenza"]; ?></i></h4>
                    </div>
                    <div class="info">
                    </div>
                <?php } ?>
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input class="logoutbtn" type="submit" id="btn" name="logout" value="Logout">
                </form>
            </div>
            <?php if ($_COOKIE["tipoUtente"] == "admin") {

                $cf = $nome = $cognome = $pass = "";
                extract($_POST);
                if ($cf != "" && $pass != "" && $cognome != "" && $nome != "") {
                    include "connect db.php";
                    $db = pg_connect($connection_string);
                    pg_prepare(
                        "add admin",
                        "insert into amministratori(cf,password,nome,cognome) values($1,$2,$3,$4)"
                    );
                    $res = pg_execute("add admin", [
                        $cf,
                        password_hash($pass, PASSWORD_DEFAULT),
                        $nome,
                        $cognome,
                    ]);
                }
            ?>
                <div class="admin-form-container">
                    <h3>Inserisci nuovo admin:</h3><br>
                    <form id="form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                        <div class="input-field">
                            <input type="text" id="cf" name="cf" placeholder="Codice fiscale">
                        </div>
                        <div class="input-field">
                            <input type="text" id="nome" name="nome" placeholder="Nome">
                        </div>
                        <div class="input-field">
                            <input type="text" id="cognome" name="cognome" placeholder="Cognome">
                        </div>
                        <div class="input-field">
                            <input type="password" id="pass" name="pass" placeholder="Password (almeno 8 caratteri)">
                        </div>
                        <div class="input-field">
                            <input type="password" id="psw_repeat" name="psw_repeat" placeholder="Reinserisci password">
                        </div>
                        <input type="submit" value="Registra" class="registerbtn"></input>
                    </form>
                </div>
            <?php
            } else {
            ?>
                <div class="corsi">
                    <?php
                    include "connect db.php";
                    $db = pg_connect($connection_string);
                    pg_prepare(
                        "cerca corsi",
                        "select nome from iscritti i join corsi c on(i.corso=c.id) where utente=$1"
                    );
                    $res = pg_execute("cerca corsi", [$utente["cf"]]);
                    $item = [];
                    do {
                        $curr = pg_fetch_assoc($res);
                        $item[] = $curr;
                    } while ($curr != false);
                    if (count($item) != 0) {
                        unset($item[count($item) - 1]);
                    }

                    if (count($item) == 0) {
                        echo "<h3>Non sei iscritto a nessun corso.</h3>";
                    } else {
                        echo "<h3>Elenco corsi:<ul></h3>";
                        $curr = "";

                        foreach ($item as $e) {
                            echo "<li>" . $e["nome"] . "</li>";
                        }
                        echo "</ul>";
                    }
                    ?>
                <?php
            } ?>
                </div>
                <script type="text/javascript">
                    document.getElementById("form").addEventListener('submit', function(e) {
                        e.preventDefault();
                        cf = document.getElementById("cf").value;
                        passw = document.getElementById("pass").value;
                        psw_repeat = document.getElementById("psw_repeat").value;
                        name = document.getElementById("nome").value;
                        surname = document.getElementById("cognome").value;
                        if (cf == "" || passw == "" || psw_repeat == "" || name == "" || surname == "") {
                            alert("Compila tutti i campi del form!");
                            return false;
                        }
                        if (cf.length != 16) {
                            alert("Codice fiscale in un formato errato!");
                            return false;
                        }
                        if (passw != psw_repeat) {
                            alert("Le due password non combaciano, riprova!");
                            return false;
                        }
                        if (!passw.match("(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}")) {
                            alert("La password deve avere almeno 8 caratteri e deve contenere almeno una maiuscola,una minuscola e un numero!")
                            return false;
                        }
                        document.forms.form.submit()
                    });
                </script>
        </div>
    <?php
    }
    include "footer.html";
    ?>
</body>

</html>